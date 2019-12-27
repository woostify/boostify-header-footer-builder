<?php


defined( 'ABSPATH' ) || exit;

/**
 * Main Boostify Header Footer Admin Class
 *
 * @class Boostify_Header_Footer_Admin
 */
if ( ! class_exists( 'Boostify_Header_Footer_Admin' ) ) {
	class Boostify_Header_Footer_Admin {


		private static $_instance;


		public static function instance() {
			if ( ! isset( self::$instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Boostify Header Footer Admin Constructor.
		 */
		public function __construct() {
			$this->hooks();
		}

		public function hooks() {
			add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_style' ) );
		}

		public function load_admin_style() {
			wp_enqueue_style(
				'wt-admin',
				HT_HF_URL . 'assets/css/admin.css',
				array(),
				HT_HF_VER
			);
		}
	}

	Boostify_Header_Footer_Admin::instance();
}
