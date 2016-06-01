<?php

//registers custom post types

function elijah_register_cpts() {

	//taxonomies
	register_taxonomy('individual-details', array(
		0 => 'research_goal',
		1 => 'research_tip',
			), array('hierarchical' => true, 'label' => 'Details Being Researched', 'show_ui' => true, 'query_var' => true, 'rewrite' => array('slug' => ''), 'singular_label' => 'Details being Researched',
				'capabilities'=>array(
					'manage_terms'=>'manage_individual-details',
					'edit_terms'=>'edit_individual-details',
					'delete_terms'=>'delete_individual-details',
					'assign_terms'=>'assign_individual-details',
				)));
	register_taxonomy('birthyear', array(
		0 => 'research_goal',
			), array('hierarchical' => true, 'label' => 'Birth Year of Individual', 'show_ui' => true, 'query_var' => true, 'rewrite' => array('slug' => ''), 'singular_label' => 'Birthyear',
				'capabilities'=>array(
					'manage_terms'=>'manage_birthyears',
					'edit_terms'=>'edit_birthyears',
					'delete_terms'=>'delete_birthyears',
					'assign_terms'=>'assign_birthyears',
				)));
	register_taxonomy('birthplace', array(
		0 => 'research_goal',
			), array('hierarchical' => true, 'label' => 'Birthplace of Individual', 'show_ui' => true, 'query_var' => true, 'rewrite' => array('slug' => ''), 'singular_label' => 'Birthplace',
				'capabilities'=>array(
					'manage_terms'=>'manage_birthplaces',
					'edit_terms'=>'edit_birthplaces',
					'delete_terms'=>'delete_birthplaces',
					'assign_terms'=>'assign_birthplaces',
				)));
	register_taxonomy('marriage-year', array(
		0 => 'research_goal',
		1 => 'research_tip',
			), array('hierarchical' => true, 'label' => 'Marriage Year of Individual', 'show_ui' => true, 'query_var' => true, 'rewrite' => array('slug' => ''), 'singular_label' => 'Marriage Year',
				'capabilities'=>array(
					'manage_terms'=>'manage_marriage-years',
					'edit_terms'=>'edit_marriage-years',
					'delete_terms'=>'delete_marriage-years',
					'assign_terms'=>'assign_marriage-years',
				)));
	register_taxonomy('marriage-place', array(
		0 => 'research_goal',
		1 => 'research_tip',
			), array('hierarchical' => true, 'label' => 'Marriage Place of Individual', 'show_ui' => true, 'query_var' => true, 'rewrite' => array('slug' => ''), 'singular_label' => 'Marriage Place',
				'capabilities'=>array(
					'manage_terms'=>'manage_marriage-places',
					'edit_terms'=>'edit_marriage-places',
					'delete_terms'=>'delete_marriage-places',
					'assign_terms'=>'assign_marriage-places',
				)));
	register_taxonomy('death-year', array(
		0 => 'research_goal',
		1 => 'research_tip',
			), array('hierarchical' => true, 'label' => 'Death Year of Individual', 'show_ui' => true, 'query_var' => true, 'rewrite' => array('slug' => ''), 'singular_label' => 'Death Year',
				'capabilities'=>array(
					'manage_terms'=>'manage_death-years',
					'edit_terms'=>'edit_death-years',
					'delete_terms'=>'delete_death-years',
					'assign_terms'=>'assign_death-years',
				)));
	register_taxonomy('death-place', array(
		0 => 'research_goal',
		1 => 'research_tip',
			), array('hierarchical' => true, 'label' => 'Death Place of Individual', 'show_ui' => true, 'query_var' => true, 'rewrite' => array('slug' => ''), 'singular_label' => 'Death Place',
				'capabilities'=>array(
					'manage_terms'=>'manage_death-places',
					'edit_terms'=>'edit_death-places',
					'delete_terms'=>'delete_death-places',
					'assign_terms'=>'assign_death-places',
				)));
	register_taxonomy('childrens-birthyears', array(
		0 => 'research_goal',
		1 => 'research_tip',
			), array('hierarchical' => true, 'label' => 'Children\'s Birthyears', 'show_ui' => true, 'query_var' => true, 'rewrite' => array('slug' => ''), 'singular_label' => 'Child\'s Birhtyear',
				'capabilities'=>array(
					'manage_terms'=>'manage_childrens-birthyears',
					'edit_terms'=>'edit_childrens-birthyears',
					'delete_terms'=>'delete_childrens-birthyears',
					'assign_terms'=>'assign_childrens-birthyears',
				)));
	register_taxonomy('childrens-birthplaces', array(
		0 => 'research_goal',
		1 => 'research_tip',
			), array('hierarchical' => true, 'label' => 'Children\'s Birthplaces', 'show_ui' => true, 'query_var' => true, 'rewrite' => array('slug' => ''), 'singular_label' => 'Child\'s Birthplace',
				'capabilities'=>array(
					'manage_terms'=>'manage_childrens-birthplaces',
					'edit_terms'=>'edit_childrens-birthplaces',
					'delete_terms'=>'delete_childrens-birthplaces',
					'assign_terms'=>'assign_childrens-birthplaces',
				)));
	register_taxonomy('tip-type', array(
		0 => 'research_goal',
		1 => 'research_tip',
			), array('hierarchical' => true, 'label' => __( 'Tip Types', 'event_espresso' ), 'show_ui' => true, 'query_var' => true, 'rewrite' => array('slug' => ''), 'singular_label' => __( 'Tip Type', 'event_espresso' ),
				'capabilities'=>array(
					'manage_terms'=>'manage_tip-types',
					'edit_terms'=>'edit_tip-types',
					'delete_terms'=>'delete_tip-types',
					'assign_terms'=>'assign_tip-types',
				)));
	$year_research_taxonomies = array('birthyear', 'marriage-year', 'death-year', 'childrens-birthyears' );
	$place_research_taxonomies = array( 'birthplace', 'death-place', 'marriage-place', 'childrens-birthplaces' );
	$other_research_taxonomies = array( 'individual-details' );
	$all_research_taxonomies = array_merge( $year_research_taxonomies, $place_research_taxonomies, $other_research_taxonomies );

	foreach( $year_research_taxonomies as $taxonomy ) {
		elijah_make_term_for_decades_after( 1600, $taxonomy );
	}
	register_post_type('research_goal', array(
		'label' => 'Research Goals',
		'description' => 'A specific thing about a specific person you want to research. E.g.: great-uncle Tim\\\\\\\\\\\\\\\'s birthplace; great-great-grandmother Susan\\\\\\\\\\\\\\\'s parent\\\\\\\\\\\\\\\'s names and birthplaces; great-aunt Gertrude\\\\\\\\\\\\\\\'s death-place and date',
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'capability_type' => 'research_goal',
		'map_meta_cap' => true,
		'hierarchical' => false,
		'rewrite' => array('slug' => 'research-goals'),
		'query_var' => true,
		'has_archive' => true,
		'exclude_from_search' => false,
		'menu_position' => 20,
		'supports' => array('title', 'editor', 'excerpt', 'comments', 'revisions', 'thumbnail', 'author',),
		'taxonomies' => $all_research_taxonomies,
		'labels' => array(
			'name' => __( 'Research Goals', 'elijah' ),
			'singular_name' => __( 'Goal', 'elijah' ),
			'menu_name' => __( 'Research Goals', 'elijah' ),
			'add_new' => __( 'Add New', 'elijah' ),
			'add_new_item' => __( 'Add New Goal', 'elijah' ),
			'edit' => __( 'Edit', 'elijah' ),
			'edit_item' => __( 'Edit Goal',	'elijah' ),
			'new_item' => __( 'New Goal', 'elijah' ),
			'view' => __( 'View', 'elijah' ),
			'view_item' => __( 'View Goal',	'elijah' ),
			'search_items' => __( 'Search Research Goals', 'elijah' ),
			'not_found' => __( 'No Goals Found', 'elijah' ),
			'not_found_in_trash' => __( 'No Goals Found in Trash','elijah' ),
			'parent' => __( 'Parent', 'elijah' ),
		),));

	register_post_type('research_tip', array(
		'label' => 'Research Tips',
		'description' => 'A generic task that can be done to complete a research goal. Eg: to find an individual\\\\\\\\\\\\\\\'s birthplace and year, search their name in New Family Search to find duplicates; to find an individual\\\\\\\\\\\\\\\'s parents, search for their birth record at local parishes; or even to find a granparent\\\\\\\\\\\\\\\'s birthplace, ask the oldest relative you know, etc.',
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'capability_type' => 'research_tip',
		'capabilities' => array(
			'edit_posts' => 'edit_research_tips',
			'edit_others_posts' => 'edit_others_research_tips',
			'publish_posts' => 'publish_research_tips', 
			'read_private_posts' => 'read_private_research_tips',
		),
		'map_meta_cap' => true,
		'hierarchical' => true,
		'rewrite' => array('slug' => 'research-tips'),
		'query_var' => true,
		'has_archive' => true,
		'exclude_from_search' => false,
		'menu_position' => 20,
		'supports' => array('title', 'editor', 'comments', 'thumbnail', 'author',),
		'taxonomies' => $all_research_taxonomies,
		'labels' => array(
			'name' => __( 'Research Tips', 'elijah' ),
			'singular_name' => __( 'Tip', 'elijah' ),
			'menu_name' => __( 'Research Tips',	'elijah' ),
			'add_new' => __( 'Add New',	'elijah' ),
			'add_new_item' => __( 'Add New Tip', 'elijah' ),
			'edit' => __( 'Edit', 'elijah' ),
			'edit_item' => __( 'Edit Tip', 'elijah' ),
			'new_item' => __( 'New Tip', 'elijah' ),
			'view' => __( 'View Tip', 'elijah' ),
			'view_item' => __( 'View Tip', 'elijah' ),
			'search_items' => __( 'Search Tips', 'elijah' ),
			'not_found' => __( 'No Tips Found', 'elijah' ),
			'not_found_in_trash' => __( 'No Tips Found in Trash', 'elijah' ),
			'parent' => __( 'Paren Tip', 'elijah' ),
		),));
	
	//new post type for completed goals
	register_post_status( 
		'complete', 
		array(
			'label'                     => _x( 'Completed', 'elijah' ),
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Completed <span class="count">(%s)</span>', 'Completed <span class="count">(%s)</span>' ),
		) 
	);
	//also add it to the admin list, see http://wordpress.stackexchange.com/questions/89351/new-post-status-for-custom-post-type
	add_action('admin_footer-post.php', 
		function (){
		 global $post;
		 $complete = '';
		 $label = '';
		 if($post->post_type == 'research_goal'){
			  if($post->post_status == 'complete'){
				   $complete = ' selected=\"selected\"';
				   $label = '<span id=\"post-status-display\"> Completed</span>';
			  }
			  echo '
			  <script>
			  jQuery(document).ready(function($){
				   $("select#post_status").append("<option value=\"complete\" '.$complete.'>Completed</option>");
				   $(".misc-pub-section label").append("'.$label.'");
			  });
			  </script>
			  ';
		  }
		}
	);
}

add_action('init', 'elijah_register_cpts', 100);

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
