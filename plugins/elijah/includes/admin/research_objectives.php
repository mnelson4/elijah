<?php
function elijah_add_research_status(){
	$post_id = $_GET['post'];
	if( 'research-objectives' != get_post_type( $post_id ) ){
		return;
	}
	?>
<div class='misc-pub-section'>
	<?php _e("Research Status: ", "event_espresso");?>
	<span style='font-weight:bold'><?php	echo elijah_research_status_dropdown($post_id);?></span>
</div>
<?php

}
add_action('post_submitbox_misc_actions','elijah_add_research_status');

/**
 * Renders a dropdown select for the objective's research strategy
 * @global type $elijah_research_statuses
 * @param type $post_id
 */
function elijah_research_status_dropdown( $post_id ){
	$current_research_status = get_post_meta( $post_id, 'research_status', TRUE );
	global $elijah_research_statuses;
	?>
<select name='research_status'>
	<?php foreach( $elijah_research_statuses as $status => $options ){
		?>
	<option value="<?php echo $status?>" <?php echo $current_research_status == $status ? 'selected="selected"' : ''?>><?php echo $options['title']?></option>
		<?php

	}?>
</select>
<?php
}

function elijah_save_research_objective_status($post_id) {

	global $wp_post_types;
	$slug = $wp_post_types['research-objectives']->rewrite['slug'];

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

add_action( 'save_post', 'elijah_save_research_objective_status');
