<?php
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
	public function get_categories() {
		return array( 'ht_hf_builder' );
	}

	public function _register_controls() { //phpcs:ignore
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
				'options'   => get_registered_nav_menus(),
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

	/**
	 * Render Copyright output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.2.0
	 * @access protected
	 */
	protected function render() {
		$settings        = $this->get_settings_for_display();
		$id_menu         = wp_rand();
		$menu_item_class = 'menu-item menu-item-type-custom';
		$args = array(
			'theme_location' => 'primary',
			'level'          => 2,
			'child_of'       => 'test',
		);
		wp_nav_menu( $args );
		?>
		<nav class="boostify-nav boostify-nav-mega boostify-menu-layout- boostify-mega-menu boostify-menu">
			<ul id="menu-<?php echo esc_attr( $id_menu ); ?>" class="menu boostify-mega-menu boostify-menu">

			<?php
			foreach ( $settings['menu'] as $menu ) {
				$sub_id        = (int) $menu['sub_menu'];
				$sub_type      = $menu['sub_type'];
				$menu_location = $menu['menu_register'];
				$child_of      = $menu['child_of'];
				$item_class    = '';

				if ( 'yes' === $menu['has_sub'] && ( 'no' !== $menu['sub_menu'] || ( $child_of && $menu_location ) ) ) {

						$item_class = ' menu-item-has-children current-menu-parent';

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
		</nav>
		<?php
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
			<ul class="sub-menu">
			<?php
			while ( $sub_menu->have_posts() ) {
				$sub_menu->the_post();
				the_content();
			}
			wp_reset_postdata();
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
			'theme_location' => $menu_id,
			'level'          => 2,
			'child_of'       => $child_of,
		);
		wp_nav_menu( $args );
	}

}
