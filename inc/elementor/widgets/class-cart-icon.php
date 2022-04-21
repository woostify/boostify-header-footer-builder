<?php
/**
 * Cart Icon
 *
 * Elementor widget for Cart Icon.
 *
 * @package Boostify_Header_Footer
 * Author: ptp
 */

namespace Boostify_Header_Footer\Widgets;

use Boostify_Header_Footer\Base_Widget;
use Boostify_Header_Footer\Module\Woocommerce as BoostifyWoocommerce;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;

/**
 * Cart Icon
 *
 * Elementor widget for Cart Icon.
 */
class Cart_Icon extends Base_Widget {

	/**
	 * Retrieve the widget name.
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
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-cart';
	}

	/**
	 * Retrieve the widget script.
	 *
	 * @access public
	 *
	 * @return array Widget script.
	 */
	public function get_script_depends() {
		return array( 'boostify_hf_cart_icon' );
	}

	/**
	 * Register Widget Controls.
	 *
	 * @access public
	 */
	public function register_controls() { //phpcs:ignore

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
	 * @access protected
	 */
	protected function render() {
		$settings        = $this->get_settings_for_display();
		$icon            = $settings['icon'];
		$show_subtotal   = $settings['show_subtotal'];
		$disable_sidebar = $settings['disable_cart_sidebar'];

		if ( class_exists( 'Woocommerce' ) ) {
			$classes = array(
				'boostify-cart-icon',
				'widget-cart-icon',
				'boostify-action-' . $settings['action'],
				'boostify-icon-' . $settings['icon'],
				'boostify-subtotal-' . $show_subtotal,
			);
			if ( 'click' === $settings['action'] ) {
				array_push( $classes, 'sidebar-position-' . $settings['position'] );
			}

			if ( 'hover' === $settings['action'] ) {
				array_push( $classes, 'hover-position-' . $settings['hover_position'] );
			}

			if ( 'yes' !== $disable_sidebar ) {
				array_push( $classes, 'boostify-ajax-add-to-cart' );
			}

			$classes = implode( ' ', $classes );

			?>
			<div class="<?php echo esc_attr( $classes ); ?>">

			<?php
			BoostifyWoocommerce::render_cart();
			if ( 'click' === $settings['action'] ) :
				?>
				<div class="boostify-cart-overlay">
				</div>
			<?php endif ?>

			</div>

			<?php
		} else {
			echo esc_html__( 'Please install or active plugin Woocommerce', 'boostify' );
		}
	}

	/**
	 * Register Content Controls.
	 *
	 * @access protected
	 */
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
				'label'   => esc_html__( 'Style', 'boostify' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'hover' => __( 'Mini Cart', 'boostify' ),
					'click' => __( 'Cart SideBar', 'boostify' ),
				),
				'default' => 'hover',
			)
		);

		$this->add_control(
			'show_subtotal',
			array(
				'label'        => __( 'Subtotal', 'boostify' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'boostify' ),
				'label_off'    => __( 'Hide', 'boostify' ),
				'return_value' => 'yes',
				'default'      => 'yes',
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
				'label'     => esc_html__( 'Position', 'boostify' ),
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
			'disable_cart_sidebar',
			array(
				'label'        => __( 'Disable when add to cart', 'boostify' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'boostify' ),
				'label_off'    => __( 'No', 'boostify' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
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

	/**
	 * Register Heading Controls.
	 *
	 * @access protected
	 */
	protected function cart_heading_style() {
		$this->start_controls_section(
			'cart_content_heading',
			array(
				'label' => __( 'Cart Heading', 'boostify' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'heading_background',
			array(
				'label'     => __( 'Background', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'action' => 'click',
				),
				'selectors' => array(
					'{{WRAPPER}} .cart-sidebar-head' => 'background-color: {{VALUE}};',
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
				'selector'  => '{{WRAPPER}} .cart-sidebar-head .count',
				'condition' => array(
					'action' => 'click',
				),
			)
		);

		$this->add_control(
			'background_color_count',
			array(
				'label'     => __( 'Background', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#d9534f',
				'selectors' => array(
					'{{WRAPPER}} .cart-sidebar-head .count' => 'background-color: {{VALUE}}',
				),
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

	/**
	 * Register Icon Controls.
	 *
	 * @access protected
	 */
	protected function cart_icon_style() {
		$this->start_controls_section(
			'style_icon',
			array(
				'label' => __( 'Cart Icon', 'boostify' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'padding',
			array(
				'label'      => __( 'Padding', 'boostify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .boostify-btn--cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'border',
				'label'    => __( 'Border', 'boostify' ),
				'selector' => '{{WRAPPER}} .boostify-btn--cart',
			)
		);

		$this->add_control(
			'border-radius',
			array(
				'label'      => __( 'Border Radius', 'boostify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .boostify-btn--cart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'cart-background-color',
			array(
				'label'     => __( 'Background Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .boostify-btn--cart' => 'background: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'subtotal_heading',
			array(
				'label'     => __( 'Subtotal', 'boostify' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'show_subtotal' => 'yes',
				),
			)
		);

		$this->add_control(
			'color_subtotal',
			array(
				'label'     => __( 'Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => array(
					'{{WRAPPER}} .boostify-subtotal .woocommerce-Price-amount' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'show_subtotal' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'subtotal_typography',
				'label'     => __( 'Typography', 'boostify' ),
				'selector'  => '{{WRAPPER}} .boostify-subtotal .woocommerce-Price-amount',
				'condition' => array(
					'show_subtotal' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'space',
			array(
				'label'      => __( 'Space', 'boostify' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 00,
						'max'  => 50,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .boostify-subtotal .woocommerce-Price-amount' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'show_subtotal' => 'yes',
				),
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
					'{{WRAPPER}} .boostify-count-product' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'icon_counter_typography',
				'label'     => __( 'Typography', 'boostify' ),
				'selector'  => '{{WRAPPER}} .boostify-count-product',
				'condition' => array(
					'action' => 'click',
				),
			)
		);

		$this->add_control(
			'icon_counter_background',
			array(
				'label'     => __( 'Background', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#d9534f',
				'selectors' => array(
					'{{WRAPPER}} .boostify-count-product' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'distance-top',
			array(
				'label'      => __( 'Distance Top', 'boostify' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => -50,
						'max'  => 50,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .boostify-cart-icon .boostify-count-product' => 'top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'distance-right',
			array(
				'label'      => __( 'Distance Right', 'boostify' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => -50,
						'max'  => 50,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .boostify-cart-icon .boostify-count-product' => 'right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Content Style Controls.
	 *
	 * @access protected
	 */
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
					'{{WRAPPER}} .boostify-cart-detail .woocommerce--mini-cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'cart_content_background',
			array(
				'label'     => __( 'Background', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce--mini-cart' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'product_image_heading',
			array(
				'label'     => __( 'Product Image', 'boostify' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'position_image',
			array(
				'label'     => __( 'Alignment', 'boostify' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'row'         => array(
						'title' => __( 'Left', 'boostify' ),
						'icon'  => 'eicon-h-align-left',
					),
					'row-reverse' => array(
						'title' => __( 'Right', 'boostify' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'default'   => 'row',
				'selectors' => array(
					'{{WRAPPER}} .boostify-cart-item-info' => 'flex-direction: {{VALUE}};',
				),
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
					'{{WRAPPER}} .mini-cart-item-detail .quantity .woocommerce-Price-amount' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'quantity_typography',
				'label'    => __( 'Typography', 'boostify' ),
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
					'{{WRAPPER}} .boostify-mini-cart__total strong' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'sub_total_typography',
				'label'    => __( 'Subtotal Typography', 'boostify' ),
				'selector' => '{{WRAPPER}} .boostify-mini-cart__total strong',
			)
		);

		$this->add_control(
			'price_total',
			array(
				'label'     => __( 'Price Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333',
				'selectors' => array(
					'{{WRAPPER}} .boostify-mini-cart__total .woocommerce-Price-amount' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'price_typography',
				'label'    => __( 'Price Typography', 'boostify' ),
				'selector' => '{{WRAPPER}} .boostify-mini-cart__total .woocommerce-Price-amount',
			)
		);

		$this->add_control(
			'total_button_background',
			array(
				'label'     => __( 'Background', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .boostify-mini-cart__total' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Button Style Controls.
	 *
	 * @access protected
	 */
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
					'{{WRAPPER}} .boostify-cart-detail .boostify-mini-cart__buttons' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'button_background',
			array(
				'label'     => __( 'Background', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .boostify-cart-detail .boostify-mini-cart__buttons' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->button_view_cart();

		$this->button_checkout();

		$this->end_controls_section();
	}

	/**
	 * Register Button View Cart Controls.
	 *
	 * @access protected
	 */
	public function button_view_cart() {
		$this->add_control(
			'view_cart_heading',
			array(
				'label'     => __( 'View Cart', 'boostify' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'padding_view_cart',
			array(
				'label'      => __( 'Padding', 'boostify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .boostify-cart-detail .boostify-mini-cart__buttons .button:first-child' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
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
					'{{WRAPPER}} .boostify-mini-cart__buttons .button:first-child' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'view_cart_background',
			array(
				'label'     => __( 'Background', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .boostify-mini-cart__buttons .button:first-child' => 'background: {{VALUE}}',
				),
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
					'{{WRAPPER}} .boostify-mini-cart__buttons .button:first-child:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'view_cart_background_hover',
			array(
				'label'     => __( 'Background', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .boostify-mini-cart__buttons .button:first-child:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'view_cart_typography',
				'label'    => __( 'Typography', 'boostify' ),
				'selector' => '{{WRAPPER}} .boostify-mini-cart__buttons .button:first-child',
			)
		);
	}

	/**
	 * Register Button Checkout Controls.
	 *
	 * @access protected
	 */
	public function button_checkout() {
		$this->add_control(
			'checkout_heading',
			array(
				'label'     => __( 'Checkout', 'boostify' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'padding_checkout',
			array(
				'label'      => __( 'Padding', 'boostify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .boostify-cart-detail .boostify-mini-cart__buttons .button:last-child' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
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
					'{{WRAPPER}} .boostify-mini-cart__buttons .button:last-child' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'checkout_background',
			array(
				'label'     => __( 'Background', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .boostify-mini-cart__buttons .button:last-child' => 'background-color: {{VALUE}}',
				),
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
					'{{WRAPPER}} .boostify-mini-cart__buttons .button:last-child:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'checkout_background_hover',
			array(
				'label'     => __( 'Background', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .boostify-mini-cart__buttons .button:last-child:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'checkout_typography',
				'label'    => __( 'Typography', 'boostify' ),
				'selector' => '{{WRAPPER}} .boostify-mini-cart__buttons .button:last-child',
			)
		);
	}
}
