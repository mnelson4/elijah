<form  action="" method="post">

	<ul class="wpuf-form">

		<li class="wpuf-el post_title">
			<div class="wpuf-label">
				<label for="wpuf-post_title"><?php _e( 'Individual Being Researched', 'event_espresso' );?><span class="required">*</span></label>
			</div>

			<div class="wpuf-fields">
				<input class="textfield" id="post_title" type="text" data-required="yes" data-type="text" required="required" name="post_title" value="<?php echo $post_id ? get_the_title( $post_id ) : '';?>" size="40">
				<span class="wpuf-help"></span>

			</div>

        </li>
		
		<li class="wpuf-el individual-details">
			<div class="wpuf-label">
				<label for="wpuf-individual-details"><?php _e( 'Individual\'s Details you Want to Find', 'event_espresso' );?><span class="required">*</span></label>
			</div>

			<div class="wpuf-fields">
				<?php $selected = wp_get_object_terms( $post_id, 'individual-details', array( 'fields' => 'ids' ) ); ?>
				<select multiple="multiple" data-required="yes" data-type="multiselect" name="individual-details[]" id="individual-details" class="individual-details multiselect">
					<?php

					foreach ( $individual_details_terms as $term ) { ?>
						<option class="level-0" value="<?php echo $term->term_id; ?>" <?php echo in_array( $term->term_id, $selected ) ? 'selected="selected"' : ''?>><?php echo $term->name; ?></option>
					<?php } ?>
				</select>
				<span class="wpuf-help"></span>
			</div>

        </li>
		<li class="wpuf-el ">
			<div class="wpuf-fields">
				<p class="wpuf-help"><?php _e( 'Known Individual Details', 'event_espresso');?></p>
			</div>
        </li>
			<?php
			$info_groups = array(
				__( 'Birth', 'event_espresso' ) => array(
					'birthyear', 'birthplace'
				),
				__( 'Marriage', 'event_espresso' ) => array(
					'marriage-year', 'marriage-place'
				),
				__( 'Childrens\'s births', 'event_espresso' ) => array(
					'childrens-birthyears', 'childrens-birthplaces'
				),
				__( 'Death', 'event_espresso' ) => array(
					'death-year', 'death-place'
				),
			);

			foreach ( $info_groups as $title => $taxonomy_names ) {
				$year_taxonomy = get_taxonomy( $taxonomy_names[ 0 ] );
				$place_taxonomy = get_taxonomy( $taxonomy_names[ 1 ] );
				?>
		<h3><?php echo $title;?></h3>
		<li class="wpuf-el <?php echo $year_taxonomy->name;?> elijah-form-half">
			<div class="wpuf-fields">
				<?php  elijah_years_input( $year_taxonomy, $post_id ); ?>
				<span class="wpuf-help"></span>
			</div>
		</li>
		<li class="wpuf-el <?php echo $place_taxonomy->name;?> elijah-form-half">
			<div class="wpuf-fields">
				<?php elijah_places_input( $place_taxonomy, $post_id ); ?>
				<span class="wpuf-help"></span>
			</div>
		</li>
			<?php
			}
			?>
		<li class="wpuf-el post_content">
			<div class="wpuf-label">
				<label for="wpuf-post_content"><?php _e( 'Extra Information', 'event_espresso' );?></label>
			</div>

			<div class="wpuf-fields">
				<?php wp_editor( ( $post_id ? get_post_field( 'post_content', $post_id )  : '' ), 'post_content' ); ?>
			</div>

        </li>
		<li class="wpuf-submit">

            <input type="hidden" id="_wpnonce" name="_wpnonce" value="<?php echo wp_create_nonce( 'add-research-objective' )?>">
            <input type="hidden" name="elijah_request" value="research_objective_submit">
			<input type="hidden" name="post_id" value="<?php echo $post_id;?>">
			<input type="submit" name="submit" value="<?php echo elijah_save_and_continue_editing_button_name;?>">
			<input type="submit" name="submit" value="<?php echo elijah_save_and_research_button_name;?>">
		</li>

	</ul>

</form>