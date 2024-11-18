<?php
/**
 * Main Boostify Header Footer Builder
 *
 * @class Boostify_Header_Footer_Builder
 *
 * @package Boostify_Header_Footer_Template
 *
 * Written by ptp
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Boostify_Header_Footer_Builder' ) ) {

	/**
	 * Boostify Header Footer Builder Class.
	 */
	class Boostify_Header_Footer_Builder {

		/**
		 * Instance Class
		 *
		 * @var instance
		 */
		private static $instance;

		/**
		 * Class Boostify_Header_Footer_Builder Instance
		 */
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
			$this->cpt();
		}

		/**
		 * Include file.
		 */
		public function includes() {
			include_once BOOSTIFY_HEADER_FOOTER_PATH . 'inc/elementor/class-elementor.php';
			include_once BOOSTIFY_HEADER_FOOTER_PATH . 'inc/admin/class-admin.php';
			include_once BOOSTIFY_HEADER_FOOTER_PATH . 'inc/admin/class-metabox.php';
			include_once BOOSTIFY_HEADER_FOOTER_PATH . 'inc/class-template.php';
			include_once BOOSTIFY_HEADER_FOOTER_PATH . 'inc/elementor/class-template-render.php';
			include_once BOOSTIFY_HEADER_FOOTER_PATH . 'inc/menu/class-wp-sub-menu.php';
			include_once BOOSTIFY_HEADER_FOOTER_PATH . 'inc/helper.php';
		}

		/**
		 * Hook.
		 */
		public function hooks() {
			add_action( 'init', array( $this, 'post_types' ) );
			add_action( 'init', array( $this, 'translate' ) );
			add_action( 'plugins_loaded', array( $this, 'init' ) );
			add_action( 'body_class', array( $this, 'body_ver' ) );
			add_action( 'elementor/editor/wp_head', array( $this, 'enqueue_icon' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'style' ), 99 );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_icon' ), 99 );
			add_action( 'boostify_hf_seach_form', 'boostify_header_footer_search_form', 10, 3 );
			add_action( 'admin_notices', array( $this, 'notice_plugin' ) );
			add_shortcode( 'btf_year', array( $this, 'get_year' ) );
			add_shortcode( 'btf_site_tile', array( $this, 'get_site_name' ) );
		}

		/**
		 * Add post type support.
		 */
		public function cpt() {
			add_post_type_support( 'btf_builder', 'elementor' );
		}

		/**
		 * Add post type support.
		 *
		 * @param (string) $classes | add class version in body.
		 */
		public function body_ver( $classes ) {
			$classes[] = 'boostify-header-footer-' . BOOSTIFY_HEADER_FOOTER_VER;

			return $classes;
		}

		/**
		 * Register post type.
		 */
		public function post_types() {
			global $wp;

			$wp->add_query_var( 'newfeed' );
			add_rewrite_rule( '^newfeed/([^/]*)/?', 'index.php?newfeed=$matches[1]', 'top' );

			if ( ! get_option( 'plugin_permalinks_flushed' ) ) {

				flush_rewrite_rules( false );
				update_option( 'plugin_permalinks_flushed', 1 );

			}
			register_post_type(
				'btf_builder',
				array(
					'supports'     => array( 'title', 'editor', 'thumbnail', 'elementor', 'page-attributes' ),
					'hierarchical' => false,
					'rewrite'      => array(
						'slug'       => 'btf_builder',
						'with_front' => false,
						'feeds'      => true,
					),
					'has_archive'  => false,
					'public'       => true,
					'show_in_rest' => true,
					'labels'       => array(
						'name'          => esc_html__( 'Boostify Header Footer Builder', 'boostify' ),
						'add_new_item'  => esc_html__( 'Add New Header, Footer', 'boostify' ),
						'edit_item'     => esc_html__( 'Edit Header, Footer', 'boostify' ),
						'all_items'     => esc_html__( 'All Header, Footer', 'boostify' ),
						'singular_name' => esc_html__( 'Elementor Builder', 'boostify' ),
					),
					'menu_icon'    => 'dashicons-editor-kitchensink',
				)
			);
		}

		/**
		 * Add translate Plugin.
		 */
		public function translate() {
			load_plugin_textdomain(
				'boostify',
				false,
				BOOSTIFY_HEADER_FOOTER_PATH . 'languages/'
			);
		}

		/**
		 * Instant class requied.
		 */
		public function init() {
			new Boostify_Header_Footer\Metabox();
			new Boostify_Header_Footer\Template_Render();
			new Boostify_Header_Footer\WP_Sub_Menu();
		}

		/**
		 * Add ionicons.
		 */
		public function enqueue_icon() {
			wp_enqueue_style(
				'ionicons',
				BOOSTIFY_HEADER_FOOTER_URL . '/assets/css/ionicons.css',
				array(),
				BOOSTIFY_HEADER_FOOTER_VER
			);

			wp_enqueue_style(
				'fontawesome',
				BOOSTIFY_HEADER_FOOTER_URL . '/assets/css/awesome.css',
				array(),
				BOOSTIFY_HEADER_FOOTER_VER
			);
		}

		/**
		 * Register style.
		 */
		public function style() {

			// FontAweSome 5 Free.
			wp_enqueue_style(
				'fontawesome-5-free',
				BOOSTIFY_HEADER_FOOTER_URL . 'assets/css/fontawesome/fontawesome.css',
				array(),
				BOOSTIFY_HEADER_FOOTER_VER
			);
			// Menu.
			wp_enqueue_style(
				'boostify-hf-nav-menu-css',
				BOOSTIFY_HEADER_FOOTER_URL . 'assets/css/elementor/nav-menu.css',
				array(),
				BOOSTIFY_HEADER_FOOTER_VER
			);

			// Search.
			wp_enqueue_style(
				'boostify-hf-search',
				BOOSTIFY_HEADER_FOOTER_URL . 'assets/css/elementor/search.css',
				array(),
				BOOSTIFY_HEADER_FOOTER_VER
			);

			// Style.
			wp_enqueue_style(
				'boostify-hf-style',
				BOOSTIFY_HEADER_FOOTER_URL . 'assets/css/style.css',
				array(),
				BOOSTIFY_HEADER_FOOTER_VER
			);

			// Cart.
			wp_enqueue_style(
				'boostify-hf-cart-icon',
				BOOSTIFY_HEADER_FOOTER_URL . 'assets/css/elementor/cart-icon.css',
				array(),
				BOOSTIFY_HEADER_FOOTER_VER
			);
		}

		/**
		 * Notice when do not install or active Elementor.
		 */
		public function notice_plugin() {
			if ( ! defined( 'ELEMENTOR_VERSION' ) || ! is_callable( 'Elementor\Plugin::instance' ) ) {

				if ( file_exists( WP_PLUGIN_DIR . '/elementor/elementor.php' ) ) {
					$url = network_admin_url() . 'plugins.php?s=elementor';
				} else {
					$url = network_admin_url() . 'plugin-install.php?s=elementor';
				}

				echo '<div class="notice notice-error">';
				/* Translators: URL to install or activate Elementor plugin. */
				echo '<p>' . sprintf( __( 'The <strong>Header Footer Elementor</strong> plugin requires <strong><a href="%s">Elementor</strong></a> plugin installed & activated.', 'header-footer-elementor' ) . '</p>', $url );// phpcs:ignore
				echo '</div>';
			}
		}

		/**
		 * Get current year.
		 */
		public function get_year() {
			return esc_html( date( 'Y' ) );// phpcs:ignore
		}

		/**
		 * Get Copyright.
		 */
		public function get_site_name() {
			return '<a class="boostify-copyright-info" href="' . esc_url( home_url( '/' ) ) . '">' . esc_html( get_bloginfo( 'name' ) ) . '</a>';
		}

		/**
		 * Notice when do not theme Support.
		 */
		public function notice_theme_support() {
			if ( ! current_theme_supports( 'boostify-header-footer' ) ) {
				?>
				<div class="notice notice-error">
					<p><?php echo esc_html__( 'Your current theme is not supported Boostify Header Footer Plugin', 'boostify' ); ?></p>
				</div>
				<?php
			}
		}
	}

	Boostify_Header_Footer_Builder::instance();
}
