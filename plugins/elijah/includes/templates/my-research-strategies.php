<?php
/*
  Template Name: My Research Strategies
 */

get_header();
?>
<div id='primary' class='site-content'>
	<div id='content' role='main'>


		<h2><?php _e("My Research Strategies", "event_espresso") ?></h2>
		<?php if (current_user_can( 'edit_research-strategies' ) ) {?>
		<a href="<?php echo get_permalink( elijah_edit_research_strategies_page_id );?>"><button class="button button-primary"><?php _e( 'Add Research Strategy', 'event_espresso' );?></button></a>
		<?php } ?>
		<div class='my-research-strategies-list'>
			<?php
			if (get_current_user_id()) {
				$research_strategies = get_posts(array(
					'posts_per_page' => 5,
					'post_type' => 'research-strategies',
					'author' => get_current_user_id()
						));
				if ($research_strategies) {
					?>
					<?php foreach ($research_strategies as $research_strategy) { ?>
						<article>
							<header class='entry-header'>
								<h1 class='entry-title'><a href='<?php echo get_permalink($research_strategy->ID)?>'><?php echo $research_strategy->post_title ?></a></h1>
							</header>
							<div class='entry-content'>
								<p><?php echo $research_strategy->post_excerpt ?> </p>
								<p style='float:right'><?php echo elijah_get_research_status_title_for_post( $research_strategy->ID ); ?></p>
							</div>
						</article>
						<?php } ?>

				<?php } else {
					?>
			<h2><?php printf(__('You have no published research strategies.', "event_espresso"), "<a href='" . get_permalink( elijah_edit_research_strategies_page_id ) . "'>", "</a>");
					?></h2>
					<?php
				}
			} else {
				?>
				<p><?php printf(__('In order to create research strategies you must first %1$s become a member %2$s', "event_espresso"), "<a href='".site_url('wp-login.php?action=register')."'>", "</a>");
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