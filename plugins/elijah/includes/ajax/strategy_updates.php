<?php

add_action('wp_ajax_elijah_strategy_update', 'elijah_strategy_update');

function elijah_strategy_update() {
	$info_string = $_POST['info_to_send'];
	//we expected it to either be something "start-45", string-number, where "string"
	//is either 'start' or 'skip', and the number is the ID of the research strategy to start or skip
	list($start_or_skip,$research_strategy_id) = explode("-",$info_string,2);
	$current_research_objective_id = $_POST['current_research_objective_id'];
	if($start_or_skip=='start' || $start_or_skip == 'restart'){
		$result = Elijah_Strategies_Applied_Logic::start_research_strategy($research_strategy_id,$current_research_objective_id);
	}else{
		$result = Elijah_Strategies_Applied_Logic::skip_research_strategy($research_strategy_id, $current_research_objective_id);
	}
//	echo "result:$result";
	die(); // this is required to return a proper result
}

add_action('wp_ajax_elijah_strategy_modified', 'elijah_strategy_modified');

function elijah_strategy_modified() {
	$connection_id = p2p_type('strategies_applied')->get_p2p_id( $_POST['strategy-id'], $_POST['objective-id'] );
	p2p_update_meta($connection_id, 'usefulness', $_POST['strategy-usefulness']);
	p2p_update_meta($connection_id, 'comments', $_POST['strategy-comments']);
	if( $_POST['strategy-usefulness'] >= ELIJAH_STRATEGY_USEFULNESS_FOUND_INFO ) {
		update_post_meta( $_POST['objective-id'],'research_status', 'resolved' );
	} else {
		//update to in progress or enqueued? do we need or want both?
		update_post_meta( $_POST['objective-id'], 'research_status', 'in-progress' );
	}
	die;
}