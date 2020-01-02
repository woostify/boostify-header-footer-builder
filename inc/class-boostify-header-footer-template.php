<?php
/**
 * Comments
 *
 * Handle comments (reviews and order notes).
 *
 * @package Boostify_Header_Footer_Template
 *
 */

defined( 'ABSPATH' ) || exit;

/**
 * Boostify Header Footer Template Class.
 */
if ( ! class_exists( 'Boostify_Header_Footer_Template' ) ) {


	class Boostify_Header_Footer_Template {

		private static $instance;

		/**
		 *  Initiator
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Hook in methods.
		 */
		public function __construct() {
			add_filter( 'single_template', array( $this, 'single_template' ) );
			add_action( 'get_header', array( $this, 'render_header' ) );
			add_action( 'get_footer', array( $this, 'render_footer' ) );
		}

		// Template single post type
		public function single_template( $single_template ) {
			if ( 'btf_builder' == get_post_type() ) {
				$single_template = HT_HF_PATH . 'templates/hf.php';
			}

			return $single_template;
		}

		public function render_header() {
			$header_default = get_option( 'bhf_default_header' );
			$id             = get_the_ID();
			$current        = get_post_meta( $id, 'bhf_display_header', true );
			if ( 'none' === $header_default && ( 'none' === $current || empty( $current ) ) ) {
				return;
			}

			if ( is_home() ) {
				return;
			}

			require HT_HF_PATH . 'templates/default/header.php';
			$templates   = [];
			$templates[] = 'header.php';
			// Avoid running wp_head hooks again.
			remove_all_actions( 'wp_head' );
			ob_start();
			locate_template( $templates, true );
			ob_get_clean();
		}

		public function render_footer() {

			$footer_default = get_option( 'bhf_default_footer' );
			$id             = get_the_ID();
			$current        = get_post_meta( $id, 'bhf_display_footer', true );
			if ( 'none' === $footer_default && ( 'none' === $current || empty( $current ) ) ) {
				return;
			}
			if ( is_home() ) {
				return;
			}
			require HT_HF_PATH . 'templates/default/footer.php';
			$templates   = [];
			$templates[] = 'footer.php';
			// Avoid running wp_footer hooks again.
			remove_all_actions( 'wp_footer' );
			ob_start();
			locate_template( $templates, true );
			ob_get_clean();

		}
	}

	Boostify_Header_Footer_Template::instance();

}
