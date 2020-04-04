<?php

namespace Boostify_Header_Footer\Widgets;

use Boostify_Header_Footer\Base_Widget;
use Boostify_Header_Footer\Module\Woocommerce;
use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;

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

		$this->content();

		$this->cart_icon_style();

		$this->cart_heading_style();

		$this->cart_content_style();

		$this->buttons_style();
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

			if ( 'hover' === $settings['action'] ) {
				array_push( $classes, 'hover-position-' . $settings['hover_position'] );
			}

			$classes = implode( ' ', $classes );

			?>
			<div class="<?php echo esc_attr( $classes ); ?>">

				<?php \Boostify_Header_Footer\Module\Woocommerce::render_cart(); ?>
				<?php if ( 'click' === $settings['action'] ) : ?>
					<div class="boostify-cart-overlay">
						<span class="ion-android-close"></span>
					</div>
				<?php endif ?>

			</div>

			<?php
		} else {
			echo esc_html__( 'Please install or active plugin Woocommerce', 'boostify' );
		}
	}

	protected function content() {
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
			'hover_position',
			array(
				'label'     => __( 'Position Content Cart', 'boostify' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'left',
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
				'condition' => array(
					'action' => 'hover',
				),
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

		$this->add_control(
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
	}

	protected function cart_heading_style() {
		$this->start_controls_section(
			'cart_content_heading',
			array(
				'label' => __( 'Cart Heading', 'boostify' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'heading_background',
				'label'     => __( 'Background', 'boostify' ),
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} .cart-sidebar-head',
				'condition' => array(
					'action' => 'click',
				),
			)
		);

		$this->add_control(
			'title_heading',
			array(
				'label'     => __( 'Title', 'boostify' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'action' => 'click',
				),
			)
		);

		$this->add_control(
			'heading_color',
			array(
				'label'     => __( 'Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'condition' => array(
					'action' => 'click',
				),
				'selectors' => array(
					'{{WRAPPER}} .cart-sidebar-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'heading_typography',
				'label'     => __( 'Typography', 'boostify' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_1,
				'selector'  => '{{WRAPPER}} .cart-sidebar-title',
				'condition' => array(
					'action' => 'click',
				),
			)
		);

		$this->add_control(
			'counter_heading',
			array(
				'label'     => __( 'Counter', 'boostify' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'action' => 'click',
				),
			)
		);

		$this->add_control(
			'color_count',
			array(
				'label'     => __( 'Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .cart-sidebar-head .count' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'action' => 'click',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'couner_typography',
				'label'     => __( 'Typography', 'boostify' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_1,
				'selector'  => '{{WRAPPER}} .cart-sidebar-head .count',
				'condition' => array(
					'action' => 'click',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'background',
				'label'     => __( 'Background', 'boostify' ),
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} .cart-sidebar-head .count',
				'condition' => array(
					'action' => 'click',
				),
			)
		);

		$this->add_control(
			'close_sidebar_heading',
			array(
				'label'     => __( 'Close Sibar Icon', 'boostify' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'action' => 'click',
				),
			)
		);

		$this->add_control(
			'color_close_sidebar',
			array(
				'label'     => __( 'Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333',
				'selectors' => array(
					'{{WRAPPER}} .boostify-close-cart-sidebar' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'action' => 'click',
				),
			)
		);

		$this->add_responsive_control(
			'font_size_close_sibar',
			array(
				'label'      => __( 'Font Size', 'boostify' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 15,
						'max'  => 90,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .boostify-close-cart-sidebar' => 'font-size: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'action' => 'click',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function cart_icon_style() {
		$this->start_controls_section(
			'style_icon',
			array(
				'label' => __( 'Cart Icon', 'boostify' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'icon_heading',
			array(
				'label'     => __( 'Icon', 'boostify' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'color_icon',
			array(
				'label'     => __( 'Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => array(
					'{{WRAPPER}} .boostify-icon--cart' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'color_icon_hover',
			array(
				'label'     => __( 'Color Hover', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => array(
					'{{WRAPPER}} .boostify-icon--cart:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'font_size',
			array(
				'label'      => __( 'Font Size', 'boostify' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 10,
						'max'  => 50,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .boostify-icon--cart' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'icon_counter_heading',
			array(
				'label'     => __( 'Counter', 'boostify' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'color_icon_counter',
			array(
				'label'     => __( 'Color Counter', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} ..boostify-count-product' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'icon_counter_typography',
				'label'     => __( 'Typography', 'boostify' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_1,
				'selector'  => '{{WRAPPER}} .boostify-count-product',
				'condition' => array(
					'action' => 'click',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'icon_counter_background',
				'label'    => __( 'Background', 'boostify' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .boostify-count-product',
			)
		);

		$this->end_controls_section();
	}

	protected function cart_content_style() {
		$this->start_controls_section(
			'cart_content',
			array(
				'label' => __( 'Cart Content', 'boostify' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'padding_content_cart',
			array(
				'label'      => __( 'Padding', 'boostify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .boostify-cart-detail .woocommerce-mini-cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'cart_content_background',
				'label'    => __( 'Background', 'boostify' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .woocommerce-mini-cart',
			)
		);

		$this->add_control(
			'product_name_heading',
			array(
				'label'     => __( 'Product Name', 'boostify' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'color_product',
			array(
				'label'     => __( 'Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333',
				'selectors' => array(
					'{{WRAPPER}} .mini-cart-item-name' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'product_name_typography',
				'label'    => __( 'Typography', 'boostify' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .mini-cart-item-name',
			)
		);

		$this->add_control(
			'product_quantity_heading',
			array(
				'label'     => __( 'Quantity', 'boostify' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'color_quantity',
			array(
				'label'     => __( 'Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#666',
				'selectors' => array(
					'{{WRAPPER}} .mini-cart-item-detail .quantity' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'quantity_typography',
				'label'    => __( 'Typography', 'boostify' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .mini-cart-item-detail .quantity',
			)
		);

		$this->add_control(
			'sub_total_heading',
			array(
				'label'     => __( 'Subtotal', 'boostify' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'color_sub_total',
			array(
				'label'     => __( 'Title Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333',
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-mini-cart__total strong' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'sub_total_typography',
				'label'    => __( 'Subtotal Typography', 'boostify' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .woocommerce-mini-cart__total strong',
			)
		);

		$this->add_control(
			'price_total',
			array(
				'label'     => __( 'Price Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333',
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-Price-amount' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'price_typography',
				'label'    => __( 'Price Typography', 'boostify' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .woocommerce-Price-amount',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'total_button_background',
				'label'    => __( 'Background', 'boostify' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .woocommerce-mini-cart__total',
			)
		);

		$this->end_controls_section();
	}

	protected function buttons_style() {
		$this->start_controls_section(
			'cart_button',
			array(
				'label' => __( 'Buttons', 'boostify' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'padding_buttons',
			array(
				'label'      => __( 'Padding', 'boostify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .boostify-cart-detail .woocommerce-mini-cart__buttons' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'button_background',
				'label'    => __( 'Background', 'boostify' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .boostify-cart-detail .woocommerce-mini-cart__buttons',
			)
		);

		$this->button_view_cart();

		$this->button_checkout();

		$this->end_controls_section();
	}

	public function button_view_cart() {
		$this->add_control(
			'view_cart_heading',
			array(
				'label'     => __( 'View Cart', 'boostify' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->start_controls_tabs( 'view_cart' );

		$this->start_controls_tab(
			'view_cart_normal',
			array(
				'label' => __( 'Normal', 'boostify' ),
			)
		);

		$this->add_control(
			'color_view_cart',
			array(
				'label'     => __( 'Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-mini-cart__buttons .button:first-child' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'view_cart_background',
				'label'    => __( 'Background', 'boostify' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .woocommerce-mini-cart__buttons .button:first-child',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'view_cart_hover',
			array(
				'label' => __( 'Hover', 'boostify' ),
			)
		);

		$this->add_control(
			'view_color_hover',
			array(
				'label'     => __( 'Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-mini-cart__buttons .button:first-child:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'view_cart_background_hover',
				'label'    => __( 'Background', 'boostify' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .woocommerce-mini-cart__buttons .button:first-child:hover',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'view_cart_typography',
				'label'    => __( 'Typography', 'boostify' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .woocommerce-mini-cart__buttons .button:last-child',
			)
		);
	}

	public function button_checkout() {
		$this->add_control(
			'checkout_heading',
			array(
				'label'     => __( 'Checkout', 'boostify' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->start_controls_tabs( 'checkout' );

		$this->start_controls_tab(
			'checkout_normal',
			array(
				'label' => __( 'Normal', 'boostify' ),
			)
		);

		$this->add_control(
			'color_checkout',
			array(
				'label'     => __( 'Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-mini-cart__buttons .button:last-child' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'checkout_background',
				'label'    => __( 'Background', 'boostify' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .woocommerce-mini-cart__buttons .button:last-child',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'checkout_hover',
			array(
				'label' => __( 'Hover', 'boostify' ),
			)
		);

		$this->add_control(
			'checkout_color_hover',
			array(
				'label'     => __( 'Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-mini-cart__buttons .button:last-child:hover' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'action' => 'click',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'checkout_background_hover',
				'label'     => __( 'Background', 'boostify' ),
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} .woocommerce-mini-cart__buttons .button:last-child:hover',
				'condition' => array(
					'action' => 'click',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'checkout_typography',
				'label'     => __( 'Typography', 'boostify' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_1,
				'selector'  => '{{WRAPPER}} .woocommerce-mini-cart__buttons .button:last-child',
				'condition' => array(
					'action' => 'click',
				),
			)
		);
	}
}
