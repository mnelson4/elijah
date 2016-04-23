<?php

/* 
 * Add a filter to customize the research objective display
 */
add_filter( 'the_content', 'elijah_customize_cpt_content' );

function elijah_customize_cpt_content( $content ) {
//	var_dump( $GLOBALS['post'] );die;
	if ($GLOBALS['post']->post_type == 'research-objectives') {
		ob_start();
		include( elijah_templates_dir . 'research-objective-content.php' );
		$content = ob_get_contents();
		ob_end_clean();
	} elseif( $GLOBALS['post']->post_type == 'research-strategies') {
		ob_start();
		include( elijah_templates_dir . 'research-strategy-content.php' );
		$content = ob_get_contents();
		ob_end_clean();
	}
	return $content;
}
