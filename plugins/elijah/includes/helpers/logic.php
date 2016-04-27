<?php

function get_strategy_status_for_goal($strategy_post_obj_with_p2ps){
	
	$status = p2p_get_meta( $strategy_post_obj_with_p2ps->p2p_id, 'status', true );
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
class Elijah_pis_Applied_Logic{
	 /**
	* Sets a research strategy as having started
	* @global WP_User $current_user
	* @param int $strategy_id
	* @param int $goal_id
	* @return int connection object, or WP_Error
	*/
   public static function start_research_strategy($strategy_id, $goal_id){
	   global $current_user;
	   //check if we've actually already started this research strategy on this goal
	   $connection_id = p2p_type('work_done')->get_p2p_id( $strategy_id, $goal_id );
	   if( ! $connection_id){
		   //great, this is what we expected: it doesn't exist yet. Let's make the connection
		   $connection_id = p2p_type( 'work_done' )->connect( $strategy_id, $goal_id, array(
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
    * Createsa  connection between strategy adn goal, indicating that teh current user
    * has skipped applying this strategy to the goal
    * @global WP_User $current_user
    * @param int $strategy_id
    * @param int $goal_id
    * @return int connection ID
    */
   public static function skip_research_strategy($strategy_id, $goal_id){
	   global $current_user;
	   //check if we've actually already started this research strategy on this goal
	   $connection_id = p2p_type('work_done')->get_p2p_id( $strategy_id, $goal_id );
	   if( ! $connection_id){
		   //great, this is what we expected: it doesn't exist yet. Let's make the connection
		   $connection_id = p2p_type( 'work_done' )->connect( $strategy_id, $goal_id, array(
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