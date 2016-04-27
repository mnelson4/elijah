<?php
/**
 * Restricts some menu items according to capabilities
 * @param string $item_output
 * @param type $item
 * @param type $depth
 * @param type $args
 * @return string
 */
function add_description_to_menu($item_output, $item, $depth, $args) {
	if( in_array( $item->attr_title, array( 'my-research_goal', 'research-goal-editor' ) ) &&
			! current_user_can( 'edit_research_tip' ) ) {
		$item_output = '';
	}
    return $item_output;
}
add_filter('walker_nav_menu_start_el', 'add_description_to_menu', 10, 4);
// End of file menu.php