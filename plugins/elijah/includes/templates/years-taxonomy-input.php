<div >
	<label for="<?php echo elijah_year_input_name( $taxonomy_name, true );?>-label"><?php _e( 'Between', 'elijah');?></label>
	<input 
		name="<?php echo elijah_year_input_name( $taxonomy_name, true );?>" 
		value="<?php echo get_post_meta( $post_id, elijah_year_input_name( $taxonomy_name, true ), true );?>"
		placeholder="Year"
		pattern="\d{4}"
		title="<?php _e( '4 Digit Year (eg 1969)');?>"> 
	<label for="<?php echo elijah_year_input_name( $taxonomy_name, false );?>-label"><?php _e( 'and', 'elijah');?></label>
	<input 
		name="<?php echo elijah_year_input_name( $taxonomy_name, false );?>" 
		value="<?php echo get_post_meta( $post_id, elijah_year_input_name( $taxonomy_name, false ), true );?>"
		placeholder="Year"
		pattern="\d{4}"
		title="<?php _e( '4 Digit Year (eg 1969)');?>">
</div>