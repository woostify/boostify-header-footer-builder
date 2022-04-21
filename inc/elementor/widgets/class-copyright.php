<?php
/**
 * Copyright
 *
 * Elementor widget for Copyright.
 *
 * @package Boostify_Header_Footer
 * Author: ptp
 */

namespace Boostify_Header_Footer\Widgets;

use Boostify_Header_Footer\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

/**
 * Copyright
 *
 * Elementor widget for Copyright.
 * Author: ptp
 */
class Copyright extends Base_Widget {

	/**
	 * Retrieve the widget name.
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

	public function register_controls() { //phpcs:ignore
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
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'caption_typography',
				'selector' => '{{WRAPPER}} .boostify-copyright-wrapper, {{WRAPPER}} .boostify-copyright-wrapper a',
			)
		);

		$this->end_controls_section();
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
