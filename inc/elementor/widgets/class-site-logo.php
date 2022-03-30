<?php
/**
 * Site Logo
 *
 * Elementor widget for Site Logo.
 *
 * @package Boostify_Header_Footer
 * Author: ptp
 */

namespace Boostify_Header_Footer\Widgets;

use Boostify_Header_Footer\Base_Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

/**
 * Site Logo
 *
 * Elementor widget for Site Logo.
 */
class Site_Logo extends Base_Widget {

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
		return esc_html__( 'Logo', 'boostify' );
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
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 */
	protected function register_controls() { // phpcs:ignore
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Logo', 'boostify' ),
			)
		);

		$this->add_control(
			'use',
			array(
				'label'   => __( 'Use', 'boostify' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'site',
				'options' => array(
					'site'   => 'Site Logo',
					'custom' => 'Custom Logo',
				),
			)
		);

		$this->add_control(
			'image',
			array(
				'label'     => __( 'Logo Custom', 'boostify' ),
				'type'      => \Elementor\Controls_Manager::MEDIA,
				'condition' => array(
					'use' => 'custom',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'        => 'thumbnail',
				'default'     => 'full',
				'label'       => esc_html__( 'Logo Size', 'boostify' ),
				'description' => esc_html__( 'Custom Logo size when selected image.', 'boostify' ),
				'condition'   => array(
					'use' => 'custom',
				),
			)
		);

		$this->add_control(
			'align',
			array(
				'label'     => esc_html__( 'Align', 'boostify' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'left',
				'selectors' => array(
					'{{WRAPPER}} .boostify-site-logo-header' => 'text-align: {{VALUE}};',
				),
				'options'   => array(
					'left'   => array(
						'icon'  => 'eicon-h-align-left',
						'title' => 'Left',
					),
					'center' => array(
						'icon'  => 'eicon-h-align-center',
						'title' => 'Center',
					),
					'right'  => array(
						'icon'  => 'eicon-h-align-right',
						'title' => 'Right',
					),
				),
			)
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
		?>
			<div class="boostify-site-logo-header">
			<?php
			if ( 'site' === $settings['use'] || empty( $settings['image']['id'] ) ) {

				if ( has_custom_logo() ) :
					the_custom_logo();
				else :
					if ( is_user_logged_in() ) {
						echo esc_html__( 'Please go to customize choose logo for site', 'boostify' );
					} else {
						?>
						<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
						<?php
					}
				endif;
			} else {

				$url = Group_Control_Image_Size::get_attachment_image_src( $settings['image']['id'], 'thumbnail', $settings );
				?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<img src="<?php echo esc_url( $url ); ?>" alt="Logo" class="custom-logo">
				</a>
				<?php

			}

			?>
		</div>
		<?php
	}

}
