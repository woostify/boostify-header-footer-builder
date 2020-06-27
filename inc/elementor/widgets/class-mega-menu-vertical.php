<?php
/**
 * Mega Menu Vertical
 *
 * @package boostify
 */

namespace Boostify_Header_Footer\Widgets;

use Boostify_Header_Footer\Nav_Menu;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

/**
 * Mega Menu Vertical
 *
 * Elementor widget for Mega Menu Vertical.
 */
class Mega_Menu_Vertical extends Nav_Menu {

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
		return 'ht-hf-mega-menu-vertical';
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
		return __( 'Mega Menu Vertical', 'boostify' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-menu-toggle';
	}

	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return array( 'boostify_hf_mega_menu_vertical' );
	}

	/**
	 * Main menu
	 */
	protected function main_menu() {
		$this->start_controls_section(
			'section_title',
			array(
				'label' => __( 'Mega Menu Vertical', 'boostify' ),
			)
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'item_text',
			array(
				'label'       => __( 'Title', 'boostify' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Menu Item', 'boostify' ),
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'icon',
			array(
				'label' => __( 'Icons', 'boostify' ),
				'type'  => Controls_Manager::ICONS,
			)
		);

		$repeater->add_control(
			'link',
			array(
				'label'         => __( 'Link', 'boostify' ),
				'type'          => Controls_Manager::URL,
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
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'boostify' ),
				'label_off'    => __( 'No', 'boostify' ),
				'return_value' => 'yes',
			)
		);

		$repeater->add_control(
			'sub_type',
			array(
				'label'     => __( 'Sub Menu Type', 'boostify' ),
				'type'      => Controls_Manager::SELECT,
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
				'type'      => Controls_Manager::SELECT,
				'default'   => 'no',
				'options'   => boostify_header_footer_sub_menu(),
				'condition' => array(
					'has_sub'  => 'yes',
					'sub_type' => 'mega',
				),
			)
		);

		$repeater->add_control(
			'sub_width',
			array(
				'label'     => __( 'Width', 'boostify' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 270,
						'max' => 1110,
					),
				),
				'default'   => array(
					'size' => 270,
				),
				'selectors' => array(
					'{{WRAPPER}} .boostify-menu .boostify-menu-child .sub-menu.sub-mega-menu' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'has_sub'   => 'yes',
					'sub_type'  => 'mega',
					'sub_menu!' => 'no',
				),
			)
		);

		$repeater->add_control(
			'icon_space_megamenu',
			array(
				'label'     => __( 'Icon Position', 'boostify' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 110,
					),
				),
				'default'   => array(
					'size' => 100,
				),
				'selectors' => array(
					'{{WRAPPER}} .boostify-mega-menu-vertical--widget .boostify-mega-menu-vetical .menu-item-has-mega > a:after' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'has_sub'   => 'yes',
					'sub_type'  => 'mega',
					'sub_menu!' => 'no',
				),
			)
		);

		$repeater->add_control(
			'menu_register',
			array(
				'label'     => __( 'Menu', 'boostify' ),
				'type'      => Controls_Manager::SELECT,
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
				'description' => esc_html__( 'Enter the name of the menu item you want to get the sub menu', 'boostify' ),
			)
		);

		$this->add_control(
			'menu',
			array(
				'label'       => __( 'Menu', 'boostify' ),
				'type'        => Controls_Manager::REPEATER,
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

		$this->add_control(
			'text_button',
			array(
				'label'       => esc_html__( 'Text Button', 'boostify' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Shop Departments', 'boostify' ),
				'default'     => esc_html__( 'Shop Departments', 'boostify' ),
			)
		);

		$this->add_control(
			'icon_position',
			array(
				'label'   => __( 'Icon Position', 'boostify' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => array(
					'vertical'   => 'Vertical',
					'horizontal' => 'Horizontal',
				),
			)
		);

		$this->add_control(
			'icon_space_horizontal',
			array(
				'label'      => __( 'Icon Space', 'boostify' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'condition'  => array(
					'icon_position' => 'horizontal',
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 60,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .boostify-icon-horizontal .boostify-menu > li .menu-item-icon' => 'padding: 0 {{SIZE}}{{UNIT}} 0 0;',
				),
			)
		);

		$this->add_control(
			'icon_space_vertical',
			array(
				'label'      => __( 'Icon Space', 'boostify' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'condition'  => array(
					'icon_position_' => 'vertical',
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 60,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .boostify-icon-vertical .boostify-menu > li .menu-item-icon' => 'padding: 0 0 {{SIZE}}{{UNIT}} 0;',
				),
			)
		);

		$this->add_control(
			'align',
			array(
				'label'     => esc_html__( 'Align', 'boostify' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .boostify-menu > li' => 'justify-content: {{VALUE}};',
				),
				'options'   => array(
					'flex-start' => array(
						'icon'  => 'eicon-h-align-left',
						'title' => 'Left',
					),
					'center'     => array(
						'icon'  => 'eicon-h-align-center',
						'title' => 'Center',
					),
					'flex-end'   => array(
						'icon'  => 'eicon-h-align-right',
						'title' => 'Right',
					),
				),
			)
		);

		$this->add_control(
			'pointer',
			array(
				'label'   => __( 'Pointer', 'boostify' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => array(
					'background' => 'Background',
					'underline'  => 'Underline',
					'overline'   => 'Overline',
					'none'       => 'None',
				),
			)
		);

		$this->add_control(
			'show_active',
			array(
				'label'        => esc_html__( 'Show Menu Vertical', 'boostify' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => esc_html__( 'No', 'boostify' ),
				'label_off'    => esc_html__( 'Yes', 'boostify' ),
				'return_value' => 'yes',
			)
		);

		$this->add_responsive_control(
			'width_button',
			array(
				'label'     => esc_html__( 'Width', 'boostify' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 270,
						'max' => 750,
					),
				),
				'default'   => array(
					'size' => 270,
				),
				'selectors' => array(
					'{{WRAPPER}} .boostify-mega-menu-vertical--widget,{{WRAPPER}} .boostify-menu > li > a' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .boostify-mega-menu-vertical--widget .boostify-mega-menu-vetical .sub-mega-menu' => 'left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Add Custom mobile menu controll
	 */
	protected function custom_mobile_menu() {
		$this->add_control(
			'menu_change',
			array(
				'label'        => __( 'Use Menu Site', 'boostify' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'boostify' ),
				'label_off'    => __( 'No', 'boostify' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'menu_mobile',
			array(
				'label'        => esc_html__( 'Menu Mobile', 'boostify' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => $this->all_menu_site(),
				'save_default' => true,
				'default'      => 'no',
			)
		);
	}


	/**
	 * Get Sub Menu Mega
	 *
	 * @param int $post_id type.
	 */
	protected function get_sub_mega_menu( $post_id ) {
		$args     = array(
			'p'         => $post_id,
			'post_type' => 'btf_builder',
		);
		$sub_menu = new \WP_Query( $args );

		if ( $post_id && 'no' !== $post_id ) :
			?>
			<ul class="sub-menu sub-mega-menu">
				<?php echo do_shortcode( '[bhf id="' . $post_id . '" type="sub_menu"]' ); ?>
			</ul>
			<?php
		endif;
	}

	/**
	 * Menu item class
	 *
	 * @param int $menu type.
	 */
	protected function menu_item_class( $menu ) {
		$icon            = $menu['icon'];
		$menu_location   = $menu['menu_register'];
		$sub_type        = $menu['sub_type'];
		$menu_item_class = array(
			'menu-item',
			'menu-item-type-custom',
		);

		if ( 'yes' === $menu['has_sub'] ) {

			if ( 'no' !== $menu['sub_menu'] && 'mega' === $sub_type ) {
				$item_class = ' menu-item-has-children current-menu-parent menu-item-has-mega';
				array_push( $menu_item_class, 'menu-item-has-children', 'menu-item-has-mega' );
			}
			if ( $menu_location && 'default' === $sub_type ) {
				array_push( $menu_item_class, 'menu-item-has-children' );
			}
			if ( ! empty( $icon['value'] ) ) {
				array_push( $menu_item_class, 'menu-item-has-icon' );
			}
		}

		$classes = implode( ' ', $menu_item_class );

		return $classes;
	}

	/**
	 * Display menu For Site
	 *
	 * @param  [type] $setting_menu type.
	 * @param  string $classes type.
	 */
	protected function get_menu_site( $setting_menu, $classes = 'boostify-menu' ) {
		$id_menu = wp_rand();
		?>
		<ul id="menu-<?php echo esc_attr( $id_menu ); ?>" class="menu boostify-mega-menu boostify-mega-menu-vetical <?php echo esc_attr( $classes ); ?>">

		<?php
		foreach ( $setting_menu as $menu ) {
			$icon          = $menu['icon'];
			$sub_id        = (int) $menu['sub_menu'];
			$sub_type      = $menu['sub_type'];
			$menu_location = $menu['menu_register'];
			$child_of      = $menu['child_of'];
			$item_class    = '';
			$classes       = $this->menu_item_class( $menu );
			?>
			<li class="<?php echo esc_attr( $classes ); ?>">

				<a href="<?php echo esc_url( $menu['link']['url'] ); ?>">
					<span class="menu-item-main-info">
						<?php
						if ( ! empty( $icon['value'] ) ) :
							if ( is_string( $icon['value'] ) ) :
								?>
								<span class="menu-item-icon <?php echo esc_attr( $icon['value'] ); ?>"></span>
							<?php else : ?>
								<span class="menu-item-icon menu-item-icon-svg">
									<img src="<?php echo esc_url( $icon['value']['url'] ); ?>" alt="<?php echo esc_attr__( 'Icon ' . $menu['item_text'], 'boostify' ); //phpcs:ignore ?>"> 
								</span>
								<?php
							endif;
						endif;
						?>

						<span class="menu-item-text">
							<?php echo esc_html( $menu['item_text'] ); ?>
						</span>
					</span>
				</a>
				<?php
				if ( $menu['has_sub'] && 'mega' === $sub_type && 'no' !== $menu['sub_menu'] ) {
					$this->get_sub_mega_menu( $sub_id );
				} elseif ( $menu['has_sub'] && $menu_location ) {
					$this->sub_menu_default( $menu_location, $child_of );
				}
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
		$settings          = $this->get_settings_for_display();
		$arg_class         = $this->nav_class();
		$classes           = implode( ' ', $arg_class );
		$show_sub_vertical = '';

		if ( 'yes' === $settings['show_active'] ) {
			$show_sub_vertical = 'show';
		}
		?>
		<div class="boostify-mega-menu-vertical--widget">
			<button class="boostify-menu-toggle-vertical">
				<?php echo esc_html( $settings['text_button'] ); ?>
				<i class="fa fa-caret-down"></i>
			</button>
			<nav class="<?php echo esc_attr( $classes ); ?> <?php echo esc_attr( $show_sub_vertical ); ?>">
				<?php $this->get_menu_site( $settings['menu'] ); ?>
			</nav>
			<?php $this->get_toggle( $settings['toggle_icon'] ); ?>

			<div class="boostify-menu-sidebar boostify--hover-<?php echo esc_attr( $settings['pointer'] ); ?>">
				<div class="boostify-menu-sidebar--wrapper">

					<nav class="site-vertical-menu boostify-menu-dropdown" aria-label="<?php esc_attr_e( 'Dropdown navigation', 'boostify' ); ?>">
						<?php
						if ( 'yes' === $settings['menu_change'] && 'no' !== $settings['menu_mobile'] ) {
								$args = array(
									'menu'       => $settings['menu_mobile'],
									'menu_id'    => '',
									'menu_class' => 'boostify-dropdown-menu',
									'container'  => '',
								);
								wp_nav_menu( $args );
						} else {
							$this->get_menu_site( $settings['menu'], 'boostify-dropdown-menu' );
						}
						?>
					</nav>
				</div>
			</div>
		</div>
		<div class="boostify-overlay">
			<a href="#" class="boostify--close-menu-side-bar ion-android-close"></a>
		</div>
		<?php
	}
}
