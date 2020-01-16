<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

/**
 * Site Search Widget
 *
 * Elementor widget for Site Search.
 */
class Boostify_Site_Search extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'ht-hf-site-search';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Site Search', 'boostify' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-search';
	}

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


	public function get_script_depends() {
		return array( 'boostify_hf_search' );
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Search', 'boostify' ),
			)
		);

		$this->add_control(
			'layout',
			[
				'label'   => esc_html__( 'Layout', 'boostify' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'icon' => 'Icon',
					'form' => 'Form',
				],
				'default' => 'icon',
			]
		);

		$this->add_control(
			'button_type',
			[
				'label'   => esc_html__( 'Button Type', 'boostify' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'icon' => 'Icon',
					'text' => 'Text',
				],
				'default' => 'icon',
			]
		);

		$this->add_control(
			'icon',
			[
				'label'     => esc_html__( 'Select Icon', 'boostify' ),
				'type'      => Controls_Manager::ICON,
				'include'   => [
					'ion-ios-search',
					'ion-ios-search-strong',
					'fa fa-search',
					'ion-ios-arrow-thin-right',
				],
				'default'   => 'ion-ios-search',
				'condition' => [
					'button_type' => 'icon',
				],

			]
		);

		$this->add_control(
			'text',
			[
				'label'       => __( 'Label', 'boostify' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter Label', 'boostify' ),
				'condition'   => [
					'button_type' => 'text',
				],
			]
		);

		$this->add_control(
			'placeholder',
			[
				'label'       => __( 'Placeholder', 'boostify' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter Placeholder', 'boostify' ),
			]
		);

		$this->add_control(
			'align',
			[
				'label'     => esc_html__( 'Align', 'boostify' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'left',
				'selectors' => [
					'{{WRAPPER}} .boostify-site-search-toggle' => 'text-align: {{VALUE}};',
				],
				'options'   => [
					'left'   => [
						'icon'  => 'eicon-h-align-left',
						'title' => 'Left',
					],
					'center' => [
						'icon'  => 'eicon-h-align-center',
						'title' => 'Center',
					],
					'right'  => [
						'icon'  => 'eicon-h-align-right',
						'title' => 'Right',
					],
				],
				'condition' => [
					'layout' => 'icon',
				],
			]
		);

		$this->add_control(
			'height',
			[
				'label'      => __( 'Height', 'boostify' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 30,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 45,
				],
				'selectors'  => [
					'{{WRAPPER}} .boostify-search-form-header .site-search-field' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'layout' => 'form',
				],
			]
		);

		$this->add_control(
			'padding',
			[
				'label'      => __( 'Padding', 'boostify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .boostify-search-icon--toggle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'layout' => 'icon',
				],
			]
		);

		$this->add_control(
			'padding_form',
			[
				'label'              => __( 'Padding', 'boostify' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => [ 'px' ],
				'allowed_dimensions' => [ 'right', 'left' ],
				'default'            => [
					'top'    => 0,
					'bottom' => 0,
					'left'   => 10,
					'right'  => 10,
				],
				'selectors'          => [
					'{{WRAPPER}} .boostify-search-form-header .btn-boostify-search-form' => 'padding: 0{{UNIT}} {{RIGHT}}{{UNIT}} 0{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'          => [
					'layout' => 'form',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			array(
				'label' => esc_html__( 'Style', 'boostify' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'heading_icon',
			[
				'label'     => __( 'Icon Layout', 'boostify' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'     => __( 'Icon Color', 'boostify' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'scheme'    => [
					'type'  => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .boostify-search-icon--toggle' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'content_typography',
				'label'    => __( 'Typography', 'boostify' ),
				'scheme'   => Elementor\Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .boostify-search-icon--toggle',
			]
		);

		$this->add_group_control(
			Elementor\Group_Control_Background::get_type(),
			[
				'name'     => 'background',
				'label'    => __( 'Background', 'boostify' ),
				'types'    => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .boostify-search-icon--toggle, {{WRAPPER}} .btn-boostify-search-form',
			]
		);

		$this->add_group_control(
			Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'border',
				'label'    => __( 'Border', 'boostify' ),
				'selector' => '{{WRAPPER}} .boostify-search-icon--toggle',
			]
		);

		$this->add_control(
			'bdrs',
			[
				'label'      => __( 'Border Radius', 'boostify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .boostify-search-icon--toggle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 */
	protected function render() {
		$settings    = $this->get_settings_for_display();
		$icon        = $settings['icon'];
		$text        = $settings['text'];
		$placeholder = $settings['placeholder'];
		if ( empty( $text ) ) {
			$text = null;
		}

		if ( 'icon' == $settings['layout'] ) {
			?>
			<div class="boostify-site-search-toggle">
				<button class="boostify-search-icon--toggle <?php echo esc_attr( $settings['icon'] ); ?>" aria-expanded="false">
					<span class="screen-reader-text"><?php echo esc_html__( 'Enter Keyword', 'boostify' ); ?></span>
				</button>
			</div>

			<div class="boostify-search--toggle">
				<div class="boostify-search-toggle--wrapper">
					<?php do_action( 'boostify_hf_seach_form', $icon, $placeholder, $text ); ?>
				</div>
				<button class="boostify--site-search-close ion-android-close">
					<span class="screen-reader-text"><?php echo esc_html__( 'Close', 'boostify' ); ?></span>
				</button>
			</div>
			<?php
		} else {
			?>
			<div class="boostify-search-form-header">
				<div class="boostify-search-form--wrapper">
					<?php do_action( 'boostify_hf_seach_form', $icon, $placeholder, $text ); ?>
				</div>
			</div>
			<?php
		}
	}
}
