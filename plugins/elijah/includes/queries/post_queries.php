<?php

/**
 * Gets all the research objectives that meet these criteria
 * @param array $args like those listed on http://codex.wordpress.org/Template_Tags/get_posts
 * @return WP_Post[]
 */
function elijah_get_my_research_objectives($args = array()){
	$default_args = array(
		'posts_per_page'=>10,
		'post_type'=>'research-objective'
	);
	$research_objectives = get_posts($default_args);
	return $research_objectives;
}