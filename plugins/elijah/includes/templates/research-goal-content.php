<?php
$post = $GLOBALS['post'];
wp_enqueue_script('debounce',plugins_url('js/jquery.debounce-1.0.5.js',elijah_main_file),array('jquery'));
wp_enqueue_script('elijah', plugins_url('js/elijah.js',elijah_main_file),array('jquery','debounce'));
wp_enqueue_style('elijah', plugins_url('css/elijah.css',elijah_main_file) );
wp_localize_script('elijah','elijah',array('ajaxurl'=>admin_url('admin-ajax.php'),'current_research_goal_id'=>$post->ID));
?>
<div class="post-body-plain">
	<?php  echo $content;?>
</div>
<div id="elijah-current-info-wrap-div">
	<div>
		<h2 style="display:inline;"><?php _e( 'Known Info', 'elijah'); ?></h2>
		<?php if( current_user_can( 'edit_research_goal', $post->ID ) ) {
		?> <a href="<?php echo elijah_get_frontend_editing_permalink( $post );?>"><?php _e( 'Edit', 'elijah' );?></a>
		<?php }?>
	</div>
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
								'connected_type' => 'work_done',
								'connected_items' => get_queried_object(),
								'connected_orderby' => 'last_updated',
								'connected_order' => 'asc',
								'nopaging' => true,
						  ) );
$work_done = $connected->posts;
?>

	<h2 id="elijah-reveal-elijah-work-done"><?php _e( 'Work Done', 'elijah'); ?></h2>
	<div class='elijah-work-done' id="elijah-work-done">
			<div class="work-done-items">
					<?php
					$work_done_ids = array();
					if( empty( $work_done ) ) {
						_e( 'None yet', 'elijah' );
					} else {
					foreach($work_done as $tip_applied){
						$work_done_ids[] = $tip_applied->ID;
						echo elijah_suggested_research_tip($tip_applied, $post);
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
									'post_type'=>array('research_tip'),
									'post__not_in' => $work_done_ids,
									'threshold'=>1,
									'limit' => 5,
//									'exclude'=>implode(",",$work_done_ids),
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
					<?php if( $results) foreach($results as $tip_suggested){

							echo elijah_suggested_research_tip($tip_suggested, $post );
							} ?>
			</div>
			<h2><?php _e( 'Do something else', 'elijah' );?></h2>
			<a href="javascript:history.go(0)"><button class="button button-primary"><?php _e( 'Refresh list', 'event_espesso' );?></button></a><br>
			<?php if( current_user_can( 'edit_research_tip' ) ){?>
				<a href="<?php echo get_permalink(elijah_edit_research_tip_page_id);?>"><button class="button button-primary"><?php _e( 'Add Tip', 'elijah' );?></button></a>
			<?php } ?>
	</div>
</div>

