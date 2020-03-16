<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

/**
 * Site Logo
 *
 * Elementor widget for Site Logo.
 */
class Boostify_Header_Footer_Image_Retina extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.2.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'ht-hf-retina';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.2.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Retina Image', 'boostify' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.2.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'boostify-icon-retina-image';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.2.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'ht_hf_builder' );
	}

	protected function _register_controls() { //phpcs:ignore
		$this->retina();
		$this->image_style();
		$this->caption_style();
	}

	/**
	 * Register Retina Logo General Controls.
	 *
	 * @since 1.2.0
	 * @access protected
	 */
	protected function retina() {
		$this->start_controls_section(
			'section_retina_image',
			array(
				'label' => __( 'Retina Image', 'boostify' ),
			)
		);
		$this->add_control(
			'image',
			array(
				'label'   => __( 'Choose Default Image', 'boostify' ),
				'type'    => Controls_Manager::MEDIA,
				'dynamic' => array(
					'active' => true,
				),
				'default' => array(
					'url' => Elementor\Utils::get_placeholder_image_src(),
				),
			)
		);
		$this->add_control(
			'retina',
			array(
				'label'   => __( 'Choose Retina Image', 'boostify' ),
				'type'    => Controls_Manager::MEDIA,
				'dynamic' => array(
					'active' => true,
				),
				'default' => array(
					'url' => Elementor\Utils::get_placeholder_image_src(),
				),
			)
		);
		$this->add_group_control(
			Elementor\Group_Control_Image_Size::get_type(),
			array(
				'name'    => 'retina_image',
				'label'   => __( 'Image Size', 'boostify' ),
				'default' => 'medium',
			)
		);
		$this->add_responsive_control(
			'align',
			array(
				'label'     => __( 'Alignment', 'boostify' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'boostify' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'boostify' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'boostify' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .boostify-retina-image-container, {{WRAPPER}} .boostify-caption-width' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'caption',
			array(
				'label'       => __( 'Custom Caption', 'boostify' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => __( 'Enter your image caption', 'boostify' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'link',
			array(
				'label'       => __( 'Link', 'boostify' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'boostify' ),
			)
		);
		$this->end_controls_section();
	}
	/**
	 * Register Retina Image Style Controls.
	 *
	 * @since 1.2.0
	 * @access protected
	 */
	protected function image_style() {
		$this->start_controls_section(
			'section_style_retina_image',
			array(
				'label' => __( 'Retina Image', 'boostify' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'width',
			array(
				'label'          => __( 'Width', 'boostify' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'unit' => '%',
				),
				'tablet_default' => array(
					'unit' => '%',
				),
				'mobile_default' => array(
					'unit' => '%',
				),
				'size_units'     => array( '%', 'px', 'vw' ),
				'range'          => array(
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
					'px' => array(
						'min' => 1,
						'max' => 1000,
					),
					'vw' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'      => array(
					'{{WRAPPER}} .boostify-retina-image img' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .boostify-retina-image .wp-caption .widget-image-caption' => 'width: {{SIZE}}{{UNIT}}; display: inline-block;',
				),
			)
		);

		$this->add_responsive_control(
			'space',
			array(
				'label'          => __( 'Max Width', 'boostify' ) . ' (%)',
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'unit' => '%',
				),
				'tablet_default' => array(
					'unit' => '%',
				),
				'mobile_default' => array(
					'unit' => '%',
				),
				'size_units'     => array( '%' ),
				'range'          => array(
					'%' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'      => array(
					'{{WRAPPER}} .boostify-retina-image img' => 'max-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wp-caption-text' => 'max-width: {{SIZE}}{{UNIT}}; display: inline-block; width: 100%;',
				),
			)
		);

		$this->add_control(
			'separator_panel_style',
			array(
				'type'  => Controls_Manager::DIVIDER,
				'style' => 'thick',
			)
		);

		$this->add_control(
			'retina_image_border',
			array(
				'label'       => __( 'Border Style', 'boostify' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'none',
				'label_block' => false,
				'options'     => array(
					'none'   => __( 'None', 'boostify' ),
					'solid'  => __( 'Solid', 'boostify' ),
					'double' => __( 'Double', 'boostify' ),
					'dotted' => __( 'Dotted', 'boostify' ),
					'dashed' => __( 'Dashed', 'boostify' ),
				),
				'selectors'   => array(
					'{{WRAPPER}} .boostify-retina-image-container .boostify-retina-img' => 'border-style: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'retina_image_border_size',
			array(
				'label'      => __( 'Border Width', 'boostify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'default'    => array(
					'top'    => '1',
					'bottom' => '1',
					'left'   => '1',
					'right'  => '1',
					'unit'   => 'px',
				),
				'condition'  => array(
					'retina_image_border!' => 'none',
				),
				'selectors'  => array(
					'{{WRAPPER}} .boostify-retina-image-container .boostify-retina-img' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'retina_image_border_color',
			array(
				'label'     => __( 'Border Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Elementor\Scheme_Color::get_type(),
					'value' => Elementor\Scheme_Color::COLOR_1,
				),
				'condition' => array(
					'retina_image_border!' => 'none',
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .boostify-retina-image-container .boostify-retina-img' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'image_border_radius',
			array(
				'label'      => __( 'Border Radius', 'boostify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .boostify-retina-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'image_box_shadow',
				'exclude'  => array(
					'box_shadow_position',
				),
				'selector' => '{{WRAPPER}} .boostify-retina-image img',
			)
		);

		$this->start_controls_tabs( 'image_effects' );

		$this->start_controls_tab(
			'normal',
			array(
				'label' => __( 'Normal', 'boostify' ),
			)
		);

		$this->add_control(
			'opacity',
			array(
				'label'     => __( 'Opacity', 'boostify' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .boostify-retina-image img' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->add_group_control(
			Elementor\Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'css_filters',
				'selector' => '{{WRAPPER}} .boostify-retina-image img',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'hover',
			array(
				'label' => __( 'Hover', 'boostify' ),
			)
		);
		$this->add_control(
			'opacity_hover',
			array(
				'label'     => __( 'Opacity', 'boostify' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .boostify-retina-image:hover img' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->add_group_control(
			Elementor\Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'css_filters_hover',
				'selector' => '{{WRAPPER}} .boostify-retina-image:hover img',
			)
		);

		$this->add_control(
			'hover_animation',
			array(
				'label' => __( 'Hover Animation', 'boostify' ),
				'type'  => Controls_Manager::HOVER_ANIMATION,
			)
		);
		$this->add_control(
			'background_hover_transition',
			array(
				'label'     => __( 'Transition Duration', 'boostify' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max'  => 3,
						'step' => 0.1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .boostify-retina-image img' => 'transition-duration: {{SIZE}}s',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}
	/**
	 * Register Caption style Controls.
	 *
	 * @since 1.2.0
	 * @access protected
	 */
	protected function caption_style() {
		$this->start_controls_section(
			'section_style_caption',
			array(
				'label'     => __( 'Caption', 'boostify' ),
				'tab'       => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => __( 'Text Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .widget-image-caption' => 'color: {{VALUE}};',
				),
				'scheme'    => array(
					'type'  => Elementor\Scheme_Color::get_type(),
					'value' => Elementor\Scheme_Color::COLOR_1,
				),
			)
		);

		$this->add_control(
			'caption_background_color',
			array(
				'label'     => __( 'Background Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .widget-image-caption' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'caption_typography',
				'selector' => '{{WRAPPER}} .widget-image-caption',
				'scheme'   => Elementor\Scheme_Typography::TYPOGRAPHY_1,
			)
		);

		$this->add_group_control(
			Elementor\Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'caption_text_shadow',
				'selector' => '{{WRAPPER}} .widget-image-caption',
			)
		);

		$this->add_responsive_control(
			'caption_padding',
			array(
				'label'      => __( 'Padding', 'boostify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .widget-image-caption' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'caption_space',
			array(
				'label'     => __( 'Caption Top Spacing', 'boostify' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'   => array(
					'size' => 0,
					'unit' => 'px',
				),
				'selectors' => array(
					'{{WRAPPER}} .widget-image-caption' => 'margin-top: {{SIZE}}{{UNIT}}; margin-bottom: 0px;',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		if ( empty( $settings['image']['url'] ) ) {
			return;
		}
		if ( ! $settings['retina']['id'] ) {
			$image_url = $settings['retina']['url'];
		} else {
			$image_url = Elementor\Group_Control_Image_Size::get_attachment_image_src( $settings['retina']['id'], 'retina_image', $settings );
		}

		if ( ! $settings['image']['id'] ) {
			$retina_url = $settings['image']['url'];
		} else {
			$retina_url = Elementor\Group_Control_Image_Size::get_attachment_image_src( $settings['image']['id'], 'retina_image', $settings );
		}
		$link    = empty( $settings['link'] ) ? false : $settings['link'];
		$caption = empty( $settings['caption'] ) ? false : $settings['caption'];
		?>
			<div class="boostify-retina-image-set boostify-retina-image">
				<div class="boostify-retina-image-container">
					<?php $this->link_open( $link ); ?>
					<img class="boostify-retina-img"  src="<?php echo esc_url( $image_url ); ?>" srcset="<?php echo esc_url( $image_url ) . esc_html( ' 1x' ) . ',' . esc_url( $retina_url ) . ' 2x'; ?>"/>
					<?php $this->link_close( $link ); ?>
				</div>
				<?php if ( $caption ) : ?>
					<div class="boostify-caption-width"> 
						<figcaption class="widget-image-caption wp-caption-text"><?php echo esc_html( $caption ); ?></figcaption>
					</div>
				<?php endif; ?>
			</div>
		<?php
	}

	public function link_open( $link ) {
		if ( $link['url'] ) {
			?>
			<a href="<?php echo esc_url( $link['url'] ); ?>">
			<?php
		}
	}

	public function link_close( $link ) {
		if ( $link['url'] ) {
			?>
			</a>
			<?php
		}
	}

}
