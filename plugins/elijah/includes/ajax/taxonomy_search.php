<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


add_action('wp_ajax_elijah_place_taxonomy_search', 'elijah_place_taxonomy_search' );
function elijah_place_taxonomy_search( ) {
	$page = isset( $_REQUEST[ 'page' ] ) ? $_REQUEST[ 'page' ]: 0;
	$country_terms = get_terms(
			$_REQUEST[ 'taxonomy'],
			array(
//				'parent' => elijah_get_anywhere_place_taxonomy_term_id( $_REQUEST[ 'taxonomy' ] ),
				'name__like' => $_REQUEST[ 'q' ],
				'number' => elijah_select2_count_per_ajax_request,
				'offset' => $page * elijah_select2_count_per_ajax_request,
				'fields' => 'all',
				'hide_empty' => false,
				'hierarchical' => false,
		) );
	return _elijah_return_select2_response_from_terms( $country_terms );
}

/**
 * 
 * @param array $terms results of get_terms
 */
function _elijah_return_select2_response_from_terms( $terms ) {
	
	$results = array();
	foreach( elijah_prepare_country_term_taxonomies( $terms ) as $id =>$term_name ) {
		$results[] = array( 'id' => $id, 'text' => $term_name );
	}
	echo json_encode( 
			array( 
				'results' => $results,
				'pagination' => array(
					'more' => count( $results ) < elijah_select2_count_per_ajax_request ? false : true,
		)) );
	die;
}