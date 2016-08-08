<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @global type $tip_usefulness_mapping
 * @param int $usefulness_int one of the keys in global $tip_usefulness_mapping
 * which is defined in plugins/elijah/init/p2p.php's elijah_connection_types
 * @return string
 */
function elijah_pretty_usefulness($usefulness_int){
	global $tip_usefulness_mapping;
	if(isset($usefulness_int)){
		return $tip_usefulness_mapping[$usefulness_int];
	}else{
		return __("Unknown", "elijah");
	}
}

/**
 * Gets teh HTML for displaying a usefulness dropdown
 * @global type $tip_usefulness_mapping
 * @param WP_Post $p2p_connection with attached p2p connection data, like the results of
 * new WP_Query( array(
								'connected_type' => 'work_done',
								'connected_items' => get_queried_object(),
								'nopaging' => true,
							  ) );
 * @param string $input_name the name you want the input to have
 * @param boolean $disabled
 * @return string of html for displaying a nice dropdown
 */
function elijah_usefulness_dropdown($p2p_connection,$input_name, $disabled = false){
	global $tip_usefulness_mapping;
	$usefulness = p2p_get_meta( $p2p_connection->p2p_id, 'usefulness', true );
	$disabled_attr = $disabled ? 'disabled="disabled"' : '';
	$html = "<span class='usefulness-dropdown-area'><label for='$input_name'>".  __("Usefulness", "elijah")."</label><img src='".site_url()."/wp-admin/images/wpspin_light.gif' style='display:none' class='spinner'><br/>";
	if( $disabled ) {
		$html .= $tip_usefulness_mapping[ $usefulness ];
	} else {
		$html .= "<select id='$input_name' name='$input_name' class='elijah-research-tip-usefulness' $disabled_attr>";
		foreach($tip_usefulness_mapping as $tip_int => $tip_text){
			if($tip_int == $usefulness){
				$selected_html = "selected='selected'";
			}else{
				$selected_html = '';
			}
			$html .= "<option value=$tip_int $selected_html>$tip_text</option>";
		}
		$html .="</select>";
	}
	$html .="</span>";
	return $html;
}
/**
 * Gets teh HTML for displaying the comments about applying the tip to a
 * research goal
 * @param WP_Post $p2p_connection with attached p2p connection data, like the results of
 * new WP_Query( array(
								'connected_type' => 'work_done',
								'connected_items' => get_queried_object(),
								'nopaging' => true,
							  ) );
 * @param string $input_name the name you want the input to have
 * @param boolean $disabled
 * @return string of html for displaying a nice textarea
 */
function elijah_comments_textbox($p2p_connection,$input_name, $disabled = false ){
	$comments = p2p_get_meta( $p2p_connection->p2p_id, 'comments', true );
	$html = "<label for='$input_name'>".  __("Comments", "elijah")."</label> <span class='autosaved-mention'></span><br/>";
	if( $disabled ) {
		if( empty( trim( $comments ) ) ) {
			$html .= __( 'No comments entered.', 'elijah' );
		} else {
			$html .= $comments;
		}
	} else {
		$html .= "<textarea id='$input_name' name='$input_name' class='tip-comments-area'>$comments</textarea>";
	}
	return $html;
}


/**
 * Gets HTML for displaying a suggested research tip for a particular post.
 * Takes into account whether or not the tip has already been applied or not
 * @param WP_Post $tip_post_obj with p2p post data which is attached to teh post when using WP_QUery(array('connected_type' => 'work_done',...));
 */
function elijah_suggested_research_tip($tip_post_obj, $goal_post_obj){
	$status = get_tip_status_for_goal($tip_post_obj);
	if( current_user_can( 'edit_research_goal', $goal_post_obj->ID ) ) {
		$can_edit = true;
	} else { 
		$can_edit = false;
	}
	 if( $can_edit ) { ?><form method='post' name="tip-applied-<?php echo $tip_post_obj->id?>">
		 <input type="hidden" name='tip-id' value="<?php echo $tip_post_obj->ID;?>"/>
		 <input type="hidden" name="goal-id" value="<?php echo $goal_post_obj->ID;?>"/>
		 <input type="hidden" name="action" value="elijah_work_done_modified"/>
	 <?php } ?>
		<div class="tip-thumbnail">
			<?php  echo get_the_post_thumbnail($tip_post_obj->id,'thumbnail'); ?>
		</div>
		<div class="tip-info">
			<h5><a href='<?php echo get_permalink_append_post_id($tip_post_obj->ID);?>'><?php echo $tip_post_obj->post_title;?></a></h5>
			<?php if( $can_edit ) {?>
			<div class="tip-buttons" <?php echo $status == 'suggested' ? '' : 'style="display:none"' ?>>
				<button class="start-research-tip" id="start-<?php echo $tip_post_obj->ID?>" ><?php	_e("Start", "elijah")?></button>
				<button class="skip-research-tip" id="skip-<?php echo $tip_post_obj->ID?>" ><?php	_e("Skip", "elijah")?></button>
			</div>
			<?php } ?>
			<div class="tip-status-info" <?php echo ! in_array($status,array('in_progress', 'completed')) ? 'style="display:none"' : ''?>>
				<div class="rowed usefulness-div">
				<?php echo elijah_usefulness_dropdown($tip_post_obj,'tip-usefulness', ! $can_edit );?>
				</div>
				<div class="rowed comments-div">
					<?php echo elijah_comments_textbox($tip_post_obj,'tip-comments', ! $can_edit );?>
				</div>
			</div>
			<p <?php echo $status == 'suggested' ? '' : 'style="display:none"' ?>><?php echo get_excerpt_or_short_content($tip_post_obj);?> <a href="<?php echo get_permalink_append_post_id( $tip_post_obj->ID);?>"><?php _e( 'Read More...', 'elijah' );?></a></p>
			<div class="tip-skipped-area" <?php echo $status != 'skipped'? 'style="display:none"' : '' ?>>
				<p><?php _e("Skipped", "elijah");?> 
					<?php if( $can_edit ) {
						printf(__("%s Unskip %s", 'elijah'),"<button class='start-research-tip' id='restart-{$tip_post_obj->ID}'>","</button>");
					}?>
				</p>
			</div>

		</div>
	</form>
	<?php
}

