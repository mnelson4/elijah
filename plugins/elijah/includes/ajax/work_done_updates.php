<?php

add_action('wp_ajax_elijah_work_done_update', 'elijah_work_done_update');

function elijah_work_done_update() {
	$info_string = $_POST['info_to_send'];
	//we expected it to either be something "start-45", string-number, where "string"
	//is either 'start' or 'skip', and the number is the ID of the research tip to start or skip
	list($start_or_skip,$research_tip_id) = explode("-",$info_string,2);
	$current_research_goal_id = $_POST['current_research_goal_id'];
	if($start_or_skip=='start' || $start_or_skip == 'restart'){
		$result = Elijah_Tips_Applied_Logic::start_research_tip($research_tip_id,$current_research_goal_id);
	}else{
		$result = Elijah_Tips_Applied_Logic::skip_research_tip($research_tip_id, $current_research_goal_id);
	}
//	echo "result:$result";
	die(); // this is required to return a proper result
}

add_action('wp_ajax_elijah_work_done_modified', 'elijah_work_done_modified');

function elijah_work_done_modified() {
	$connection_id = p2p_type('work_done')->get_p2p_id( $_POST['tip-id'], $_POST['goal-id'] );
	p2p_update_meta($connection_id, 'usefulness', $_POST['tip-usefulness']);
	p2p_update_meta($connection_id, 'comments', $_POST['tip-comments']);
	$post = get_post( $_POST['goal-id'] );
	if( ! $post instanceof WP_Post ) {
		echo "no post!";
		die;
	}
	if( $_POST['tip-usefulness'] >= ELIJAH_TIP_USEFULNESS_FOUND_INFO ) {
		$post->post_status = 'complete';
	} else {
		//check if this update means the goal has been completed
		$completed = new WP_Query( array(
			'connected_type' => 'work_done',
			'connected_items' => $_POST['goal-id'],
			'connected_meta' => array(
				'key' => 'usefulness',
				'value' => ELIJAH_TIP_USEFULNESS_FOUND_INFO,
				'compare' => '>=',
				'type' => 'numeric',
			),
			//we just want to see if there's one
			'posts_per_page' => 1,
		) );
		if( $completed ) {
			$post->post_status = 'complete';
		} else {
			$post->post_status = 'publish';
		}
	}
	wp_update_post( $post, true );
	
	die;
}