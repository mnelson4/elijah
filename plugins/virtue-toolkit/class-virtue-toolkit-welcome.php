<?php
/**
 * Build Welcome Page
 *
 * @package Virtue Toolkit
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Virtue_Toolkit_Welcome' ) ) {

	/**
	 * Build Welcome Page class
	 *
	 * @category class
	 */
	class Virtue_Toolkit_Welcome {
		/**
		 * Current theme.
		 *
		 * @var object WP_Theme
		 */
		private $my_theme;
		/**
		 * Current theme name.
		 *
		 * @var string theme name
		 */
		public $theme_name;
		/**
		 * Current theme title.
		 *
		 * @var string theme title
		 */
		public $theme_title;
		/**
		 * TGMPA instance.
		 *
		 * @var object
		 */
		public $tgmpa;
		/**
		 * Class version.
		 *
		 * @var string
		 */
		public $version = '1.0.0';
		/**
		 * Class Constructor.
		 */
		public function __construct() {
			if ( is_admin() ) {
				$this->my_theme = wp_get_theme(); // Get theme data object.
				if ( 'Ascend' === $this->my_theme->get( 'Name' ) || 'ascend' === $this->my_theme->get( 'Template' ) ) {
					$this->theme_name  = 'Ascend';
					$this->theme_title = 'Ascend Theme';
				} elseif ( 'Virtue' === $this->my_theme->get( 'Name' ) || 'virtue' === $this->my_theme->get( 'Template' ) ) {
					$this->theme_name  = 'Virtue';
					$this->theme_title = 'Virtue Theme';
				} elseif ( 'Pinnacle' === $this->my_theme->get( 'Name' ) || 'pinnacle' === $this->my_theme->get( 'Template' ) ) {
					$this->theme_name  = 'Pinnacle';
					$this->theme_title = 'Pinnacle Theme';
				} else {
					$this->theme_name  = 'Not Kadence';
					$this->theme_title = 'Not Kadence';
				}
				if ( 'Not Kadence' !== $this->theme_name ) {
					add_action( 'admin_menu', array( $this, 'add_menu' ) );
					add_action( 'tgmpa_register', array( $this, 'register_importer' ), 30 );
					add_filter( 'plugin_action_links_virtue-toolkit/virtue_toolkit.php', array( $this, 'add_settings_link' ) );
				}
				add_action( 'wp_ajax_kadence_import_plugin', array( $this, 'ajax_install_import_plugin' ), 10, 0 );
				add_action( 'admin_init', array( $this, 'load_tgmpa_installer' ), 1 );
			}
		}
		/**
		 * Load Installer
		 */
		public function load_tgmpa_installer() {
			if ( class_exists( 'TGM_Plugin_Activation' ) ) {
				$this->tgmpa = isset( $GLOBALS['tgmpa'] ) ? $GLOBALS['tgmpa'] : TGM_Plugin_Activation::get_instance();
			}
		}
		/**
		 * Add option page menu
		 */
		public function add_menu() {
			$page = add_theme_page( __( 'Getting Started with: ', 'virtue-toolkit' ) . $this->theme_name, esc_html__( 'Getting Started', 'virtue-toolkit' ), 'manage_options', 'kadence_welcome_page', array( $this, 'config_page' ) );
			add_action( 'admin_print_styles-' . $page, array( $this, 'css_scripts' ) );
		}
		/**
		 * Loads admin style sheets
		 */
		public function css_scripts() {
			wp_enqueue_style( 'toolkit-welcome-css', VIRTUE_TOOLKIT_URL . '/welcome/toolkit-welcome.css', array(), $this->version, 'all' );
			wp_enqueue_script( 'toolkit-welcome-js', VIRTUE_TOOLKIT_URL . '/welcome/toolkit-welcome.js', array( 'jquery' ), $this->version, true );
			wp_localize_script( 'toolkit-welcome-js', 'toolkit_welcome_params', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'wpnonce' => wp_create_nonce( 'install-plugin_kadence-importer' ),
			) );
		}

		/**
		 * Loads config page
		 */
		public function config_page() {
			if ( 'Not Kadence' === $this->theme_name ) { ?>
				<div class="wrap kt_theme_welcome">
					<h2 class="notices"></h2>
					<div class="kt_title_area">
						<h1>
						<?php echo esc_html__( 'The Kadence Toolkit is only designed to work with Kadence Themes', 'virtue-toolkit' ); ?>
						</h1>
					</div>
				</div>
			<?php
			} else {
			?>
				<div class="wrap kt_theme_welcome">
<svg aria-hidden="true" style="position: absolute; width: 0; height: 0; overflow: hidden;" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
<defs>
<symbol id="kt-svg-icon-mbri-extension" viewBox="0 0 32 32">
<title>mbri-extension</title>
<path d="M25.131 0.465c-0.619-0.619-1.643-0.619-2.261 0l-6.404 6.404c-0.619 0.619-0.619 1.643 0 2.261l6.404 6.405c0.619 0.619 1.643 0.619 2.261 0l6.405-6.404c0.619-0.619 0.619-1.643 0-2.261l-6.405-6.405zM24.378 1.22l6.403 6.403c0.215 0.215 0.215 0.539 0 0.754l-6.403 6.404c-0.215 0.215-0.539 0.215-0.754 0l-6.404-6.404c-0.215-0.215-0.215-0.539 0-0.754l6.404-6.403c0.215-0.215 0.539-0.215 0.754 0zM17.6 19.2c-0.877 0-1.6 0.723-1.6 1.6v9.6c0 0.877 0.723 1.6 1.6 1.6h9.6c0.877 0 1.6-0.723 1.6-1.6v-9.6c0-0.877-0.723-1.6-1.6-1.6h-9.6zM17.6 20.267h9.6c0.305 0 0.533 0.228 0.533 0.533v9.6c0 0.305-0.228 0.533-0.533 0.533h-9.6c-0.305 0-0.533-0.228-0.533-0.533v-9.6c0-0.305 0.228-0.533 0.533-0.533zM1.6 3.2c-0.877 0-1.6 0.723-1.6 1.6v9.6c0 0.877 0.723 1.6 1.6 1.6h9.6c0.877 0 1.6-0.723 1.6-1.6v-9.6c0-0.877-0.723-1.6-1.6-1.6h-9.6zM1.6 4.267h9.6c0.305 0 0.533 0.228 0.533 0.533v9.6c0 0.305-0.228 0.533-0.533 0.533h-9.6c-0.305 0-0.533-0.228-0.533-0.533v-9.6c0-0.305 0.228-0.533 0.533-0.533zM1.6 19.2c-0.877 0-1.6 0.723-1.6 1.6v9.6c0 0.877 0.723 1.6 1.6 1.6h9.6c0.877 0 1.6-0.723 1.6-1.6v-9.6c0-0.877-0.723-1.6-1.6-1.6h-9.6zM1.6 20.267h9.6c0.305 0 0.533 0.228 0.533 0.533v9.6c0 0.305-0.228 0.533-0.533 0.533h-9.6c-0.305 0-0.533-0.228-0.533-0.533v-9.6c0-0.305 0.228-0.533 0.533-0.533z"></path>
</symbol>
<symbol id="kt-svg-icon-mbri-edit" viewBox="0 0 32 32">
<title>mbri-edit</title>
<path d="M29.856 6.866c-0.619-0.619-1.643-0.619-2.261 0l-0.462 0.461c-0.619 0.619-0.619 1.643 0 2.261l1.678 1.679c0.621 0.619 1.643 0.619 2.263 0l0.461-0.462c0.619-0.619 0.619-1.641 0-2.261l-1.679-1.678zM29.103 7.62l1.678 1.678c0.215 0.215 0.215 0.539 0 0.754l-0.462 0.462c-0.213 0.215-0.539 0.215-0.754 0l-1.678-1.678c-0.215-0.215-0.215-0.539 0-0.754l0.462-0.462c0.215-0.215 0.539-0.215 0.754 0zM25.466 9.604c-0.32-0.026-0.71 0.064-1.001 0.356l-10.976 10.995c-0.199 0.203-0.411 0.412-0.537 0.738-0.125 0.326-0.151 0.683-0.151 1.239v1.6c0 0.539 0.459 1.067 1.067 1.067h1.6c0.559 0 0.923-0.032 1.248-0.158 0.326-0.128 0.537-0.338 0.73-0.531l10.976-10.997c0.528-0.529 0.48-1.34 0-1.82l-2.133-2.133c-0.226-0.224-0.501-0.331-0.821-0.356zM25.532 10.714l2.133 2.133c0.041 0.041 0.081 0.233 0 0.314l-10.976 10.995c-0.192 0.192-0.249 0.249-0.359 0.292-0.112 0.043-0.354 0.085-0.864 0.085h-1.599v-1.6c0-0.512 0.041-0.752 0.082-0.859 0.041-0.107 0.096-0.163 0.295-0.363l10.976-10.997c0.096-0.096 0.263-0.048 0.311 0zM4.8 11.733h13.867c0.295 0 0.533 0.238 0.533 0.533s-0.238 0.533-0.533 0.533h-13.867c-0.295 0-0.533-0.238-0.533-0.533s0.238-0.533 0.533-0.533zM4.8 8.533h13.867c0.295 0 0.533 0.238 0.533 0.533s-0.238 0.533-0.533 0.533h-13.867c-0.295 0-0.533-0.238-0.533-0.533s0.238-0.533 0.533-0.533zM4.8 5.333h13.867c0.295 0 0.533 0.238 0.533 0.533s-0.238 0.533-0.533 0.533h-13.867c-0.295 0-0.533-0.238-0.533-0.533s0.238-0.533 0.533-0.533zM1.6 0c-0.877 0-1.6 0.723-1.6 1.6v28.8c0 0.877 0.723 1.6 1.6 1.6h20.267c0.877 0 1.6-0.723 1.6-1.6v-8.533c0-0.713-1.067-0.691-1.067 0v8.533c0 0.305-0.228 0.533-0.533 0.533h-20.267c-0.305 0-0.533-0.228-0.533-0.533v-28.8c0-0.305 0.228-0.533 0.533-0.533h20.267c0.305 0 0.533 0.228 0.533 0.533v7.467c0 0.717 1.067 0.691 1.067 0v-7.467c0-0.877-0.723-1.6-1.6-1.6z"></path>
</symbol>
<symbol id="kt-svg-icon-browser" viewBox="0 0 42 32">
<title>browser</title>
<path d="M41.5 10c-0.276 0-0.5 0.224-0.5 0.5v20c0 0.276-0.224 0.5-0.5 0.5h-39c-0.276 0-0.5-0.224-0.5-0.5v-20c0-0.276-0.224-0.5-0.5-0.5s-0.5 0.224-0.5 0.5v20c0 0.827 0.673 1.5 1.5 1.5h39c0.827 0 1.5-0.673 1.5-1.5v-20c0-0.276-0.224-0.5-0.5-0.5zM40.5 0h-39c-0.827 0-1.5 0.673-1.5 1.5v6c0 0.276 0.224 0.5 0.5 0.5h41c0.276 0 0.5-0.224 0.5-0.5v-6c0-0.827-0.673-1.5-1.5-1.5zM41 7h-40v-5.5c0-0.276 0.224-0.5 0.5-0.5h39c0.276 0 0.5 0.224 0.5 0.5v5.5zM17.5 28c0.276 0 0.5-0.224 0.5-0.5v-16c0-0.276-0.224-0.5-0.5-0.5h-13c-0.276 0-0.5 0.224-0.5 0.5v16c0 0.276 0.224 0.5 0.5 0.5h13zM5 12h12v15h-12v-15zM22.5 15h15c0.276 0 0.5-0.224 0.5-0.5s-0.224-0.5-0.5-0.5h-15c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5zM22.5 20h15c0.276 0 0.5-0.224 0.5-0.5s-0.224-0.5-0.5-0.5h-15c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5zM22.5 25h15c0.276 0 0.5-0.224 0.5-0.5s-0.224-0.5-0.5-0.5h-15c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5zM3 4c0 0.552 0.448 1 1 1s1-0.448 1-1c0-0.552-0.448-1-1-1s-1 0.448-1 1zM7 4c0 0.552 0.448 1 1 1s1-0.448 1-1c0-0.552-0.448-1-1-1s-1 0.448-1 1zM11 4c0 0.552 0.448 1 1 1s1-0.448 1-1c0-0.552-0.448-1-1-1s-1 0.448-1 1z"></path>
</symbol>
<symbol id="kt-svg-icon-adjustments" viewBox="0 0 33 32">
<title>adjustments</title>
<path d="M31 0h-29c-0.822 0-2 1.178-2 2v28c0 0.822 1.178 2 2 2h29c0.822 0 2-1.178 2-2v-28c0-0.822-1.178-2-2-2zM32 30c-0.006 0.284-0.716 0.994-1 1h-29c-0.284-0.006-0.994-0.716-1-1v-28c0.006-0.284 0.716-0.994 1-1h29c0.284 0.006 0.994 0.716 1 1v28zM16.5 5.5c-1.103 0-2 0.897-2 2s0.897 2 2 2 2-0.897 2-2-0.897-2-2-2zM16.5 8.5c-0.551 0-1-0.449-1-1s0.449-1 1-1 1 0.449 1 1-0.449 1-1 1zM24.5 21.5c-1.103 0-2 0.897-2 2s0.897 2 2 2 2-0.897 2-2-0.897-2-2-2zM24.5 24.5c-0.551 0-1-0.449-1-1s0.449-1 1-1 1 0.449 1 1-0.449 1-1 1zM8.5 16.5c-1.103 0-2 0.897-2 2s0.897 2 2 2 2-0.897 2-2-0.897-2-2-2zM8.5 19.5c-0.551 0-1-0.449-1-1s0.449-1 1-1 1 0.449 1 1-0.449 1-1 1zM8.5 15c0.276 0 0.5-0.224 0.5-0.5v-9c0-0.276-0.224-0.5-0.5-0.5s-0.5 0.224-0.5 0.5v9c0 0.276 0.224 0.5 0.5 0.5zM8.5 22c-0.276 0-0.5 0.224-0.5 0.5v3c0 0.276 0.224 0.5 0.5 0.5s0.5-0.224 0.5-0.5v-3c0-0.276-0.224-0.5-0.5-0.5zM16.5 11c-0.276 0-0.5 0.224-0.5 0.5v14c0 0.276 0.224 0.5 0.5 0.5s0.5-0.224 0.5-0.5v-14c0-0.276-0.224-0.5-0.5-0.5zM24.5 20c0.276 0 0.5-0.224 0.5-0.5v-14c0-0.276-0.224-0.5-0.5-0.5s-0.5 0.224-0.5 0.5v14c0 0.276 0.224 0.5 0.5 0.5z"></path>
</symbol>
<symbol id="kt-svg-icon-envelope" viewBox="0 0 38 32">
<title>envelope</title>
<path d="M20.060 0.413c-0.58-0.533-1.539-0.533-2.119 0l-17.688 10.671c-0.15 0.091-0.242 0.253-0.242 0.428v18.922c0 0.863 0.706 1.566 1.574 1.566h34.83c0.868 0 1.574-0.703 1.574-1.566v-18.922c0-0.175-0.092-0.337-0.242-0.428l-17.687-10.671zM18.504 1.24c0.035-0.021 0.066-0.046 0.095-0.074 0.108-0.107 0.25-0.166 0.401-0.166s0.293 0.059 0.4 0.166c0.029 0.028 0.061 0.053 0.095 0.074l17.227 10.394-12.478 7.436c-0.237 0.142-0.315 0.448-0.174 0.686 0.094 0.157 0.26 0.244 0.43 0.244 0.087 0 0.175-0.022 0.255-0.070l12.245-7.29v17.757l-16.935-11.266c-0.538-0.429-1.594-0.429-2.096-0.025l-16.969 11.286v-17.752l12.244 7.29c0.080 0.048 0.169 0.070 0.256 0.070 0.17 0 0.336-0.087 0.43-0.244 0.141-0.237 0.063-0.544-0.174-0.686l-12.479-7.436 17.227-10.394zM36.090 31h-34.188l16.656-11.086c0.173-0.138 0.712-0.137 0.919 0.025l16.613 11.061zM6.5 13h25c0.276 0 0.5-0.224 0.5-0.5s-0.224-0.5-0.5-0.5h-25c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5z"></path>
</symbol>
<symbol id="kt-svg-icon-presentation" viewBox="0 0 31 32">
<title>presentation</title>
<path d="M0.5 3c-0.276 0-0.5 0.224-0.5 0.5v17c0 0.827 0.673 1.5 1.5 1.5h28c0.827 0 1.5-0.673 1.5-1.5v-17c0-0.276-0.224-0.5-0.5-0.5s-0.5 0.224-0.5 0.5v17c0 0.276-0.224 0.5-0.5 0.5h-28c-0.276 0-0.5-0.224-0.5-0.5v-17c0-0.276-0.224-0.5-0.5-0.5zM32.5 0h-34c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5h34c0.276 0 0.5-0.224 0.5-0.5s-0.224-0.5-0.5-0.5zM11.854 8.146c-0.183-0.183-0.475-0.196-0.674-0.031l-6 5c-0.212 0.177-0.241 0.492-0.064 0.705 0.177 0.212 0.491 0.241 0.705 0.064l5.649-4.708 5.677 5.677c0.097 0.098 0.225 0.147 0.353 0.147s0.256-0.049 0.354-0.146l7.146-7.147v3.793c0 0.276 0.224 0.5 0.5 0.5s0.5-0.224 0.5-0.5v-5c0-0.065-0.013-0.13-0.038-0.191-0.051-0.122-0.148-0.22-0.271-0.271-0.061-0.025-0.126-0.038-0.191-0.038h-5c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5h3.793l-6.793 6.793-5.646-5.647zM8.057 31.732c0.128 0.245 0.43 0.339 0.675 0.211l6.768-3.545 6.768 3.545c0.074 0.039 0.153 0.057 0.232 0.057 0.18 0 0.354-0.098 0.443-0.268 0.128-0.245 0.034-0.547-0.211-0.675l-6.893-3.61c0.098-0.091 0.161-0.219 0.161-0.364v-2.583c0-0.276-0.224-0.5-0.5-0.5s-0.5 0.224-0.5 0.5v2.583c0 0.144 0.063 0.272 0.161 0.363l-6.893 3.61c-0.245 0.129-0.339 0.431-0.211 0.676z"></path>
</symbol>
<symbol id="kt-svg-icon-lifesaver" viewBox="0 0 32 32">
<title>lifesaver</title>
<path d="M5.738 2.796l-2.943 2.942c-0.195 0.196-0.195 0.512 0 0.707 0.098 0.098 0.226 0.146 0.354 0.146s0.256-0.049 0.354-0.146l2.943-2.942c0.195-0.195 0.195-0.512 0-0.707s-0.512-0.195-0.708 0zM28.496 25.553l-2.943 2.942c-0.195 0.195-0.195 0.512 0 0.707 0.098 0.098 0.226 0.146 0.354 0.146s0.256-0.049 0.354-0.146l2.943-2.942c0.195-0.195 0.195-0.512 0-0.707s-0.513-0.196-0.708 0zM26.652 2.992c-0.195-0.195-0.512-0.195-0.707 0s-0.195 0.512 0 0.707l2.747 2.746c0.098 0.098 0.226 0.146 0.354 0.146s0.256-0.049 0.354-0.146c0.195-0.195 0.195-0.512 0-0.707l-2.748-2.746zM2.992 25.945c-0.195 0.195-0.195 0.512 0 0.707l2.747 2.746c0.098 0.098 0.226 0.146 0.354 0.146s0.256-0.049 0.354-0.146c0.195-0.195 0.195-0.512 0-0.707l-2.747-2.746c-0.197-0.195-0.513-0.195-0.708 0zM4.532 9.86c-0.204 0.38-0.381 0.771-0.544 1.164-1.309 3.164-1.31 6.791-0.001 9.952 0.202 0.487 0.427 0.953 0.668 1.383 0.583 1.041 1.306 1.996 2.148 2.838 0.964 0.965 2.069 1.769 3.284 2.389 0.308 0.157 0.621 0.296 0.938 0.427 1.582 0.655 3.256 0.987 4.975 0.987s3.393-0.332 4.976-0.987c0.001 0 0.001-0.001 0.002-0.002 0.393-0.162 0.782-0.34 1.161-0.543 1.125-0.602 2.154-1.366 3.058-2.271 0.904-0.903 1.668-1.932 2.271-3.059 0.203-0.38 0.382-0.769 0.545-1.164 0 0 0-0.001 0-0.001 1.307-3.163 1.307-6.789-0.001-9.949-0.131-0.316-0.271-0.631-0.427-0.938-0.619-1.214-1.423-2.318-2.389-3.284-0.841-0.841-1.795-1.563-2.837-2.147-0.429-0.241-0.895-0.467-1.384-0.669-3.163-1.309-6.783-1.309-9.951 0.001 0 0-0.001 0-0.001 0-0.395 0.164-0.784 0.341-1.164 0.545-1.124 0.602-2.152 1.366-3.056 2.271-0.904 0.903-1.669 1.931-2.271 3.057zM14.1 11.414c0 0 0 0 0 0 0.432-0.178 0.877-0.295 1.322-0.347 0.466-0.055 0.939-0.043 1.402 0.035 0.368 0.061 0.73 0.165 1.077 0.307 0 0 0 0 0.001 0 0.604 0.25 1.146 0.612 1.611 1.077 0.461 0.461 0.822 1.003 1.073 1.612 0 0 0 0 0 0.001s0 0 0 0c0.211 0.511 0.335 1.042 0.37 1.58 0.019 0.297 0.011 0.599-0.023 0.896-0.052 0.446-0.168 0.891-0.347 1.322 0 0 0 0 0 0s0 0 0 0.001c-0.251 0.609-0.612 1.151-1.073 1.612-0.46 0.46-1.003 0.821-1.613 1.073 0 0 0 0 0 0-0.432 0.177-0.877 0.295-1.322 0.346-0.297 0.035-0.596 0.043-0.897 0.023-0.537-0.034-1.069-0.159-1.581-0.37-0.61-0.252-1.153-0.613-1.613-1.073-0.465-0.465-0.827-1.006-1.077-1.611-0.143-0.346-0.247-0.709-0.309-1.078-0.078-0.465-0.089-0.937-0.035-1.401 0.052-0.446 0.168-0.891 0.347-1.322 0 0 0 0 0 0s0 0 0-0.001c0.251-0.609 0.612-1.151 1.073-1.612 0.461-0.457 1.004-0.818 1.614-1.070zM27.271 11.873c0.966 2.648 0.966 5.604 0 8.254l-5.588-2.314c0.119-0.368 0.2-0.743 0.244-1.12 0.042-0.356 0.051-0.719 0.028-1.076-0.031-0.483-0.122-0.962-0.272-1.429l5.588-2.315zM10.116 16.989c0.047 0.279 0.114 0.555 0.199 0.824l-5.586 2.315c-0.966-2.648-0.965-5.604 0.001-8.254l5.587 2.314c-0.119 0.368-0.2 0.743-0.244 1.119-0.064 0.558-0.051 1.124 0.043 1.682zM7.51 24.49c-0.777-0.777-1.444-1.658-1.983-2.62-0.146-0.26-0.285-0.533-0.416-0.818l5.586-2.313c0.281 0.546 0.645 1.042 1.083 1.481 0.436 0.437 0.934 0.799 1.483 1.080l-2.316 5.591c-0.136-0.062-0.271-0.127-0.406-0.195-1.121-0.574-2.141-1.315-3.031-2.206zM11.873 27.271l2.315-5.589c0.467 0.15 0.946 0.241 1.428 0.272 0.358 0.021 0.718 0.015 1.078-0.028 0.376-0.044 0.75-0.126 1.118-0.244l2.315 5.589c-2.643 0.968-5.611 0.968-8.254 0zM24.49 24.49c-0.834 0.835-1.784 1.54-2.824 2.097-0.202 0.108-0.407 0.209-0.614 0.304l-2.316-5.591c0.55-0.281 1.047-0.643 1.483-1.080 0.437-0.436 0.799-0.934 1.080-1.483l5.59 2.315c-0.095 0.208-0.195 0.413-0.304 0.615-0.555 1.040-1.26 1.989-2.095 2.823zM24.49 7.51c0.892 0.892 1.633 1.911 2.205 3.032 0.069 0.134 0.133 0.269 0.196 0.406l-5.591 2.315c-0.282-0.549-0.643-1.047-1.080-1.483-0.439-0.439-0.936-0.803-1.482-1.084l2.315-5.586c0.286 0.132 0.56 0.271 0.819 0.417 0.961 0.539 1.842 1.206 2.618 1.983zM20.128 4.729l-2.315 5.587c-0.27-0.086-0.546-0.152-0.824-0.199-0.553-0.094-1.12-0.108-1.683-0.042-0.376 0.044-0.75 0.126-1.118 0.244l-2.315-5.589c2.645-0.969 5.612-0.969 8.255-0.001zM10.333 5.413c0.202-0.108 0.407-0.209 0.615-0.303l2.315 5.59c-0.55 0.281-1.047 0.644-1.483 1.080s-0.799 0.934-1.080 1.483l-5.59-2.315c0.094-0.208 0.194-0.413 0.303-0.615 0.557-1.040 1.262-1.989 2.097-2.823 0.834-0.835 1.784-1.54 2.823-2.097zM23.418 29.041c-4.576 2.607-10.261 2.607-14.837 0-0.241-0.136-0.546-0.054-0.682 0.188-0.137 0.239-0.053 0.545 0.187 0.682 2.441 1.391 5.177 2.085 7.914 2.085s5.473-0.694 7.914-2.085c0.24-0.137 0.324-0.442 0.187-0.682-0.138-0.242-0.443-0.324-0.683-0.188zM2.090 8.087c-2.781 4.881-2.781 10.945 0 15.826 0.092 0.162 0.261 0.253 0.435 0.253 0.084 0 0.169-0.021 0.247-0.065 0.24-0.137 0.324-0.442 0.187-0.682-2.607-4.576-2.607-10.262 0-14.838 0.137-0.239 0.053-0.545-0.187-0.682-0.241-0.135-0.546-0.052-0.682 0.188zM29.91 23.913c2.781-4.881 2.781-10.945 0-15.826-0.137-0.24-0.444-0.323-0.682-0.188-0.24 0.137-0.324 0.442-0.187 0.682 2.607 4.576 2.607 10.262 0 14.838-0.137 0.239-0.053 0.545 0.187 0.682 0.078 0.044 0.163 0.065 0.247 0.065 0.174 0 0.343-0.091 0.435-0.253zM8.582 2.959c4.576-2.607 10.261-2.607 14.837 0 0.078 0.044 0.163 0.065 0.247 0.065 0.174 0 0.343-0.091 0.435-0.253 0.137-0.239 0.053-0.545-0.187-0.682-4.882-2.781-10.945-2.781-15.827 0-0.24 0.137-0.324 0.442-0.187 0.682 0.136 0.241 0.441 0.322 0.682 0.188z"></path>
</symbol>
<symbol id="kt-svg-icon-documents" viewBox="0 0 34 32">
<title>documents</title>
<path d="M1.512 28h17.988c0.827 0 1.5-0.673 1.5-1.5v-19c0-0.023-0.010-0.043-0.013-0.065s-0.007-0.041-0.013-0.062c-0.023-0.086-0.060-0.166-0.122-0.227l-6.999-6.999c-0.061-0.061-0.141-0.098-0.227-0.122-0.021-0.006-0.040-0.010-0.062-0.013s-0.041-0.012-0.064-0.012h-11.994c-0.83 0-1.506 0.673-1.506 1.5v25c0 0.827 0.678 1.5 1.512 1.5zM14 1.707l5.293 5.293h-4.793c-0.275 0-0.5-0.224-0.5-0.5v-4.793zM1 1.5c0-0.276 0.227-0.5 0.506-0.5h11.494v5.5c0 0.827 0.673 1.5 1.5 1.5h5.5v18.5c0 0.276-0.225 0.5-0.5 0.5h-17.988c-0.283 0-0.512-0.224-0.512-0.5v-25zM4.5 12h12c0.276 0 0.5-0.224 0.5-0.5s-0.224-0.5-0.5-0.5h-12c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5zM4.5 16h12c0.276 0 0.5-0.224 0.5-0.5s-0.224-0.5-0.5-0.5h-12c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5zM4.5 8h5c0.276 0 0.5-0.224 0.5-0.5s-0.224-0.5-0.5-0.5h-5c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5zM4.5 20h12c0.276 0 0.5-0.224 0.5-0.5s-0.224-0.5-0.5-0.5h-12c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5zM4.5 24h12c0.276 0 0.5-0.224 0.5-0.5s-0.224-0.5-0.5-0.5h-12c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5zM21.5 5h4.5v5.5c0 0.827 0.673 1.5 1.5 1.5h5.5v18.5c0 0.276-0.225 0.5-0.5 0.5h-17.988c-0.283 0-0.512-0.224-0.512-0.5v-1c0-0.276-0.224-0.5-0.5-0.5s-0.5 0.224-0.5 0.5v1c0 0.827 0.678 1.5 1.512 1.5h17.988c0.827 0 1.5-0.673 1.5-1.5v-19c0-0.023-0.010-0.043-0.013-0.065s-0.007-0.041-0.013-0.062c-0.023-0.086-0.060-0.166-0.122-0.227l-6.999-6.999c-0.061-0.062-0.142-0.099-0.228-0.122-0.021-0.006-0.039-0.009-0.061-0.012s-0.041-0.013-0.064-0.013h-5c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5zM27.5 11c-0.275 0-0.5-0.224-0.5-0.5v-4.793l5.293 5.293h-4.793zM23.5 16h6c0.276 0 0.5-0.224 0.5-0.5s-0.224-0.5-0.5-0.5h-6c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5zM23.5 20h6c0.276 0 0.5-0.224 0.5-0.5s-0.224-0.5-0.5-0.5h-6c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5zM23.5 24h6c0.276 0 0.5-0.224 0.5-0.5s-0.224-0.5-0.5-0.5h-6c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5zM23.5 28h6c0.276 0 0.5-0.224 0.5-0.5s-0.224-0.5-0.5-0.5h-6c-0.276 0-0.5 0.224-0.5 0.5s0.224 0.5 0.5 0.5z"></path>
</symbol>
</defs>
</svg>
					<h2 class="notices"></h2>
					<div class="kt_title_area">
						<h1>
							<?php echo apply_filters( 'kt_getting_started_page_title', esc_html__( 'Getting Started with the ', 'virtue-toolkit' ) . '<strong>' . esc_html( $this->theme_title ) . '</strong>' ); ?>
						</h1>
						<h4>
							<?php echo apply_filters( 'kt_getting_started_page_subtitle', esc_html__( 'Demo content, recommended plugins and helpful links.', 'virtue-toolkit' ) ); ?>
						</h4>
					</div>
					<?php ob_start(); ?>
					<div class="kad-panel-left kt-admin-clearfix">
						<div class="kad-panel-contain">
							<h2 class="nav-tab-wrapper">
							<?php do_action( 'kt_getting_started_nav_before' ); ?>
								<a class="nav-tab nav-tab-active nav-tab-link" data-tab-id="kt-helplinks" href="#"><?php echo esc_html__( 'Dashboard', 'virtue-toolkit' ); ?></a>
								<a class="nav-tab nav-tab-link" data-tab-id="kt-page-builder" href="#"><?php echo esc_html__( 'Page Builder', 'virtue-toolkit' ); ?></a>
								<a class="nav-tab nav-tab-link" data-tab-id="kt-plugins" href="#"><?php echo esc_html__( 'Recommended Plugins', 'virtue-toolkit' ); ?></a>
							<?php
							if ( 'Ascend' === $this->theme_name ) {
								$pro_link = 'https://www.kadencethemes.com/product/ascend-premium-wordpress-theme/?utm_source=toolkit-welcome&utm_medium=dashboard&utm_campaign=toolkit-ascend';
							} elseif ( 'Virtue' === $this->theme_name ) {
								$pro_link = 'https://www.kadencethemes.com/product/virtue-premium-theme/?utm_source=toolkit-welcome&utm_medium=dashboard&utm_campaign=toolkit-virtue';
							} elseif ( 'Pinnacle' === $this->theme_name ) {
								$pro_link = 'https://www.kadencethemes.com/product/pinnacle-premium-wordpress-theme/?utm_source=toolkit-welcome&utm_medium=dashboard&utm_campaign=toolkit-pinnacle';
							}
							?>
							<a class="nav-tab go-pro-tab" target="_blank" href="<?php echo esc_attr( $pro_link ); ?>"><?php echo esc_html__( 'Go Pro', 'virtue-toolkit' ); ?> <i class="dashicons dashicons-external"></i></a>
							<?php do_action( 'kt_getting_started_nav_after' ); ?>
							</h2>
							<?php do_action( 'kt_getting_started_before' ); ?>
							<div id="kt-helplinks" class="nav-tab-content panel_open kt-admin-clearfix">
								<div class="kad-helpful-links kt-main">
									<h2><?php echo esc_html__( 'Getting Started', 'virtue-toolkit' ); ?></h2>
									<div class="kt-promo-row">
										<div class="kt-promo-box-contain">
											<div class="kt-docs-promo kt-promo-box">
												<div class="kt-welcome-clearfix kt-promo-icon-container">
													<svg class="kt-svg-icon kt-svg-icon-adjustments"><use xlink:href="#kt-svg-icon-adjustments"></use></svg>
												</div>
												<div class="kt-content-promo">
													<h3><?php echo esc_html__( 'Customization', 'virtue-toolkit' ); ?></h3>
													<p><?php echo esc_html__( 'Layouts, fonts, colors, and more. You can control it all from the options panel.', 'virtue-toolkit' ); ?></p>
													<?php
													if ( 'Ascend' === $this->theme_name ) {
														$options_url = admin_url( 'themes.php?page=kad_options' );
													} elseif ( 'Virtue' === $this->theme_name ) {
														$options_url = admin_url( 'themes.php?page=ktoptions' );
													} elseif ( 'Pinnacle' === $this->theme_name ) {
														$options_url = admin_url( 'themes.php?page=ktoptions' );
													}
													echo '<a href="' . esc_attr( $options_url ) . '">' . esc_html__( 'Customize', 'virtue-toolkit' ) . '</a>'; ?>
												</div>
											</div>
										</div>
										<div class="kt-promo-box-contain">
											<div class="kt-demos-promo kt-promo-box">
												<div class="kt-welcome-clearfix kt-promo-icon-container">
												<svg class="kt-svg-icon kt-svg-icon-browser"><use xlink:href="#kt-svg-icon-browser"></use></svg>
												</div>
												<div class="kt-content-promo">
													<h3><?php echo esc_html__( 'Import Demo Content', 'virtue-toolkit' ); ?></h3>
													<p><?php echo esc_html__( 'In just a few clicks you can import an entire demo site to work from.', 'virtue-toolkit' ); ?></p>
													<?php
													if ( class_exists( 'Kadence_Importer' ) ) {
														echo '<a href="' . esc_url( admin_url( 'tools.php?page=kadence-importer' ) ) . '" class="kt-welcome-btn">' . esc_html__( 'View Importer', 'virtue-toolkit' ) . '</a>';
													} else {
														$installed_plugins = get_plugins();
														if ( ! isset( $installed_plugins['kadence-importer/kadence-importer.php'] ) ) {
															$button_label = esc_html__( 'Install Importer', 'virtue-toolkit' );
															$data_action  = 'install';
															wp_create_nonce( 'tgmpa-install' );
														} elseif ( ! is_plugin_active( 'kadence-importer/kadence-importer.php' ) ) {
															$button_label = esc_html__( 'Activate Importer', 'virtue-toolkit' );
															$data_action  = 'activate';
														}
														$install_link    = admin_url( 'admin-ajax.php' );
														$install_nonce   = '';
														$activate_nonce  = wp_create_nonce( 'activate-plugin_kadence-importer/kadence-importer.php' );
														$activation_link = self_admin_url( 'plugins.php?_wpnonce=' . $activate_nonce . '&action=activate&plugin=kadence-importer%2Fkadence-importer.php' );
														?>
														<a class="kt-welcome-btn button kt-trigger-plugin-install install-toolkit" data-redirect-url="<?php echo esc_url( admin_url( 'tools.php?page=kadence-importer' ) ); ?>" data-activating-label="<?php echo esc_attr__( 'Activating...', 'virtue-toolkit' ); ?>" data-installing-label="<?php echo esc_attr__( 'Installing...', 'virtue-toolkit' ); ?>" data-installed-label="<?php echo esc_attr__( 'Installed', 'virtue-toolkit' ); ?>" data-activated-label="<?php echo esc_attr__( 'Activated', 'virtue-toolkit' ); ?>" data-action="<?php echo esc_attr( $data_action ); ?>" data-install-url="<?php echo esc_attr( $install_link ); ?>" data-install-nonce="<?php echo esc_attr( $install_nonce ); ?>" data-activate-url="<?php echo esc_attr( $activation_link ); ?>"><?php echo esc_html( $button_label ); ?></a>
														<?php
													}
													?>
												</div>
											</div>
										</div>
									</div>
									<div class="kt-promo-row">
										<div class="kt-promo-box-contain">
											<div class="kt-builder-promo kt-promo-box">
												<div class="kt-welcome-clearfix kt-promo-icon-container">
													<svg class="kt-svg-icon kt-svg-icon-mbri-edit"><use xlink:href="#kt-svg-icon-mbri-edit"></use></svg>
												</div>
												<div class="kt-content-promo">
													<h3><?php echo esc_html__( 'Page Builder', 'virtue-toolkit' ); ?></h3>
													<p><?php echo esc_html__( 'Take total control over your content with a powerful page builder that will make editing a breeze.', 'virtue-toolkit' ); ?></p>
													<?php echo '<a data-tab-id="kt-page-builder" class="nav-tab-link" href="#">' . esc_html__( 'Get a Builder', 'virtue-toolkit' ) . '</a>'; ?>
												</div>
											</div>
										</div>
										<div class="kt-promo-box-contain">
											<div class="kt-plugins-promo kt-promo-box">
												<div class="kt-welcome-clearfix kt-promo-icon-container">
													<svg class="kt-svg-icon kt-svg-icon-mbri-extension"><use xlink:href="#kt-svg-icon-mbri-extension"></use></svg>
												</div>
												<div class="kt-content-promo">
													<h3><?php echo esc_html__( 'Recommend Plugins', 'virtue-toolkit' ); ?></h3>
													<p><?php echo esc_html__( 'An excellent selection of extensions for your site that can get you powered for greatness.', 'virtue-toolkit' ); ?></p>
													<?php echo '<a data-tab-id="kt-plugins" class="nav-tab-link" href="#">' . esc_html__( 'Explore', 'virtue-toolkit' ) . '</a>'; ?>
												</div>
											</div>
										</div>
									</div>
									<h2><?php echo esc_html__( 'Helpful Links and Resources', 'virtue-toolkit' ); ?></h2>
									<div class="kt-promo-row">
										<div class="kt-promo-box-contain kt-promo-three">
											<div class="kt-tutorials-promo kt-promo-box">
												<div class="kt-welcome-clearfix kt-promo-icon-container">
													<svg class="kt-svg-icon kt-svg-icon-lifesaver"><use xlink:href="#kt-svg-icon-lifesaver"></use></svg>
												</div>
												<div class="kt-content-promo">
													<h3><?php echo esc_html__( 'Support', 'virtue-toolkit' ); ?></h3>
													<p><?php echo esc_html__( 'Are you having trouble getting things to look how you want? Or are you stuck and not sure what to do? We can help!', 'virtue-toolkit' ); ?></p>
													<?php
													if ( 'Ascend' === $this->theme_name ) {
														$link = 'https://wordpress.org/support/theme/ascend';
													} elseif ( 'Virtue' === $this->theme_name ) {
														$link = 'https://wordpress.org/support/theme/virtue';
													} elseif ( 'Pinnacle' === $this->theme_name ) {
														$link = 'https://wordpress.org/support/theme/pinnacle';
													}
													?>
													<a href="<?php echo esc_url( $link ); ?>" target="_blank"><?php echo esc_html__( 'Ask a question', 'virtue-toolkit' ); ?></a>
												</div>
											</div>
										</div>
										<div class="kt-promo-box-contain kt-promo-three">
											<div class="kt-tutorials-promo kt-promo-box">
												<div class="kt-welcome-clearfix kt-promo-icon-container">
													<svg class="kt-svg-icon kt-svg-icon-presentation"><use xlink:href="#kt-svg-icon-presentation"></use></svg>
												</div>
												<div class="kt-content-promo">
													<h3><?php echo esc_html__( 'Tutorials', 'virtue-toolkit' ); ?></h3>
													<p><?php echo esc_html__( 'Are you not sure how to do something? Check out our tutorials for quick help with many topics.', 'virtue-toolkit' ); ?></p>
													<a href="https://www.kadencethemes.com/kadence-tutorials/?utm_source=toolkit-welcome&utm_medium=dashboard&utm_campaign=toolkit-<?php echo esc_attr( strtolower( $this->theme_name ) ); ?>" target="_blank"><?php echo esc_html__( 'View', 'virtue-toolkit' ); ?></a>
												</div>
											</div>
										</div>
										<div class="kt-promo-box-contain kt-promo-three">
											<div class="kt-tutorials-promo kt-promo-box">
												<div class="kt-welcome-clearfix kt-promo-icon-container">
													<svg class="kt-svg-icon kt-svg-icon-documents"><use xlink:href="#kt-svg-icon-documents"></use></svg>
												</div>
												<div class="kt-content-promo">
													<h3><?php echo esc_html__( 'Theme Documentation', 'virtue-toolkit' ); ?></h3>
													<p><?php /* translators: %1$s: Theme Title, %2$s: Theme title */ printf( esc_html__( 'The %1$s has thorough documentation that can help you learn about any aspect of the %2$s.', 'virtue-toolkit' ), esc_html( $this->theme_title ), esc_html( $this->theme_title ) ); ?></p>
													<?php echo '<a href="http://docs.kadencethemes.com/' . esc_attr( strtolower( $this->theme_name ) ) . '-free/" target="_blank">' . esc_html__( 'Browse', 'virtue-toolkit' ) . '</a>'; ?>
												</div>
											</div>
										</div>
									</div>
									<div class="kt-promo-row">
										<div class="kt-promo-box-contain kt-promo-full">
											<div class="kt-newsletter-promo kt-promo-box">
												<div class="kt-welcome-clearfix kt-promo-icon-container">
													<svg class="kt-svg-icon kt-svg-icon-envelope"><use xlink:href="#kt-svg-icon-envelope"></use></svg>
												</div>
												<div class="kt-content-promo">
													<h3><?php echo esc_html__( 'Kadence Themes Newsletter', 'virtue-toolkit' ); ?></h3>
													<p><?php echo esc_html__( 'Get the latest news about product updates and new plugins right to your inbox.', 'virtue-toolkit' ); ?></p>
													<a href="https://www.kadencethemes.com/newsletter-subscribe/?utm_source=toolkit-welcome&utm_medium=dashboard&utm_campaign=toolkit" target="_blank"><?php echo esc_html__( 'Subscribe', 'virtue-toolkit' ); ?></a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div id="kt-page-builder" class="nav-tab-content kt-admin-clearfix">
								<div class="kad-recomended-plugins kt-main">
									<h2><?php echo esc_html__( 'Choosing a Page Builder', 'virtue-toolkit' ); ?></h2>
									<p class="kt-subtitle"><?php /* translators: %1$s: <u>, %2$s: </u>, %3$s: Theme Title   */  printf( esc_html__( 'We recommend using %1$sONE%2$s of these page builders. You can certainly try them all out but for performance we suggest just using one. The %3$s will work great with any one.', 'virtue-toolkit' ), '<u>', '</u>', esc_html( $this->theme_title ) ); ?></p>
									<div class="kt_suggest_section kt-admin-clearfix">
										<?php
										$suggested_builder = $this->suggested_builder_plugins();
										foreach ( $suggested_builder as $builder_plugin ) {
											echo '<div class="kt_plugin_box builder_box">';
												echo '<div class="builder_box_inner">';
													echo '<div class="builder_box_image">';
														echo '<img src="' . esc_url( $builder_plugin['image'] ) . '">';
														echo '<h5 class="' . esc_attr( $builder_plugin['activated_cs'] ) . '">' . esc_html( $builder_plugin['activated'] ) . '</h5>';
													echo '</div>';
													echo '<div class="builder_box_content">';
														echo '<h2>' . esc_html( $builder_plugin['name'] ) . '</h2>';
														echo '<p>' . wp_kses_post( $builder_plugin['desc'] ) . '</p>';
														echo '<div class="kt-pros-cons kt-pros">';
															echo '<h4>' . esc_html__( 'Pros:', 'virtue-toolkit' ) . '</h4>';
															echo '<ul>' . wp_kses_post( $builder_plugin['pros'] ) . '</ul>';
														echo '</div>';
														echo '<div class="kt-pros-cons">';
															echo '<h4>' . esc_html__( 'Cons:', 'virtue-toolkit' ) . '</h4>';
															echo '<ul>' . wp_kses_post( $builder_plugin['cons'] ) . '</ul>';
														echo '</div>';
														echo '<div class="kt-welcome-clearfix"></div>';
														echo '<a class="kt_plugin_button button ' . esc_attr( $builder_plugin['class'] ) . '" data-redirect-url="' . esc_url( $builder_plugin['redirect_url'] ) . '" data-activating-label="' . esc_attr__( 'Activating...', 'virtue-toolkit' ) . '" data-activated-label="' . esc_attr__( 'Activated', 'virtue-toolkit' ) . '"  data-installing-label="' . esc_attr__( 'Installing...', 'virtue-toolkit' ) . '"  data-installed-label="' . esc_attr__( 'Installed', 'virtue-toolkit' ) . '" data-action="' . esc_attr( $builder_plugin['data_action'] ) . '" data-install-url="' . esc_attr( $builder_plugin['install_link'] ) . '" data-activate-url="' . esc_attr( $builder_plugin['activation_link'] ) . '" href="' . esc_attr( $builder_plugin['action'] ) . '">' . esc_html( $builder_plugin['action_title'] ) . '</a>';
													echo '</div>';
												echo '</div>';
											echo '</div>';
										}
										?>
									</div>
									<p class="kt-subtitle"><?php /* translators: %1$s: <a>, %2$s: </a>   */ printf( esc_html__( 'Having trouble deciding? Check out our post about the best %1$sPage Builders%2$s.', 'virtue-toolkit' ), '<a href="https://www.kadencethemes.com/recommended-page-builders-for-wordpress/?utm_source=toolkit-welcome&utm_medium=dashboard&utm_campaign=toolkit" target="_blank">', '</a>' ); ?></p>
								</div>
							</div>
							<div id="kt-plugins" class="nav-tab-content kt-admin-clearfix">
								<div class="kad-recomended-plugins kt-main">
									<h2><?php echo esc_html__( 'Recommended Plugins', 'virtue-toolkit' ); ?></h2>
									<p class="kt-subtitle"><?php echo esc_html__( 'These are plugins are not required. But they are worth checking out for the impressive features that can really enhance your site.', 'virtue-toolkit' ); ?></p>
									<div class="kt_suggest_section kt-admin-clearfix">
										<?php
										$suggested = $this->suggested_plugins();
										foreach ( $suggested as $plugin ) {
											echo '<div class="kt_plugin_box">';
											echo '<img src="' . esc_url( $plugin['image'] ) . '">';
											echo '<h5 class="' . esc_attr( $plugin['activated_cs'] ) . '">' . esc_html( $plugin['activated'] ) . '</h5>';
											echo '<h3>' . esc_html( $plugin['name'] ) . '</h3>';
											echo '<p>' . wp_kses_post( $plugin['desc'] ) . '</p>';
											echo '<a class="kt_plugin_button ' . esc_attr( $plugin['class'] ) . '" data-redirect-url="' . esc_url( $plugin['redirect_url'] ) . '" data-activating-label="' . esc_attr__( 'Activating...', 'virtue-toolkit' ) . '" data-activated-label="' . esc_attr__( 'Activated', 'virtue-toolkit' ) . '"  data-installing-label="' . esc_attr__( 'Installing...', 'virtue-toolkit' ) . '"  data-installed-label="' . esc_attr__( 'Installed', 'virtue-toolkit' ) . '" data-action="' . esc_attr( $plugin['data_action'] ) . '" data-install-url="' . esc_attr( $plugin['install_link'] ) . '" data-activate-url="' . esc_attr( $plugin['activation_link'] ) . '" href="' . esc_attr( $plugin['action'] ) . '">' . esc_html( $plugin['action_title'] ) . '</a>';
											echo '</div>';
										}
										?>
									</div>
								</div>
							</div>
							<?php do_action( 'kt_getting_started_after' ); ?>
						</div>
					</div>
					<div class="kad-panel-bottom kt-admin-clearfix">
						<div class="kad-featured-items kt-admin-clearfix">
							<?php if ( class_exists( 'woocommerce' ) ) { ?>
								<h2><?php echo esc_html__( 'Featured WooCommerce Extensions', 'virtue-toolkit' ); ?></h2>
								<div class="featured-row">
									<div class="featured">
										<a href="https://wordpress.org/plugins/kadence-woocommerce-elementor/" target="_blank">
											<img src="<?php echo esc_url( VIRTUE_TOOLKIT_URL . 'welcome/images/kt-woo-ele.jpg' ); ?>">
										</a>
										<a href="https://wordpress.org/plugins/kadence-woocommerce-elementor/" target="_blank"><h4>Kadence WooCommerce Elementor</h4></a>
										<p>Build Custom layouts for your products in WooCommerce using the amazing Elementor page building experience. You can work inside one product or build templates that you can assign to multiple products.</p>
										<a href="https://wordpress.org/plugins/kadence-woocommerce-elementor/" class="kt-feat-btn" target="_blank">Learn More</a>
									</div>
									<div class="featured">
										<a href="https://www.kadencethemes.com/product/kadence-woo-extras/?utm_source=toolkit&utm_medium=cpc&utm_campaign=woo_extra" target="_blank">
											<img src="<?php echo esc_url( VIRTUE_TOOLKIT_URL . 'welcome/images/kt-woo-email-designer.jpg' ); ?>">
										</a>
										<a href="https://www.kadencethemes.com/product/kadence-woo-extras/?utm_source=toolkit&utm_medium=cpc&utm_campaign=woo_extra" target="_blank"><h4>Kadence WooCommerce Email Designer</h4></a>
										<p>This plugin lets you easily customize the default transactional WooCommerce email templates. Edit the design using the native WordPress customizer for instant visual edits. Customize the text (including body text) or each email template in WooCommerce without editing code.</p>
										<a href="https://wordpress.org/plugins/kadence-woocommerce-email-designer/" class="kt-feat-btn" target="_blank">Learn More</a>
									</div>
								</div>
							<?php } else { ?>
								<div class="featured-row">
									<h2><?php echo esc_html__( 'Featured WooCommerce Extensions', 'virtue-toolkit' ); ?></h2>
									<div class="featured">
										<a href="https://wordpress.org/plugins/kadence-woocommerce-elementor/" target="_blank">
											<img src="<?php echo esc_url( VIRTUE_TOOLKIT_URL . 'welcome/images/kt-woo-ele.jpg' ); ?>">
										</a>
										<a href="https://wordpress.org/plugins/kadence-woocommerce-elementor/" target="_blank"><h4>Kadence WooCommerce Elementor</h4></a>
										<p>Build Custom layouts for your products in WooCommerce using the amazing Elementor page building experience. You can work inside one product or build templates that you can assign to multiple products.</p>
										<a href="https://wordpress.org/plugins/kadence-woocommerce-elementor/" class="kt-feat-btn" target="_blank">Learn More</a>
									</div>
									<div class="featured">
										<a href="https://www.kadencethemes.com/product/kadence-woo-extras/?utm_source=toolkit&utm_medium=cpc&utm_campaign=woo_extra" target="_blank">
											<img src="<?php echo esc_url( VIRTUE_TOOLKIT_URL . 'welcome/images/kt-woo-email-designer.jpg' ); ?>">
										</a>
										<a href="https://www.kadencethemes.com/product/kadence-woo-extras/?utm_source=toolkit&utm_medium=cpc&utm_campaign=woo_extra" target="_blank"><h4>Kadence WooCommerce Email Designer</h4></a>
										<p>This plugin lets you easily customize the default transactional WooCommerce email templates. Edit the design using the native WordPress customizer for instant visual edits. Customize the text (including body text) or each email template in WooCommerce without editing code.</p>
										<a href="https://wordpress.org/plugins/kadence-woocommerce-email-designer/" class="kt-feat-btn" target="_blank">Learn More</a>
									</div>
								</div>
							<?php } ?>
						</div>
					</div>
					<?php
					$welcome_content = ob_get_clean();
					echo apply_filters( 'kt_getting_started_content', $welcome_content );
					?>
				</div>
				<?php
			}
		}
		/**
		 * Get array of suggested plguins.
		 */
		public function suggested_plugins() {
			$suggested         = array(
				'woocommerce'   => array(
					'slug'         => 'woocommerce',
					'base'         => 'woocommerce',
					'plugin_check' => 'woocommerce/woocommerce.php',
					'active_url'   => admin_url( 'admin.php?page=wc-settings' ),
					'action_title' => __( 'WooCommerce Settings', 'virtue-toolkit' ),
					'name'         => 'WooCommerce',
					'desc'         => 'WooCommerce is a free eCommerce plugin that allows you to sell anything, beautifully. Built to integrate seamlessly with WordPress.',
					'image'        => esc_url( VIRTUE_TOOLKIT_URL . 'welcome/images/woo_logo.jpg' ),
					'author'       => 'Automattic',
					'redirect_url' => '',
				),
				'wpforms-lite'  => array(
					'slug'         => 'wpforms-lite',
					'base'         => 'wpforms',
					'plugin_check' => 'wpforms-lite/wpforms.php',
					'active_url'   => admin_url( 'admin.php?page=wc-settings' ),
					'action_title' => __( 'WPForms Settings', 'virtue-toolkit' ),
					'name'         => 'Contact Form by WPForms',
					'desc'         => 'A Drag & Drop Form Builder for WordPress, you can manage multiple contact forms, customize the form a with builder tools.',
					'image'        => esc_url( VIRTUE_TOOLKIT_URL . 'welcome/images/wpforms_logo.jpg' ),
					'author'       => 'WPForms',
					'redirect_url' => '',
				),
				'wordpress-seo' => array(
					'slug'         => 'wordpress-seo',
					'base'         => 'wp-seo',
					'plugin_check' => 'wordpress-seo/wp-seo.php',
					'active_url'   => admin_url( 'admin.php?page=wpseo_dashboard' ),
					'action_title' => __( 'Yoast SEO Settings', 'virtue-toolkit' ),
					'name'         => 'Yoast SEO',
					'desc'         => 'Improve your WordPress SEO: Write better content and have a fully optimized WordPress site using Yoast SEO plugin.',
					'image'        => esc_url( VIRTUE_TOOLKIT_URL . 'welcome/images/ws_logo.jpg' ),
					'author'       => 'Yoast',
					'redirect_url' => '',
				),
			);
			$installed_plugins = get_plugins();
			foreach ( $suggested as $plugin ) {
				if ( is_plugin_active( $plugin['plugin_check'] ) ) {
					$action          = $plugin['active_url'];
					$action_title    = $plugin['action_title'];
					$activated       = esc_html__( 'Activated', 'virtue-toolkit' );
					$activated_class = 'activated plugin-active';
					$class           = '';
					$data_action     = '';
					$redirect_url    = $plugin['redirect_url'];
					$install_link    = '';
					$activation_link = '';
				} elseif ( isset( $installed_plugins[ $plugin['plugin_check'] ] ) ) {
					$data_action     = 'activate';
					$install_link    = '';
					$activate_nonce  = wp_create_nonce( 'activate-plugin_' . $plugin['plugin_check'] );
					$activation_link = self_admin_url( 'plugins.php?_wpnonce=' . $activate_nonce . '&action=activate&plugin=' . esc_attr( $plugin['slug'] ) . '%2F' . esc_attr( $plugin['base'] ) . '.php' );
					$action_title    = esc_html__( 'Activate', 'virtue-toolkit' ) . ' ' . $plugin['name'];
					$activated_class = 'activated plugin-installed';
					$activated       = esc_html__( 'Installed', 'virtue-toolkit' );
					$class           = 'kt-trigger-plugin-install';
					$action          = '#';
					$redirect_url    = $plugin['redirect_url'];
				} else {
					$install_link    = wp_nonce_url(
						add_query_arg(
							array(
								'action' => 'install-plugin',
								'plugin' => $plugin['slug'],
							),
							network_admin_url( 'update.php' )
						),
						'install-plugin_' . $plugin['slug']
					);
					$redirect_url    = $plugin['redirect_url'];
					$activate_nonce  = wp_create_nonce( 'activate-plugin_' . $plugin['plugin_check'] );
					$activation_link = self_admin_url( 'plugins.php?_wpnonce=' . $activate_nonce . '&action=activate&plugin=' . esc_attr( $plugin['slug'] ) . '%2F' . esc_attr( $plugin['base'] ) . '.php' );
					$action          = '#';
					$action_title    = esc_html__( 'Install', 'virtue-toolkit' ) . ' ' . $plugin['name'];
					$activated       = esc_html__( 'Not Installed', 'virtue-toolkit' );
					$activated_class = 'activated plugin-not-installed';
					$class           = 'kt-trigger-plugin-install';
					$data_action     = 'install';
				}
				$output[ $plugin['slug'] ] = array(
					'image'           => $plugin['image'],
					'name'            => $plugin['name'],
					'author'          => $plugin['author'],
					'desc'            => $plugin['desc'],
					'action'          => $action,
					'action_title'    => $action_title,
					'activated'       => $activated,
					'activated_cs'    => $activated_class,
					'data_action'     => $data_action,
					'install_link'    => $install_link,
					'activation_link' => $activation_link,
					'redirect_url'    => $redirect_url,
					'class'           => $class,
				);
			}
			return $output;
		}
		/**
		 * Get array of suggested page builders.
		 */
		public function suggested_builder_plugins() {
			$suggested         = array(
				'elementor'         => array(
					'slug'         => 'elementor',
					'base'         => 'elementor',
					'plugin_check' => 'elementor/elementor.php',
					'active_url'   => admin_url( 'admin.php?page=elementor' ),
					'redirect_url' => admin_url( 'admin.php?page=elementor' ),
					'name'         => 'Elementor Page Builder',
					'desc'         => 'A free front-end page builder with tons of ready-made content you can easily use. Elementor offers very powerful features and can create an incredible range of designs while giving great mobile specific editing. A <a href="https://elementor.com/pro/?ref=2435" target="_blank">pro extension</a> is available which adds even more features.',
					'pros'         => '<li>Fully functional as a free plugin</li><li>Good user base with lots of extensions</li><li>Tons of pre-built content</li><li>Mobile previews and responsive design controls.</li>',
					'cons'         => '<li>For some it can be overwhelming to learn, certain elements can be confusing.</li><li>While you don\'t need the pro extension, you may get annoyed seeing things it can do or demos it can install that you would have to pay to use.</li>',
					'image'        => VIRTUE_TOOLKIT_URL . 'welcome/images/toolkit_ele.jpg',
					'author'       => 'elementor.com',
					'action_title' => __( 'Elementor Settings', 'virtue-toolkit' ),
				),
				'brizy'             => array(
					'slug'         => 'brizy',
					'base'         => 'brizy',
					'plugin_check' => 'brizy/brizy.php',
					'active_url'   => admin_url( 'admin.php?page=brizy-settings' ),
					'redirect_url' => admin_url( 'admin.php?page=brizy-settings' ),
					'name'         => 'Brizy – Page Builder',
					'desc'         => 'A free front-end page builder that is lighting fast and intuitive to use. Brizy offers powerful features with an app like feel. The editor is very clutter-free with a lot of features not found in other builders. A <a href="https://brizy.io/account/aff/go/kadence_themes?i=4" target="_blank">pro extension</a> is coming which will add more features.',
					'pros'         => '<li>Fully functional as a free plugin</li><li>Very fast and for many intuitive to use.</li><li>Features only found with brizy, like global linked colors and fonts or 4k icons</li>',
					'cons'         => '<li>Early in development and still working out all the compatibility bugs.</li><li>Very little documentation or tutorials so far.</li><li>No API, so no extensions for it yet.</li><li>Missing some key features, like galleries.</li>',
					'image'        => VIRTUE_TOOLKIT_URL . 'welcome/images/toolkit_brizy.jpg',
					'author'       => 'brizy.co',
					'action_title' => __( 'Brizy Settings', 'virtue-toolkit' ),
				),
				'siteorigin-panels' => array(
					'slug'         => 'siteorigin-panels',
					'base'         => 'siteorigin-panels',
					'plugin_check' => 'siteorigin-panels/siteorigin-panels.php',
					'active_url'   => admin_url( 'options-general.php?page=siteorigin_panels' ),
					'redirect_url' => admin_url( 'options-general.php?page=siteorigin_panels' ),
					'name'         => 'Page Builder by SiteOrigin',
					'desc'         => 'A Free drag and drop, page builder that simplifies building your website using tools that will feel familiar if you have worked in WordPress. While there is a "live editor" mode it does not really compete with the other page builders for real front-end editing. But the developers have plans to improve Gutenberg with it\'s features which could make Gutenberg much more useful.',
					'pros'         => '<li>Fully functional as a free plugin</li><li>Widely popular with tons of free add on widgets</li><li>Stable with years of solid development behind it.</li>',
					'cons'         => '<li>Not as user friendly as front-end builders</li><li>Slower editing experience</li><li>Not a lot of new features being developed.</li>',
					'image'        => VIRTUE_TOOLKIT_URL . 'welcome/images/toolkit_siteorigin.jpg',
					'author'       => 'SiteOrigin',
					'action_title' => __( 'SiteOrigin Page Builder Settings', 'virtue-toolkit' ),
				),
			);
			$installed_plugins = get_plugins();
			foreach ( $suggested as $plugin ) {
				if ( is_plugin_active( $plugin['plugin_check'] ) ) {
					$action          = $plugin['active_url'];
					$action_title    = $plugin['action_title'];
					$activated       = esc_html__( 'Activated', 'virtue-toolkit' );
					$activated_class = 'activated plugin-active';
					$class           = '';
					$data_action     = '';
					$redirect_url    = $plugin['redirect_url'];
					$install_link    = '';
					$activation_link = '';
				} elseif ( isset( $installed_plugins[ $plugin['plugin_check'] ] ) ) {
					$data_action     = 'activate';
					$install_link    = '';
					$activate_nonce  = wp_create_nonce( 'activate-plugin_' . $plugin['plugin_check'] );
					$activation_link = self_admin_url( 'plugins.php?_wpnonce=' . $activate_nonce . '&action=activate&plugin=' . esc_attr( $plugin['slug'] ) . '%2F' . esc_attr( $plugin['slug'] ) . '.php' );
					$action_title    = esc_html__( 'Activate', 'virtue-toolkit' ) . ' ' . $plugin['name'];
					$activated_class = 'activated plugin-installed';
					$activated       = esc_html__( 'Installed', 'virtue-toolkit' );
					$class           = 'kt-trigger-plugin-install';
					$action          = '#';
					$redirect_url    = $plugin['redirect_url'];
				} else {
					$install_link    = wp_nonce_url(
						add_query_arg(
							array(
								'action' => 'install-plugin',
								'plugin' => $plugin['slug'],
							),
							network_admin_url( 'update.php' )
						),
						'install-plugin_' . $plugin['slug']
					);
					$redirect_url    = $plugin['redirect_url'];
					$activate_nonce  = wp_create_nonce( 'activate-plugin_' . $plugin['plugin_check'] );
					$activation_link = self_admin_url( 'plugins.php?_wpnonce=' . $activate_nonce . '&action=activate&plugin=' . esc_attr( $plugin['slug'] ) . '%2F' . esc_attr( $plugin['slug'] ) . '.php' );
					$action          = '#';
					$action_title    = esc_html__( 'Install', 'virtue-toolkit' ) . ' ' . $plugin['name'];
					$activated       = esc_html__( 'Not Installed', 'virtue-toolkit' );
					$activated_class = 'activated plugin-not-installed';
					$class           = 'kt-trigger-plugin-install';
					$data_action     = 'install';
				}
				$output[ $plugin['slug'] ] = array(
					'image'           => $plugin['image'],
					'name'            => $plugin['name'],
					'author'          => $plugin['author'],
					'desc'            => $plugin['desc'],
					'pros'            => $plugin['pros'],
					'cons'            => $plugin['cons'],
					'action'          => $action,
					'action_title'    => $action_title,
					'activated'       => $activated,
					'activated_cs'    => $activated_class,
					'data_action'     => $data_action,
					'install_link'    => $install_link,
					'activation_link' => $activation_link,
					'redirect_url'    => $redirect_url,
					'class'           => $class,
				);
			}
			return $output;
		}
		/**
		 * Register_importer
		 */
		public function register_importer() {
			$plugins   = array();
			$plugins[] = array(
				'name'               => 'Kadence Importer',
				'slug'               => 'kadence-importer',
				'source'             => 'https://s3.amazonaws.com/ktupdates/importer/kadence-importer.zip',
				'required'           => false,
				'version'            => '2.0.3',
				'force_activation'   => false,
				'force_deactivation' => false,
				'external_url'       => '',
			);
			$config    = array(
				'domain'       => 'virtue-toolkit', // Text domain - likely want to be the same as your theme.
				'default_path' => '', // Default absolute path to pre-packaged plugins
				'parent_slug'  => 'themes.php', // Parent menu slug.
				'menu'         => 'install-recommended-plugins', // Menu slug
				'has_notices'  => false, // Show admin notices or not
				'is_automatic' => false, // Automatically activate plugins after installation or not
				'message'      => '', // Message to output right before the plugins table.
				'strings'      => array(
					'page_title'                      => __( 'Install Recommended Plugins', 'virtue-toolkit' ),
					'menu_title'                      => __( 'Theme Plugins', 'virtue-toolkit' ),
					'installing'                      => __( 'Installing Plugin: %s', 'virtue-toolkit' ), // %1$s = plugin name
					'oops'                            => __( 'Something went wrong with the plugin API.', 'virtue-toolkit' ),
					'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'virtue-toolkit' ), // %1$s = plugin name(s)
					'notice_can_install_recommended'  => _n_noop( 'This theme comes packaged with the following premium plugin: %1$s. Plugin is not required, but suggested.', 'This theme comes packaged with the following premium plugins: %1$s. Plugins are not required, but suggested.', 'virtue-toolkit' ), // %1$s = plugin name(s)
					'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'virtue-toolkit' ), // %1$s = plugin name(s)
					'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' , 'virtue-toolkit'), // %1$s = plugin name(s)
					'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' , 'virtue-toolkit'), // %1$s = plugin name(s)
					'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'virtue-toolkit' ), // %1$s = plugin name(s)
					'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' , 'virtue-toolkit'), // %1$s = plugin name(s)
					'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' , 'virtue-toolkit'), // %1$s = plugin name(s)
					'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'virtue-toolkit' ),
					'activate_link'                   => _n_noop( 'Activate installed plugin', 'Activate installed plugins', 'virtue-toolkit' ),
					'return'                          => __( 'Return to recommended Plugins Installer', 'virtue-toolkit' ),
					'plugin_activated'                => __( 'Plugin activated successfully.', 'virtue-toolkit' ),
					'complete'                        => __( 'All plugins installed and activated successfully. %s', 'virtue-toolkit' ), // %1$s = dashboard link
					'nag_type'                        => 'updated' // Determines admin notice type - can only be 'updated' or 'error'.
				),
			);
			if( 'Not Kadence' !== $this->theme_name ) {
				tgmpa( $plugins, $config );
			}
		}
		/**
		 * add settings link
		 */
		public function add_settings_link( $links ) {
			$settings_link = '<a href="' . admin_url( 'themes.php?page=kadence_welcome_page' ) . '">' . __( 'Settings', 'virtue-toolkit' ) . '</a>';
			array_push( $links, $settings_link );
			return $links;
		}
		/**
		 * Install Importer
		 */
		public function ajax_install_import_plugin() {
			if ( ! check_ajax_referer( 'install-plugin_kadence-importer', 'wpnonce' ) ) {
				exit( 0 );
			}
			$tgmpa_url = $this->tgmpa->get_tgmpa_url();
			$json      = array(
				'url'           => admin_url( 'themes.php?page=install-recommended-plugins' ),
				'plugin'        => array( 'kadence-importer' ),
				'tgmpa-page'    => 'install-recommended-plugins',
				'plugin_status' => 'all',
				'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
				'action'        => 'tgmpa-bulk-install',
				'action2'       => - 1,
				'message'       => esc_html__( 'Installing', 'virtue-toolkit' ),
			);
			wp_send_json( $json );
		}

	}
}

new Virtue_Toolkit_Welcome();
