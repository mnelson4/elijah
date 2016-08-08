<?php

function get_tip_status_for_goal($tip_post_obj_with_p2ps){
	
	$status = p2p_get_meta( $tip_post_obj_with_p2ps->p2p_id, 'status', true );
	if( ! $status){
		$status = 'suggested';
	}
	return $status;
}

function get_excerpt_or_short_content($post){
	$text = null;
	if(empty($post->post_excerpt)){
		$text = wp_trim_words($post->post_content,50);
	}else{
		$text = $post->post_excerpt;
	}
	return $text;
}
/**
 * Given an array of WP_Term_With_Children, returns only leaf terms (ie have no children)
 * @param WP_Term_With_Children[] $terms_with_children
 */
function elijah_hierarchical_get_leaf_terms( $terms_with_children ){
	$leaf_nodes = array();
	foreach( $terms_with_children as $term_with_children ) {
		if( $term_with_children->children() ) {
			$leaf_nodes = array_merge( $leaf_nodes, elijah_hierarchical_get_leaf_terms( $term_with_children->children() ) );
		} else {
			$leaf_nodes[] = $term_with_children;
		}
	}
	return $leaf_nodes;
}

/**
 * Returns results exactly get_terms, except instead returns the results organized
 * by their parent-child relationships. This is done by returning an array of thin wrappers
 * of WP_Term, called WP_Term_With_Children. The wrapper has the originaly term, 
 * PLUS a list of its direct children. Each of those children has their direct children etc.
 * So if you ran this on the entire set of terms, the top-level array would be all
 * WP_Term_With_Children that have no parent
 * @param array $args what you'd pass to get_terms
 * @return WP_Term_With_Children[]
 */
function elijah_get_terms_hierarchically( $args ) {
	$defaults = array(
		'hide_empty' => false,		
	);
	$final_args = array_replace( $defaults, $args );
	
	$terms = get_terms( $final_args );
	
	//convert the WP_Terms to WP_Term_With_Childrens
	$terms_to_organize = array();
	foreach( $terms as $term ) {
		$terms_to_organize[ $term->term_id ] = new WP_Term_With_Children( $term );
	}
	//we want to keep a flat reference to all the terms
	$terms_ref = $terms_to_organize;
	//start building the term tree by adding a term onto its parent as a child
	$i = 0;
	do{
		$organized_one_on_this_pass = false;
		foreach( $terms_to_organize as $term_id => $term_with_children ) {
			if( isset( $terms_ref[ $term_with_children->term()->parent] ) ){
				$parent_with_children = $terms_ref[ $term_with_children->term()->parent];
				$parent_with_children->add_child( $term_with_children );
				unset( $terms_to_organize[ $term_id ] );
				$organized_one_on_this_pass = true;
			}
		}
	} while ( $organized_one_on_this_pass && $i++ < 50 );
	//all done organizing!
	return $terms_to_organize;
}

class WP_Term_With_Children {
	protected $term;
	protected $children;
	
	public function __construct( WP_Term $term ) {
		$this->term = $term;
	}
	
	/**
	 * 
	 * @return WP_Term
	 */
	public function term() {
		return $this->term;
	}
	public function add_child( WP_Term_With_Children $child_term ) {
		$this->children[] = $child_term;
	}
	
	/**
	 * 
	 * @return WP_Term[]
	 */
	public function children() {
		return $this->children;
	}
}
/**
* Gets the URL for editing thie post on the frontend
* @param WP_Post $post
* @return string URL for editing the post on the frontend
*/
function elijah_get_frontend_editing_permalink( WP_Post $post ) {
	if( $post->post_type === 'research_goal' ) {
		$url = add_query_arg( 'goal_id', $post->ID, get_permalink( elijah_edit_research_goals_page_id ) );
	} elseif( $post->post_type === 'research_tip' ) {
		$url = add_query_arg( 'tip_id', $post->ID, get_permalink( elijah_edit_research_tip_page_id ) );
	} else {
		if( WP_DEBUG ) {
			throw new Exception( 'Could not get frontend editing permalink for post ' . print_r( $post, true ) );
		} else {
			$url = site_url();
		}
	}
	return $url;
}

/**
 * Class for containing functions which interact with p2p in order to update
 * which tips have been applied to which goals
 */
class Elijah_Tips_Applied_Logic{
	 /**
	* Sets a research tip as having started
	* @global WP_User $current_user
	* @param int $tip_id
	* @param int $goal_id
	* @return int connection object, or WP_Error
	*/
   public static function start_research_tip($tip_id, $goal_id){
	   global $current_user;
	   //check if we've actually already started this research tip on this goal
	   $connection_id = p2p_type('work_done')->get_p2p_id( $tip_id, $goal_id );
	   if( ! $connection_id){
		   //great, this is what we expected: it doesn't exist yet. Let's make the connection
		   $connection_id = p2p_type( 'work_done' )->connect( $tip_id, $goal_id, array(
			   'status' => 'in_progress',
			   'usefulness'=>0,
			   'comments'=>'',
			   'author_id'=>$current_user->ID,
			   'started'=>current_time('mysql'),
			   'last_updated'=>current_time('mysql'),
			   'finished'=>null
		   ) );
	   }else{
		   //just update it then
		   p2p_update_meta($connection_id, 'status', 'in_progress');
		   p2p_update_meta($connection_id,'last_updated',current_time('mysql'));
	   }
	   return $connection_id;
   }
   
   /**
    * Createsa  connection between tip adn goal, indicating that teh current user
    * has skipped applying this tip to the goal
    * @global WP_User $current_user
    * @param int $tip_id
    * @param int $goal_id
    * @return int connection ID
    */
   public static function skip_research_tip($tip_id, $goal_id){
	   global $current_user;
	   //check if we've actually already started this research tip on this goal
	   $connection_id = p2p_type('work_done')->get_p2p_id( $tip_id, $goal_id );
	   if( ! $connection_id){
		   //great, this is what we expected: it doesn't exist yet. Let's make the connection
		   $connection_id = p2p_type( 'work_done' )->connect( $tip_id, $goal_id, array(
			   'status' => 'skipped',
			   'usefulness'=>0,
			   'comments'=>'',
			   'author_id'=>$current_user->ID,
			   'started'=>current_time('mysql'),
			   'last_updated'=>current_time('mysql'),
			   'finished'=>null
		   ) );
	   }else{
		   //just update it then
		   p2p_update_meta($connection_id, 'status', 'skipped');
		   p2p_update_meta($connection_id,'last_updated',current_time('mysql'));
	   }
	   return $connection_id;
   }
   
   
}