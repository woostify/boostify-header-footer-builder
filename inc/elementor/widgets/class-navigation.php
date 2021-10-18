<?php
/**
 * Navigation
 *
 * Elementor widget for Navigation.
 *
 * @package Boostify_Header_Footer
 * Author: ptp
 */

namespace Boostify_Header_Footer\Widgets;

use Boostify_Header_Footer\Nav_Menu;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;

/**
 * Boostify Nav Menu
 *
 * Elementor widget for Navigation.
 */
class Navigation extends Nav_Menu {

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
	 * Retrieve the widget script.
	 *
	 * @access public
	 *
	 * @return array Widget script.
	 */
	public function get_script_depends() {
		return array( 'boostify_hf_nav_menu' );
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
			<nav class="boostify-main-navigation boostify-nav-default boostify-nav boostify-header-navigation <?php echo esc_attr( $settings['menu'] . ' boostify--hover-' . esc_attr( $settings['pointer'] ) ); ?>" aria-label="<?php esc_attr_e( 'Primary navigation', 'boostify' ); ?>">
				<?php
				if ( 'none' !== $settings['menu'] ) {
					$args = array(
						'menu'           => $settings['menu'],
						'container'      => '',
						'menu_class'     => 'boostify-menu',
						'theme_location' => 'primary',
					);

					wp_nav_menu( $args );
				} elseif ( is_user_logged_in() ) {
					?>
					<a class="add-menu" href="<?php echo esc_url( get_admin_url() . 'nav-menus.php' ); ?>"><?php esc_html_e( 'Add a Primary Menu', 'boostify' ); ?></a>
				<?php } ?>
			</nav>

			<?php $this->get_toggle( $settings['toggle_icon'] ); ?>

			<div class="boostify-menu-sidebar boostify--hover-<?php echo esc_attr( $settings['pointer'] . ' show-logo-' . $settings['logo'] . ' show-form-' . $settings['search'] ); ?>">
				<div class="boostify-menu-sidebar--wrapper">
					<?php if ( 'yes' === $settings['logo'] ) : ?>
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
					if ( 'yes' === $settings['search'] ) :
						do_action( 'boostify_hf_seach_form', 'ion-ios-search' );
					endif;
					?>

					<nav class="boostify-menu-dropdown <?php echo esc_attr( $settings['menu'] ); ?>" aria-label="<?php esc_attr_e( 'Dropdown navigation', 'boostify' ); ?>">
						<?php
						if ( 'none' !== $settings['menu'] ) {
							$args = array(
								'menu'       => $settings['menu'],
								'container'  => '',
								'menu_class' => 'boostify-dropdown-menu',
							);

							wp_nav_menu( $args );
						} elseif ( is_user_logged_in() ) {
							?>
							<a class="add-menu" href="<?php echo esc_url( get_admin_url() . 'nav-menus.php' ); ?>"><?php esc_html_e( 'Add a Primary Menu', 'boostify' ); ?></a>
						<?php } ?>
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

	/**
	 * Main menu control.
	 */
	protected function main_menu() {
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
				'options'      => $this->all_menu_site(),
				'save_default' => true,
				'default'      => 'no',
				'description'  => sprintf( __( 'Go to the <a href="%s" target="_blank">Menus screen</a> to manage your menus.', 'boostify' ), admin_url( 'nav-menus.php' ) ), //phpcs:ignore
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

		$this->add_control(
			'submenu_heading',
			array(
				'label'     => __( 'Sub Menu', 'boostify' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'border_item_submenu',
				'label'    => __( 'Border Item', 'boostify' ),
				'selector' => '{{WRAPPER}} .boostify-menu .sub-menu li:not( :last-child )',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Control mobule menu.
	 */
	protected function custom_mobile_menu() {
		$this->add_control(
			'logo',
			array(
				'label'        => __( 'Show Logo', 'boostify' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'boostify' ),
				'label_off'    => __( 'Hide', 'boostify' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'logo-align',
			array(
				'label'     => esc_html__( 'Toggle Align', 'boostify' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'left',
				'condition' => array(
					'logo' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} .logo-sidebar' => 'text-align: {{VALUE}};',
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

		$this->add_control(
			'search',
			array(
				'label'        => __( 'Show Search', 'boostify' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'boostify' ),
				'label_off'    => __( 'Hide', 'boostify' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			)
		);
	}
}
