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