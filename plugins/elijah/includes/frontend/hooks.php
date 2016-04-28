<?php

/* 
 * Add a filter to customize the research goal display
 */


function elijah_customize_cpt_content( $content ) {
//	var_dump( $GLOBALS['post'] );die;
	if ($GLOBALS['post']->post_type == 'research_goal') {
		ob_start();
		include( elijah_templates_dir . 'research-goal-content.php' );
		$content = ob_get_contents();
		ob_end_clean();
	} elseif( $GLOBALS['post']->post_type == 'research_tip') {
		ob_start();
		include( elijah_templates_dir . 'research-tip-content.php' );
		$content = ob_get_contents();
		ob_end_clean();
	}
	return $content;
}
add_filter( 'the_content', 'elijah_customize_cpt_content' );

//add checkmarks
add_filter( 
	'the_title', 
	function( $title, $post_id ) {
		$post = get_post( $post_id );
		if( $post instanceof WP_Post 
			&& $post->post_type === 'research_goal'
		) {
			if( $post->post_status === 'complete' ) {
				$title = '☑ ' . sprintf( '%1$s (%2$s)', $title, __( 'Completed', 'elijah' ) );
			} else {
				$title = '◻ ' . $title;
			}
		}
		return $title;
	},
	10,
	2
);

/* Use research goals for author archive page instead of posts */
function custom_post_author_archive($query) {
    if ($query->is_author)
        $query->set( 'post_type', array('research_goal' ) );
    remove_action( 'pre_get_posts', 'custom_post_author_archive' );
}
add_action('pre_get_posts', 'custom_post_author_archive');

add_filter( 'kadence_title', function ( $title ) {
    if( is_author() ) {
		$viewed_user_display_name = get_the_author();
		$current_user = wp_get_current_user();
		global $wp_query;
		$current_post_type = reset( $wp_query->query_vars['post_type'] );
		$current_post_type_info = get_post_type_object( $current_post_type );
		$current_post_type_label = $current_post_type_info->labels->name;
		if( $viewed_user_display_name === $current_user->display_name ) {
			$title = sprintf(
				__( 'My %1$s', 'event_espresso' ),
				$current_post_type_label
			);
		} else {
			$title = sprintf( 
				__( '%1$s of %2$s', 'event_espresso' ),
				$current_post_type_label,
				$viewed_user_display_name
			);
		}
    }

    return $title;

});

/**
 * I manually added this filter onto pinnacle theme's archive.php page which filters the subtitle
 */
add_filter( 'kadence_subtitle', function( $title, $page, $pinnacle ) {
	if( is_author() ) {
		$viewed_user_display_name = get_the_author();
		$current_user = wp_get_current_user();
		global $wp_query;
		$current_post_type = reset( $wp_query->query_vars['post_type'] );
		$current_post_type_info = get_post_type_object( $current_post_type );
		$current_post_type_label = $current_post_type_info->labels->name;
		if( $viewed_user_display_name === $current_user->display_name ) {
			$title = '<a href="' . get_permalink( elijah_edit_research_goals_page_id ) . '" class="button button-primary">' . __( 'Add New Research Goal', 'event_espresso' ) . '</a>';
				
		}
    }

    return $title;
},
	10,
	3
);