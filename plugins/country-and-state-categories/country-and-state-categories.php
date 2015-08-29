<?php
/*
Plugin Name: Country And State Categories
Plugin URI: http://google.com
Description: Adds country and state categories for any taxonomy specified
Author: Mike Nelson
Version: 0.0.1
*/
define( 'CASC_DIR', dirname( __FILE__ ) . '/' );
function casc_setup_to_modify_add_category_admin_page(){
	foreach( get_taxonomies() as $taxonomy_name ) {
		add_action( $taxonomy_name . '_pre_add_form', 'casc_add_to_category_admin_page', 10, 1 );
	}
}
add_action( 'init', 'casc_setup_to_modify_add_category_admin_page', 200 );

function casc_add_to_category_admin_page( $taxonomy ) {
	?>
<h2><?php _e( 'Country and State Categories', 'event_espresso' );?></h2>
<form method='post'>
	<input type="hidden" name="action" value="add-country-tags" />
	<input type="hidden" name="taxonomy" value="<?php echo esc_attr($taxonomy); ?>" />
	<?php wp_nonce_field( 'add-country-tags' ); ?>
	<button class="button-primary"><?php	_e( 'Add Countries and States as Terms', 'event_espresso' );?></button>
</form>
<?php
}

function casc_handle_add_countries_and_states_form() {
	if( isset( $_POST[ 'action' ] ) && $_POST[ 'action' ] == 'add-country-tags' ) {
		check_admin_referer( 'add-country-tags' );
		casc_import_countries_and_states_as_taxonomies_for( $_POST[ 'taxonomy' ] );
	}
}
add_action( 'init', 'casc_handle_add_countries_and_states_form', 110 );

function casc_import_countries_and_states_as_taxonomies_for( $taxonomy ) {
	$countries = simplexml_load_file(CASC_DIR . 'countries.xml');
	foreach($countries as $country_element) {
		$result = wp_insert_term(
				$country_element['name'],
				$taxonomy,
				array( 'slug' => $country_element['alpha-2'] ) );
	}
	$countries = simplexml_load_file(CASC_DIR . 'states.xml');
	$count = 0;
	foreach($countries as $country_element) {
		foreach($country_element->iso_3166_subset as $state_type_element ) {
			$country_term = get_term_by('slug', $country_element['code'], $taxonomy);
			foreach($state_type_element->iso_3166_2_entry as $state_element ) {
				$result = wp_insert_term( $state_element['name'],$taxonomy, array( 'slug' => $state_element['code'], 'parent' => intval( $country_term->term_id ) ) );
			}
		}
	}
	update_option( 'casc_success_notice', true );

}

function my_admin_notice() {
	if( get_option( 'casc_success_notice' ) ) {
		?>
		<div class="updated">
			<p><?php _e( 'Countries and States were successfully added as terms!', 'my-text-domain' ); ?></p>
		</div>
		<?php
		update_option( 'casc_success_notice', false );
	}
}
add_action( 'admin_notices', 'my_admin_notice' );

// End of file country-and-state-categories.php