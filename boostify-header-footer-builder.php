<?php
/**
 * Plugin Name: Boostify Header Footer Builder
 * Plugin URI: https://boostifythemes.com
 * Description: Create Header and Footer for your site using Elementor Page Builder.
 * Version: 1.0.0
 * Author: Woostify
 * Author URI: https://woostify.com
 */

define( 'HT_HF_PATH', plugin_dir_path( __FILE__ ) );
define( 'HT_HF_URL', plugin_dir_url( __FILE__ ) );
define( 'HT_HF_VER', '1.0' );

require_once HT_HF_PATH . 'inc/class-boostify-header-footer-builder.php';