/**
 * Exactly like get_permalink, except it also appends the referer's id to teh querystring,
 * using the specified name (defaults to 'referer'). If no referer id is provided, it is assumed
 * to be teh current post id
 * @param type $tip_id
 */
function get_permalink_append_post_id($tip_id, $referer_id=null, $referer_arg_name='referer'){
	if( ! $referer_id){
		global $wp_query;

		$current_post = $wp_query->post;
		$referer_id = $current_post->ID;
	}
	return add_query_arg($referer_arg_name,$referer_id,get_permalink($tip_id));
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
			return __( 'Anytime', 'elijah' );
		} else {
			return $anytime_text;
		}
	} else {
		return sprintf( __( '%1$s-%2$s', 'elijah' ), $begin_year, $end_year );
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
			$value = __( 'Anywhere', 'elijah' ); 
		} else {
			$value = $anywhere_text;
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

function elijah_hierarchical_reveal_checkboxes( $taxonomy, $post_id ) {
	$terms_organized_hierarchically = elijah_get_terms_hierarchically( array( 'taxonomy' => $taxonomy ) );
	$terms_selected = wp_get_object_terms( $post_id, $taxonomy, array( 'fields' => 'ids' ) );
	return elijah_hierarchical_reveal_checkbox_and_child( $terms_organized_hierarchically, $taxonomy, $terms_selected );
}

/**
 * Recursively walks through $terms_with_children, creating HTML with checkboxes and 
 * divs
 * @param WP_Term[] $terms_with_children
 * @param string $input_name
 * @param array $selected array of term IDs
 * @return string
 */
function elijah_hierarchical_reveal_checkbox_and_child( $terms_with_children, $input_name, $selected_term_ids = array() ) {
	$html = '';
	foreach( $terms_with_children as $term_with_children ) {
		$this_id = $input_name . $term_with_children->term()->slug;
		$selected = in_array( $term_with_children->term()->term_id, $selected_term_ids );
		$selected_attribute = $selected ? 'checked="checked"' : '';
		$hidden_style = $selected ? '' : 'display:none;';
		$html .= '<div class="hierarchical-reveal-checkbox-and-area">';
		$html .= ' <label for="' . $this_id . '"><input type="checkbox" class="hierarchical-reveal-source" id = "' . $this_id . '" name="' . $input_name . '[]" value="' . $term_with_children->term()->term_id . '" ' . $selected_attribute . '>' . $term_with_children->term()->name . '</label>';
		$html .= ' <div class="hierarchical-reveal-destination-area" id="' . $this_id . '-children" style="' . $hidden_style .'">';
		$html .= elijah_hierarchical_reveal_checkbox_and_child( $term_with_children->children(), $input_name, $selected_term_ids );
		$html .= ' </div>';
		$html .= '</div>';
	}
	return $html;
}

function elijah_hierarchical_show_leaf_nodes( $taxonomy, $post_id ) {
	$selected_terms = wp_get_object_terms( $post_id, $taxonomy, array( 'fields' => 'ids' ) );
	if( ! $selected_terms ) {
		return '';
	}
	$terms_organized_hierarchically = elijah_get_terms_hierarchically( 
			array( 'taxonomy' => $taxonomy, 
				'include' => $selected_terms
			)
		);
	$leaf_terms = elijah_hierarchical_get_leaf_terms( $terms_organized_hierarchically );
	$term_names = array();
	foreach( $leaf_terms as $term ) {
		$term_names[] = $term->term()->name;
	}
	return implode( ', ', $term_names );
}