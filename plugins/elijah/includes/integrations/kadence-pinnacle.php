<?php
//customize the author archive page title for pages which include only one custom post type
add_filter( 'kadence_title', function ( $title ) {

    if( is_author() ) {
		$current_post_type_info = get_post_type_object( elijah_get_single_post_type_from_wp_query() );
		if( $current_post_type_info ) {
			$viewed_user = elijah_get_author_from_wp_query();
			$viewed_user_display_name = $viewed_user->display_name;
			$current_user = wp_get_current_user();
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
    }
    return $title;

});

/**
 * I manually added this filter onto pinnacle theme's archive.php page which filters the subtitle
 */
add_filter( 'kadence_page_subtitle', function( $title, $page, $pinnacle ) {
	if( is_author() ) {
		$viewed_user = elijah_get_author_from_wp_query();
		$viewed_user_display_name = $viewed_user->display_name;
		$current_user = wp_get_current_user();
		$current_post_type_info = get_post_type_object( elijah_get_single_post_type_from_wp_query() );
		$current_post_type_label = $current_post_type_info->labels->name;
		if( $viewed_user_display_name === $current_user->display_name ) {
			$link = $current_post_type_info->name == 'research_goal' ? get_permalink( elijah_edit_research_goals_page_id ) : get_permalink( elijah_edit_research_tip_page_id );
			$title = '<a href="' . $link . '" class="button button-primary">' . $current_post_type_info->labels->add_new_item . '</a>';
				
		}
    }

    return $title;
},
	10,
	3
);

/**
 * 
 * @return WP_User
 */
function elijah_get_author_from_wp_query() {
	global $wp_query;
	$viewed_user_nicename = $wp_query->query_vars['author_name'];
	return get_user_by( 'slug', $viewed_user_nicename );
}

function elijah_get_single_post_type_from_wp_query() {
	global $wp_query;
	if (
			is_array( $wp_query->query_vars['post_type'] ) 
			&& count( $wp_query->query_vars['post_type'] ) == 1
		) {
		$current_post_type = reset( $wp_query->query_vars['post_type'] );
	}elseif( is_string( $wp_query->query_vars['post_type'] )  ) {
		$current_post_type = $wp_query->query_vars[ 'post_type' ];
	} else {
		$current_post_type = null;
	}
	return $current_post_type;
}