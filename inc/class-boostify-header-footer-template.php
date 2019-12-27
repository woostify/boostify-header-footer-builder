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
			add_filter( 'single_template', array( $this, 'wt_tour_single_template' ) );
		}

		// Template single post type
		public function wt_tour_single_template( $single_template ) {
			if ( 'btf_builder' == get_post_type() ) {
				$single_template = HT_HF_PATH . 'templates/hf.php';
			}

			return $single_template;
		}
	}

	Boostify_Header_Footer_Template::instance();

}
