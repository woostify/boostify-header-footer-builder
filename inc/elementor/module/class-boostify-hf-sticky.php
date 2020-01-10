<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Core\Base\Module;
require HT_HF_PATH . 'inc/elementor/module/base/class-boostify-module-base.php';
/**
 * wanderlust Hello World
 *
 * Elementor widget for hello world.
 */
class Boostify_Hf_Sticky extends Module_Base {

	public function __construct() {
		parent::__construct();

		$this->add_actions();
	}

	public function get_name() {
		return 'sticky';
	}

	public function register_controls( Controls_Stack $element ) {
		$element->start_controls_section(
			'section_bhf_sticky_header',
			[
				'label' => __( 'Sticky Header', 'boostify' ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			]
		);

		$element->add_control(
			'bsticky',
			[
				'label'              => __( 'Enable', 'boostify' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_on'           => __( 'On', 'boostify' ),
				'label_off'          => __( 'Off', 'boostify' ),
				'return_value'       => 'yes',
				'default'            => '',
				'frontend_available' => true,
				'prefix_class'       => 'boostify-sticky-',
			]
		);

		$element->add_control(
			'bstick_on',
			[
				'label'              => __( 'Enable On', 'boostify' ),
				'type'               => Controls_Manager::SELECT2,
				'multiple'           => true,
				'label_block'        => 'true',
				'default'            => [ 'desktop', 'tablet', 'mobile' ],
				'options'            => [
					'desktop' => __( 'Desktop', 'boostify' ),
					'tablet'  => __( 'Tablet', 'boostify' ),
					'mobile'  => __( 'Mobile', 'boostify' ),
				],
				'condition'          => [
					'bsticky!' => '',
				],
				'render_type'        => 'none',
				'frontend_available' => true,
			]
		);

		$element->add_responsive_control(
			'bsticky_distance',
			[
				'label'              => __( 'Scroll Distance (px)', 'boostify' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => [ 'px' ],
				'description'        => __( 'Choose the scroll distance to enable Sticky Header Effects', 'boostify' ),
				'frontend_available' => true,
				'default'            => [
					'size' => 60,
				],
				'range'              => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'condition'          => [
					'bsticky!' => '',
				],
			]
		);

		$element->add_control(
			'bsticky_background_show',
			[
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
				'condition'          => [
					'bsticky!' => '',
				],

			]
		);

		$element->add_control(
			'bsticky_background',
			[
				'label'              => __( 'Color', 'boostify' ),
				'type'               => Controls_Manager::COLOR,
				'render_type'        => 'none',
				'frontend_available' => true,
				'condition'          => [
					'bsticky_background_show' => 'yes',
				],
			]
		);

		$element->add_control(
			'bsticky_border',
			[
				'label'              => __( 'Bottom Border', 'boostify' ),
				'type'               => Controls_Manager::SWITCHER,
				'separator'          => 'before',
				'label_on'           => __( 'On', 'boostify' ),
				'label_off'          => __( 'Off', 'boostify' ),
				'return_value'       => 'yes',
				'default'            => '',
				'description'        => __( 'Choose bottom border size and color', 'boostify' ),
				'frontend_available' => true,
				'prefix_class'       => 'boostify-sticky-border-',
				'condition'          => [
					'bsticky!' => '',
				],
			]
		);

		$element->add_control(
			'bsticky_border_color',
			[
				'label'              => __( 'Color', 'boostify' ),
				'type'               => Controls_Manager::COLOR,
				'render_type'        => 'none',
				'frontend_available' => true,
				'condition'          => [
					'bsticky_border' => 'yes',
					'bsticky!'       => '',
				],
			]
		);

		$element->add_responsive_control(
			'bsticky_border_width',
			[
				'label'              => __( 'Width (px)', 'boostify' ),
				'type'               => Controls_Manager::SLIDER,
				'frontend_available' => true,
				'size_units'         => [ 'px' ],
				'default'            => [
					'size' => 0,
				],
				'range'              => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'condition'          => [
					'bsticky_border' => 'yes',
					'bsticky!'       => '',
				],
			]
		);

		$element->add_responsive_control(
			'bsticky_height_header',
			[
				'label'              => __( 'Height (px)', 'boostify' ),
				'type'               => Controls_Manager::SLIDER,
				'frontend_available' => true,
				'size_units'         => [ 'px' ],
				'default'            => [
					'size' => 70,
				],
				'range'              => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'condition'          => [
					'bsticky!' => '',
				],
			]
		);

		$element->end_controls_section();
	}

	private function add_actions() {

		add_action( 'elementor/element/section/section_advanced/after_section_end', [ $this, 'register_controls' ] );

		add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'enqueue_styles' ] );
		if ( ! Elementor\Plugin::instance()->editor->is_edit_mode() ) {
			add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		}
	}

	public function enqueue_styles() {
		$suffix = boostify_hf_suffix();

		wp_enqueue_style(
			'boostify-sticky-style',
			HT_HF_URL . 'assets/css/elementor/sticky.css',
			[],
			HT_HF_VER
		);
	}

	public function enqueue_scripts() {
		$suffix = boostify_hf_suffix();

		wp_enqueue_script(
			'boostify-hf-sticky',
			HT_HF_URL . 'assets/js/sticky' . $suffix . '.js',
			[ 'jquery' ],
			HT_HF_VER,
			false
		);
	}


}
