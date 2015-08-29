<?php

function my_research_objectives_shortcode_datatables($atts){
//	extract( shortcode_atts( array(
//		'foo' => 'something',
//		'bar' => 'something else',
//	), $atts ) );
	wp_register_script('dataTables','http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js',array('jquery'));
	wp_enqueue_script('dataTables');

	$code = <<<HEREDOC
	<script>
		jQuery(document).ready(function() {
    jQuery('#my_research_objectives_table').dataTable( {
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": ajaxurl,
        "fnServerParams": function ( aoData ) {
            aoData.push( { "name": "more_data", "value": "my_value" } );
        }
    } );
} );

	</script>

<table id='my_research_objectives_table'>
<thead>
<tr>
<th>#</th>
<th>Objective</th>
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
add_shortcode('my_research_objectives','my_research_objectives_shortcode');

function elijah_add_research_strategy_shortcode( $atts ) {
	$individual_details_terms = get_terms( 'individual-details' );
	$post_id = isset( $_GET[ 'research_strategy' ] ) ? intval( $_GET[ 'research_strategy' ] ) : 0;
	ob_start();
	include( elijah_root . '/includes/templates/add-research-strategy.php' );
	return ob_get_clean();
}
add_shortcode( 'elijah_add_research_strategy', 'elijah_add_research_strategy_shortcode' );
