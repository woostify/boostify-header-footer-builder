<?php
/**
 * Module Sticky
 *
 * @package Boostify_Header_Footer\Module\Sticky;
 *
 * Written by ptp
 */

namespace Boostify_Header_Footer\Module;

use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Plugin;
use Elementor\Controls_Stack;
use Elementor\Core\Base\Module;


/**
 * Sticky Module
 *
 * Elementor Module Sticky
.*
 * Author: ptp
 */
class Sticky {

	/**
	 * Instance Class
	 *
	 * @var instance
	 */
	private static $instance = null;

	/**
	 * Class Module Sticky Instance
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Boostify Header Footer Builder Module Sticky Constructor.
	 */
	public function __construct() {

		$this->add_actions();
	}

	/**
	 * Module name.
	 */
	public function get_name() {
		return 'hf-sticky';
	}

	/**
	 * Register Module Controls.
	 *
	 * @param object Element_Base $element | Element_Base class.
	 */
	public function register_controls( Element_Base $element ) {
		$element->start_controls_section(
			'section_bhf_sticky_header',
			array(
				'label' => __( 'Sticky Header', 'boostify' ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			)
		);

		$element->add_control(
			'bsticky',
			array(
				'label'              => __( 'Enable', 'boostify' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_on'           => __( 'On', 'boostify' ),
				'label_off'          => __( 'Off', 'boostify' ),
				'return_value'       => 'yes',
				'default'            => '',
				'frontend_available' => true,
				'prefix_class'       => 'boostify-sticky-',
			)
		);

		$element->add_control(
			'bsticky_tranparent',
			array(
				'label'              => __( 'Header Tranparent', 'boostify' ),
				'type'               => Controls_Manager::SWITCHER,
				'separator'          => 'before',
				'label_on'           => __( 'On', 'boostify' ),
				'label_off'          => __( 'Off', 'boostify' ),
				'return_value'       => 'yes',
				'default'            => '',
				'frontend_available' => true,
				'prefix_class'       => 'boostify-header-tranparent-',
			)
		);

		$element->add_control(
			'bstick_on',
			array(
				'label'              => __( 'Enable On', 'boostify' ),
				'type'               => Controls_Manager::SELECT2,
				'multiple'           => true,
				'label_block'        => 'true',
				'default'            => array( 'desktop', 'tablet', 'mobile' ),
				'options'            => array(
					'desktop' => __( 'Desktop', 'boostify' ),
					'tablet'  => __( 'Tablet', 'boostify' ),
					'mobile'  => __( 'Mobile', 'boostify' ),
				),
				'condition'          => array(
					'bsticky!' => '',
				),
				'render_type'        => 'none',
				'frontend_available' => true,
			)
		);

		$element->add_responsive_control(
			'bsticky_distance',
			array(
				'label'              => __( 'Scroll Distance (px)', 'boostify' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => array( 'px' ),
				'description'        => __( 'Choose the scroll distance to enable Sticky Header Effects', 'boostify' ),
				'frontend_available' => true,
				'default'            => array(
					'size' => 0,
				),
				'range'              => array(
					'px' => array(
						'min' => 0,
						'max' => 500,
					),
				),
				'condition'          => array(
					'bsticky!' => '',
				),
			)
		);

		$element->add_control(
			'bsticky_background_show',
			array(
				'label'              => __( 'Header Background', 'boostify' ),
				'type'               => Controls_Manager::SWITCHER,
				'separator'          => 'before',
				'label_on'           => __( 'On', 'boostify' ),
				'label_off'          => __( 'Off', 'boostify' ),
				'return_value'       => 'yes',
				'default'            => '',
				'frontend_available' => true,
				'prefix_class'       => 'boostify-sticky-background-',
				'description'        => __( 'Choose background color after scrolling', 'boostify' ),
				'condition'          => array(
					'bsticky!' => '',
				),

			)
		);

		$element->add_control(
			'bsticky_background',
			array(
				'label'              => __( 'Color', 'boostify' ),
				'type'               => Controls_Manager::COLOR,
				'render_type'        => 'none',
				'frontend_available' => true,
				'condition'          => array(
					'bsticky_background_show' => 'yes',
					'bsticky!'                => '',
				),
			)
		);

		$element->add_control(
			'image',
			array(
				'label'              => __( 'Logo Sticky', 'boostify' ),
				'type'               => \Elementor\Controls_Manager::MEDIA,
				'frontend_available' => true,
				'description'        => __( 'Choose Logo after scrolling', 'boostify' ),
				'condition'          => array(
					'bsticky!' => '',
				),
			)
		);

		$element->add_control(
			'bsticky_menu_color_custom',
			array(
				'label'              => __( 'Menu Color', 'boostify' ),
				'type'               => Controls_Manager::SWITCHER,
				'separator'          => 'before',
				'label_on'           => __( 'On', 'boostify' ),
				'label_off'          => __( 'Off', 'boostify' ),
				'return_value'       => 'yes',
				'default'            => '',
				'frontend_available' => true,
				'description'        => __( 'Choose menu color after scrolling', 'boostify' ),
				'condition'          => array(
					'bsticky!' => '',
				),

			)
		);

		$element->add_control(
			'bsticky_menu_color',
			array(
				'label'              => __( 'Menu Color', 'boostify' ),
				'type'               => Controls_Manager::COLOR,
				'render_type'        => 'none',
				'frontend_available' => true,
				'condition'          => array(
					'bsticky_menu_color_custom' => 'yes',
					'bsticky!'                  => '',
				),
			)
		);

		$element->end_controls_section();
	}

	/**
	 * Register Hooks.
	 */
	private function add_actions() {
		add_action( 'elementor/element/section/section_advanced/after_section_end', array( $this, 'register_controls' ) );
		add_action( 'elementor/element/common/section_advanced/after_section_start', array( $this, 'register_controls' ) );
		add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Register Style.
	 */
	public function enqueue_styles() {
		$suffix = boostify_header_footer_suffix();

		wp_enqueue_style(
			'boostify-sticky-style',
			BOOSTIFY_HEADER_FOOTER_URL . 'assets/css/elementor/sticky.css',
			array(),
			BOOSTIFY_HEADER_FOOTER_VER
		);
	}

	/**
	 * Register script.
	 */
	public function enqueue_scripts() {
		$suffix = boostify_header_footer_suffix();

		wp_enqueue_script(
			'boostify-hf-sticky',
			BOOSTIFY_HEADER_FOOTER_URL . 'assets/js/sticky' . $suffix . '.js',
			array( 'jquery' ),
			BOOSTIFY_HEADER_FOOTER_VER,
			false
		);

	}

}
