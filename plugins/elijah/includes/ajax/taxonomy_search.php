<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


add_action('wp_ajax_elijah_taxonomy_country_search', 'elijah_taxonomy_country_search' );
function elijah_taxonomy_country_search( ) {
	$per_page = 30;
	$country_terms = get_terms(
			$_REQUEST[ 'taxonomy'],
			array(
				'parent' => 0,
				'number' => $per_page,
				'offset' => $_REQUEST[ 'page' ] * $per_page,
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
					'more' => count( $results ) < $per_page ? false : true
		)) );
	die;
}

add_action('wp_ajax_elijah_taxonomy_state_search', 'elijah_taxonomy_state_search' );
function elijah_taxonomy_state_search( ) {
	if( is_array( $_REQUEST[ 'countries' ] ) ) {
		$state_terms = get_terms(
				$_REQUEST[ 'taxonomy'],
				array(
					'parent' => end( $_REQUEST[ 'countries' ] ),
					'number' => $per_page,
					'offset' => $_REQUEST[ 'page' ] * $per_page,
					'fields' => 'id=>name',
					'hide_empty' => false,
					'hierarchical' => false,
			) );
		$results = array();
		foreach( $state_terms as $key => $value ) {
			$results[] = array( 'id' => $key, 'text' => $value );
		}
	} else {
		$results = array();
	}
	echo json_encode( 
			array( 
				'results' => $results,
				'pagination' => array(
					'more' => count( $results ) < $per_page ? false : true,
		)) );
	die;
}