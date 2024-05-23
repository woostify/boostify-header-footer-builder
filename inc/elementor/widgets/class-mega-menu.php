<?php
/**
 * Mega Menu
 *
 * Elementor widget for Mega Menu.
 *
 * @package Boostify_Header_Footer
 * Author: ptp
 */

namespace Boostify_Header_Footer\Widgets;

use Boostify_Header_Footer\Nav_Menu;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;

/**
 * Mega Menu
 *
 * Elementor widget for Mega Menu.
 * Author: ptp
 */
class Mega_Menu extends Nav_Menu {

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
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-menu-bar';
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
	 * Retrieve the widget script.
	 *
	 * @access public
	 *
	 * @return array Widget script.
	 */
	public function get_script_depends() {
		return array( 'boostify_hf_nav_mega' );
	}

	/**
	 * Register Menu main Controls.
	 *
	 * @access protected
	 */
	protected function main_menu() {
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
				'type'      => Controls_Manager::SELECT,
				'default'   => 'default',
				'options'   => array(
					'default'   => __( 'Default', 'boostify' ),
					'container' => __( 'Container', 'boostify' ),
					'full'      => __( 'Full Width', 'boostify' ),
					'custom'    => __( 'Custom', 'boostify' ),
				),
				'condition' => array(
					'has_sub'   => 'yes',
					'sub_type'  => 'mega',
					'sub_menu!' => 'no',
				),
			)
		);

		$repeater->add_control(
			'width_custom',
			array(
				'label'      => __( 'Width Custom', 'boostify' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 200,
						'max'  => 1920,
						'step' => 1,
					),
				),
				'default'    => array(
					'size' => 500,
				),
				'condition'  => array(
					'sub_width' => 'custom',
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
				'default'   => 'flex-start',
				'selectors' => array(
					'{{WRAPPER}} .boostify-menu' => 'justify-content: {{VALUE}};',
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

		$this->end_controls_section();
	}

	/**
	 * Add Custom mobile menu controll
	 *
	 * @access protected
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
				'condition'    => array(
					'menu_change' => 'yes',
				),
			)
		);
	}

	/**
	 * Add icon menu controll
	 */
	protected function icon_menu_style() {
		$this->add_control(
			'icon_menu_color',
			array(
				'label'     => __( 'Icon Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .boostify-menu .menu-item-icon'       => 'color: {{VALUE}};',
					'{{WRAPPER}} .boostify-menu .menu-item-icon svg'   => 'fill: {{VALUE}};',
					'{{WRAPPER}} .boostify-menu .menu-item-icon svg g' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'icon_font_size',
			array(
				'label'      => __( 'Font Size', 'boostify' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'default'    => array(
					'size' => 14,
				),
				'selectors'  => array(
					'{{WRAPPER}} .boostify-menu .menu-item-icon'     => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .boostify-menu .menu-item-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
				),
			)
		);
	}

	/**
	 * Add icon menu hover controls
	 *
	 * @access protected
	 */
	protected function icon_menu_hover_style() {
		$this->add_control(
			'icon_menu_color_hover',
			array(
				'label'     => __( 'Icon Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .boostify-menu > li:hover .menu-item-icon'       => 'color: {{VALUE}};',
					'{{WRAPPER}} .boostify-menu > li:hover .menu-item-icon svg'   => 'fill: {{VALUE}};',
					'{{WRAPPER}} .boostify-menu > li:hover .menu-item-icon svg g' => 'fill: {{VALUE}};',
				),
			)
		);
	}


	/**
	 * Get Sub Menu Mega
	 *
	 * @access protected
	 * @param int $post_id | Sub menu builder ID.
	 */
	protected function get_sub_mega_menu( $post_id ) {
		$args     = array(
			'p'         => $post_id,
			'post_type' => 'btf_builder',
		);
		$sub_menu = new \WP_Query( $args );

		if ( $post_id && 'no' !== $post_id ) :
			?>
			<div class="sub-mega-menu sub-menu">
			<?php
			echo do_shortcode( '[bhf id="' . $post_id . '" type="sub_menu"]' );

			?>
			</div>
			<?php
		endif;
	}

	/**
	 * Menu icon class
	 *
	 * @access protected
	 * @param array $menu | menu setting.
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
			if ( ! empty( $menu['sub_width'] ) ) {
				$width = $menu['sub_width'];
				array_push( $menu_item_class, 'sub-width-' . $width );
			}
		}

		$classes = implode( ' ', $menu_item_class );

		return $classes;
	}

	/**
	 * Dissplay menu For Site
	 *
	 * @access protected
	 * @param array  $setting_menu | menu setting.
	 * @param string $classes | Custom class.
	 */
	protected function get_menu_site( $setting_menu, $classes = 'boostify-menu' ) {
		$id_menu = wp_rand();
		?>
		<ul id="menu-<?php echo esc_attr( $id_menu ); ?>" class="menu boostify-mega-menu <?php echo esc_attr( $classes ); ?>">

		<?php
		foreach ( $setting_menu as $menu ) {
			$icon          = $menu['icon'];
			$sub_id        = (int) $menu['sub_menu'];
			$sub_type      = $menu['sub_type'];
			$menu_location = $menu['menu_register'];
			$child_of      = $menu['child_of'];
			$custom_width  = $menu['width_custom'];
			$item_class    = '';
			$classes       = $this->menu_item_class( $menu );
			$attributes    = ( ! empty( $custom_width ) && isset( $custom_width['size'] ) ) ? 'data-custom-width="' . sanitize_text_field( $custom_width['size'] ) . '"' : '';
			?>
			<li class="<?php echo esc_attr( $classes ); ?>" <?php echo esc_attr( $attributes ); ?>>

				<a href="<?php echo esc_url( $menu['link']['url'] ); ?>">
					<span class="menu-item-main-info">
						<?php
						if ( ! empty( $icon['value'] ) ) :
							if ( is_string( $icon['value'] ) ) :
								?>
								<span class="menu-item-icon <?php echo esc_attr( $icon['value'] ); ?>"></span>
							<?php else : ?>
								<span class="menu-item-icon menu-item-icon-svg">
									<?php Icons_Manager::render_icon( $icon, array( 'aria-hidden' => 'true' ) ); ?>
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
				if ( $menu['has_sub'] && 'mega' === $sub_type && 'no' !== $menu['sub_menu'] ) :
						$this->get_sub_mega_menu( $sub_id );
				elseif ( $menu['has_sub'] && $menu_location ) :
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
		$settings  = $this->get_settings_for_display();
		$arg_class = $this->nav_class();
		$classes   = implode( ' ', $arg_class );
		?>
		<div class="boostify-navigation--widget">
			<nav class="<?php echo esc_attr( $classes ); ?>">
				<?php $this->get_menu_site( $settings['menu'] ); ?>
			</nav>
			<?php $this->get_toggle( $settings['toggle_icon'] ); ?>

			<div class="boostify-menu-sidebar boostify--hover-<?php echo esc_attr( $settings['pointer'] ); ?>">
				<div class="boostify-menu-sidebar--wrapper">

					<nav class="boostify-menu-dropdown" aria-label="<?php esc_attr_e( 'Dropdown navigation', 'boostify' ); ?>">
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

					<?php do_action( 'boostify_hf_sidebar_nav_bottom' ); ?>
				</div>
			</div>
		</div>
		<div class="boostify-overlay">
			<a href="#" class="boostify--close-menu-side-bar ion-android-close"></a>
		</div>
		<?php
	}
}
