<?php
global $post;
wp_enqueue_script('debounce',plugins_url('js/jquery.debounce-1.0.5.js',elijah_main_file),array('jquery'));
wp_enqueue_script('elijah', plugins_url('js/elijah.js',elijah_main_file),array('jquery','debounce'));
wp_enqueue_style('elijah', plugins_url('css/elijah.css',elijah_main_file) );
wp_localize_script('elijah','elijah',array('ajaxurl'=>admin_url('admin-ajax.php'),'current_research_goal_id'=>$post->ID));
if( current_user_can( 'edit_research_tip', $post->ID ) ) {
	?> <a href="<?php echo elijah_get_frontend_editing_permalink( $post );?>"><?php _e( 'Edit Tip', 'elijah' );?></a>
<?php }?>
<div class="post-body-plain">
	<?php echo $content?>
</div>
<div id="elijah-current-info-wrap-div">
	<dl id="elijah-current-info">
		<?php
		$tip_type = implode(', ', wp_get_post_terms($post->ID, 'tip-type', array( 'fields' => 'names' ) ) );
		echo elijah_datalist_item( __( 'Type'), $tip_type );
		
		$individual_details = implode(', ', wp_get_post_terms($post->ID, 'individual-details', array( 'fields' => 'names' ) ) );
		echo elijah_datalist_item( __( 'Use This Strategy When Searching for'), $individual_details ? $individual_details : __( 'Anything', 'elijah' ) );
		?>
		<dt><?php _e( '...and the individual being researched matched the following...', 'elijah' );?></dt>
		<?php
		echo elijah_datalist_item( __('Birthyear', 'elijah'), elijah_year_output( 'birthyear', $post->ID ) );
		echo elijah_datalist_item( __( 'Birthplace', 'elijah'), elijah_places_output( 'birthplace', $post->ID ) );
		echo elijah_datalist_item( __( 'Marriage Year', 'elijah'), elijah_year_output( 'marriage-year', $post->ID ) );
		echo elijah_datalist_item( __( 'Marriage Place', 'elijah'), elijah_places_output( 'marriage-place', $post->ID ) );
		echo elijah_datalist_item( __( 'Childrens\' Birthyears', 'elijah'), elijah_year_output( 'childrens-birthyears', $post->ID ) );
		echo elijah_datalist_item( __( 'Childrens\' Birthplaces', 'elijah'), elijah_places_output( 'childrens-birthplaces', $post->ID ) );
		echo elijah_datalist_item( __( 'Death Year', 'elijah'), elijah_year_output( 'death-year', $post->ID ) );
		echo elijah_datalist_item( __( 'Death Place', 'elijah'), elijah_places_output( 'deathplace', $post->ID ) );
		echo elijah_datalist_item( __( 'Group Affiliations', 'elijah' ), elijah_hierarchical_show_leaf_nodes( 'group-affiliation', $post->ID ) );
		?>
	</dl>
</div>