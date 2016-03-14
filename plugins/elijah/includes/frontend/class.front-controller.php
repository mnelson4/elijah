<?php
/**
 *
 * class
 *
 * @package			Event Espresso
 * @subpackage
 * @author				Mike Nelson
 *
 * Adds hooks so that this serves as a controller for routing requests
 *
 */
class Elijah_Front_Controller {
	public function __construct() {
		add_action( 'wp_loaded', array( $this, 'wp_loaded' ) );
	}
	public function wp_loaded() {
		if( isset( $_REQUEST[ 'elijah_request' ] ) ){
			$method_name = 'handle_' . $_REQUEST[ 'elijah_request' ];
			if( method_exists( $this, $method_name ) ) {
				return call_user_method( $method_name, $this );
			}
		}
	}
	
	/**
	 * Takes care fo ahndling request sto create or update a research objective,
	 * and does a redirect afterwards.
	 */
	function handle_research_objective_submit() {
		$this->_handle_research_thing_submitted( 'objective', 'objectives' );
	}
	/**
	 * Takes care of handling requests to create or update a research strategy. Also takes
	 * care of performing redirect
	 */
	function handle_research_strategy_submit() {
		$this->_handle_research_thing_submitted( 'strategy', 'strategies' );
	}
	
	protected function _handle_research_thing_submitted( $type_singular, $type_plural ) {
		if( ! wp_verify_nonce( $_REQUEST[ '_wpnonce'], 'add-research-' . $type_singular ) ) {
			wp_die( __( 'Cheatin\' huh?', 'event_espresso' ) );
		}
		$post_id = isset( $_REQUEST[ 'research_' . $type_singular ] ) ? intval( $_REQUEST[ 'research_' . $type_singular ] ): null;
		
		if( $post_id ) {
			if( ! current_user_can(  'edit_research-' . $type_singular, $post_id ) ) {
				wp_die( __( 'You don\'t have permission to edit this!', 'event_espresso' ));
			}
			//is there a post ID?
//			echo "post id $post_id";
			$post = get_post( $post_id );
//			echo "post:";var_dump($post);
			if( $post instanceof WP_Post && $post->post_type == 'research-' . $type_plural) {
				//and it's a real research strategy?
//				//if so update it
//				echo "update post!!";
				$success = wp_update_post(
						array(
							'ID' => $post_id,
							'post_title' => sanitize_post_field( 'post_title', $_REQUEST[ 'post_title'], $post_id ),
							'post_content' => sanitize_post_field( 'post_content', $_REQUEST[ 'post_content'], $post_id )
						));

			}

		}
		if ( ! $post instanceof WP_Post || $post->post_type != 'research-' . $type_plural ) {
			if( ! current_user_can(  'edit_research-' . $type_plural ) ) {
				wp_die( __( 'You don\'t have permission to insert this!', 'event_espresso' ));
			}
			//if no post ID, create one
//inserting post
			if( current_user_can( 'publish_research-' . $type_plural ) ) {
				$post_status = 'publish';
			} else {
				$post_status = 'draft';
			}
			$post_id = wp_insert_post(
					array(
						'post_title' => sanitize_post_field( 'post_title', $_REQUEST[ 'post_title'], $post_id ),
						'post_content' => sanitize_post_field( 'post_content', $_REQUEST[ 'post_content'], $post_id ),
						'post_type' => 'research-' . $type_plural,
						'post_status' => $post_status
					));
			$post = get_post( $post_id );
		}
		$results = wp_set_object_terms( $post_id, array_map('intval', $_REQUEST[ 'individual-details' ] ), 'individual-details' );
		//add relations to all the taxonomies mentioned in the request
		//and remove all its taxonomies
		$taxonomies = array(
			'birthyear',
			'birthplace',
			'marriage-year',
			'marriage-place',
			'childrens-birthyears',
			'childrens-birthplaces',
			'death-year',
			'death-place'
		);


		foreach( $taxonomies as $taxonomy ) {
			if( ! taxonomy_exists($taxonomy) ) {
				global $wp_taxonomies;
//				echo "$taxonomy is not a valid taxonomy, taxonomies are:";var_dump($wp_taxonomies);
			}

			if( strpos( $taxonomy, 'year' ) !== false ) {
				$begin_input_name = elijah_year_input_name( $taxonomy, true );
				$end_input_name = elijah_year_input_name( $taxonomy, false );
				
				$begin_year = intval( $_REQUEST[ $begin_input_name ] ) ?
						intval( $_REQUEST[ $begin_input_name ] ) :
						'';				
				$success = update_post_meta( $post_id, $begin_input_name, $begin_year );
				$begin_range = round( 
						max( 
								intval( $begin_year ),
								1500 ), 
						-1 );
				$end_year = intval( $_REQUEST[ $end_input_name ] );
				update_post_meta( $post_id, $end_input_name, $end_year );
				$end_range = round( 
								$end_year ? $end_year : intval( date('Y') ),
								-1 );
				$vals = array_map( 
						function( $input) { return strval( $input ) . 's'; }, 
						range( $begin_range, $end_range, 10 ) );
				$results = wp_set_object_terms( $post_id, $vals, $taxonomy );
			}elseif( strpos( $taxonomy, 'place' ) !== false ) {

				$results = wp_set_object_terms(
						$post_id,
						array_map(
								'intval',
								array_unique( (array) $_REQUEST[ $taxonomy  ] ) ),
						$taxonomy );
			}
		}
		if( $_REQUEST[ 'submit' ] == elijah_save_and_continue_editing_button_name ) {
			//just save and return to edit
			wp_safe_redirect( $this->get_frontend_editing_permalink( $post ) );
			die;
		} else/*if ( $_REQUEST[ 'submit' ] == elijah_save_and_research_button_name )*/ {
			wp_safe_redirect( get_permalink( $post->ID ) );
			die;
		}
	}

	/**
	 * Gets the URL for editing thie post on the frontend
	 * @param WP_Post $post
	 * @return string URL for editing the post on the frontend
	 */
	public function get_frontend_editing_permalink( WP_Post $post ) {
		if( $post->post_type === 'research-objectives' ) {
			$url = add_query_arg( 'research_objective', $post->ID, get_permalink( elijah_edit_research_objectives_page_id ) );
		} elseif( $post->post_type === 'research-strategies' ) {
			$url = add_query_arg( 'research_strategy', $post->ID, get_permalink( elijah_edit_research_strategies_page_id ) );
		} else {
			if( WP_DEBUG ) {
				throw new Exception( 'Could not get frontend editing permalink for post ' . print_r( $post, true ) );
			} else {
				$url = site_url();
			}
		}
		return $url;
	}
}
new Elijah_Front_Controller();
// End of file class.front-controller.php