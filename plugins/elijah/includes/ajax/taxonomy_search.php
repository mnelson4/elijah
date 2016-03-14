<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


add_action('wp_ajax_elijah_taxonomy_country_search', 'elijah_taxonomy_country_search' );
function elijah_taxonomy_country_search( ) {
	$page = isset( $_REQUEST[ 'page' ] ) ? $_REQUEST[ 'page' ]: 0;
	$country_terms = get_terms(
			$_REQUEST[ 'taxonomy'],
			array(
				'parent' => elijah_get_anywhere_place_taxonomy_term_id( $_REQUEST[ 'taxonomy' ] ),
				'name__like' => $_REQUEST[ 'q' ],
				'number' => elijah_select2_count_per_ajax_request,
				'offset' => $page * elijah_select2_count_per_ajax_request,
				'fields' => 'id=>name',
				'hide_empty' => false,
				'hierarchical' => false,
		) );
	return _elijah_return_select2_response_from_terms( $country_terms );
}

add_action('wp_ajax_elijah_taxonomy_state_search', 'elijah_taxonomy_state_search' );
function elijah_taxonomy_state_search( ) {
	if( is_array( $_REQUEST[ 'countries' ] ) ) {
		$page = isset( $_REQUEST[ 'page' ] ) ? $_REQUEST[ 'page' ]: 0;
		$state_terms = get_terms(
				$_REQUEST[ 'taxonomy'],
				array(
					'parent' => end( $_REQUEST[ 'countries' ] ),
					'name__like' => $_REQUEST[ 'q' ],
					'number' => elijah_select2_count_per_ajax_request,
					'offset' => $page * elijah_select2_count_per_ajax_request,
					'fields' => 'id=>name',
					'hide_empty' => false,
					'hierarchical' => false,
			) );
	} else {
		$state_terms = array();
	}
	return _elijah_return_select2_response_from_terms( $state_terms );
	
}

/**
 * 
 * @param array $terms results of get_terms
 */
function _elijah_return_select2_response_from_terms( $terms ) {
	$results = array();
	foreach( $terms as $key => $value ) {
		$results[] = array( 'id' => $key, 'text' => $value );
	}
	echo json_encode( 
			array( 
				'results' => $results,
				'pagination' => array(
					'more' => count( $results ) < elijah_select2_count_per_ajax_request ? false : true,
		)) );
	die;
}