<?php

defined( 'ABSPATH' ) || exit;

/**
 * Main Boostify Header Footer Builder
 *
 * @class Boostify_Header_Footer_Builder
 */
if ( ! class_exists( 'Boostify_Header_Footer_Builder' ) ) {
	class Boostify_Header_Footer_Builder {

		private static $_instance;

		public static function instance() {
			if ( ! isset( self::$instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Boostify Header Footer Builder Constructor.
		 */
		public function __construct() {
			$this->includes();
			$this->hooks();
		}

		public function includes() {
			include_once HT_HF_PATH . 'inc/admin/class-boostify-header-footer-admin.php';
			include_once HT_HF_PATH . 'inc/admin/class-boostify-header-footer-metabox.php';
			include_once HT_HF_PATH . 'inc/class-boostify-header-footer-template.php';
			include_once HT_HF_PATH . 'inc/elementor/class-boostify-header-footer-elementor.php';
		}

		public function hooks() {
			add_action( 'init', array( $this, 'post_types' ) );
			add_action( 'plugins_loaded', array( $this, 'init' ) );
			add_action( 'body_class', array( $this, 'body_ver' ) );
		}

		public function body_ver( $classes ) {
			$classes[] = 'boostify-header-footer-' . HT_HF_VER;

			return $classes;
		}

		// Register Post Type
		public function post_types() {
			register_post_type(
				'btf_builder',
				array(
					'supports'     => array( 'title', 'page-attributes' ),
					'hierarchical' => true,
					'rewrite'      => array( 'slug' => 'btf_builder' ),
					'has_archive'  => false,
					'public'       => true,
					'labels'       => array(
						'name'          => esc_html__( 'Header Footer Builder', 'ht-wanderlust' ),
						'add_new_item'  => esc_html__( 'Add New Header, Footer', 'ht-wanderlust' ),
						'edit_item'     => esc_html__( 'Edit Header, Footer', 'ht-wanderlust' ),
						'all_items'     => esc_html__( 'All Header, Footer', 'ht-wanderlust' ),
						'singular_name' => esc_html__( 'Header Footer Builder', 'ht-wanderlust' ),
					),
					'menu_icon'    => 'dashicons-editor-kitchensink',
				)
			);
		}

		// Listing Teamplate
		public function init() {
			new Boostify_Header_Footer_Metabox();
		}

	}

	Boostify_Header_Footer_Builder::instance();
}
