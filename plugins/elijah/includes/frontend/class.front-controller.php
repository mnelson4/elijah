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
	 * Takes care fo ahndling request sto create or update a research goal,
	 * and does a redirect afterwards.
	 */
	function handle_research_goal_submit() {
		$this->_handle_research_thing_submitted( 'goal', 'goals' );
	}
	/**
	 * Takes care of handling requests to create or update a research tip. Also takes
	 * care of performing redirect
	 */
	function handle_research_tip_submit() {
		$this->_handle_research_thing_submitted( 'tip', 'tips' );
	}
	
	protected function _handle_research_thing_submitted( $type_singular, $type_plural ) {
		if( ! wp_verify_nonce( $_REQUEST[ '_wpnonce'], 'add-research-' . $type_singular ) ) {
			wp_die( __( 'Cheatin\' huh?', 'elijah' ) );
		}
		$post_id = isset( $_REQUEST[ $type_singular . '_id'] ) ? intval( $_REQUEST[ $type_singular . '_id' ] ): null;
		
		if( current_user_can( 'publish_research_' . $type_plural ) && $_REQUEST['submit'] !== elijah_save_draft ) {
			$post_status = 'publish';
		} else {
			$post_status = 'draft';
		}
			
		if( $post_id ) {
			if( ! current_user_can(  'edit_research_' . $type_singular, $post_id ) ) {
				wp_die( __( 'You don\'t have permission to edit this!', 'elijah' ));
			}
			//is there a post ID?
//			echo "post id $post_id";
			$post = get_post( $post_id );
//			echo "post:";var_dump($post);
			if( $post instanceof WP_Post && $post->post_type == 'research_' . $type_singular) {
				//and it's a real research tip?
//				//if so update it
//				echo "update post!!";
				$success = wp_update_post(
						array(
							'ID' => $post_id,
							'post_title' => sanitize_post_field( 'post_title', $_REQUEST[ 'post_title'], $post_id ),
							'post_content' => sanitize_post_field( 'post_content', $_REQUEST[ 'post_content'], $post_id ),
							'post_status' => $post_status
						));

			}

		}
		if ( ! $post instanceof WP_Post || $post->post_type != 'research_' . $type_singular ) {
			if( ! current_user_can(  'edit_research_' . $type_plural ) ) {
				wp_die( __( 'You don\'t have permission to insert this!', 'elijah' ));
			}
			//if no post ID, create one
//inserting post
			$post_id = wp_insert_post(
					array(
						'post_title' => sanitize_post_field( 'post_title', $_REQUEST[ 'post_title'], $post_id ),
						'post_content' => sanitize_post_field( 'post_content', $_REQUEST[ 'post_content'], $post_id ),
						'post_type' => 'research_' . $type_singular,
						'post_status' => $post_status
					));
			$post = get_post( $post_id );
		}
		$results = wp_set_object_terms( $post_id, array_map('intval', $_REQUEST[ 'individual-details' ] ), 'individual-details' );
		$results = wp_set_object_terms( $post_id, array_map('intval', $_REQUEST[ 'tip-type' ] ), 'tip-type' );
		$results = wp_set_object_terms( $post_id, array_map('intval', $_REQUEST[ 'group-affiliation' ] ), 'group-affiliation' );
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
				
				$end_year = intval( $_REQUEST[ $end_input_name ] ) ?
						intval( $_REQUEST[ $end_input_name ] ) :
						'';
				
				
				
				if( empty( $begin_year ) 
					&& empty( $end_year ) ) {
					$vals = array( 'anytime' );
				} else {
					if( empty( $begin_year ) ) {
						$begin_year = $end_year;
					} 
					if( empty( $end_year ) ) {
						$end_year = $begin_year;
					} 
					if( $begin_year > $end_year ) {
						$temp = $end_year;
						$end_year = $begin_year;
						$begin_year = $temp;
					}
					$vals = array_map( 
						function( $input) { 
							return strval( $input ) . 's'; 
						
						}, 
						range( $begin_year, $end_year, 10 ) 
					);
				}
				update_post_meta( $post_id, $begin_input_name, $begin_year );
				update_post_meta( $post_id, $end_input_name, $end_year );
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
		if( $_REQUEST[ 'submit' ] === elijah_save_draft ) {
			//just save and return to edit
			wp_safe_redirect( elijah_get_frontend_editing_permalink( $post ) );
			die;
		} else/*if ( $_REQUEST[ 'submit' ] == elijah_save_and_research_button_name )*/ {
			wp_safe_redirect( get_permalink( $post->ID ) );
			die;
		}
	}
}
new Elijah_Front_Controller();
// End of file class.front-controller.php