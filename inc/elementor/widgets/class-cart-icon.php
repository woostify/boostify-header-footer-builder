<?php

namespace Boostify_Header_Footer\Widgets;

use Boostify_Header_Footer\Base_Widget;
use Boostify_Header_Footer\Module\Woocommerce;
use Elementor\Controls_Manager;

/**
 * Cart Icon
 *
 * Elementor widget for Copyright.
 * Author: ptp
 */
class Cart_Icon extends Base_Widget {

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
		return 'ht-hf-cart-icon';
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
		return __( 'Cart Icon', 'boostify' );
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
		return 'eicon-cart';//eicon-cart
	}

	public function get_script_depends() {
		return array( 'boostify_hf_cart_icon' );
	}

	public function _register_controls() { //phpcs:ignore
		$this->start_controls_section(
			'cart_icon',
			array(
				'label' => __( 'Cart Icon', 'boostify' ),
			)
		);
		$this->add_control(
			'icon',
			array(
				'label'   => esc_html__( 'Icon', 'boostify' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'ion-ios-cart'         => 'Cart Solid',
					'ion-ios-cart-outline' => 'Cart Outline',
					'ion-bag'              => 'Bag',
				),
				'default' => 'ion-bag',
			)
		);

		$this->add_control(
			'subtotal',
			array(
				'label'        => esc_html__( 'Subtotal', 'boostify' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'boostify' ),
				'label_off'    => __( 'Hide', 'boostify' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'action',
			array(
				'label'   => esc_html__( 'Action', 'boostify' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'hover' => __( 'Hover', 'boostify' ),
					'click' => __( 'Click', 'boostify' ),
				),
				'default' => 'hover',
			)
		);

		$this->add_control(
			'position',
			array(
				'label'     => esc_html__( 'Action', 'boostify' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'right' => __( 'Right', 'boostify' ),
					'left'  => __( 'Left', 'boostify' ),
				),
				'default'   => 'right',
				'condition' => array(
					'action' => 'click',
				),
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
					'{{WRAPPER}} .elementor-widget-container' => 'text-align: {{VALUE}};',
				),
			)
		);


		$this->end_controls_section();

		$this->start_controls_section(
			'style_icon',
			array(
				'label' => __( 'Icon', 'boostify' ),
				'tab'   => Controls_Manager::TAB_STYLE,
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
		$settings = $this->get_settings_for_display();
		if ( class_exists( 'Woocommerce' ) ) {

			$classes = array(
				'boostify-cart-icon',
				'widget-cart-icon',
				'boostify-action-' . $settings['action'],
				'boostify-icon-' . $settings['icon'],
			);
			if ( 'click' === $settings['action'] ) {
				array_push( $classes, 'sidebar-position-' . $settings['position'] );
			}

			$classes = implode( ' ', $classes );

			?>
			<div class="<?php echo esc_attr( $classes ); ?>">

				<?php \Boostify_Header_Footer\Module\Woocommerce::render_cart(); ?>

			</div>
			<?php if ( 'click' === $settings['action'] ): ?>
				<div class="boostify-cart-overlay">
					<span class="ion-android-close"></span>
				</div>
			<?php endif ?>
			<?php
		} else {
			echo esc_html__( 'Please install or active plugin Woocommerce', 'boostify' );
		}
	}
}
