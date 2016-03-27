<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @global type $strategy_usefulness_mapping
 * @param int $usefulness_int one of the keys in global $strategy_usefulness_mapping
 * which is defined in plugins/elijah/init/p2p.php's elijah_connection_types
 * @return string
 */
function elijah_pretty_usefulness($usefulness_int){
	global $strategy_usefulness_mapping;
	if(isset($usefulness_int)){
		return $strategy_usefulness_mapping[$usefulness_int];
	}else{
		return __("Unknown", "event_espresso");
	}
}

/**
 * Gets teh HTML for displaying a usefulness dropdown
 * @global type $strategy_usefulness_mapping
 * @param WP_Post $p2p_connection with attached p2p connection data, like the results of
 * new WP_Query( array(
								'connected_type' => 'strategies_applied',
								'connected_items' => get_queried_object(),
								'nopaging' => true,
							  ) );
 * @param string $input_name the name you want the input to have
 * @return string of html for displaying a nice dropdown
 */
function elijah_usefulness_dropdown($p2p_connection,$input_name){
	global $strategy_usefulness_mapping;
	$usefulness = p2p_get_meta( $p2p_connection->p2p_id, 'usefulness', true );
	$html = "<span class='usefulness-dropdown-area'><label for='$input_name'>".  __("Usefulness", "event_espresso")."</label><img src='".site_url()."/wp-admin/images/wpspin_light.gif' style='display:none' class='spinner'><br/><select id='$input_name' name='$input_name' class='elijah-research-strategy-usefulness'>";
	foreach($strategy_usefulness_mapping as $strategy_int => $strategy_text){
		if($strategy_int == $usefulness){
			$selected_html = "selected='selected'";
		}else{
			$selected_html = '';
		}
		$html .= "<option value=$strategy_int $selected_html>$strategy_text</option>";
	}
	$html .="</select></span>";
	return $html;
}
/**
 * Gets teh HTML for displaying the comments about applying the strategy to a
 * research objective
 * @param WP_Post $p2p_connection with attached p2p connection data, like the results of
 * new WP_Query( array(
								'connected_type' => 'strategies_applied',
								'connected_items' => get_queried_object(),
								'nopaging' => true,
							  ) );
 * @param string $input_name the name you want the input to have
 * @return string of html for displaying a nice textarea
 */
function elijah_comments_textbox($p2p_connection,$input_name){
	$comments = p2p_get_meta( $p2p_connection->p2p_id, 'comments', true );
	$html = "<label for='$input_name'>".  __("Comments", "event_espresso")."</label><br/><textarea id='$input_name' name='$input_name' class='strategy-comments-area'>$comments</textarea>";
	return $html;
}


/**
 * Gets HTML for displaying a suggested research strategy for a particular post
 * @param WP_Post $strategy_post_obj with p2p post data which is attached to teh post when using WP_QUery(array('connected_type' => 'strategies_applied',...));
 */
function elijah_suggested_research_strategy($strategy_post_obj, $objective_post_obj){
	$status = get_strategy_status_for_objective($strategy_post_obj);
	 ?><form method='post' name="strategy-applied-<?php echo $strategy_post_obj->id?>">
		 <input type="hidden" name='strategy-id' value="<?php echo $strategy_post_obj->ID;?>"/>
		 <input type="hidden" name="objective-id" value="<?php echo $objective_post_obj->ID;?>"/>
		 <input type="hidden" name="action" value="elijah_strategy_modified"/>
		<div class="strategy-thumbnail">
			<?php  echo get_the_post_thumbnail($strategy_post_obj->id,'thumbnail'); ?>
		</div>
		<div class="strategy-info">
			<h5><a href='<?php echo get_permalink_append_post_id($strategy_post_obj->ID);?>'><?php echo $strategy_post_obj->post_title;?></a></h5>

			<div class="strategy-buttons" <?php echo $status == 'suggested' ? '' : 'style="display:none"' ?>>
				<button class="start-research-strategy" id="start-<?php echo $strategy_post_obj->ID?>" ><?php	_e("Start", "event_espresso")?></button>
				<button class="skip-research-strategy" id="skip-<?php echo $strategy_post_obj->ID?>" ><?php	_e("Skip", "event_espresso")?></button>
			</div>
			<div class="strategy-status-info" <?php echo ! in_array($status,array('in_progress', 'completed')) ? 'style="display:none"' : ''?>>
				<div class="rowed usefulness-div">
				<?php echo elijah_usefulness_dropdown($strategy_post_obj,'strategy-usefulness');?>
				</div>
				<div class="rowed comments-div">
					<?php echo elijah_comments_textbox($strategy_post_obj,'strategy-comments');?>
				</div>
			</div>
			<p <?php echo $status == 'suggested' ? '' : 'style="display:none"' ?>><?php echo get_excerpt_or_short_content($strategy_post_obj);?></p>
			<div class="strategy-skipped-area" <?php echo $status != 'skipped'? 'style="display:none"' : '' ?>>
				<p><?php	_e("Skipped", "event_espresso");?> <?php printf(__("%s Unskip %s", 'event_espresso'),"<button class='start-research-strategy' id='restart-{$strategy_post_obj->ID}'>","</button>");?></p>
			</div>

		</div>
	</form>
	<?php
}

