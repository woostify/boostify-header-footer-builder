<?php

namespace Boostify_Header_Footer;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

abstract class Nav_Menu extends Widget_Base {

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-nav-menu';
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
	 * Get Sub Mega Menu Class
	 *
	 * @return array | list menu in site
	 */
	protected function all_menu_site() {
		$menus   = wp_get_nav_menus();
		$options = array(
			'no' => 'Select Menu',
		);

		foreach ( $menus as $menu ) {
			$options[ $menu->slug ] = $menu->name;
		}

		return $options;
	}

	protected function _register_controls() { //phpcs:ignore
		$this->main_menu();
		$this->custom_button_vertical();
		$this->mobile_menu();
		$this->menu_style();
		$this->submenu_style();
		$this->menu_sidebar_style();
		$this->custon_style();
		$this->custon_style2();
	}

	/**
	 * Get Settings For Main Menu
	 */
	abstract protected function main_menu();

	/**
	 * Get Settings For Mobile Menu
	 */
	protected function mobile_menu() {
		$this->start_controls_section(
			'section_mobile_menu',
			array(
				'label' => esc_html__( 'Mobile Menu', 'boostify' ),
			)
		);

		$this->custom_mobile_menu();

		$this->add_control(
			'toggle_icon',
			array(
				'label'     => __( 'Toggle Icons', 'boostify' ),
				'type'      => Controls_Manager::ICON,
				'separator' => 'before',
				'default'   => 'ion-android-menu',
				'include'   => array(
					'fa fa-navicon',
					'ion-navicon',
					'ion-navicon-round',
					'ion-android-menu',
				),
			)
		);

		$this->add_control(
			'toggle-align',
			array(
				'label'     => esc_html__( 'Toggle Align', 'boostify' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'right',
				'selectors' => array(
					'{{WRAPPER}} .boostify-menu-toggle' => 'text-align: {{VALUE}};',
				),
				'options'   => array(
					'left'   => array(
						'icon'  => 'eicon-h-align-left',
						'title' => 'Top',
					),
					'center' => array(
						'icon'  => 'eicon-h-align-center',
						'title' => 'Center',
					),
					'right'  => array(
						'icon'  => 'eicon-h-align-right',
						'title' => 'Bottom',
					),
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Get Settings Menu Style
	 */
	protected function menu_style() {
		$this->start_controls_section(
			'menu_style',
			array(
				'label' => __( 'Menu Style', 'boostify' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'menu_item_padding',
			array(
				'label'      => __( 'Padding', 'boostify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'top'    => 0,
					'bottom' => 0,
					'left'   => 20,
					'right'  => 20,
				),
				'selectors'  => array(
					'{{WRAPPER}} .boostify-menu > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .boostify--hover-overline .boostify-menu>li:hover>a:before' => 'width: calc( 100% - {{RIGHT}}{{UNIT}} - {{LEFT}}{{UNIT}} );',
					'{{WRAPPER}} .boostify--hover-underline .boostify-menu>li:hover>a:before' => 'width: calc( 100% - {{RIGHT}}{{UNIT}} - {{LEFT}}{{UNIT}} );',
					'{{WRAPPER}} .boostify--hover-overline .boostify-menu>licurrent-menu-item>a:before' => 'width: calc( 100% - {{RIGHT}}{{UNIT}} - {{LEFT}}{{UNIT}} );',
					'{{WRAPPER}} .boostify--hover-underline .boostify-menu>licurrent-menu-item>a:before' => 'width: calc( 100% - {{RIGHT}}{{UNIT}} - {{LEFT}}{{UNIT}} );',
				),
			)
		);


		$this->start_controls_tabs(
			'menu_style_tabs'
		);

		$this->start_controls_tab(
			'style_nemu_normal_tab',
			array(
				'label' => __( 'Normal', 'boostify' ),
			)
		);

		$this->menu_style_nornal();

		$this->end_controls_tab();

		$this->start_controls_tab(
			'style_menu_hover_tab',
			array(
				'label' => __( 'Hover', 'boostify' ),
			)
		);

		$this->menu_style_hover();

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function menu_style_nornal() {
		$this->add_control(
			'menucolor',
			array(
				'label'     => __( 'Menu Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => array(
					'{{WRAPPER}} .boostify-menu > li > a' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'menu_typo',
				'label'    => __( 'Typography', 'boostify' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .boostify-menu > li > a',
			)
		);
	}

	protected function menu_style_hover() {
		$this->add_control(
			'menu_color_hover',
			array(
				'label'     => __( 'Menu Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#aa3166',
				'selectors' => array(
					'{{WRAPPER}} .boostify-menu > li:hover > a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .boostify--hover-underline .boostify-menu > li:hover > a:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .boostify--hover-overline .boostify-menu li a:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .boostify-menu li.current-menu-item a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .boostify--hover-underline .boostify-menu > li.current-menu-item > a:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .boostify--hover-overline .boostify-menu li a.current-menu-item > a:before' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'menu_item_background_color_hover',
			array(
				'label'     => __( 'Background Item Hover', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#eee',
				'selectors' => array(
					'{{WRAPPER}} .boostify--hover-background .boostify-menu > li:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'menu_typo_hover',
				'label'    => __( 'Typography', 'boostify' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .boostify-menu > li:hover > a',
			)
		);
	}

	/**
	 * Get Settings Sub Menu Style
	 */
	protected function submenu_style() {
		$this->start_controls_section(
			'submenu_style',
			array(
				'label' => __( 'Sub Menu Style', 'boostify' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'width_submenu',
			array(
				'label'     => __( 'Width', 'koacher' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 2000,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .boostify-menu .sub-menu' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'dropdown_top_distance',
			array(
				'label'     => __( 'Distance', 'boostify' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .boostify-menu>.menu-item-has-children>.boostify-menu-child' => 'padding-top: {{SIZE}}{{UNIT}} !important',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'padding_submenu',
			array(
				'label'      => __( 'Padding', 'boostify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .boostify-menu .menu-item .boostify-menu-child .sub-menu' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
				),
			)
		);

		$this->add_control(
			'padding_submenu_item',
			array(
				'label'      => __( 'Padding Menu Item', 'boostify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'separator'  => 'before',
				'default'    => array(
					'top'    => 15,
					'bottom' => 15,
					'left'   => 20,
					'right'  => 20,
				),
				'selectors'  => array(
					'{{WRAPPER}} .boostify-menu .sub-menu a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs(
			'submenu_style_tabs'
		);

		$this->start_controls_tab(
			'style_submemu_normal_tab',
			array(
				'label' => __( 'Normal', 'boostify' ),
			)
		);

		$this->submenu_style_nornal();

		$this->end_controls_tab();

		$this->start_controls_tab(
			'style_submenu_hover_tab',
			array(
				'label' => __( 'Hover', 'boostify' ),
			)
		);

		$this->submenu_style_hover();

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'color_submenu_border',
			array(
				'label'     => __( 'SubMenu Border Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#d1346f',
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .boostify-menu .sub-menu' => 'border-top-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'background_submenu',
				'label'    => __( 'Background', 'boostify' ),
				'types'    => array( 'classic', 'gradient', 'video' ),
				'selector' => '.boostify-menu .sub-menu',
			)
		);

		$this->end_controls_section();
	}

	protected function submenu_style_nornal() {
		$this->add_control(
			'color_submenu',
			array(
				'label'     => __( 'SubMenu Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => array(
					'{{WRAPPER}} .boostify-menu .menu-item-has-children .sub-menu a' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'bgcolor_submenu',
			array(
				'label'     => __( 'Background color SubMenu', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f2f2f2',
				'selectors' => array(
					'{{WRAPPER}} .boostify-menu .menu-item-has-children .sub-menu li' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'submenu_typo',
				'label'    => __( 'Typography', 'boostify' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .boostify-menu .menu-item-has-children .sub-menu a',
			)
		);
	}

	public function submenu_style_hover() {
		$this->add_control(
			'color_submenu_hover',
			array(
				'label'     => __( 'SubMenu Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#aa3166',
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .boostify-menu li .sub-menu > li:hover > a'                                  => 'color: {{VALUE}}',
					'{{WRAPPER}} .boostify--hover-underline .boostify-menu li .sub-menu > li:hover > a:after' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .boostify-menu .sub-menu li.current-menu-item a'                                       => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'bgcolor_submenu_hover',
			array(
				'label'     => __( 'Background color SubMenu', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => array(
					'{{WRAPPER}} .boostify-menu .menu-item-has-children .sub-menu li:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'submenu_item_background_color_hover',
			array(
				'label'     => __( 'Background Submenu Item Hover', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#eee',
				'selectors' => array(
					'{{WRAPPER}} .boostify--hover-background .boostify-menu .sub-menu > li:hover' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'pointer' => 'background',
				),
			)
		);
	}

	/**
	 * Get Settings Mobile Menu Style
	 */
	public function menu_sidebar_style() {
		$this->start_controls_section(
			'menu_sidebar_style',
			array(
				'label' => __( 'Menu Mobie Style', 'boostify' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs(
			'menu_sidebar_style_tabs'
		);

		$this->start_controls_tab(
			'style_memu_sidebar_normal_tab',
			array(
				'label' => __( 'Normal', 'boostify' ),
			)
		);

		$this->menu_sidebar_style_nornal();

		$this->end_controls_tab();

		$this->start_controls_tab(
			'style_menu_sidebar_hover_tab',
			array(
				'label' => __( 'Hover', 'boostify' ),
			)
		);

		$this->menu_sidebar_style_hover();

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	public function menu_sidebar_style_nornal() {
		$this->add_control(
			'color_toggle',
			array(
				'label'     => __( 'Toggle Icon Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .boostify-menu-toggle' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'color_menu_sidebar',
			array(
				'label'     => __( 'SubMenu Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .boostify-menu-sidebar .boostify-dropdown-menu a' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'menu_sidebar_typo',
				'label'    => __( 'Typography', 'boostify' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .boostify-menu-sidebar .boostify-dropdown-menu a',
			)
		);
	}

	public function menu_sidebar_style_hover() {
		$this->add_control(
			'color_toggle_hover',
			array(
				'label'     => __( 'Toggle Icon Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .boostify-menu-toggle:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'color_menu_sidebar_hover',
			array(
				'label'     => __( 'SubMenu Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#d1346f',
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .boostify-menu-sidebar .boostify-dropdown-menu li:hover > a' => 'color: {{VALUE}}',
				),
			)
		);
	}

	/**
	 * Custom mobile menu
	 */
	abstract protected function custom_mobile_menu();

	/**
	 * Menu Class
	 * @param array $settings in elementor.
	 * @return array $classes navigation
	 */
	protected function nav_class() {
		$settings = $this->get_settings_for_display();
		$name     = $this->get_name();
		$classes  = array(
			'boostify-main-navigation',
			'boostify-header-navigation',
		);
		if ( array_key_exists( 'pointer', $settings ) ) {
			$pointer = $settings['pointer'];
			array_push( $classes, 'boostify--hover-' . $pointer );
		}
		if ( array_key_exists( 'icon_position', $settings ) ) {
			$icon_position = $settings['icon_position'];
			array_push( $classes, 'boostify-icon-' . $icon_position );
		}
		if ( 'ht-hf-mega-menu' === $name ) {
			array_push( $classes, 'boostify-mega-menu' );
		}

		return $classes;
	}

	/**
	 * Get Sub Mega Menu Class
	 * @param int $post_id
	 * @return object $sub menu default layout
	 */
	protected function sub_menu_default( $menu_id, $child_of ) {
		$args = array(
			'menu'       => $menu_id,
			'menu_id'    => '',
			'menu_class' => 'sub-menu',
			'container'  => '',
		);
		if ( ! empty( $child_of ) ) {
			$args['level']    = 2;
			$args['child_of'] = $child_of;
		}

		wp_nav_menu( $args );
	}

	protected function get_toggle( $icon ) {
		?>
		<a href="#" class="boostify-menu-toggle" aria-expanded="false">
			<span class="menu-toggle-wrapper <?php echo esc_attr( $icon ); ?>"></span><!-- .menu-toggle-wrapper -->
			<span class="screen-reader-text menu-toggle-text"><?php esc_html_e( 'Menu', 'boostify' ); ?></span>
		</a><!-- .menu-toggle -->
		<?php
	}

	/**
	 * Get Settings Button Vertical For Main Menu
	 */
	protected function custom_button_vertical() {}

	/**
	 * Get Setting Button Vertical Mega Menu For Style Tab
	 */
	protected function custon_style() {}

	/**
	 * Get Settings Mega Menu Vertical For Style Tab
	 */
	protected function custon_style2() {}

}

