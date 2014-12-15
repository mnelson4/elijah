<?php

//registers custom post types

function elijah_register_cpts() {

	//taxonomies
	register_taxonomy('birthyear', array(
		0 => 'research-objectives',
			), array('hierarchical' => true, 'label' => 'Birth Year of Individual', 'show_ui' => true, 'query_var' => true, 'rewrite' => array('slug' => ''), 'singular_label' => 'Birthyear',
				'capabilities'=>array(
					'manage_terms'=>'manage_birthyears',
					'edit_terms'=>'edit_birthyears',
					'delete_terms'=>'delete_birthyears',
					'assign_terms'=>'assign_birthyears',
				)));
	register_taxonomy('birthplace', array(
		0 => 'research-objectives',
			), array('hierarchical' => true, 'label' => 'Birthplace of Individual', 'show_ui' => true, 'query_var' => true, 'rewrite' => array('slug' => ''), 'singular_label' => 'Birthplace',
				'capabilities'=>array(
					'manage_terms'=>'manage_birthplaces',
					'edit_terms'=>'edit_birthplaces',
					'delete_terms'=>'delete_birthplaces',
					'assign_terms'=>'assign_birthplaces',
				)));
	register_taxonomy('death-place', array(
		0 => 'research-objectives',
		1 => 'research-strategies',
			), array('hierarchical' => true, 'label' => 'Death Place of Individual', 'show_ui' => true, 'query_var' => true, 'rewrite' => array('slug' => ''), 'singular_label' => 'Death Place',
				'capabilities'=>array(
					'manage_terms'=>'manage_death-places',
					'edit_terms'=>'edit_death-places',
					'delete_terms'=>'delete_death-places',
					'assign_terms'=>'assign_death-places',
				)));
	register_taxonomy('marriage-year', array(
		0 => 'research-objectives',
		1 => 'research-strategies',
			), array('hierarchical' => true, 'label' => 'Marriage Year of Individual', 'show_ui' => true, 'query_var' => true, 'rewrite' => array('slug' => ''), 'singular_label' => 'Marriage Year',
				'capabilities'=>array(
					'manage_terms'=>'manage_marriage-years',
					'edit_terms'=>'edit_marriage-years',
					'delete_terms'=>'delete_marriage-years',
					'assign_terms'=>'assign_marriage-years',
				)));
	register_taxonomy('individual-details', array(
		0 => 'research-objectives',
		1 => 'research-strategies',
			), array('hierarchical' => true, 'label' => 'Details Being Researched', 'show_ui' => true, 'query_var' => true, 'rewrite' => array('slug' => ''), 'singular_label' => 'Details being Researched',
				'capabilities'=>array(
					'manage_terms'=>'manage_individual-details',
					'edit_terms'=>'edit_individual-details',
					'delete_terms'=>'delete_individual-details',
					'assign_terms'=>'assign_individual-details',
				)));
	register_taxonomy('death-year', array(
		0 => 'research-objectives',
		1 => 'research-strategies',
			), array('hierarchical' => true, 'label' => 'Death Year of Individual', 'show_ui' => true, 'query_var' => true, 'rewrite' => array('slug' => ''), 'singular_label' => 'Death Year',
				'capabilities'=>array(
					'manage_terms'=>'manage_death-years',
					'edit_terms'=>'edit_death-years',
					'delete_terms'=>'delete_death-years',
					'assign_terms'=>'assign_death-years',
				)));
	register_taxonomy('marriage-place', array(
		0 => 'research-objectives',
		1 => 'research-strategies',
			), array('hierarchical' => true, 'label' => 'Marriage Place of Individual', 'show_ui' => true, 'query_var' => true, 'rewrite' => array('slug' => ''), 'singular_label' => 'Marriage Place',
				'capabilities'=>array(
					'manage_terms'=>'manage_marriage-places',
					'edit_terms'=>'edit_marriage-places',
					'delete_terms'=>'delete_marriage-places',
					'assign_terms'=>'assign_marriage-places',
				)));
	register_taxonomy('childrens-birthplaces', array(
		0 => 'research-objectives',
		1 => 'research-strategies',
			), array('hierarchical' => true, 'label' => 'Children\'s Birthplaces', 'show_ui' => true, 'query_var' => true, 'rewrite' => array('slug' => ''), 'singular_label' => 'Child\'s Birthplace',
				'capabilities'=>array(
					'manage_terms'=>'manage_childrens-birthplaces',
					'edit_terms'=>'edit_childrens-birthplaces',
					'delete_terms'=>'delete_childrens-birthplaces',
					'assign_terms'=>'assign_childrens-birthplaces',
				)));
	register_taxonomy('childrens-birthyears', array(
		0 => 'research-objectives',
		1 => 'research-strategies',
			), array('hierarchical' => true, 'label' => 'Children\'s Birthyears', 'show_ui' => true, 'query_var' => true, 'rewrite' => array('slug' => ''), 'singular_label' => 'Child\'s Birhtyear',
				'capabilities'=>array(
					'manage_terms'=>'manage_childrens-birthyears',
					'edit_terms'=>'edit_childrens-birthyears',
					'delete_terms'=>'delete_childrens-birthyears',
					'assign_terms'=>'assign_childrens-birthyears',
				)));
	$year_research_taxonomies = array('birthyear', 'marriage-year', 'death-year', 'childrens-birthyears' );
	$place_research_taxonomies = array( 'birthplace', 'death-place', 'marriage-place', 'childrens-birthplaces' );
	$other_research_taxonomies = array( 'individual-details' );
	$all_research_taxonomies = array_merge( $year_research_taxonomies, $place_research_taxonomies, $other_research_taxonomies );

	foreach( $year_research_taxonomies as $taxonomy ) {
		elijah_make_term_for_decades_after( 1600, $taxonomy );
	}

	register_post_type('research-objectives', array(
		'label' => 'Research Objectives',
		'description' => 'A specific thing about a specific person you want to research. E.g.: great-uncle Tim\\\\\\\\\\\\\\\'s birthplace; great-great-grandmother Susan\\\\\\\\\\\\\\\'s parent\\\\\\\\\\\\\\\'s names and birthplaces; great-aunt Gertrude\\\\\\\\\\\\\\\'s death-place and date',
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'capability_type' => 'research-objective',
		'map_meta_cap' => true,
		'hierarchical' => false,
		'rewrite' => array('slug' => ''),
		'query_var' => true,
		'has_archive' => true,
		'exclude_from_search' => false,
		'menu_position' => 2,
		'supports' => array('title', 'editor', 'excerpt', 'comments', 'revisions', 'thumbnail', 'author',),
		'taxonomies' => $all_research_taxonomies,
		'labels' => array(
			'name' => 'Research Objectives',
			'singular_name' => 'Research Objective',
			'menu_name' => 'Research Objectives',
			'add_new' => 'Add Research Objective',
			'add_new_item' => 'Add New Research Objective',
			'edit' => 'Edit',
			'edit_item' => 'Edit Research Objective',
			'new_item' => 'New Research Objective',
			'view' => 'View Research Objective',
			'view_item' => 'View Research Objective',
			'search_items' => 'Search Research Objectives',
			'not_found' => 'No Research Objectives Found',
			'not_found_in_trash' => 'No Research Objectives Found in Trash',
			'parent' => 'Parent Research Objective',
		),));
	register_research_status('enqueued',array('title'=>  __("Enqueued for Research", "event_espresso")));
	register_research_status('in-progress',array('title'=>  __("In Progress", "event_espresso")));
	register_research_status('resolved',array('title'=> __("Resolved", "event_espresso")));

	register_post_type('research-strategies', array(
		'label' => 'Research Strategies',
		'description' => 'A generic task that can be done to complete a research objective. Eg: to find an individual\\\\\\\\\\\\\\\'s birthplace and year, search their name in New Family Search to find duplicates; to find an individual\\\\\\\\\\\\\\\'s parents, search for their birth record at local parishes; or even to find a granparent\\\\\\\\\\\\\\\'s birthplace, ask the oldest relative you know, etc.',
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'capability_type' => 'research-strategy',
		'map_meta_cap' => true,
		'hierarchical' => true,
		'rewrite' => array('slug' => 'research-strategies'),
		'query_var' => true,
		'has_archive' => true,
		'exclude_from_search' => false,
		'menu_position' => 2,
		'supports' => array('title', 'editor', 'comments', 'thumbnail', 'author',),
		'taxonomies' => $all_research_taxonomies,
		'labels' => array(
			'name' => 'Research Strategies',
			'singular_name' => 'Research Strategy',
			'menu_name' => 'Research Strategies',
			'add_new' => 'Add Research Strategy',
			'add_new_item' => 'Add New Research Strategy',
			'edit' => 'Edit',
			'edit_item' => 'Edit Research Strategy',
			'new_item' => 'New Research Strategy',
			'view' => 'View Research Strategy',
			'view_item' => 'View Research Strategy',
			'search_items' => 'Search Research Strategies',
			'not_found' => 'No Research Strategies Found',
			'not_found_in_trash' => 'No Research Strategies Found in Trash',
			'parent' => 'Parent Research Strategy',
		),));

	}

