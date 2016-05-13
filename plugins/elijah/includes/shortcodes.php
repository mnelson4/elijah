<?php

function my_research_goals_shortcode_datatables($atts){
//	extract( shortcode_atts( array(
//		'foo' => 'something',
//		'bar' => 'something else',
//	), $atts ) );
	wp_register_script('dataTables','http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js',array('jquery'));
	wp_enqueue_script('dataTables');

	$code = <<<HEREDOC
	<script>
		jQuery(document).ready(function() {
    jQuery('#my_research_goals_table').dataTable( {
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": ajaxurl,
        "fnServerParams": function ( aoData ) {
            aoData.push( { "name": "more_data", "value": "my_value" } );
        }
    } );
} );

	</script>

<table id='my_research_goals_table'>
<thead>
<tr>
<th>#</th>
<th>goal</th>
<th>Description</th>
<th>Status</th>
</tr>
</thead>
<tbody>

</tbody>

</table>

HEREDOC;
	return $code;
}
add_shortcode('my_research_goals','my_research_goals_shortcode');

/**
 * Shortcode taht renders a form for creating or editing research tip.
 * The actual logic of handling the form submission is ocntained in elijah/includes/class.front-controller.php
 * @param array $atts
 * @return string
 */
function elijah_edit_research_tip_shortcode( $atts ) {
	return _elijah_show_research_thing( 'tip' );
}
add_shortcode( 'elijah_edit_research_tip', 'elijah_edit_research_tip_shortcode' );

/**
 * Shortcode taht renders a form for creating or editing research goals.
 * The actual logic of handling the form submission is ocntained in elijah/includes/class.front-controller.php
 * @param array $atts
 * @return string
 */
function elijah_edit_research_goal_shortcode( $atts ) {
	return _elijah_show_research_thing( 'goal' );
}
add_shortcode( 'elijah_edit_research_goal', 'elijah_edit_research_goal_shortcode' );

/**
 * Handles common logic between the two edit-thing shortcodes
 * @param string $thing
 * @return string
 */
function _elijah_show_research_thing( $thing ) {
	$individual_details_terms = get_terms( 
		'individual-details',
		array(
			'hide_empty' => false
		)
	);
	$post_id = isset( $_GET[ $thing . '_id' ] ) ? intval( $_GET[ $thing . '_id' ] ) : 0;
	if( $post_id && current_user_can( 'edit_research_' . $thing, $post_id ) ) {
		$post = get_post( $post_id );
	} else {
		$post = null;
	}
	ob_start();
	include( elijah_root . '/includes/templates/edit-research-' . $thing . '.php' );
	return ob_get_clean();
}
