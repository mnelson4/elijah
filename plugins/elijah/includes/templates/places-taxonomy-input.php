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
	jQuery('#<?php echo $taxonomy->name;?>-countries').select2({
		ajax: {
		url: ajaxurl,
		dataType: 'json',
		delay: 250,
		data: function (params) {
		  return {
			q: params.term, // search term
			page: params.page,
			taxonomy: '<?php echo $taxonomy->name;?>',
			action: 'elijah_taxonomy_search'
		  };
		},
		processResults: function (data, params) {
			return data;
		  // parse the results into the format expected by Select2
		  // since we are using custom formatting functions we do not need to
		  // alter the remote JSON data, except to indicate that infinite
		  // scrolling can be used
//		  params.page = params.page || 1;
//
//		  return {
//			results: data.items,
//			pagination: {
//			  more: (params.page * 30) < data.total_count
//			}
//		  };
		},
		cache: true
		},
//		escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
		minimumInputLength: 0,
//		templateResult: formatRepo, // omitted for brevity, see the source of this page
//		templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
	});
});
	
</script>
<div class="elijah-places-taxonomy-input-area" style='background-color:yellow' >
	<select multiple="multiple" name="<?php echo $taxonomy->name;?>-countries[]" id="<?php echo $taxonomy->name;?>-countries" >
	<?php
	foreach( $country_terms as $country_term_id => $country_name ) {?>
		<option value="<?php echo $country_term_id;?>" <?php echo in_array( $country_term_id, $selected_terms ) ? 'selected="selected"' : ''?>><?php echo $country_name;?></option>
	<?php } ?>
	</select>
</div>