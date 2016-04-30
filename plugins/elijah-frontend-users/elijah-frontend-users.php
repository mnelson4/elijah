<?php
/*
Plugin Name: Elijah Frontend Users
Description: Keeps Users on the frontend
Author: Mike Nelson
Version: 0.0.1
*/

//change the topbar menu
function elijah_wp_nav_menu_args( $args = '' ) {

	if ($args['theme_location'] == 'topbar_navigation') {
		if( is_user_logged_in()) {
			$args['menu'] = 'topbar';
		}else{
			$args['menu'] = 'topbar-topbar-navigation-logged-out';
		}
	}
	return $args;
}
add_filter( 'wp_nav_menu_args', 'elijah_wp_nav_menu_args' );

//add logout button
function elijah_add_logout_button( $items, $args ) {
	if( $args->menu == 'topbar' ) {
		$redirect = is_home() ? false : get_permalink();
		$link = '<a href="' . wp_logout_url( $redirect ) . '">' . __( 'Logout', 'elijah' ) . '</a>';
		$items .= '<li class="menu-item menu-type-link">' . $link . '</li>';
	}
	return $items;
}
add_filter( 'wp_nav_menu_items', 'elijah_add_logout_button', 10, 2 );

/**
 * Replaces the string "current_user" in the menu with the current user's display name
 * @global type $current_user
 * @param type $items
 * @param type $args
 * @return string
 */
function elijah_replace_current_user( $items, $args ) {
	global $current_user;
	$items = str_replace('current_user', $current_user->display_name, $items );
	return $items;
}
add_filter( 'wp_nav_menu_items', 'elijah_replace_current_user', 10, 2 );

//redirect users to their research goals
add_filter( 'login_redirect', function( $url, $requested_url, $user ){
		if( $user instanceof WP_User ) {
			return '/author/' . $user->display_name;
		} else {
			return $url;
		}
	},
	20,
	3 
);
	
//and when they logout send them to the logged out page
add_filter( 'logout_redirect', function( $url, $requested_url, $user ){
		return '/logout';
	},
	20,
	3 
);