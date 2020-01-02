<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

/**
 * wanderlust Hello World
 *
 * Elementor widget for hello world.
 */
class Boostify_Site_Logo extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'ht-hf-site-logo';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Logo', 'ht-wanderlust' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-site-logo';
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

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Destination', 'ht-wanderlust' ),
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'   => esc_html__( 'Layout', 'ht-wanderlust' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'default' => esc_html__( 'Default', 'ht-wanderlust' ),
					'simple'  => esc_html__( 'Simple', 'ht-wanderlust' ),
					'classic' => esc_html__( 'Classic', 'ht-wanderlust' ),
				],
				'default' => 'default',
			)
		);

		$this->add_control(
			'label_position',
			array(
				'label'     => esc_html__( 'Title Positon', 'ht-wanderlust' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'flex-end',
				'selectors' => [
					'{{WRAPPER}} .ewt-destination-info' => 'align-items: {{VALUE}};',
				],
				'options'   => [
					'flex-start' => [
						'icon'  => 'eicon-v-align-top',
						'title' => 'Top',
					],
					'center'     => [
						'icon'  => 'eicon-v-align-middle',
						'title' => 'Center',
					],
					'flex-end'   => [
						'icon'  => 'eicon-v-align-bottom',
						'title' => 'Bottom',
					],
				],

			)
		);


		$this->add_control(
			'image',
			array(
				'label'       => esc_html__( 'Image', 'ht-wanderlust' ),
				'type'        => Controls_Manager::MEDIA,
				'description' => esc_html__( 'If you do not select a photo, it will be taken destination thumbnail', 'ht-wanderlust' ),
				'default'     => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Image_Size::get_type(),
			[
				'name'        => 'thumbnail',
				'default'     => 'full',
				'label'       => esc_html__( 'Image Size', 'ht-wanderlust' ),
				'description' => esc_html__( 'Custom image size when selected image.', 'ht-wanderlust' ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style',
			[
				'label' => esc_html__( 'Style', 'ht-wanderlust' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Title Color', 'ht-wanderlust' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => [
					'{{WRAPPER}} .ewt-destination-title' => 'color: {{VALUE}};',
				],

			]
		);

		$this->add_group_control(
			Elementor\Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Title Typography', 'ht-wanderlust' ),
				'name'     => 'title_typo',
				'scheme'   => Elementor\Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .ewt-destination-title',
			]
		);

		$this->add_control(
			'count_color',
			[
				'label'     => esc_html__( 'Text Color', 'ht-wanderlust' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .text-count' => 'color: {{VALUE}};',
				],

			]
		);

		$this->add_group_control(
			Elementor\Group_Control_Typography::get_type(),
			[
				'label'    => esc_html__( 'Text Typography', 'ht-wanderlust' ),
				'name'     => 'text_typo',
				'scheme'   => Elementor\Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .text-count',
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
		$settings = $this->get_settings_for_display();

	}



}
