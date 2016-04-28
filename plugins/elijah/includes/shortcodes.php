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
	$individual_details_terms = get_terms( 
		'individual-details',
		array(
			'hide_empty' => false
		)
	);
	$post_id = isset( $_GET[ 'tip_id' ] ) ? intval( $_GET[ 'tip_id' ] ) : 0;
	ob_start();
	include( elijah_root . '/includes/templates/edit-research-tip.php' );
	return ob_get_clean();
}
add_shortcode( 'elijah_edit_research_tip', 'elijah_edit_research_tip_shortcode' );

/**
 * Shortcode taht renders a form for creating or editing research goals.
 * The actual logic of handling the form submission is ocntained in elijah/includes/class.front-controller.php
 * @param array $atts
 * @return string
 */
function elijah_edit_research_goal_shortcode( $atts ) {
	$individual_details_terms = get_terms( 'individual-details' );
	$post_id = isset( $_GET[ 'goal_id' ] ) ? intval( $_GET[ 'goal_id' ] ) : 0;
	$post = get_post( $post_id );
	ob_start();
	include( elijah_root . '/includes/templates/edit-research-goal.php' );
	return ob_get_clean();
}
add_shortcode( 'elijah_edit_research_goal', 'elijah_edit_research_goal_shortcode' );
