<?php
/**
 * Elementor
 *
 * Elementor Class.
 *
 * @package Boostify_Header_Footer
 * Author: ptp
 */

namespace Boostify_Header_Footer;

/**
 * Elementor Class.
 */
class Elementor {
	/**
	 * Instance
	 *
	 * @since 1.2.0
	 * @access private
	 * @static
	 *
	 * @var Plugin The single instance of the class.
	 */
	private static $instance = null;

	/**
	 * Elementor Modules Manager
	 *
	 * @since 1.2.0
	 * @access private
	 * @static
	 *
	 * @var Plugin The single instance of the class.
	 */
	private $modules_manager;
	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return Plugin An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Register custom widget categories.
	 *
	 * @param object $elements_manager | Elementer elements manager.
	 */
	public function add_elementor_widget_categories( $elements_manager ) {
		$elements_manager->add_category(
			'ht_hf_builder',
			array(
				'title' => esc_html__( 'Boostify Header Footer', 'boostify' ),
			)
		);
	}

	/**
	 * Widget Class
	 */
	public function get_widgets() {
		$widgets = array(
			'Site_Logo',
			'Site_Search',
			'Navigation',
			'Mega_Menu',
			'Mega_Menu_Vertical',
			'Copyright',
			'Image_Retina',
		);

		if ( class_exists( 'Woocommerce' ) ) {
			array_push( $widgets, 'Cart_Icon' );
		}

		return $widgets;
	}

	/**
	 * Widget scripts
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function widget_scripts() {
		wp_register_script(
			'boostify_hf_nav_menu',
			BOOSTIFY_HEADER_FOOTER_URL . 'assets/js/nav-menu' . boostify_header_footer_suffix() . '.js',
			array( 'jquery' ),
			BOOSTIFY_HEADER_FOOTER_VER,
			true
		);

		// Search.
		wp_register_script(
			'boostify_hf_search',
			BOOSTIFY_HEADER_FOOTER_URL . 'assets/js/search' . boostify_header_footer_suffix() . '.js',
			array( 'jquery' ),
			BOOSTIFY_HEADER_FOOTER_VER,
			true
		);

		// Mega Menu.
		wp_register_script(
			'boostify_hf_nav_mega',
			BOOSTIFY_HEADER_FOOTER_URL . 'assets/js/mega-menu' . boostify_header_footer_suffix() . '.js',
			array( 'jquery' ),
			BOOSTIFY_HEADER_FOOTER_VER,
			true
		);

		// Mega Menu Vertical.
		wp_register_script(
			'boostify_hf_mega_menu_vertical',
			BOOSTIFY_HEADER_FOOTER_URL . 'assets/js/mega-menu-vertical' . boostify_header_footer_suffix() . '.js',
			array( 'jquery' ),
			BOOSTIFY_HEADER_FOOTER_VER,
			true
		);

		// Cart Icon.
		wp_register_script(
			'boostify_hf_cart_icon',
			BOOSTIFY_HEADER_FOOTER_URL . 'assets/js/cart-icon' . boostify_header_footer_suffix() . '.js',
			array( 'jquery' ),
			BOOSTIFY_HEADER_FOOTER_VER,
			true
		);

		$admin_vars = array(
			'url'   => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'boostify_nonce' ),
		);

		wp_localize_script(
			'boostify_hf_cart_icon',
			'admin',
			$admin_vars
		);
	}
	/**
	 * Include Widgets files
	 *
	 * Load widgets files
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function autoload_widgets() {
		$widgets = $this->get_widgets();
		foreach ( $widgets as $widget ) {
			$filename = strtolower( $widget );
			$filename = str_replace( '_', '-', $filename );
			$filename = BOOSTIFY_HEADER_FOOTER_PATH . 'inc/elementor/widgets/class-' . $filename . '.php';

			if ( is_readable( $filename ) ) {
				include $filename;
			}
		}
	}

	/**
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function init_widgets() {
		$this->autoload_widgets();
		// Its is now safe to include Widgets files.
		$widget_manager = \Elementor\Plugin::instance()->widgets_manager;
		foreach ( $this->get_widgets() as $widget ) {
			$class_name = 'Boostify_Header_Footer\Widgets\\' . $widget;
			$widget_manager->register( new $class_name() );
		}
	}

	/**
	 * Register Sticky Module
	 *
	 * Register new Elementor widgets.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function init() {

		$this->modules_manager = \Boostify_Header_Footer\Module\Sticky::instance();

		$elementor = \Elementor\Plugin::$instance;

		// Add element category in panel.
		$elementor->elements_manager->add_category(
			'boostify-sticky',
			array(
				'title' => __( 'Header Sticky', 'boostify' ),
				'icon'  => 'font',
			),
			1
		);

		do_action( 'elementor_controls/init' ); // phpcs:ignore
	}

	/**
	 * Add icon for elementor.
	 *
	 * @param (object) $controls_registry | controls_registry.
	 */
	public function modify_controls( $controls_registry ) {
		// Get existing icons.
		$icons = $controls_registry->get_control( 'icon' )->get_settings( 'options' );
		// Append new icons.
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
		// Then we set a new list of icons as the options of the icon control.
		$controls_registry->get_control( 'icon' )->set_settings( 'options', $new_icons );
	}

	/**
	 * Elementor hooks.
	 */
	private function setup_hooks() {
		// Register Module.
		add_action( 'elementor/init', array( $this, 'init' ) );
		add_action( 'elementor/init', array( $this, 'register_abstract' ) );
		// Register custom widget categories.
		add_action( 'elementor/elements/categories_registered', array( $this, 'add_elementor_widget_categories' ) );
		// Register widget scripts.
		add_action( 'elementor/frontend/after_register_scripts', array( $this, 'widget_scripts' ) );
		// Register widgets.
		add_action( 'elementor/widgets/register', array( $this, 'init_widgets' ) );
		add_action( 'elementor/init', array( $this, 'maybe_init_cart' ) );
		add_action( 'elementor/controls/controls_registered', array( $this, 'modify_controls' ), 10, 1 );
	}

	/**
	 * Include abstract.
	 */
	public function register_abstract() {
		require BOOSTIFY_HEADER_FOOTER_PATH . 'inc/elementor/abstract/class-base-widget.php';
		require BOOSTIFY_HEADER_FOOTER_PATH . 'inc/elementor/abstract/class-nav-menu.php';
		require BOOSTIFY_HEADER_FOOTER_PATH . 'inc/elementor/module/class-woocommerce.php';
	}

	/**
	 * Include file.
	 */
	public function includes() {
		require BOOSTIFY_HEADER_FOOTER_PATH . 'inc/elementor/module/class-sticky.php';
	}

	/**
	 * Woocommerce.
	 */
	public function maybe_init_cart() {
		if ( class_exists( 'Woocommerce' ) ) {
			new \Boostify_Header_Footer\Module\Woocommerce();
		}
	}

	/**
	 *  Plugin class constructor
	 *
	 * Register plugin action hooks and filters
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function __construct() {
		$this->includes();
		$this->setup_hooks();
	}
}
// Instantiate Elementor Class.
Elementor::instance();

