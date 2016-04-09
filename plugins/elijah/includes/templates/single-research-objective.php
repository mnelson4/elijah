<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
get_header();
wp_enqueue_script('debounce',plugins_url('js/jquery.debounce-1.0.5.js',elijah_main_file),array('jquery'));
wp_enqueue_script('elijah', plugins_url('js/elijah.js',elijah_main_file),array('jquery','debounce'));
wp_enqueue_style('elijah', plugins_url('css/elijah.css',elijah_main_file) );

global $post;
$current_objective = $post;
wp_localize_script('elijah','elijah',array('ajaxurl'=>admin_url('admin-ajax.php'),'current_research_objective_id'=>$post->ID));?>
<div id="primary" >
	<div id="content" role="main">
<?php while (have_posts()) : the_post(); ?>

	<?php get_template_part('content-single', get_post_format()); ?>
	<h1><?php the_title()?></h1>
	<h2><?php echo elijah_pretty_research_objective_status( $post-ID); ?></h2>
	<p>
		<?php printf( __( 'A Research Objective of %1$s', 'elijah' ), get_the_author() );
		if( current_user_can( 'edit_research-objective', $post->ID ) ) {
		?> <a href="<?php echo elijah_get_frontend_editing_permalink( $post );?>"><?php _e( 'Edit', 'elijah' );?></a>
		<?php
	}?>
	</p>
	<div class="post-body-plain">
		<?php the_content();?>
	</div>
	<div id="elijah-current-info-wrap-div">
		<h2><?php _e( 'Known Info', 'elijah'); ?></h2>
		<dl id="elijah-current-info">
			<?php
			echo elijah_datalist_item(__( 'Searching for'), implode(', ', wp_get_post_terms($post->ID, 'individual-details', array( 'fields' => 'names' ) ) ) );
			echo elijah_datalist_item(__('Birthyear', 'elijah'), elijah_year_output( 'birthyear', $post->ID, ' ' ) );
			echo elijah_datalist_item( __( 'Birthplace', 'elijah'), elijah_places_output( 'birthplace', $post->ID, ' ' ) );
			echo elijah_datalist_item( __( 'Marriage Year', 'elijah'), elijah_year_output( 'marriage-year', $post->ID, ' ' ) );
			echo elijah_datalist_item( __( 'Marriage Place', 'elijah'), elijah_places_output( 'marriage-place', $post->ID, ' ' ) );
			echo elijah_datalist_item( __( 'Childrens\' Birthyears', 'elijah'), elijah_year_output( 'childrens-birthyears', $post->ID, ' ' ) );
			echo elijah_datalist_item( __( 'Childrens\' Birthplaces', 'elijah'), elijah_places_output( 'childrens-birthplaces', $post->ID, ' ' ) );
			echo elijah_datalist_item( __( 'Death Year', 'elijah'), elijah_year_output( 'death-year', $post->ID, ' ' ) );
			echo elijah_datalist_item( __( 'Death Place', 'elijah'), elijah_places_output( 'deathplace', $post->ID, ' ' ) );
			?>
		</dl>
	</div>
	<div id="elijah-work-done-wrap-div">
	<?php
	$connected = new WP_Query( array(
									'connected_type' => 'strategies_applied',
									'connected_items' => get_queried_object(),
									'connected_orderby' => 'last_updated',
									'connected_order' => 'asc',
									'nopaging' => true,
							  ) );
	$strategies_applied = $connected->posts;
	?>

		<h2 id="elijah-reveal-elijah-work-done"><?php _e( 'Work Done', 'elijah'); ?></h2>
		<div class='elijah-work-done' id="elijah-work-done">
				<div class="work-done-items">
						<?php
						$strategies_applied_ids = array();
						if( empty( $strategies_applied ) ) {
							_e( 'None yet', 'elijah' );
						} else {
						foreach($strategies_applied as $strategy_applied){
							$strategies_applied_ids[] = $strategy_applied->ID;
							echo elijah_suggested_research_strategy($strategy_applied, $post);
							}
						}?>
				</div>
		</div>
	</div>
	<div id="elijah-work-todo-div">
		<?php
		global $yarpp;

		$results = $yarpp->get_related(get_the_ID(),
								array(
										'post_type'=>array('research-strategies'),
										'post__not_in' => $strategies_applied_ids,
										'threshold'=>1,
										'limit' => 5,
//									'exclude'=>implode(",",$strategies_applied_ids),
										'weight'=>array(
												//'body'=>1,
												'tax'=>array(
														'birthyear'=>1,
														'birthplace'=>1,
														'marriage-year'=>1,
														'marriage-place'=>1,
														'death-year'=>1,
														'death-place'=>1,
														'individual-details'=>5,

												)
												)));
		?>
		<h2 id="elijah-reveal-elijah-work-todo"><?php _e( 'Work To-Do', 'elijah'); ?></h2>
		<div id="elijah-work-todo">
				<div class="work-todo-items">
						<?php foreach($results as $strategy_suggested){

								echo elijah_suggested_research_strategy($strategy_suggested, $post );
								} ?>
				</div>
				<h2><?php _e( 'Do something else', 'elijah' );?></h2>
				<a href="javascript:history.go(0)"><button class="button button-primary"><?php _e( 'Refresh list', 'event_espesso' );?></button></a><br>
				<?php if( current_user_can( 'edit_research-strategies' ) ){?>
					<a href="<?php echo get_permalink(185);?>"><button class="button button-primary"><?php _e( 'Add Research Strategy', 'elijah' );?></button></a>
				<?php } ?>
		</div>
	</div>

	<?php comments_template('', true); ?>

<?php endwhile; // end of the loop.  ?>

	</div><!-- #content -->

</div><!-- #primary -->

<?php get_footer(); ?>