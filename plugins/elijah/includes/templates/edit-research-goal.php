<form id="elijah-edit-researc-thing" action="" method="post">

	<ul class="wpuf-form">

		<li class="wpuf-el post_title">
			<div class="wpuf-label">
				<label for="wpuf-post_title"><?php _e( 'Individual Being Researched', 'elijah' );?><span class="required">*</span></label>
			</div>

			<div class="wpuf-fields">
				<input class="textfield" id="post_title" type="text" data-required="yes" data-type="text" required="required" name="post_title" value="<?php echo $post instanceof WP_Post ?
                    esc_attr($post->post_title) : '';?>" size="40">
				<span class="wpuf-help"></span>

			</div>

        </li>
		
		<li class="wpuf-el individual-details">
			<div class="wpuf-label">
				<label for="wpuf-individual-details"><?php _e( 'Individual\'s Details you Want to Find', 'elijah' );?><span class="required">*</span></label>
			</div>

			<div class="wpuf-fields">
				<?php $selected = wp_get_object_terms( $post_id, 'individual-details', array( 'fields' => 'ids' ) ); ?>
				<select multiple="multiple" data-required="yes" required="required" data-type="multiselect" name="individual-details[]" id="individual-details" class="individual-details multiselect">
					<?php

					foreach ( $individual_details_terms as $term ) { ?>
						<option class="level-0" value="<?php esc_attr_e($term->term_id); ?>" <?php echo in_array( $term->term_id, $selected ) ? 'selected="selected"' : ''?>><?php esc_attr_e
                            ($term->name);
						?></option>
					<?php } ?>
				</select>
				<span class="wpuf-help"></span>
			</div>

        </li>
		<li class="wpuf-el ">
			<div class="wpuf-fields">
				<p class="wpuf-help"><?php _e( 'Known Individual Details', 'elijah');?></p>
			</div>
        </li>
			<?php
			$info_groups = array(
				__( 'Birth', 'elijah' ) => array(
					'birthyear', 'birthplace'
				),
				__( 'Marriage', 'elijah' ) => array(
					'marriage-year', 'marriage-place'
				),
				__( 'Childrens\'s births', 'elijah' ) => array(
					'childrens-birthyears', 'childrens-birthplaces'
				),
				__( 'Death', 'elijah' ) => array(
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
			} ?>
		<li class="wpuf-el group-affiliations">
			<div class="wpuf-label">
				<label for="wpuf-group-affiliations"><?php _e( 'Individual\'s Group Affiliations', 'elijah' );?></label>
			</div>
			<p class="help"><?php printf( __( 'Groups to which this individual probably belonged, which might help in researching them. (If you need other groups added to this list, please %1$s contact us %2$s.)', 'elijah' ), '<a href="/contact-us-feedback">', '</a>' );?></p>

			<div class="wpuf-fields">
				<?php
				echo elijah_hierarchical_reveal_checkboxes( 'group-affiliation', $post_id );
				?>
			</div>
		</li>
		<li class="wpuf-el post_content">
			<div class="wpuf-label">
				<label for="wpuf-post_content"><?php _e( 'Extra Information', 'elijah' );?></label>
			</div>

			<div class="wpuf-fields">
				<?php wp_editor( ( $post_id ? get_post_field( 'post_content', $post_id )  : '' ), 'post_content' ); ?>
			</div>

        </li>
		
		<li class="wpuf-el tip-types">
			<div class="wpuf-label">
				<label for="wpuf-tip-types"><?php _e( 'Research Tip Type', 'elijah' );?><span class="required">*</span></label>
			</div>

			<div class="wpuf-fields">
				<?php $selected = wp_get_object_terms( $post_id, 'tip-type', array( 'fields' => 'ids' ) ); ?>
				<select multiple="multiple" data-required="yes" required="required" data-type="multiselect" name="tip-type[]" id="tip-type" class="tip-types multiselect">
					<?php

					foreach ( $tip_type_terms as $term ) { ?>
						<option class="level-0" value="<?php esc_attr_e($term->term_id); ?>"
                            <?php echo in_array( $term->term_id, $selected ) ? 'selected="selected"' : ''?>><?php
                            esc_html_e($term->name);
						?></option>
					<?php } ?>
				</select>
				<span class="wpuf-help"><?php _e( 'Types of Research Tips you\'d like to see when researching this goal.')?></span>
			</div>

        </li>
		<li class="wpuf-submit">

            <input type="hidden" id="_wpnonce" name="_wpnonce" value="<?php esc_attr_e(wp_create_nonce( 'add-research-goal' ))?>">
            <input type="hidden" name="elijah_request" value="research_goal_submit">
			<input type="hidden" name="post_id" value="<?php esc_attr_e($post_id); ?>">
			<input type="submit" name="submit" value="<?php echo elijah_save_and_research_button_name;?>">
			<input type="submit" name="submit" value="<?php echo elijah_save_draft;?>">
		</li>

	</ul>

</form>