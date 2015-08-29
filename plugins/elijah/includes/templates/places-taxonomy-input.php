<?php
/**
 * @post WP_Post
 * @taxonomy stdClass like what's returned by `get_object_taxonomies( $taxonomy, 'objects' );`
 */
?>
<div class="elijah-places-taxonomy-input-area" style='background-color:yellow' >
	<select multiple="multiple" name="<?php echo $taxonomy->name;?>-countries[]">
	<?php
	foreach( $country_terms as $country_term_id => $country_name ) {?>
		<option value="<?php echo $country_term_id;?>" <?php echo in_array( $country_term_id, $selected_terms ) ? 'selected="selected"' : ''?>><?php echo $country_name;?></option>
	<?php } ?>
	</select>
	<select multiple="multiple" name="<?php echo $taxonomy->name;?>-states[]">
	<?php foreach($state_terms as $state_term_id => $state_name ) { ?>
		<option value="<?php echo $state_term_id;?>" <?php echo in_array( $state_term_id, $selected_terms ) ? 'selected="selected"' : ''?>><?php echo $state_name;?></option>
	<?php } ?>
	</select>
</div>