<?php
/*
Plugin Name: Elijah
Plugin URI: http://google.com
Description: Elijah-specific features
Author: Mike Nelson
Version: 0.0.1
*/
define('elijah_version','0.0.1.dev');
define('elijah_root',dirname(__FILE__));
define('elijah_main_file',__FILE__);
define( 'elijah_templates_dir', elijah_root . '/includes/templates/' );


//do definitions
define( 'elijah_edit_research_goals_page_id', 167 );
define( 'elijah_edit_research_tip_page_id', 153 );
define( 'elijah_save_and_research_button_name', __( 'Save & Research', 'elijah' ) );
define( 'elijah_save_and_view_button_name', __( 'Save & View', 'elijah' ) );
define( 'elijah_select2_count_per_ajax_request', 30 );

//general init
require_once(elijah_root.'/includes/init/cpts.php');
require_once(elijah_root.'/includes/init/p2p.php');
require_once(elijah_root.'/includes/helpers/display.php');
require_once(elijah_root.'/includes/helpers/logic.php');
require_once(elijah_root.'/includes/queries/taxonomy_queries.php');


//admin-init
if(is_admin()){
	//actually only needed on post pagae when its a research goal
	require_once(elijah_root.'/includes/admin/research_goals.php');
	if (defined('DOING_AJAX') && DOING_AJAX) {
		require_once(elijah_root.'/includes/ajax/work_done_updates.php');
		require_once(elijah_root.'/includes/ajax/taxonomy_search.php');
	}
}
//frontned-init
else{
	require_once(elijah_root.'/includes/shortcodes.php');
	require_once( elijah_root.'/includes/frontend/menu.php');
	require_once( elijah_root. '/includes/frontend/class.front-controller.php' );
	require_once( elijah_root. '/includes/frontend/hooks.php' );
	wp_enqueue_style('elijah', plugins_url('css/elijah.css',elijah_main_file) );
}


