<?php

function get_strategy_status_for_objective($strategy_post_obj_with_p2ps){
	
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
 * Class for containing functions which interact with p2p in order to update
 * which strategies have been applied to which objectives
 */
class Elijah_Strategies_Applied_Logic{
	 /**
	* Sets a research strategy as having started
	* @global WP_User $current_user
	* @param int $strategy_id
	* @param int $objective_id
	* @return int connection object, or WP_Error
	*/
   public static function start_research_strategy($strategy_id, $objective_id){
	   global $current_user;
	   //check if we've actually already started this research strategy on this objective
	   $connection_id = p2p_type('strategies_applied')->get_p2p_id( $strategy_id, $objective_id );
	   if( ! $connection_id){
		   //great, this is what we expected: it doesn't exist yet. Let's make the connection
		   $connection_id = p2p_type( 'strategies_applied' )->connect( $strategy_id, $objective_id, array(
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
    * Createsa  connection between strategy adn objective, indicating that teh current user
    * has skipped applying this strategy to the objective
    * @global WP_User $current_user
    * @param int $strategy_id
    * @param int $objective_id
    * @return int connection ID
    */
   public static function skip_research_strategy($strategy_id, $objective_id){
	   global $current_user;
	   //check if we've actually already started this research strategy on this objective
	   $connection_id = p2p_type('strategies_applied')->get_p2p_id( $strategy_id, $objective_id );
	   if( ! $connection_id){
		   //great, this is what we expected: it doesn't exist yet. Let's make the connection
		   $connection_id = p2p_type( 'strategies_applied' )->connect( $strategy_id, $objective_id, array(
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