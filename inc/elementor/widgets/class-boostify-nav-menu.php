<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

/**
 * Boostify Nav Menu
 *
 * Elementor widget for hello world.
 */
class Boostify_Nav_Menu extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'ht-hf-nav-menu';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Nav Menu', 'boostify' );
	}

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

	public function get_script_depends() {
		return array( 'boostify_hf_nav_menu' );
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 */
	protected function _register_controls() {

		$this->main_menu();

		$this->mobile_menu();

		$this->menu_style();

		$this->submenu_style();

		$this->menu_sidebar_style();

	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="boostify-navigation--widget">
			<nav class="boostify-main-navigation boostify-nav boostify-header-navigation <?php echo esc_attr( $settings['menu'] . ' boostify--hover-' . esc_attr( $settings['pointer'] ) ); ?>" aria-label="<?php esc_attr_e( 'Primary navigation', 'boostify' ); ?>">
				<?php
				if ( 'none' !== $settings['menu'] ) {
					$args = [
						'menu'           => $settings['menu'],
						'container'      => '',
						'menu_class'     => 'boostify-menu',
						'theme_location' => 'primary',
					];

					wp_nav_menu( $args );
				} elseif ( is_user_logged_in() ) {
					?>
					<a class="add-menu" href="<?php echo esc_url( get_admin_url() . 'nav-menus.php' ); ?>"><?php esc_html_e( 'Add a Primary Menu', 'boostify' ); ?></a>
				<?php } ?>
			</nav>

			<a href="#" class="boostify-menu-toggle" aria-controls="primary-menu" aria-expanded="false">
				<span class="menu-toggle-wrapper <?php echo esc_attr( $settings['toggle_icon'] ); ?>"></span><!-- .menu-toggle-wrapper -->

				<span class="screen-reader-text menu-toggle-text"><?php esc_html_e( 'Menu', 'wunderlust' ); ?></span>
			</a><!-- .menu-toggle -->

			<div class="boostify-menu-sidebar boostify--hover-<?php echo esc_attr( $settings['pointer'] ); ?>">
				<div class="boostify-menu-sidebar--wrapper">
					<?php if ( 'yes' == $settings['logo'] ) : ?>
						<div class="logo-sidebar">
							<?php
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
							?>
						</div>
					<?php endif ?>

					<?php
					if ( 'yes' == $settings['search'] ) :
						do_action( 'boostify_hf_seach_form' );
					endif;
					?>


					<nav class="boostify-menu-dropdown <?php echo esc_attr( $settings['menu'] ); ?>" aria-label="<?php esc_attr_e( 'Dropdown navigation', 'boostify' ); ?>">
						<?php
						if ( 'none' !== $settings['menu'] ) {
							$args = [
								'menu'           => $settings['menu'],
								'container'      => '',
								'menu_class'     => 'boostify-dropdown-menu',
								'theme_location' => 'primary',
							];

							wp_nav_menu( $args );
						} elseif ( is_user_logged_in() ) {
							?>
							<a class="add-menu" href="<?php echo esc_url( get_admin_url() . 'nav-menus.php' ); ?>"><?php esc_html_e( 'Add a Primary Menu', 'boostify' ); ?></a>
						<?php } ?>
					</nav>
				</div>
			</div>
		</div>
		<div class="boostify-overlay">
			<a href="#" class="boostify--close-menu-side-bar ion-android-close"></a>
		</div>
		<?php
	}

	public function get_all_nav_active() {
		$menus   = wp_get_nav_menus();
		$options = [];

		foreach ( $menus as $menu ) {
			$options[ $menu->slug ] = $menu->name;
		}

		return $options;
	}


	public function main_menu() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Layout', 'boostify' ),
			)
		);

		$this->add_control(
			'menu',
			array(
				'label'        => esc_html__( 'Menu', 'boostify' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => $this->get_all_nav_active(),
				'save_default' => true,
				'description'  => sprintf( __( 'Go to the <a href="%s" target="_blank">Menus screen</a> to manage your menus.', 'boostify' ), admin_url( 'nav-menus.php' ) ),
			)
		);

		$this->add_control(
			'align',
			[
				'label'     => esc_html__( 'Align', 'boostify' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'flex-start',
				'selectors' => [
					'{{WRAPPER}} .boostify-menu' => 'justify-content: {{VALUE}};',
				],
				'options'   => [
					'flex-start' => [
						'icon'  => 'eicon-h-align-left',
						'title' => 'Left',
					],
					'center'     => [
						'icon'  => 'eicon-h-align-center',
						'title' => 'Center',
					],
					'flex-end'   => [
						'icon'  => 'eicon-h-align-right',
						'title' => 'Right',
					],
				],
			]
		);

		$this->add_control(
			'pointer',
			[
				'label'   => __( 'Pointer', 'boostify' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'background' => 'Background',
					'underline'  => 'Underline',
					'none'       => 'None',
				],

			]
		);

		$this->add_control(
			'submenu_heading',
			[
				'label'     => __( 'Sub Menu', 'boostify' ),
				'type'      => Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'border_item_submenu',
				'label'    => __( 'Border Item', 'boostify' ),
				'selector' => '{{WRAPPER}} .boostify-menu .sub-menu li:not( :last-child )',
			]
		);

		$this->end_controls_section();
	}


	public function mobile_menu() {

		$this->start_controls_section(
			'section_mobile_menu',
			array(
				'label' => esc_html__( 'Mobile Menu', 'boostify' ),
			)
		);

		$this->add_control(
			'logo',
			[
				'label'        => __( 'Show Logo', 'boostify' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'boostify' ),
				'label_off'    => __( 'Hide', 'boostify' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'logo-align',
			[
				'label'     => esc_html__( 'Toggle Align', 'boostify' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'left',
				'condition' => [
					'logo' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .logo-sidebar' => 'text-align: {{VALUE}};',
				],
				'options'   => [
					'left'   => [
						'icon'  => 'eicon-h-align-left',
						'title' => 'Top',
					],
					'center' => [
						'icon'  => 'eicon-h-align-center',
						'title' => 'Center',
					],
					'right'  => [
						'icon'  => 'eicon-h-align-right',
						'title' => 'Bottom',
					],
				],
			]
		);

		$this->add_control(
			'search',
			[
				'label'        => __( 'Show Search', 'boostify' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'boostify' ),
				'label_off'    => __( 'Hide', 'boostify' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'toggle_icon',
			[
				'label'     => __( 'Toggle Icons', 'boostify' ),
				'type'      => \Elementor\Controls_Manager::ICON,
				'separator' => 'before',
				'default'   => 'ion-android-menu',
				'include'   => [
					'fa fa-navicon',
					'ion-navicon',
					'ion-navicon-round',
					'ion-android-menu',
				],
			]
		);

		$this->add_control(
			'toggle-align',
			[
				'label'     => esc_html__( 'Toggle Align', 'boostify' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'right',
				'selectors' => [
					'{{WRAPPER}} .boostify-menu-toggle' => 'text-align: {{VALUE}};',
				],
				'options'   => [
					'left'   => [
						'icon'  => 'eicon-h-align-left',
						'title' => 'Top',
					],
					'center' => [
						'icon'  => 'eicon-h-align-center',
						'title' => 'Center',
					],
					'right'  => [
						'icon'  => 'eicon-h-align-right',
						'title' => 'Bottom',
					],
				],
			]
		);

		$this->end_controls_section();
	}

	public function menu_style() {
		$this->start_controls_section(
			'menu_style',
			[
				'label' => __( 'Menu Style', 'boostify' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs(
			'menu_style_tabs'
		);

		$this->start_controls_tab(
			'style_nemu_normal_tab',
			[
				'label' => __( 'Normal', 'boostify' ),
			]
		);

		$this->menu_style_nornal();

		$this->end_controls_tab();

		$this->start_controls_tab(
			'style_menu_hover_tab',
			[
				'label' => __( 'Hover', 'boostify' ),
			]
		);

		$this->menu_style_hover();

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	public function menu_style_nornal() {
		$this->add_control(
			'menucolor',
			[
				'label'     => __( 'Menu Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'scheme'    => [
					'type'  => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .boostify-menu > li > a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'menu_typo',
				'label'    => __( 'Typography', 'boostify' ),
				'scheme'   => Elementor\Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .boostify-menu > li > a',
			]
		);
	}

	public function menu_style_hover() {
		$this->add_control(
			'menu_color_hover',
			[
				'label'     => __( 'Menu Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#aa3166',
				'scheme'    => [
					'type'  => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .boostify-menu > li:hover > a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .boostify--hover-underline .boostify-menu > li:hover > a:after' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'menu_item_background_color_hover',
			[
				'label'     => __( 'Background Item Hover', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#eee',
				'scheme'    => [
					'type'  => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .boostify--hover-background .boostify-menu > li:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'menu_typo_hover',
				'label'    => __( 'Typography', 'boostify' ),
				'scheme'   => Elementor\Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .boostify-menu > li:hover > a',
			]
		);
	}

	public function submenu_style() {
		$this->start_controls_section(
			'submenu_style',
			[
				'label' => __( 'Sub Menu Style', 'boostify' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'dropdown_top_distance',
			[
				'label'     => __( 'Distance', 'boostify' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .boostify-menu>.menu-item-has-children>.boostify-menu-child' => 'padding-top: {{SIZE}}{{UNIT}} !important',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'padding_submenu_item',
			[
				'label'      => __( 'Padding', 'boostify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default'    => [
					'top'    => 15,
					'bottom' => 15,
					'left'   => 20,
					'right'  => 20,
				],
				'selectors'  => [
					'{{WRAPPER}} .boostify-menu .sub-menu a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->start_controls_tabs(
			'submenu_style_tabs'
		);

		$this->start_controls_tab(
			'style_submemu_normal_tab',
			[
				'label' => __( 'Normal', 'boostify' ),
			]
		);

		$this->submenu_style_nornal();

		$this->end_controls_tab();

		$this->start_controls_tab(
			'style_submenu_hover_tab',
			[
				'label' => __( 'Hover', 'boostify' ),
			]
		);

		$this->submenu_style_hover();

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'color_submenu_border',
			[
				'label'     => __( 'SubMenu Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#d1346f',
				'separator' => 'before',
				'scheme'    => [
					'type'  => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .boostify-menu .sub-menu' => 'border-top-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Elementor\Group_Control_Background::get_type(),
			[
				'name'     => 'background_submenu',
				'label'    => __( 'Background', 'boostify' ),
				'types'    => [ 'classic', 'gradient', 'video' ],
				'selector' => '.boostify-menu .sub-menu',
			]
		);

		$this->end_controls_section();
	}

	public function submenu_style_nornal() {
		$this->add_control(
			'color_submenu',
			[
				'label'     => __( 'SubMenu Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'separator' => 'before',
				'scheme'    => [
					'type'  => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .boostify-menu .menu-item-has-children .sub-menu a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'submenu_typo',
				'label'    => __( 'Typography', 'boostify' ),
				'scheme'   => Elementor\Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .boostify-menu .menu-item-has-children .sub-menu a',
			]
		);
	}

	public function submenu_style_hover() {
		$this->add_control(
			'color_submenu_hover',
			[
				'label'     => __( 'SubMenu Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#aa3166',
				'separator' => 'before',
				'scheme'    => [
					'type'  => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .boostify-menu li .sub-menu > li:hover > a'                                  => 'color: {{VALUE}}',
					'{{WRAPPER}} .boostify--hover-underline .boostify-menu li .sub-menu > li:hover > a:after' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'submenu_item_background_color_hover',
			[
				'label'     => __( 'Background Submenu Item Hover', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#eee',
				'scheme'    => [
					'type'  => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .boostify--hover-background .boostify-menu .sub-menu > li:hover' => 'background-color: {{VALUE}}',
				],
			]
		);
	}

	public function menu_sidebar_style() {
		$this->start_controls_section(
			'menu_sidebar_style',
			[
				'label' => __( 'Menu Mobie Style', 'boostify' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs(
			'menu_sidebar_style_tabs'
		);

		$this->start_controls_tab(
			'style_memu_sidebar_normal_tab',
			[
				'label' => __( 'Normal', 'boostify' ),
			]
		);

		$this->menu_sidebar_style_nornal();

		$this->end_controls_tab();

		$this->start_controls_tab(
			'style_menu_sidebar_hover_tab',
			[
				'label' => __( 'Hover', 'boostify' ),
			]
		);

		$this->menu_sidebar_style_hover();

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	public function menu_sidebar_style_nornal() {
		$this->add_control(
			'color_toggle',
			[
				'label'     => __( 'Toggle Icon Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'separator' => 'before',
				'scheme'    => [
					'type'  => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .boostify-menu-toggle' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'color_menu_sidebar',
			[
				'label'     => __( 'SubMenu Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'separator' => 'before',
				'scheme'    => [
					'type'  => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .boostify-menu-sidebar .boostify-dropdown-menu a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'menu_sidebar_typo',
				'label'    => __( 'Typography', 'boostify' ),
				'scheme'   => Elementor\Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .boostify-menu-sidebar .boostify-dropdown-menu a',
			]
		);
	}

	public function menu_sidebar_style_hover() {
		$this->add_control(
			'color_toggle_hover',
			[
				'label'     => __( 'Toggle Icon Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'separator' => 'before',
				'scheme'    => [
					'type'  => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .boostify-menu-toggle:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'color_menu_sidebar_hover',
			[
				'label'     => __( 'SubMenu Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#d1346f',
				'separator' => 'before',
				'scheme'    => [
					'type'  => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .boostify-menu-sidebar .boostify-dropdown-menu li:hover > a' => 'color: {{VALUE}}',
				],
			]
		);

	}

}
