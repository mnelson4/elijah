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
	<p>
		<?php printf( __( 'A Research Strategy by %1$s', 'event_espresso' ), get_the_author() );
		if( current_user_can( 'edit_research-strategy', $post->ID ) ) {
		?> <a href="<?php echo elijah_get_frontend_editing_permalink( $post );?>"><?php _e( 'Edit', 'event_espresso' );?></a>
		<?php
	}?>
	</p>
	<div class="post-body-plain">
		<?php the_content();?>
	</div>
	<div id="elijah-current-info-wrap-div">
		<dl id="elijah-current-info">
			<?php
			$individual_details = implode(', ', wp_get_post_terms($post->ID, 'individual-details', array( 'fields' => 'names' ) ) );
			echo elijah_datalist_item( __( 'Use This Strategy When Searching for'), $individual_details ? $individual_details : __( 'Anything', 'event_espresso' ) );
			?>
			<h2><?php _e( '...and the individual being researched matched the following...', 'event_espresso' );?></h2>
			<?php
			echo elijah_datalist_item( __('Birthyear', 'event_espresso'), elijah_year_output( 'birthyear', $post->ID, ' ' ) );
			echo elijah_datalist_item( __( 'Birthplace', 'event_espresso'), elijah_places_output( 'birthplace', $post->ID, ' ' ) );
			echo elijah_datalist_item( __( 'Marriage Year', 'event_espresso'), elijah_year_output( 'marriage-year', $post->ID, ' ' ) );
			echo elijah_datalist_item( __( 'Marriage Place', 'event_espresso'), elijah_places_output( 'marriage-place', $post->ID, ' ' ) );
			echo elijah_datalist_item( __( 'Childrens\' Birthyears', 'event_espresso'), elijah_year_output( 'childrens-birthyears', $post->ID, ' ' ) );
			echo elijah_datalist_item( __( 'Childrens\' Birthplaces', 'event_espresso'), elijah_places_output( 'childrens-birthplaces', $post->ID, ' ' ) );
			echo elijah_datalist_item( __( 'Death Year', 'event_espresso'), elijah_year_output( 'death-year', $post->ID, ' ' ) );
			echo elijah_datalist_item( __( 'Death Place', 'event_espresso'), elijah_places_output( 'deathplace', $post->ID, ' ' ) );
			?>
		</dl>
	</div>
	<?php comments_template('', true); ?>

<?php endwhile; // end of the loop.  ?>

	</div><!-- #content -->

</div><!-- #primary -->

<?php get_footer(); ?>