add_action('init', 'elijah_register_cpts', 100);

/**
 * Registers a 'research status', and adds it to a new global array
 * called '$elijah_research_statuses'.
 * Options you can provide are:
 * -title (i18n-ized name)
 * @param string $status
 * @param array $options
 * @return void
 */
function register_research_status($status,$options=array()){
	global $elijah_research_statuses;
	if( ! $elijah_research_statuses ){
		$elijah_research_statuses = array();
	}

	$options = array_merge(array(
		'title'=>$status,
		'other_option-x'=>'monkey'
	),$options);

	$elijah_research_statuses[$status] = $options;
}

/**
 * Convenience function for getting the pretty title for a research objective status
 * @global array $elijah_research_statuses declared in register_research_status
 * @param string $status
 * @return string
 */
function elijah_get_research_status_title($status){
	global $elijah_research_statuses;
	if(array_key_exists($status, $elijah_research_statuses)){
		return $elijah_research_statuses[$status]['title'];
	}else{
		return 'status does not exist';
	}
}

/**
 * Gets the research title for the post with id $post_id. If none is set,
 * sets it
 * @param int $post_id
 * @return string
 */
function elijah_get_research_status_title_for_post($post_id){
	$status = get_post_meta($post_id,'research_status',true);
	if( ! $status ){
		$status = 'enqueued';
		update_post_meta($post_id,'research_status',$status);
	}
	return elijah_get_research_status_title( $status  );
}

function elijah_make_term_for_decades_after( $year, $taxonomy ){
	$current_year = date("Y");
	for( ; $year < $current_year; $year += 10 ) {
		$term = round( $year, -1 ) . "s";
		if( ! term_exists( $term, $taxonomy ) ) {
			echo "<br>would insert $term";
			wp_insert_term( $term, $taxonomy );
		}
	}
}