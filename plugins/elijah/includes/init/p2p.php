<?php

//hooks for initing posts 2 posts extension
function elijah_connection_types(){
	if ( !function_exists( 'p2p_register_connection_type' ) )
        return;
	/**
	 * define new global here, which can be used elsewhere for getting pretty values of strategy usefulnesses
	 */
	global $strategy_usefulness_mapping,$strategy_stati_mapping;
	$strategy_usefulness_mapping = array(
					60=>  __("Found Missing Info and More", "event_espresso"),
					50=>  __("Found Missing Info", "event_espresso"),
					40=>  __("Found Something Else", "event_espresso"),
					30=>  __("Found a hint", "event_espresso"),
					20=>  __("Looked useful, but didn't find anything", "event_espresso"),
					0=>  __("---", "event_espresso"),
					-10 => __("Didn't Complete", "event_espresso"),
					-30=>  __("Waste of time", "event_espresso"),
					-100=>  __("Spam", "event_espresso"));
	$strategy_stati_mapping = array(
		'suggested'=>  __("Suggested", "event_espresso"),
		'in_progress'=>  __("In Progress", "event_espresso"),
		'completed'=>  __("Complete", "event_espresso"),
		'skipped'=>  __("Skip", "event_espresso")
	);
	p2p_register_connection_type( array(
        'name' => 'strategies_applied',
        'from' => 'research-objectives',
        'to' => 'research-strategies',
		'from_labels'=>array(
			'singular_name' => __( 'Research Objectives that have used this strategy', 'my-textdomain' ),
			'search_items' => __( 'Research Objectives which have used this strategy', 'my-textdomain' ),
			'not_found' => __( 'No one has used this research strategy yet', 'my-textdomain' ),
			'create' => __( 'Mark a Research Objective as having used this Strategy', 'my-textdomain' ),
		),
		'to_labels'=>array(
			'singular_name' => __( 'Strategy Applied', 'my-textdomain' ),
			'search_items' => __( 'Search Strategies Applied', 'my-textdomain' ),
			'not_found' => __( 'No strategies applied yet', 'my-textdomain' ),
			'create' => __( 'Apply a Strategy to this Research Objective', 'my-textdomain' ),
		),
		'fields'=>array(
			'status'=>array(
				'title'=>  __("Status", "event_espresso"),
				'type'=>'select',
				'values'=>$strategy_stati_mapping
			),
			'usefulness'=>array(
				'title'=>__('Usefulness','my-textdomain'),
				'type'=>'select',
				'values'=>$strategy_usefulness_mapping
					
			),
			'comments'=>array(
				'title'=>  __("Comments", "event_espresso"),
				'type'=>'textarea'
			),
			'author_id'=>array(
				'type'=>'hidden',
				'default_cb'=>'elijah_default_author_on_strategies_applied'
			),
			'started'=>array(
				'type'=>'hidden',
				'default'=>current_time('mysql'),
			),
			'last_updated'=>array(
				'title'=>  __("Last Updated", "event_espresso"),
				'type'=>'text',
				'default'=>current_time('mysql'),
			),
			'finished'=>array(
				'type'=>'hidden'
			)
		)
    ) );
}

add_action( 'p2p_init', 'elijah_connection_types' );

/**
 * Called by post 2 post to get teh default author ID on a research strategy applied
 * @param type $connection post2post connection
 * @param type $direction I believe either teh string 'from' or 'to'
 * @return int the wp user id
 */
function elijah_default_author_on_strategies_applied($connection, $direction){
	$current_user = wp_get_current_user();
	if($current_user){
		return $current_user->ID;
	}else{
		return 0;
	}
}