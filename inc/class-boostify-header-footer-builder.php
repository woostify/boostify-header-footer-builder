<?php

defined( 'ABSPATH' ) || exit;

/**
 * Main Boostify Header Footer Builder
 *
 * @class Boostify_Header_Footer_Builder
 */
if ( ! class_exists( 'Boostify_Header_Footer_Builder' ) ) {
	class Boostify_Header_Footer_Builder {

		private static $instance;

		public static function instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Boostify Header Footer Builder Constructor.
		 */
		public function __construct() {
			$this->includes();
			$this->hooks();
		}

		public function includes() {
			include_once BOOSTIFY_HEADER_FOOTER_PATH . 'inc/helper.php';
			include_once BOOSTIFY_HEADER_FOOTER_PATH . 'inc/admin/class-boostify-header-footer-admin.php';
			include_once BOOSTIFY_HEADER_FOOTER_PATH . 'inc/admin/class-boostify-header-footer-metabox.php';
			include_once BOOSTIFY_HEADER_FOOTER_PATH . 'inc/class-boostify-header-footer-template.php';
			include_once BOOSTIFY_HEADER_FOOTER_PATH . 'inc/elementor/class-boostify-header-footer-template-render.php';
			include_once BOOSTIFY_HEADER_FOOTER_PATH . 'inc/elementor/class-boostify-header-footer-elementor.php';
		}

		public function hooks() {
			add_action( 'init', array( $this, 'post_types' ) );
			add_action( 'plugins_loaded', array( $this, 'init' ) );
			add_action( 'body_class', array( $this, 'body_ver' ) );
			add_action( 'elementor/controls/controls_registered', array( $this, 'modify_controls' ), 10, 1 );
			add_action( 'elementor/editor/wp_head', array( $this, 'enqueue_icon' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'style' ), 99 );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_icon' ), 99 );
			add_action( 'boostify_hf_seach_form', 'boostify_header_footer_search_form', 10, 3 );
		}

		public function body_ver( $classes ) {
			$classes[] = 'boostify-header-footer-' . BOOSTIFY_HEADER_FOOTER_VER;

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
						'name'          => esc_html__( 'Elementor Builder', 'boostify' ),
						'add_new_item'  => esc_html__( 'Add New Header, Footer', 'boostify' ),
						'edit_item'     => esc_html__( 'Edit Header, Footer', 'boostify' ),
						'all_items'     => esc_html__( 'All Header, Footer', 'boostify' ),
						'singular_name' => esc_html__( 'Elementor Builder', 'boostify' ),
					),
					'menu_icon'    => 'dashicons-editor-kitchensink',
				)
			);
		}

		// Listing Teamplate
		public function init() {
			new Boostify_Header_Footer_Metabox();
			new Boostify_Header_Footer_Template_Render();
		}

		// Add Icon Elementor
		public function modify_controls( $controls_registry ) {
			// Get existing icons
			$icons = $controls_registry->get_control( 'icon' )->get_settings( 'options' );
			// Append new icons
			$new_icons = array_merge(
				array(
					'ion-android-arrow-dropdown'  => 'Ion Dropdown',
					'ion-android-arrow-dropright' => 'Ion Dropright',
					'ion-android-arrow-forward'   => 'Ion Forward',
					'ion-chevron-right'           => 'Ion Right',
					'ion-chevron-down'            => 'Ion Downr',
					'ion-ios-arrow-down'          => 'Ion Ios Down',
					'ion-ios-arrow-forward'       => 'Ion Ios Forward',
					'ion-ios-arrow-thin-right'    => 'Thin Right',
					'ion-navicon'                 => 'Ion Navicon',
					'ion-navicon-round'           => 'Navicon Round',
					'ion-android-menu'            => 'Menu',
					'ion-ios-search'              => 'Search',
					'ion-ios-search-strong'       => 'Search Strong',
				),
				$icons
			);
			// Then we set a new list of icons as the options of the icon control
			$controls_registry->get_control( 'icon' )->set_settings( 'options', $new_icons );
		}

		// ADD ICON STYLE IN EDITOR ELEMENTOR MODE
		public function enqueue_icon() {
			wp_enqueue_style(
				'ionicons',
				BOOSTIFY_HEADER_FOOTER_URL . '/assets/css/ionicons.css',
				array(),
				BOOSTIFY_HEADER_FOOTER_VER
			);
		}


		public function style() {
			// Menu
			wp_enqueue_style(
				'boostify-hf-nav-menu-css',
				BOOSTIFY_HEADER_FOOTER_URL . 'assets/css/elementor/nav-menu.css',
				array(),
				BOOSTIFY_HEADER_FOOTER_VER
			);

			// Search
			wp_enqueue_style(
				'boostify-hf-search',
				BOOSTIFY_HEADER_FOOTER_URL . 'assets/css/elementor/search.css',
				array(),
				BOOSTIFY_HEADER_FOOTER_VER
			);
		}
	}

	Boostify_Header_Footer_Builder::instance();
}
