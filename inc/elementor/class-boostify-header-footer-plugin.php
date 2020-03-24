<?php

use Elementor\Widget_Base;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Main class plugin
 */
class Boostify_Header_Footer_Plugin {

	/**
	 * @var Plugin
	 */
	private static $instance;

	/**
	 * @var Manager
	 */
	private $modules_manager;

	/**
	 * @return Plugin
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function includes() {
		require BOOSTIFY_HEADER_FOOTER_PATH . 'inc/elementor/module/class-boostify-hf-sticky.php';
	}


	public function hf_init() {

		$this->modules_manager = Boostify_Header_Footer_Sticky::instance();

		$elementor = Elementor\Plugin::$instance;

		// Add element category in panel
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

	private function setup_hooks() {
		add_action( 'elementor/init', array( $this, 'hf_init' ) );
	}

	/**
	 * Plugin constructor.
	 */
	private function __construct() {
		$this->includes();

		$this->setup_hooks();
	}
}

Boostify_Header_Footer_Plugin::instance();

