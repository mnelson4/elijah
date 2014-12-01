<?php
/*
  Template Name: My Research Objectives
 */

get_header();
?>
<div id='primary' class='site-content'>
	<div id='content' role='main'>


		<h2><?php _e("My Research Objectives", "event_espresso") ?></h2>
		<div class='my-research-objectives-list'>
			<?php
			if (get_current_user_id()) {
				$research_objectives = get_posts(array(
					'posts_per_page' => 5,
					'post_type' => 'research-objectives',
					'author' => get_current_user_id()
						));
				if ($research_objectives) {
					?>
					<?php foreach ($research_objectives as $research_objective) { ?>
						<article>
							<header class='entry-header'>
								<h1 class='entry-title'><a href='<?php echo get_permalink($research_objective->ID)?>'><?php echo $research_objective->post_title ?></a></h1>
							</header>
							<div class='entry-content'>
								<p><?php echo $research_objective->post_excerpt ?> </p>
								<p style='float:right'><?php echo elijah_get_research_status_title_for_post( $research_objective->ID ); ?></p>
							</div>
						</article>
						<?php } ?>
					
				<?php } else {
					?>
					<h2><?php printf(__('You have no current research objectives. Please %1$s create one %2$s', "event_espresso"), "<a href='" . admin_url("post-new.php?post_type=research-objectives") . "'>", "</a>");
					?></h2>
					<?php
				}
			} else {
				?>
				<p><?php printf(__('In order to create research objectives you must first %1$s become a member %2$s', "event_espresso"), "<a href='".site_url('wp-login.php?action=register')."'>", "</a>");
				?></p>
				<p><?php printf(__("Already a member? %s Login %s", "event_espresso"),"<a href='".site_url('wp-login.php')."'>","</a>");?></p>
				<?php
			}
			?>
		</div>
	</div>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>