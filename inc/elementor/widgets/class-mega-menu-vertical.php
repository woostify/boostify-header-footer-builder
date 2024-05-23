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
use \Elementor\Group_Control_Border;
use Elementor\Icons_Manager;

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
		return __( 'Vertical Menu Button', 'boostify' );
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
				'label'     => __( 'Width', 'boostify' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 270,
						'max' => 1110,
					),
				),
				'default'   => array(
					'size' => 918,
				),
				'condition' => array(
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

		$this->end_controls_section();
	}

	/**
	 * Icon menu style
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
					'{{WRAPPER}} .boostify-menu .menu-item-icon svg' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);
	}

	/**
	 * Icon menu hover style
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
			<div class="sub-menu sub-mega-menu">
				<?php echo do_shortcode( '[bhf id="' . $post_id . '" type="sub_menu"]' ); ?>
			</div>
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
			if ( ! empty( $menu['sub_width'] ) ) {
				$width = $menu['sub_width'];
				array_push( $menu_item_class, 'sub-width-' . $width );
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
	 * Add Custom Menu Style Menu Button Vertical
	 */
	protected function custom_button_vertical() {
		$this->start_controls_section(
			'menu_button_vertical',
			array(
				'label' => esc_html__( 'Button Vertical', 'boostify' ),
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
			'button_toggle_icon',
			array(
				'label' => __( 'Toggle Icons', 'boostify' ),
				'type'  => Controls_Manager::ICONS,
			)
		);

		$this->add_control(
			'icon_vertical_position',
			array(
				'label'   => __( 'Icon Position', 'boostify' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => array(
					'left'  => 'Left',
					'right' => 'Right',
				),
			)
		);

		$this->add_control(
			'icon_vertical_right_space',
			array(
				'label'      => __( 'Icon Space', 'boostify' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'condition'  => array(
					'icon_vertical_position' => 'right',
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 60,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .boostify-menu-toggle-vertical .icon-vertical' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'icon_vertical_left_space',
			array(
				'label'      => __( 'Icon Space', 'boostify' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'condition'  => array(
					'icon_vertical_position' => 'left',
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 60,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .boostify-menu-toggle-vertical .icon-vertical' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'align_button_vertical',
			array(
				'label'   => __( 'Alignment', 'boostify' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'left'    => array(
						'title' => __( 'Left', 'boostify' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => __( 'Center', 'boostify' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => __( 'Right', 'boostify' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => __( 'Justified', 'boostify' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'default' => '',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Custom Style Menu Vertical Vertical
	 */
	protected function custon_style() {
		$this->start_controls_section(
			'button_vertical_style',
			array(
				'label' => __( 'Style Button Vertical', 'boostify' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typo',
				'selector' => '{{WRAPPER}} .boostify-mega-menu-vertical--widget .boostify-menu-toggle-vertical',
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .boostify-mega-menu-vertical--widget .boostify-menu-toggle-vertical' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'margin_button',
			array(
				'label'      => esc_html__( 'Space', 'boostify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .boostify-mega-menu-vertical--widget .boostify-menu-toggle-vertical' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'svgwidth_button',
			array(
				'label'     => esc_html__( 'Width SVG', 'boostify' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'   => array(
					'size' => 0,
				),
				'selectors' => array(
					'{{WRAPPER}} .boostify-mega-menu-vertical--widget .custon-svg-button-vertical' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'boostify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .boostify-mega-menu-vertical--widget .boostify-menu-toggle-vertical' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'border_vertical_button',
				'label'    => esc_html__( 'Border Button Vertical', 'boostify' ),
				'selector' => '{{WRAPPER}} .boostify-menu-toggle-vertical',
			)
		);

		$this->add_control(
			'bg_title_color',
			array(
				'label'     => esc_html__( 'Background Title Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fc5a34',
				'selectors' => array(
					'{{WRAPPER}} .boostify-mega-menu-vertical--widget .boostify-menu-toggle-vertical' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Custom Style Menu Vertical
	 */
	protected function custon_style2() {
		$this->start_controls_section(
			'menu_vertical_style',
			array(
				'label' => __( 'Style Mega Menu Vertical', 'boostify' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'width_megamenu',
			array(
				'label'     => esc_html__( 'Width Mega Menu', 'boostify' ),
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
					'{{WRAPPER}} .boostify-mega-menu-vertical--widget .boostify-mega-menu-vetical,{{WRAPPER}} .boostify-menu > li > a' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'position_megamenu',
			array(
				'label'     => esc_html__( 'Position Mega Menu', 'boostify' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 270,
				),
				'selectors' => array(
					'{{WRAPPER}} .boostify-mega-menu-vertical--widget .boostify-mega-menu-vetical .sub-mega-menu' => 'left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .boostify-mega-menu-vertical--widget .boostify-mega-menu-vetical .sub-menu' => 'min-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'submenu_vertical_space',
			array(
				'label'     => esc_html__( 'Space Top', 'boostify' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 30,
					),
				),
				'default'   => array(
					'size' => 0,
				),
				'selectors' => array(
					'{{WRAPPER}} .boostify-mega-menu-vertical--widget .boostify-mega-menu-vetical' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'padding_submenu_vertical',
			array(
				'label'     => __( 'Space Top & Bottom Submenu', 'boostify' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'default'   => array(
					'size' => 0,
				),
				'selectors' => array(
					'{{WRAPPER}} .boostify-mega-menu-vetical > .menu-item-has-mega:first-child > a' => 'padding-top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .boostify-mega-menu-vetical > .menu-item:first-child > a' => 'padding-top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .boostify-mega-menu-vetical > .menu-item-has-mega:last-child > a' => 'padding-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .boostify-mega-menu-vetical > .menu-item:last-child > a' => 'padding-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'border_radius',
			array(
				'label'      => __( 'Border Radius', 'boostify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .boostify-mega-menu-vertical--widget .boostify-mega-menu-vetical' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'bg_color_menu',
			array(
				'label'     => esc_html__( 'Background Menu Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .boostify-mega-menu-vertical--widget .boostify-mega-menu-vetical' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'padding-box-menu',
			array(
				'label'      => esc_html__( 'Padding Box menu', 'boostify' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .boostify-mega-menu-vertical--widget .boostify-mega-menu-vetical' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'bg_color_menu_item',
			array(
				'label'     => esc_html__( 'Background Item Color', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .boostify-mega-menu-vertical--widget .boostify-mega-menu-vetical > li' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'bg_color_menu_item_hover',
			array(
				'label'     => esc_html__( 'Background Color hover', 'boostify' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ccc',
				'selectors' => array(
					'{{WRAPPER}} .boostify-mega-menu-vertical--widget .boostify-mega-menu-vetical > li:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'border_subitem',
			array(
				'label'      => __( 'Width', 'boostify' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 20,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .boostify-mega-menu-vertical--widget .boostify-mega-menu-vetical > li:not(:last-child)' => 'border-bottom-width: {{SIZE}}{{UNIT}}; border-bottom-style: solid;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'border_menu',
				'selector' => '.boostify-mega-menu-vetical',
			)
		);

		$this->add_control(
			'border_item_color',
			array(
				'label'     => __( 'Border Item Color', 'boostify' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .boostify-mega-menu-vertical--widget .boostify-mega-menu-vetical > li:not(:last-child)' => 'border-bottom: 1px solid {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Add Custom mobile menu controll
	 */
	protected function custom_mobile_menu() {
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
		$icon              = $settings['button_toggle_icon'];

		if ( 'yes' === $settings['show_active'] ) {
			$show_sub_vertical = 'show';
		}
		?>
		<div class="boostify-mega-menu-vertical--widget elementor-align-<?php echo esc_html( $settings['align_button_vertical'] ); ?>">
			<button class="boostify-menu-toggle-vertical">
				<?php
				if ( 'left' === $settings['icon_vertical_position'] ) {
					if ( ! empty( $icon['value'] ) ) :
						if ( is_string( $icon['value'] ) ) :
							?>
							<span class="icon-vertical menu-item-icon <?php echo esc_attr( $icon['value'] ); ?>"></span>
						<?php else : ?>
							<span class="icon-vertical menu-item-icon menu-item-icon-svg">
								<img src="<?php echo esc_url( $icon['value']['url'] ); ?>" alt="icon-vertical-button" class="custon-svg-button-vertical">
							</span>
							<?php
						endif;
					endif;
				}

				echo esc_html( $settings['text_button'] );

				if ( 'right' === $settings['icon_vertical_position'] ) {
					if ( ! empty( $icon['value'] ) ) :
						if ( is_string( $icon['value'] ) ) :
							?>
							<span class="icon-vertical menu-item-icon <?php echo esc_attr( $icon['value'] ); ?>"></span>
						<?php else : ?>
							<span class="icon-vertical menu-item-icon menu-item-icon-svg">
								<img src="<?php echo esc_url( $icon['value']['url'] ); ?>" alt="icon-vertical-button" class="custon-svg-button-vertical">
							</span>
							<?php
						endif;
					endif;
				}
				?>
			</button>

			<nav class="<?php echo esc_attr( $classes ); ?> <?php echo esc_attr( $show_sub_vertical ); ?>">
				<?php $this->get_menu_site( $settings['menu'] ); ?>
			</nav>
			<?php $this->get_toggle( $settings['toggle_icon'] ); ?>

			<?php do_action( 'boostify_hf_sidebar_nav_bottom' ); ?>
		</div>
		<div class="boostify-overlay">
			<a href="#" class="boostify--close-menu-side-bar ion-android-close"></a>
		</div>
		<?php
	}
}

