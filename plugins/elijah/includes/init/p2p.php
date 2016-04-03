<?php
define( 'ELIJAH_STRATEGY_USEFULNESS_FOUND_INFO_AND_MORE', 60 );
define( 'ELIJAH_STRATEGY_USEFULNESS_FOUND_INFO', 50 );
define( 'ELIJAH_STRATEGY_USEFULNESS_FOUND_SOMETHING_ELSE', 40 );
define( 'ELIJAH_STRATEGY_USEFULNESS_FOUND_HINT', 30 );
define( 'ELIJAH_STRATEGY_USEFULNESS_FOUND_NOTHING', 20 );
define( 'ELIJAH_STRATEGY_USEFULNESS_DEFAULT', 0 );
define( 'ELIJAH_STRATEGY_USEFULNESS_DIDNT_COMPLETE', -10 );
define( 'ELIJAH_STRATEGY_USEFULNESS_WASTE', -30 );
define( 'ELIJAH_STRATEGY_USEFULNESS_SPAM', -100 );
//hooks for initing posts 2 posts extension
function elijah_connection_types(){
	if ( !function_exists( 'p2p_register_connection_type' ) )
        return;
	/**
	 * define new global here, which can be used elsewhere for getting pretty values of strategy usefulnesses
	 */
	global $strategy_usefulness_mapping,$strategy_stati_mapping;
	$strategy_usefulness_mapping = array(
					ELIJAH_STRATEGY_USEFULNESS_FOUND_INFO_AND_MORE =>  __("Found Missing Info and More", "elijah"),
					ELIJAH_STRATEGY_USEFULNESS_FOUND_INFO =>  __("Found Missing Info", "elijah"),
					ELIJAH_STRATEGY_USEFULNESS_FOUND_SOMETHING_ELSE =>  __("Found Something Else", "elijah"),
					ELIJAH_STRATEGY_USEFULNESS_FOUND_HINT =>  __("Found a hint", "elijah"),
					ELIJAH_STRATEGY_USEFULNESS_FOUND_NOTHING =>  __("Looked useful, but didn't find anything", "elijah"),
					ELIJAH_STRATEGY_USEFULNESS_DEFAULT =>  __("---", "elijah"),
					ELIJAH_STRATEGY_USEFULNESS_DIDNT_COMPLETE => __("Didn't Complete", "elijah"),
					ELIJAH_STRATEGY_USEFULNESS_WASTE =>  __("Waste of time", "elijah"),
					ELIJAH_STRATEGY_USEFULNESS_SPAM =>  __("Spam", "elijah"));
	$strategy_stati_mapping = array(
		'suggested'=>  __("Suggested", "elijah"),
		'in_progress'=>  __("In Progress", "elijah"),
		'completed'=>  __("Complete", "elijah"),
		'skipped'=>  __("Skip", "elijah")
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
				'title'=>  __("Status", "elijah"),
				'type'=>'select',
				'values'=>$strategy_stati_mapping
			),
			'usefulness'=>array(
				'title'=>__('Usefulness','my-textdomain'),
				'type'=>'select',
				'values'=>$strategy_usefulness_mapping

			),
			'comments'=>array(
				'title'=>  __("Comments", "elijah"),
				'type'=>'textarea'
			),
			'finished'=>array(
				'type'=>'hidden'
			),
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