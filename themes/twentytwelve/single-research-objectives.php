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

global $post;
$current_objective = $post;
wp_localize_script('elijah','elijah',array('ajaxurl'=>admin_url('admin-ajax.php'),'current_research_objective_id'=>$post->ID));?>
<div id="primary" >
	<div id="content" role="main">

<?php while (have_posts()) : the_post(); ?>

			<nav id="nav-single">
				<h3 class="assistive-text"><?php _e('Post navigation', 'twentyeleven'); ?></h3>
				<span class="nav-previous"><?php previous_post_link('%link', __('<span class="meta-nav">&larr;</span> Previous', 'twentyeleven')); ?></span>
				<span class="nav-next"><?php next_post_link('%link', __('Next <span class="meta-nav">&rarr;</span>', 'twentyeleven')); ?></span>
			</nav><!-- #nav-single -->

				<?php get_template_part('content-single', get_post_format()); ?>
			<div><h2><?php the_title()?> <?php edit_post_link( __( 'Edit', 'twentytwelve' ), '<span class="edit-link">', '</span>' ); ?></h2></div>
			<div id='research-objectives-sidebar' style='float:right'>
				<?php
				$connected = new WP_Query( array(
								'connected_type' => 'strategies_applied',
								'connected_items' => get_queried_object(),
								'nopaging' => true,
							  ) );
				$strategies_applied = $connected->posts;
//				foreach($connected->posts as $strategy_applied){
//					echo "strategy:".$strategy_applied->post_title;
//					$usefulness =  p2p_get_meta( $strategy_applied->p2p_id, 'usefulness', true );

//					echo "usefulness:".  elijah_pretty_usefulness($usefulness);
//				}

				?>
				<div class='work-done'>
					<h2><?php			_e("Work done", "event_espresso")?></h2>
					<div class="work-done-items">
						<?php
						$strategies_applied_ids = array();
						foreach($strategies_applied as $strategy_applied){
							$strategies_applied_ids[] = $strategy_applied->ID;
							echo elijah_suggested_research_strategy($strategy_applied, $post);
							} ?>
					</div>
				</div>
				<?php
				global $yarpp;

				$results = $yarpp->get_related(get_the_ID(),
							array(
								'post_type'=>array('research-strategies'),
									'wp_query_args' => array(
										'post__not_in' => $strategies_applied_ids,
										'post_type'=>array('research-strategies')
									),
									'threshold'=>1,
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
				<div class='work-todo'>
					<h2><?php			_e("Work Todos", "event_espresso")?></h2>
					<div class="work-todo-items">
						<?php foreach($results as $strategy_suggested){

							echo elijah_suggested_research_strategy($strategy_suggested, $post );
							} ?>
					</div>
				</div>
				<?php



//			  echo 'echodump of $connected->posts';
//			  echo "<div style='width:800px;overflow:auto'>";var_dump($connected->posts);
//			  echo " usefulness: ".p2p_get_meta( $post->p2p_id, 'usefulness', true );";
//			  echo "</div>";
//				yarpp_related(array('post_type'=>array('research-strategies'),
//									'threshold'=>1,
//									'weight'=>array(
//										//'body'=>1,
//										'tax'=>array(
//											'birthyear'=>1,
//											'birthplace'=>1,
//											'marriage-year'=>1,
//											'marriage-place'=>1,
//											'death-year'=>1,
//											'death-place'=>1,
//											'individual-details'=>5,
//
//										)
//									)),get_the_ID());
				//dynamic_sidebar('research_objectives_sidebar') ?>
			</div>
			<ul>
				<?php foreach (get_taxonomies(null, 'objects') as $taxonomy_name => $tax_object) { ?>
				<li><b><?php echo $tax_object->labels->singular_name ?></b>
						<?php
						$term_names = array();
						foreach (wp_get_post_terms($post->ID, $taxonomy_name) as $term_value) {
							$term_names[] = $term_value->name;
						}
						if(empty($term_names)){
							echo "Unknown";
						}else{
							echo implode(", ", $term_names);
						}
						?>
					</li>
					<?php
				}
				?>
			</ul>

			<?php comments_template('', true); ?>

<?php endwhile; // end of the loop.  ?>

	</div><!-- #content -->

</div><!-- #primary -->

<?php get_footer(); ?>