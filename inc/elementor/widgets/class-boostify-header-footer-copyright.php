<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

/**
 * Copyright
 *
 * Elementor widget for Copyright.
 * Author: ptp
 */
class Boostify_Header_Footer_Copyright extends Widget_Base {

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
		return 'ht-hf-copyright';
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
		return __( 'Copyright', 'boostify' );
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
		return 'fa fa-copyright';
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

	public function _register_controls() { //phpcs:ignore
		$this->start_controls_section(
			'section_title',
			array(
				'label' => __( 'Copyright', 'boostify' ),
			)
		);

		$this->add_control(
			'shortcode',
			array(
				'label'   => __( 'Copyright Text', 'boostify' ),
				'type'    => Controls_Manager::TEXTAREA,
				'dynamic' => array(
					'active' => true,
				),
				'default' => __( 'Copyright © [btf_year] [btf_site_tile] | All Rights Reserved. Designed by [btf_site_tile].', 'boostify' ),
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
				'selectors' => array(
					'{{WRAPPER}} .boostify-copyright-wrapper' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Text Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					// Stronger selector to avoid section style from overwriting.
					'{{WRAPPER}} .boostify-copyright-wrapper a, {{WRAPPER}} .boostify-copyright-wrapper' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'caption_typography',
				'selector' => '{{WRAPPER}} .boostify-copyright-wrapper, {{WRAPPER}} .boostify-copyright-wrapper a',
				'scheme'   => Elementor\Scheme_Typography::TYPOGRAPHY_3,
			)
		);
	}

	/**
	 * Render Copyright output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.2.0
	 * @access protected
	 */
	protected function render() {
		$settings             = $this->get_settings_for_display();
		$copy_right_shortcode = do_shortcode( shortcode_unautop( $settings['shortcode'] ) );
		?>
		<div class="boostify-copyright-wrapper">
			<div class="boostify-main--wrapper">
				<span>
					<?php
						echo wp_kses(
							$copy_right_shortcode,
							array(
								'a' => array(
									'class' => array(),
									'href'  => array(),
									'id'    => array(),
								),
							)
						);
					?>
				</span>
			</div>
		</div>
		<?php
	}

}
