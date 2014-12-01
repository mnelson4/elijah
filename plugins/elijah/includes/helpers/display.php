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