<div style='background-color:red'>
	<label for="<?php echo elijah_year_input_name( $taxonomy_name, true );?>-label"><?php _e( 'Between', 'event_espresso');?></label>
	<input name="<?php echo elijah_year_input_name( $taxonomy_name, true );?>" value="<?php echo get_post_meta( $post_id, elijah_year_input_name( $taxonomy_name, true ), true );?>"> 
	<label for="<?php echo elijah_year_input_name( $taxonomy_name, false );?>-label"><?php _e( 'and', 'event_espresso');?></label>
	<input name="<?php echo elijah_year_input_name( $taxonomy_name, false );?>" value="<?php echo get_post_meta( $post_id, elijah_year_input_name( $taxonomy_name, false ), true );?>">
</div>