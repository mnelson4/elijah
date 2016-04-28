<?php

function elijah_save_research_goal_status($post_id) {

	global $wp_post_types;
	$slug = $wp_post_types['research_goal']->rewrite['slug'];

    /* check whether anything should be done */
//    $_POST += array("{$slug}_edit_nonce" => '');
//    if ( $slug != $_POST['post_type'] ) {
//        return;
//    }
    if ( !current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
//    if ( !wp_verify_nonce( $_POST["{$slug}_edit_nonce"],
//                           'Elijah' ) )
//    {
//        return;
//    }

    /* Request passes all checks; update the post's metadata */
    if (isset($_REQUEST['research_status'])) {
        update_post_meta($post_id, 'research_status', $_REQUEST['research_status']);
    }else{
		update_post_meta($post_id,'research_status','enqueued');
	}
}

add_action( 'save_post', 'elijah_save_research_goal_status');
