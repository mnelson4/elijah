<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * Finds the term_id for the term-taxonomy "anywhere" for this taxonomy
 * @param string $taxonomy
 * @return int
 */
function elijah_get_anywhere_place_taxonomy_term_id( $taxonomy ) {
	$anywhere_tax = get_term_by( 'slug', 'anywhere', $taxonomy );
	if( is_object( $anywhere_tax ) ) {
		$top_level_term_id = $anywhere_tax->term_id;
	} else {
		$top_level_term_id = 0;
	}
	return $top_level_term_id;
}

/**
 * 
 * @param type $terms
 * @return array keys are term ids, values are term names 
 *	(if the term is a state name, includes the country name too)
 */
function elijah_prepare_country_term_taxonomies( $terms ) {
	$results = array();
	foreach( $terms as $term ) {
		$parent_term = get_term_by( 'id', $term->parent, $term->taxonomy );
		if( ! is_object( $parent_term ) || $parent_term->slug == 'anywhere' ) {
			$full_name = $term->name;
		} else {
			$full_name = $term->name . ', ' . $parent_term->name;
		}
		$results[ $term->term_id ] = $full_name ;
	}
	return $results;
}