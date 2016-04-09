<?php
/*
Plugin Name: Elijah Frontend Users
Description: Keeps Users on the frontend
Author: Mike Nelson
Version: 0.0.1
*/

//change the topbar menu
function my_wp_nav_menu_args( $args = '' ) {

	if ($args['theme_location'] == 'topbar_navigation') {
		if( is_user_logged_in()) {
			$args['menu'] = 'topbar';
		}else{
			$args['menu'] = 'topbar-topbar-navigation-logged-out';
		}
	}
	return $args;
}
add_filter( 'wp_nav_menu_args', 'my_wp_nav_menu_args' );
