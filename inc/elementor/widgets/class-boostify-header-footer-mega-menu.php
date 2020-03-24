<?php

use Boostify_Header_Footer\Nav_Menu;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

/**
 * Mega Menu
 *
 * Elementor widget for Mega Menu.
 * Author: ptp
 */
class Boostify_Header_Footer_Mega_Menu extends Widget_Base {

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
		return 'ht-hf-mega-menu';
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
		return __( 'Mega Menu', 'boostify' );
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
	// public function get_categories() {
	// 	return array( 'ht_hf_builder' );
	// }

	public function get_script_depends() {
		return array( 'boostify_hf_nav_mega' );
	}

	public function _register_controls() { //phpcs:ignore
		$this->add_control_main_menu();
		$this->add_control_mobile();
		$this->add_control_menu_style();
		$this->add_control_submenu_style();
	}

	protected function add_control_main_menu() {
		$this->start_controls_section(
			'section_title',
			array(
				'label' => __( 'Mega Menu', 'boostify' ),
			)
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'item_text',
			array(
				'label'       => __( 'Title', 'boostify' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => __( 'Menu Item', 'boostify' ),
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'icon',
			array(
				'label' => __( 'Icons', 'boostify' ),
				'type'  => \Elementor\Controls_Manager::ICON,
			)
		);

		$repeater->add_control(
			'link',
			array(
				'label'         => __( 'Link', 'boostify' ),
				'type'          => \Elementor\Controls_Manager::URL,
				'placeholder'   => __( 'https://your-link.com', 'boostify' ),
				'show_external' => true,
				'default'       => array(
					'url'         => '#',
					'is_external' => true,
					'nofollow'    => true,
				),
			)
		);

		$repeater->add_control(
			'has_sub',
			array(
				'label'        => __( 'Have Sub Menu', 'boostify' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'boostify' ),
				'label_off'    => __( 'No', 'boostify' ),
				'return_value' => 'yes',
			)
		);

		$repeater->add_control(
			'sub_type',
			array(
				'label'     => __( 'Sub Menu Type', 'boostify' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'mega',
				'options'   => array(
					'mega'    => 'Mega',
					'default' => 'Default',
				),
				'condition' => array(
					'has_sub' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'sub_menu',
			array(
				'label'     => __( 'Sub Mega Menu', 'boostify' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'no',
				'options'   => $this->get_all_submenu(),
				'condition' => array(
					'has_sub'  => 'yes',
					'sub_type' => 'mega',
				),
			)
		);

		$repeater->add_control(
			'menu_register',
			array(
				'label'     => __( 'Menu', 'boostify' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => $this->all_menu_site(),
				'condition' => array(
					'has_sub'  => 'yes',
					'sub_type' => 'default',
				),
			)
		);

		$repeater->add_control(
			'child_of',
			array(
				'label'       => __( 'Sub Menu Of', 'boostify' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'Enter parent menu name Ex: Home', 'boostify' ),
				'condition'   => array(
					'has_sub'  => 'yes',
					'sub_type' => 'default',
				),
			)
		);

		$this->add_control(
			'menu',
			array(
				'label'       => __( 'Menu', 'boostify' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ item_text }}}',
				'default'     => array(
					array(
						'item_text' => __( 'Menu Item 1', 'boostify' ),
					),
					array(
						'item_text' => __( 'Menu Item 2', 'boostify' ),
					),
				),
			)
		);

		$this->end_controls_section();
	}



	protected function add_control_mobile() {
		$this->start_controls_section(
			'mega_mobile',
			array(
				'label' => esc_html__( 'Menu Mobile', 'boostify' ),
			)
		);

		$this->add_control(
			'toggle_icon',
			array(
				'label'     => __( 'Toggle Icons', 'boostify' ),
				'type'      => \Elementor\Controls_Manager::ICON,
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

	public function add_control_menu_style() {
		$this->start_controls_section(
			'menu_style',
			array(
				'label' => __( 'Menu Style', 'boostify' ),
				'tab'   => Controls_Manager::TAB_STYLE,
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

	public function menu_style_nornal() {
		$this->add_control(
			'menucolor',
			array(
				'label'     => __( 'Menu Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'scheme'    => array(
					'type'  => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .boostify-menu > li > a' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'menu_typo',
				'label'    => __( 'Typography', 'boostify' ),
				'scheme'   => Elementor\Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .boostify-menu > li > a',
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
				),
			)
		);
	}

	public function menu_style_hover() {
		$this->add_control(
			'menu_color_hover',
			array(
				'label'     => __( 'Menu Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#aa3166',
				'scheme'    => array(
					'type'  => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .boostify-menu > li:hover > a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .boostify--hover-underline .boostify-menu > li:hover > a:after' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'menu_item_background_color_hover',
			array(
				'label'     => __( 'Background Item Hover', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#eee',
				'scheme'    => array(
					'type'  => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .boostify--hover-background .boostify-menu > li:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'menu_typo_hover',
				'label'    => __( 'Typography', 'boostify' ),
				'scheme'   => Elementor\Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .boostify-menu > li:hover > a',
			)
		);
	}

	public function add_control_submenu_style() {
		$this->start_controls_section(
			'submenu_style',
			array(
				'label' => __( 'Sub Menu Style', 'boostify' ),
				'tab'   => Controls_Manager::TAB_STYLE,
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
			'padding_submenu_item',
			array(
				'label'      => __( 'Padding', 'boostify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
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
				'scheme'    => array(
					'type'  => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .boostify-menu .sub-menu' => 'border-top-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Elementor\Group_Control_Background::get_type(),
			array(
				'name'     => 'background_submenu',
				'label'    => __( 'Background', 'boostify' ),
				'types'    => array( 'classic', 'gradient', 'video' ),
				'selector' => '.boostify-menu .sub-menu',
			)
		);

		$this->end_controls_section();
	}

	public function submenu_style_nornal() {
		$this->add_control(
			'color_submenu',
			array(
				'label'     => __( 'SubMenu Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'separator' => 'before',
				'scheme'    => array(
					'type'  => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .boostify-menu .menu-item-has-children .sub-menu a' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'submenu_typo',
				'label'    => __( 'Typography', 'boostify' ),
				'scheme'   => Elementor\Scheme_Typography::TYPOGRAPHY_1,
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
				'scheme'    => array(
					'type'  => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .boostify-menu li .sub-menu > li:hover > a'                                  => 'color: {{VALUE}}',
					'{{WRAPPER}} .boostify--hover-underline .boostify-menu li .sub-menu > li:hover > a:after' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .boostify-menu .sub-menu li.current-menu-item a'                                       => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'submenu_item_background_color_hover',
			array(
				'label'     => __( 'Background Submenu Item Hover', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#eee',
				'scheme'    => array(
					'type'  => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .boostify--hover-background .boostify-menu .sub-menu > li:hover' => 'background-color: {{VALUE}}',
				),
			)
		);
	}

	/**
	 * Get Sub Menu Mega
	 *
	 * @return List Sub Menu
	 */
	protected function get_all_submenu() {
		$args      = array(
			'post_type'           => 'btf_builder',
			'orderby'             => 'name',
			'order'               => 'ASC',
			'posts_per_page'      => -1,
			'ignore_sticky_posts' => 1,
			'meta_query'          => array(
				array(
					'key'     => 'bhf_type',
					'compare' => 'LIKE',
					'value'   => 'sub_menu',
				),
			),
		);
		$sub_menu  = new WP_Query( $args );
		$list_item = array(
			'no' => __( 'Select Sub Menu', 'boostify' ),
		);

		if ( $sub_menu->have_posts() ) {
			while ( $sub_menu->have_posts() ) {
				$sub_menu->the_post();
				$list_item[ get_the_ID() ] = get_the_title();
			}
			wp_reset_postdata();
		}

		return $list_item;
	}

	/**
	 * Get Sub Menu Mega
	 * @param int $post_id
	 * @return object $sub_menu
	 */
	protected function get_sub_mega_menu( $post_id ) {
		$args     = array(
			'p'         => $post_id,
			'post_type' => 'btf_builder',
		);
		$sub_menu = new WP_Query( $args );

		if ( $post_id && 'no' !== $post_id ) :
			?>
			<ul class="sub-menu sub-mega-menu">
			<?php
			echo do_shortcode( '[bhf id="' . $post_id . '" type="sub_menu"]' );

			?>
			</ul>
			<?php
		endif;
	}

	/**
	 * Get Sub Mega Menu Class
	 * @param int $post_id
	 * @return object $sub menu default layout
	 */
	protected function sub_menu_default( $menu_id, $child_of ) {
		$args = array(
			'menu'       => $menu_id,
			'level'      => 2,
			'child_of'   => $child_of,
			'menu_id'    => '',
			'menu_class' => 'sub-menu',
			'container'  => '',
		);
		wp_nav_menu( $args );
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

	/**
	 * Dissplay menu For Site
	 *
	 * @return template menu site
	 */
	protected function get_menu_site( $setting_menu ) {
		$id_menu         = wp_rand();
		$menu_item_class = 'menu-item menu-item-type-custom';
		?>
		<ul id="menu-<?php echo esc_attr( $id_menu ); ?>" class="menu boostify-mega-menu boostify-menu">

		<?php
		foreach ( $setting_menu as $menu ) {
			$sub_id        = (int) $menu['sub_menu'];
			$sub_type      = $menu['sub_type'];
			$menu_location = $menu['menu_register'];
			$child_of      = $menu['child_of'];
			$item_class    = '';

			if ( 'yes' === $menu['has_sub'] ) {
				if ( 'no' !== $menu['sub_menu'] ) {
					$item_class = ' menu-item-has-children current-menu-parent menu-item-has-mega';
				}
				if ( $child_of && $menu_location ) {
					$item_class = ' menu-item-has-children current-menu-parent';
				}
			}

			$classes = $menu_item_class . $item_class;
			?>
			<li class="<?php echo esc_attr( $classes ); ?>">
				<a href="<?php echo esc_url( $menu['link']['url'] ); ?>">
					<?php echo esc_html( $menu['item_text'] ); ?>
				</a>
				<?php
				if ( $menu['has_sub'] && 'mega' === $sub_type && 'no' !== $menu['sub_menu'] ) :
						$this->get_sub_mega_menu( $sub_id );
				elseif ( $menu['has_sub'] && $menu_location && $child_of ) :
						$this->sub_menu_default( $menu_location, $child_of );

				endif;
				?>
			</li>
			<?php
		}
		?>
		</ul>
		<?php
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
		?>
		<div class="boostify-navigation--widget">
			<nav class="boostify-nav-mega boostify-menu-layout- boostify-mega-menu boostify-main-navigation">
				<?php $this->get_menu_site( $settings['menu'] ); ?>
			</nav>
			<a href="#" class="boostify-menu-toggle" aria-expanded="false">
				<span class="menu-toggle-wrapper <?php echo esc_attr( $settings['toggle_icon'] ); ?>"></span><!-- .menu-toggle-wrapper -->

				<span class="screen-reader-text menu-toggle-text"><?php esc_html_e( 'Menu', 'wunderlust' ); ?></span>
			</a><!-- .menu-toggle -->

			<div class="boostify-menu-sidebar boostify--hover-">
				<div class="boostify-menu-sidebar--wrapper">

					<nav class="boostify-menu-dropdown" aria-label="<?php esc_attr_e( 'Dropdown navigation', 'boostify' ); ?>">
						<?php $this->get_menu_site( $settings['menu'] ); ?>
					</nav>
				</div>
			</div>
		</div>
		<?php
		echo class_exists( '\Elementor\Widget_Base' );
	}
}

