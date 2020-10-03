<?php
/**
 * Entry point for the plugin. Checks if Elementor is installed and activated and loads it's own files and actions.
 *
 * @package Boostify Header Footer Builder
 */

namespace Boostify_Header_Footer;

/**
 * Class Boostify_Hf_Template_Render
 */
class Template_Render {

	/**
	 * Current theme template
	 *
	 * @var String
	 */
	public $template;

	/**
	 * Instance of Elemenntor Frontend class.
	 *
	 * @var \Elementor\Frontend()
	 */
	private static $elementor_instance;
	/**
	 * Constructor
	 */
	public function __construct() {

		if ( defined( 'ELEMENTOR_VERSION' ) && is_callable( 'Elementor\Plugin::instance' ) ) {

			self::$elementor_instance = \Elementor\Plugin::instance();

			// Scripts and styles.
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_shortcode( 'bhf', array( $this, 'render_template' ) );
		}
	}


	/**
	 * Prints the admin notics when Elementor is not installed or activated.
	 */
	protected function elementor_not_available() {

		if ( file_exists( WP_PLUGIN_DIR . '/elementor/elementor.php' ) ) {
			$url = network_admin_url() . 'plugins.php?s=elementor';
		} else {
			$url = network_admin_url() . 'plugin-install.php?s=elementor';
		}
	}

	/**
	 * Enqueue styles and scripts.
	 */
	public function enqueue_scripts() {

		if ( class_exists( '\Elementor\Plugin' ) ) {
			$elementor = \Elementor\Plugin::instance();
			$elementor->frontend->enqueue_styles();
		}

		if ( class_exists( '\ElementorPro\Plugin' ) ) {
			$elementor_pro = \ElementorPro\Plugin::instance();
			$elementor_pro->enqueue_styles();
		}
		$header_id = boostify_header_template_id();
		$footer_id = boostify_footer_template_id();

		if ( self::get_header_template() ) {
			if ( class_exists( '\Elementor\Plugin' ) ) {
				$elementor = \Elementor\Plugin::instance();
				$elementor->frontend->enqueue_styles();
				if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
					$css_file = new \Elementor\Core\Files\CSS\Post( $header_id );
				} elseif ( class_exists( '\Elementor\Post_CSS_File' ) ) {
					$css_file = new \Elementor\Post_CSS_File( $header_id );
				}
				$css_file->enqueue();
			}
		}
		if ( self::get_footer_template() ) {
			if ( class_exists( '\Elementor\Plugin' ) ) {
				$elementor = \Elementor\Plugin::instance();
				$elementor->frontend->enqueue_styles();
				if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
					$css_file = new \Elementor\Core\Files\CSS\Post( $footer_id );
				} elseif ( class_exists( '\Elementor\Post_CSS_File' ) ) {
					$css_file = new \Elementor\Post_CSS_File( $footer_id );
				}
				$css_file->enqueue();
			}
		}
	}


	/**
	 * Callback to shortcode.
	 *
	 * @param array $atts attributes for shortcode.
	 */
	public function render_template( $atts ) {

		$atts = shortcode_atts(
			array(
				'id'   => '',
				'type' => '',
			),
			$atts,
			'bhf'
		);

		$id   = ! empty( $atts['id'] ) ? intval( $atts['id'] ) : '';
		$type = ! empty( $atts['type'] ) ? $atts['type'] : '';

		if ( empty( $id ) ) {
			return '';
		}

		if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
			$css_file = new \Elementor\Core\Files\CSS\Post( $id );
		} elseif ( class_exists( '\Elementor\Post_CSS_File' ) ) {
			$css_file = new \Elementor\Post_CSS_File( $id );
		}
		$css_file->enqueue();

		return self::$elementor_instance->frontend->get_builder_content_for_display( $id );
	}


	/**
	 * Header Template.
	 *
	 * @return Header Template.
	 */
	public static function get_header_template() {
		$id = boostify_header_template_id();

		return self::$elementor_instance->frontend->get_builder_content_for_display( $id );
	}

	/**
	 * Footer Template.
	 *
	 * @return Footer Template.
	 */
	public static function get_footer_template() {
		$id = boostify_footer_template_id();
		return self::$elementor_instance->frontend->get_builder_content_for_display( $id );
	}
}
