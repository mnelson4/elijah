<?php
/*WebDevStudios.com
Plugin Name: Elijah
Plugin URI: http://google.com
Description: Elijah-specific features
Author: Mike Nelson
Version: 0.0.1
*/
define('elijah_version','0.0.1.dev');
define('elijah_root',dirname(__FILE__));
define('elijah_main_file',__FILE__);


//do definitions

//general init
require_once(elijah_root.'/includes/init/cpts.php');
require_once(elijah_root.'/includes/init/p2p.php');
require_once(elijah_root.'/includes/helpers/display.php');
require_once(elijah_root.'/includes/helpers/logic.php');


//admin-init
if(is_admin()){
	//actually only needed on post pagae when its a research objective
	require_once(elijah_root.'/includes/admin/research_objectives.php');
	if (defined('DOING_AJAX') && DOING_AJAX) {
		require_once(elijah_root.'/includes/ajax/strategy_updates.php');
	}
}
//frontned-init
else{
	require_once(elijah_root.'/includes/shortcodes.php');
}


