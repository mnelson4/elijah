<?php
/**
 * @post WP_Post
 * @taxonomy stdClass like what's returned by `get_object_taxonomies( $taxonomy, 'objects' );`
 */
?>
<?php 
wp_enqueue_script( 'select2', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1-rc.1/js/select2.min.js', array( 'jquery'), '4.0.1', true );
wp_enqueue_style( 'select2', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1-rc.1/css/select2.min.css', array(), '4.0.1' );
?>
<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery('#<?php echo $taxonomy->name;?>').select2({
		ajax: {
		url: ajaxurl,
		dataType: 'json',
		delay: 250,
		data: function (params) {
		  return {
			q: params.term, // search term
			page: params.page,
			taxonomy: '<?php echo $taxonomy->name;?>',
			action: 'elijah_place_taxonomy_search'
		  };
		},
		processResults: function (data, params) {
			return data;
		},
		cache: true
		},
		minimumInputLength: 0,
	});
});
	
</script>
<div class="elijah-places-taxonomy-input-area" >
	<select style="width:15em;visibility:hidden" multiple="multiple" name="<?php echo $taxonomy->name;?>" id="<?php echo $taxonomy->name;?>" >
		<?php
		foreach( $terms as $country_term_id => $country_name ) {?>
			<option value="<?php echo $country_term_id;?>" <?php echo in_array( $country_term_id, $selected_terms ) ? 'selected="selected"' : ''?>><?php echo $country_name;?></option>
		<?php } ?>
	</select>
</div>