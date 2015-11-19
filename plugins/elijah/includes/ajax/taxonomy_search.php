<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

add_action('wp_ajax_elijah_taxonomy_search', 'elijah_taxonomy_search' );
function elijah_taxonomy_search( ) {
//	echo json_encode( array( 
//		'results' => array(
//			array( 'id' => 1, 'text' => 'one' ), 
//			array( 'id' => 2, 'text' => 'two' ),
//			),
//		'pagination' => array(
//			'more' => true
//		)) );
//	die;
	$country_terms = get_terms(
			$_REQUEST[ 'taxonomy'],
			array(
				'parent' => 0,
				'fields' => 'id=>name',
				'hide_empty' => false,
				'hierarchical' => false,
		) );
	$results = array();
	foreach( $country_terms as $key => $value ) {
		$results[] = array( 'id' => $key, 'text' => $value );
	}
	echo json_encode( 
			array( 
				'results' => $results,
				'pagination' => array(
					'more' => true
		)) );
	die;
//	var_dump( $_REQUEST );
}