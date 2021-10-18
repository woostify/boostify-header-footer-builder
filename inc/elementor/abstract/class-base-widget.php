<?php
/**
 * Main Boostify Header Footer Builder
 *
 * @class Boostify_Header_Footer\Base_Widget
 *
 * @package Boostify_Header_Footer_Template
 *
 * Written by ptp
 */

namespace Boostify_Header_Footer;

use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Abstract class Base Widget.
 *
 * Used to determine where to display the widget in the editor.
 *
 * @return array Widget categories.
 */
abstract class Base_Widget extends Widget_Base {

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'ht_hf_builder' );
	}
}
