<?php
/**
 * Plugin Name: Boostify Header Footer Builder
 * Plugin URI: https://boostifythemes.com
 * Description: Create Header and Footer for your site using Elementor Page Builder.
 * Version: 1.0.3
 * Author: Woostify
 * Author URI: https://woostify.com
 */

define( 'BOOSTIFY_HEADER_FOOTER_PATH', plugin_dir_path( __FILE__ ) );
define( 'BOOSTIFY_HEADER_FOOTER_URL', plugin_dir_url( __FILE__ ) );
define( 'BOOSTIFY_HEADER_FOOTER_VER', '1.0.3' );

require_once BOOSTIFY_HEADER_FOOTER_PATH . 'inc/class-boostify-header-footer-builder.php';