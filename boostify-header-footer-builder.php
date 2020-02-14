<?php
/**
 * Plugin Name: Boostify Header Footer Builder
 * Plugin URI: https://boostifythemes.com
 * Description: The most advanced frontend drag & drop page builder. Create high-end, pixel perfect websites at record speeds. Any theme, any page, any design.
 * Version: 1.0
 * Author: BoostifyTheme
 * Author URI: https://boostifythemes.com
 */

define( 'BOOSTIFY_HEADER_FOOTER_PATH', plugin_dir_path( __FILE__ ) );
define( 'BOOSTIFY_HEADER_FOOTER_URL', plugin_dir_url( __FILE__ ) );
define( 'BOOSTIFY_HEADER_FOOTER_VER', '1.0' );

require_once BOOSTIFY_HEADER_FOOTER_PATH . 'inc/class-boostify-header-footer-builder.php';