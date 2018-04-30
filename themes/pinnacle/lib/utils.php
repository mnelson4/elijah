<?php

/**
 * Page titles
 */
function pinnacle_title() {
  	if (is_home()) {
	    if (get_option('page_for_posts', true)) {
	      $title = get_the_title(get_option('page_for_posts', true));
	    } else {
	      $title = __('Latest Posts', 'pinnacle');
	    }
  	} elseif (is_archive()) {
	   $title = get_the_archive_title();
  	} elseif (is_search()) {
    	$title = sprintf(__('Search Results for %s', 'pinnacle'), get_search_query());
  	} elseif (is_404()) {
    	$title = __('Not Found', 'pinnacle');
  	} else {
    	$title = get_the_title();
  	}
  	return apply_filters('kadence_title', $title);
}

function kad_is_element_empty($element) {
  	$element = trim($element);
  	return empty($element) ? false : true;
}

add_filter('get_the_archive_title', 'pinnacle_filter_archive_title');
function pinnacle_filter_archive_title($title){
		$term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
    	if ( is_category() ) {
      		$title = single_cat_title( '', false );
    	} elseif ( is_tag() ) {
        	$title = single_tag_title( '', false );
    	} elseif (is_author()) {
      		$title = sprintf(__('Author: %s', 'pinnacle'), get_the_author());
    	} else if ($term) {
      		$title = $term->name;
    	} elseif (is_day()) {
      		$title = sprintf(__('Day: %s', 'pinnacle'), get_the_date());
    	} elseif (is_month()) {
      		$title = sprintf(__('Month: %s', 'pinnacle'), get_the_date('F Y'));
    	} elseif (is_year()) {
      		$title = sprintf(__('Year: %s', 'pinnacle'), get_the_date('Y'));
    	} 
    	return $title;
}