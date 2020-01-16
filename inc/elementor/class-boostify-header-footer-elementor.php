<?php
/**
 * Class Header Footer Elementor
 *
 * Main Plugin class
 * @since 1.2.0
 */

if ( ! class_exists( 'Boostify_Header_Footer_Elementor' ) ) {

	class Boostify_Header_Footer_Elementor {
		/**
		 * Instance
		 *
		 * @since 1.2.0
		 * @access private
		 * @static
		 *
		 * @var Plugin The single instance of the class.
		 */
		private static $_instance = null;


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
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Register custom widget categories.
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
		 * widget_scripts
		 *
		 * Load required plugin core files.
		 *
		 * @since 1.2.0
		 * @access public
		 */
		public function widget_scripts() {
			wp_register_script(
				'boostify_hf_nav_menu',
				HT_HF_URL . 'assets/js/nav-menu' . boostify_hf_suffix() . '.js',
				array( 'jquery' ),
				HT_HF_VER,
				true
			);

			// Search
			wp_register_script(
				'boostify_hf_search',
				HT_HF_URL . 'assets/js/search' . boostify_hf_suffix() . '.js',
				array( 'jquery' ),
				HT_HF_VER,
				true
			);
		}
		/**
		 * Include Widgets files
		 *
		 * Load widgets files
		 *
		 * @since 1.2.0
		 * @access private
		 */
		private function include_widgets_files() {

			$widgets = glob( HT_HF_PATH . 'inc/elementor/widgets/*.php' );

			foreach ( $widgets as $key ) {
				if ( file_exists( $key ) ) {
					require_once $key;
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
		public function register_widgets() {
			// Its is now safe to include Widgets files
			$this->include_widgets_files();

			$widgets = glob( HT_HF_PATH . 'inc/elementor/widgets/*.php' );
			// Register Widgets
			foreach ( $widgets as $key ) {
				if ( file_exists( $key ) ) {
					$paths      = pathinfo( $key );
					$prefix     = str_replace( '-', ' ', $paths['filename'] );
					$prefix     = ucwords( $prefix );
					$class_name = str_replace( ' ', '_', $prefix );
					$class_name = str_replace( 'Class_', '', $class_name );
					\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new $class_name() );
				}
			}
		}

		private function includes() {
			require HT_HF_PATH . 'inc/elementor/module/class-boostify-hf-sticky.php';
		}

		public function init() {

			$this->modules_manager = Boostify_Hf_Sticky::instance();

			$elementor = Elementor\Plugin::$instance;

			// Add element category in panel
			$elementor->elements_manager->add_category(
				'boostify-sticky',
				[
					'title' => __( 'Header Sticky', 'boostify' ),
					'icon'  => 'font',
				],
				1
			);

			do_action( 'elementor_controls/init' );
		}

		private function setup_hooks() {
			// Register Modul.
			add_action( 'elementor/init', array( $this, 'init' ) );
			// Register custom widget categories.
			add_action( 'elementor/elements/categories_registered', array( $this, 'add_elementor_widget_categories' ) );
			// Register widget scripts
			add_action( 'elementor/frontend/after_register_scripts', array( $this, 'widget_scripts' ) );
			// Register widgets
			add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_widgets' ) );
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
	// Instantiate Boostify_Header_Footer_Elementor Class
	Boostify_Header_Footer_Elementor::instance();
}