/**
 * Exactly like get_permalink, except it also appends the referer's id to teh querystring,
 * using the specified name (defaults to 'referer'). If no referer id is provided, it is assumed
 * to be teh current post id
 * @param type $strategy_id
 */
function get_permalink_append_post_id($strategy_id, $referer_id=null, $referer_arg_name='referer'){
	if( ! $referer_id){
		global $wp_query;

		$current_post = $wp_query->post;
		$referer_id = $current_post->ID;
	}
	return add_query_arg($referer_arg_name,$referer_id,get_permalink($strategy_id));
}

/**
 * Shows HTML for form elements relating to the post's taxonomies terms, assuming
 * this taxonomy is a place-related one
 * @param stdClass $taxonomy like one of the items returned by `get_object_taxonomies( $taxonomy, 'objects' );`
 * @param int $post
 */
function elijah_places_input( $taxonomy, $post_id) {
	$terms = elijah_prepare_country_term_taxonomies(
		wp_get_object_terms( 
			$post_id,
			$taxonomy->name,
			array(
				'fields' => 'all',
			) 
		)
	);
	include( elijah_templates_dir . '/places-taxonomy-input.php');
}

function elijah_years_input( $taxonomy, $post_id ) {
	$taxonomy_name = $taxonomy->name;
	include( elijah_templates_dir . '/years-taxonomy-input.php' );
}

function elijah_year_output( $taxonomy, $post_id, $anytime_text = null ) {
	$begin_input_name = elijah_year_input_name( $taxonomy, true );
	$end_input_name = elijah_year_input_name( $taxonomy, false );
	$begin_year = get_post_meta( $post_id, $begin_input_name, true );
	$end_year = get_post_meta( $post_id, $end_input_name, true );
	if( empty( $begin_year ) 
		|| empty( $end_year ) ) {
		if( $anytime_text === null ) {
			return __( 'Anytime', 'event_espresso' );
		} else {
			return $anytime_text;
		}
	} else {
		return sprintf( __( '%1$s-%2$s', 'event_espresso' ), $begin_year, $end_year );
	}
}
function elijah_places_output( $taxonomy, $post_id, $anywhere_text = null ) {
	$term_names = array();
	foreach (wp_get_post_terms($post_id, $taxonomy) as $term_value) {
			$term_names[] = $term_value->name;
	}
	$term_names = array_filter( $term_names );
	if(empty($term_names)){
		if( $anywhere_text === null ) {
			$value = $anywhere_text; 
		} else {
			$value = __( 'Anywhere', 'event_espresso' );
		}
	}else{
		
		$value =  implode(", ", $term_names);
	}
	return $value;
}

function elijah_datalist_item( $key, $value ) {
	if( $value ) {
		return '<dt>' . $key . '</dt><dd>' . $value . '</dd>';
	} else {
		return '';
	}
}

/**
 * Gets the input name for the specified taxonomy
 * @param string $taxonomy_name
 * @param boolean $begin_or_end true for 'begin' input, false for 'end'
 */
function elijah_year_input_name( $taxonomy_name, $begin_or_end ) {
	$suffix = $begin_or_end ? 'begin' : 'end';
	return $taxonomy_name . '-' . $suffix;
}

/**
 *
 * @param string $name for the html select option
 * @param array $select_options keys html "value" attributes, and values are the names for display. Can be an array of terms, and the term_id will be used for the html "value" tag
 *		and the term's name will be used for display
 * @param array $selected values are options selected
 * @param array $html_attributes key 'select' is all the html tags for the 'select' attribute
 */
function elijah_select( $name, $select_options, $selected = false, $html_attributes = array() ) {
	ob_start();
	$html_attributes_string = '';
	$html_attributes['name'] = $name;
	foreach( $html_attributes as $attr_name => $attr_value ) {
		$html_attributes_string .= "$attr_name='" . esc_attr( $attr_value ) . "' ";
	}
	if( $first_item instanceof stdClass &&
			property_exists(  $first_item, 'term_id' ) &&
			property_exists( $first_item, 'name' ) ) {
		$normalized_select_options = array();
		foreach( $select_options as $term ) {
			$normalized_select_options[ $term->term_id ] = $term->name;
		}
	} else {
		$normalized_select_options = $select_options;
	}
	?>
	<select name="<?php echo $html_attributes_string;?>"  >
		<?php
		foreach ( $normalized_select_options as $key => $array_value) { ?>
			<option class="level-0" value="<?php echo $key; ?>" <?php echo in_array( $key, $selected ) ? 'selected="selected"' : ''?>><?php echo $array_value; ?></option>
		<?php } ?>
	</select>

	<?php
	return ob_get_clean();
}