<?php

use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Plugin;
use Elementor\Controls_Stack;
use Elementor\Core\Base\Module;

/**
 * wanderlust Hello World
 *
 * Elementor widget for hello world.
 */
class Boostify_Hf_Sticky extends Module {

	public function __construct() {

		$this->add_actions();
	}

	public function get_name() {
		return 'hf-sticky';
	}

	public function register_controls( Element_Base $element ) {
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
			'bsticky_tranparent',
			[
				'label'              => __( 'Header Tranparent', 'boostify' ),
				'type'               => Controls_Manager::SWITCHER,
				'separator'          => 'before',
				'label_on'           => __( 'On', 'boostify' ),
				'label_off'          => __( 'Off', 'boostify' ),
				'return_value'       => 'yes',
				'default'            => '',
				'frontend_available' => true,
				'prefix_class'       => 'boostify-header-tranparent-',
				'description'        => __( 'Choose background color after scrolling', 'boostify' ),
				'condition'          => [
					'bsticky!' => '',
				],
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
					'size' => 0,
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
					'bsticky!'                => '',
				],
			]
		);

		$element->add_control(
			'image',
			[
				'label'              => __( 'Logo Sticky', 'boostify' ),
				'type'               => \Elementor\Controls_Manager::MEDIA,
				'frontend_available' => true,
				'description'        => __( 'Choose Logo after scrolling', 'boostify' ),
				'condition'          => [
					'bsticky!' => '',
				],
				'default'            => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$element->end_controls_section();
	}

	private function add_actions() {
		add_action( 'elementor/element/section/section_advanced/after_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/element/common/section_advanced/after_section_start', [ $this, 'register_controls' ] );
		add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'enqueue_styles' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
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
