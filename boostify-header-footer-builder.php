<?php
/**
 * Plugin Name: Boostify Header Footer Builder
 * Plugin URI: https://boostifythemes.com
 * Description: Create Header and Footer for your site using Elementor Page Builder.
 * Version: 1.3.8
 * Author: Woostify
 * Author URI: https://woostify.com
 * Text Domain: boostify
 *
 * @package Boostify_Header_Footer_Template
 */

define( 'BOOSTIFY_HEADER_FOOTER_PATH', plugin_dir_path( __FILE__ ) );
define( 'BOOSTIFY_HEADER_FOOTER_URL', plugin_dir_url( __FILE__ ) );
define( 'BOOSTIFY_HEADER_FOOTER_VER', '1.3.8' );


/**
 * Initialize the plugin tracker
 *
 * @return void
 */
function appsero_init_tracker_boostify_header_footer_builder() {

	if ( ! class_exists( 'Appsero\Client' ) ) {
		require_once __DIR__ . '/vendor/appsero/client/src/Client.php';
	}

	$client = new Appsero\Client( '745cf48e-22cd-49a0-abb5-3f225d08b708', 'Boostify Header Footer Builder for Elementor', __FILE__ );

	// Active insights.
	$client->insights()->init();

}

appsero_init_tracker_boostify_header_footer_builder();

require_once BOOSTIFY_HEADER_FOOTER_PATH . 'inc/class-boostify-header-footer-builder.php';
