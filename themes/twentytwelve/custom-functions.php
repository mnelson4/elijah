<?php

//functions specific to my website

function elijah_widgets_init() {
	register_sidebar( array(
		'name' => 'Research Objectives Sidebar',
		'id' => 'research_objectives_sidebar',
		'before_widget' => '<div>',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="rounded">',
		'after_title' => '</h2>',
	) );
}
add_action( 'widgets_init', 'elijah_widgets_init' );