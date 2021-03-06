<?php

/* 
 * Add a filter to customize the research goal display
 */
function elijah_customize_cpt_content( $content ) {
	if( ! is_archive() && ! is_author() && !is_search() ) {
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
	}
	return $content;
}
add_filter( 'the_content', 'elijah_customize_cpt_content' );

//add checkmarks onto goal titles
add_filter( 
	'the_title', 
	function( $title, $post_id ) {
		$post = get_post( $post_id );
		if( $post instanceof WP_Post 
			&& $post->post_type === 'research_goal'
		) {
			if( $post->post_status === 'complete' ) {
				$title = '☑ ' . sprintf( '%1$s (%2$s)', $title, __( 'Completed', 'elijah' ) );
			} elseif( $post->post_status === 'draft' ) {
				$title = '✍ ' . sprintf( '%1$s (%2$s)', $title, __( 'Draft', 'event_espresso' ) );
			} else{
				$title = '◻ ' . $title;
			}
		}elseif( $post instanceof WP_Post 
			&& $post->post_type === 'research_tip'
		) {
			if( $post->post_status === 'draft' ) {
				$title = '✍ ' . sprintf( '%1$s (%2$s)', $title, __( 'Draft', 'event_espresso' ) );
			}
		}
		return $title;
	},
	11,
	2
);

/* Use research goals for author archive page instead of posts */
function elijah_post_author_archive($query) {
    if ($query->is_author && empty( $_GET['post_type'] ) ) {
        $query->set( 'post_type', array('research_goal' ) );
	} elseif( ! empty( $_GET['s'])) {
		$query->set( 'post_type', array( 'research_goal', 'research_tip' ) );
	}
    remove_action( 'pre_get_posts', 'elijah_post_author_archive' );
}
add_action('pre_get_posts', 'elijah_post_author_archive');

add_action( 'pre_get_posts', function ( $query ) {

    if( is_author() ) {
		$current_post_type_info = get_post_type_object( elijah_get_single_post_type_from_wp_query() );
		if( $current_post_type_info ) {
			$viewed_user = elijah_get_author_from_wp_query();
			$viewed_user_display_name = $viewed_user->display_name;
			$current_user = wp_get_current_user();
			$current_post_type_label = $current_post_type_info->labels->name;
			if( $viewed_user_display_name === $current_user->display_name ) {
				if( $query instanceof WP_Query ) {
					$query->set( 'post_status', array( 'publish', 'draft', 'complete' ) );
				}
			}
		}	
    }
    return $query;
});


function elijah_init_modify_cpt_titles_on_search() {
	
	if( ! empty( $_GET['s'] ) ) {
		add_filter( 'the_title', 'elijah_customize_cpt_titles_on_search', 10, 2 );
	}
}
add_action( 'init', 'elijah_init_modify_cpt_titles_on_search' );

function elijah_customize_cpt_titles_on_search( $title, $post_id ) {
	$post = get_post( $post_id );
	if( $post instanceof WP_Post ) {
		if( $post->post_type === 'research_goal') {
			$title = __( 'Goal: ', 'event_espresso' ) . $title;
		} elseif( $post->post_type === 'research_tip' ) {
			$title = __( 'Tip: ', 'event_espresso' ). $title;
		}
	}
	return $title;
}