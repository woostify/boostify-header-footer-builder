<?php


use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Main class plugin
 */
class Boostify_Hf_Plugin {

	/**
	 * @var Plugin
	 */
	private static $_instance;

	/**
	 * @var Manager
	 */
	private $modules_manager;

	/**
	 * @return Plugin
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	private function includes() {
		require HT_HF_PATH . 'inc/elementor/module/class-boostify-hf-sticky.php';
	}


	public function hf_init() {

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

Boostify_Hf_Plugin::instance();

