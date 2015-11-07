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
			<div><h1><?php the_title()?></h1></div>
                        <div id="elijah-current-info-wrap-div">
                            <a id="elijah-reveal-elijah-current-info" class="elijah-reveal"><?php _e( 'Known Info', 'event_espress'); ?></a>
                            <div id="elijah-current-info" class="elijah-start-hidden">
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
                            </div>
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
//				foreach($connected->posts as $strategy_applied){
//					echo "strategy:".$strategy_applied->post_title;
//					$usefulness =  p2p_get_meta( $strategy_applied->p2p_id, 'usefulness', true );

//					echo "usefulness:".  elijah_pretty_usefulness($usefulness);
//				}

                        ?>
                        
                            <a id="elijah-reveal-elijah-work-done" class="elijah-reveal"><?php _e( 'Work Done', 'event_espress'); ?></a>
                            <div class='elijah-work-done elijah-start-hidden' id="elijah-work-done">
                                    <div class="work-done-items">
                                            <?php
                                            $strategies_applied_ids = array();
                                            foreach($strategies_applied as $strategy_applied){
                                                    $strategies_applied_ids[] = $strategy_applied->ID;
                                                    echo elijah_suggested_research_strategy($strategy_applied, $post);
                                                    } ?>
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
                            <a id="elijah-reveal-elijah-work-todo" class="elijah-reveal"><?php _e( 'Work To-Do', 'event_espress'); ?></a>
                            <div id="elijah-work-todo" class="elijah-start-hidden">
                                    <div class="work-todo-items">
                                            <?php foreach($results as $strategy_suggested){

                                                    echo elijah_suggested_research_strategy($strategy_suggested, $post );
                                                    } ?>
                                    </div>
                                    <h2><?php _e( 'Do something else', 'event_espresso' );?></h2>
                                    <?php if( current_user_can( 'edit_research-strategies' ) ){?>
                                        <a href="<?php echo get_permalink(185);?>"><button class="button button-primary"><?php _e( 'Add Research Strategy', 'event_espresso' );?></button></a>
                                    <?php } ?>
                            </div>
                        </div>
			<?php comments_template('', true); ?>

<?php endwhile; // end of the loop.  ?>

	</div><!-- #content -->

</div><!-- #primary -->

<?php get_footer(); ?>