<?php
/**
 * Comments
 *
 * Handle comments (reviews and order notes).
 *
 * @package Boostify_Header_Footer_Template
 *
 * Written by ptp
 */

namespace Boostify_Header_Footer;

defined( 'ABSPATH' ) || exit;

/**
 * Boostify Header Footer Template Class.
 */
class Template {

	/**
	 * Instance Class
	 *
	 * @var instance
	 */
	private static $instance;

	/**
	 * Post ID
	 *
	 * @var int
	 */
	public $post_id;

	/**
	 * Post type
	 *
	 * @var String
	 */
	public $post_type;

	/**
	 *  Initiator
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Function Construct.
	 */
	public function __construct() {
		add_action( 'wp', array( $this, 'hooks' ) );
		add_action( 'wp_head', array( $this, 'wp_head' ) );
		add_filter( 'single_template', array( $this, 'single_template' ) );
		// Get header Template.
		add_action( 'boostify_hf_get_header', array( $this, 'header_template' ), 10 );
		// Get Footer Template.
		add_action( 'boostify_hf_get_footer', array( $this, 'footer_template' ), 10 );
	}

	/**
	 * Hook in methods.
	 */
	public function hooks() {
		if ( ! current_theme_supports( 'boostify-header-footer' ) ) {
			add_action( 'get_header', array( $this, 'render_header' ) );
			add_action( 'get_footer', array( $this, 'render_footer' ) );
		}
	}

	/**
	 * Reset Post Data header.
	 */
	public function wp_head() {
		wp_reset_postdata();
	}

	/**
	 * Get Header Site.
	 *
	 * @param (string) $single_template | Single template path.
	 * @return Header Site.
	 */
	public function single_template( $single_template ) {
		if ( 'btf_builder' == get_post_type() ) { // phpcs:ignore
			$single_template = BOOSTIFY_HEADER_FOOTER_PATH . 'templates/hf.php';
		}

		return $single_template;
	}

	/**
	 * Get Header Site.
	 */
	public function render_header() {
		$header_id = $this->template_header_id();
		if ( $header_id ) {
			require BOOSTIFY_HEADER_FOOTER_PATH . 'templates/default/header.php';
			$templates   = array();
			$templates[] = 'header.php';
			// Avoid running wp_head hooks again.
			remove_all_actions( 'wp_head' );
			ob_start();
			locate_template( $templates, true );
			ob_get_clean();
		}
	}

	/**
	 * Get Footer Site.
	 */
	public function render_footer() {
		$footer_id = $this->template_footer_id();
		if ( $footer_id ) {
			require BOOSTIFY_HEADER_FOOTER_PATH . 'templates/default/footer.php';
			$templates   = array();
			$templates[] = 'footer.php';
			// Avoid running wp_footer hooks again.
			remove_all_actions( 'wp_footer' );
			ob_start();
			locate_template( $templates, true );
			ob_get_clean();
		}
	}

	/**
	 * Get Header Buider width condition.
	 */
	public function header_template() {
		global $post;
		$shop_id = get_option( 'woocommerce_shop_page_id' );
		if ( ! empty( $post ) ) {
			$id        = $post->ID;
			$post_type = get_post_type( $post->ID );
		}

		if ( class_exists( 'Woocommerce' ) && is_shop() ) {
			$id        = $shop_id;
			$post_type = get_post_type( $shop_id );
		}

		$path      = BOOSTIFY_HEADER_FOOTER_PATH . 'templates/content/content-header.php';
		$page_type = $this->page_type();

		if ( is_singular() || ( class_exists( 'Woocommerce' ) && is_shop() ) ) {

			$header = $this->current_single( $id, $post_type );

			if ( ! $header ) {
				$header = $this->all_single( $id, $post_type );
				if ( ! $header ) {
					$header = $this->display_all();
				}
			}
			$this->render( $header, $path );
		}

		if ( $this->display_all() ) {
			$header = $this->display_all();
			$this->render( $header, $path );
		}

		if ( is_singular() || ( class_exists( 'Woocommerce' ) && is_shop() ) ) {
			$header = $this->current_single( $id, $post_type );

			$this->render( $header, $path );
		}


		if ( ! empty( $page_type ) ) {
			$header = $this->display_template( $page_type );
			if ( ! $header ) {
				$header = $this->display_all();
			}
			$this->render( $header, $path );
		}
	}

	/**
	 * Get Footer Buider width condition.
	 */
	public function footer_template() {
		global $post;
		$shop_id = get_option( 'woocommerce_shop_page_id' );
		if ( ! empty( $post ) ) {
			$id        = $post->ID;
			$post_type = get_post_type( $post->ID );
		}

		if ( class_exists( 'Woocommerce' ) && is_shop() ) {
			$id        = $shop_id;
			$post_type = get_post_type( $shop_id );
		}

		$path      = BOOSTIFY_HEADER_FOOTER_PATH . 'templates/content/content-footer.php';
		$page_type = $this->page_type();
		if ( is_singular() || ( class_exists( 'Woocommerce' ) && is_shop() ) ) {
			$footer = $this->current_single( $id, $post_type, 'footer' );

			$this->render( $footer, $path );
		}

		if ( $this->display_all( 'footer' ) ) {
			$footer = $this->display_all( 'footer' );
			$this->render( $footer, $path );
		}
		if ( is_singular() || ( class_exists( 'Woocommerce' ) && is_shop() ) ) {
			$footer = $this->current_single( $id, $post_type, 'footer' );

			$this->render( $footer, $path );
		}

		if ( ! empty( $page_type ) ) {
			$footer = $this->display_template( $page_type, 'footer' );
			if ( ! $footer ) {
				$footer = $this->display_all( 'footer' );
			}
			$this->render( $footer, $path );
		}

	}

	/**
	 * Template( header or Footer ) width conditon For All.
	 *
	 * @param (string) $type | Type of btf_builder custom post type.
	 * @return Mixed (object) $template or (Boolean) false.
	 */
	public function display_all( $type = 'header' ) {
		$args = array(
			'post_type'           => 'btf_builder',
			'orderby'             => 'id',
			'order'               => 'DESC',
			'posts_per_page'      => 1,
			'ignore_sticky_posts' => 1,
			'meta_query'          => array( //phpcs:ignore
				array(
					'key'     => 'bhf_type',
					'compare' => 'LIKE',
					'value'   => $type,
				),
				array(
					'key'     => 'bhf_display',
					'compare' => 'LIKE',
					'value'   => 'all',
				),
			),
		);

		$header = new \WP_Query( $args );

		$this->check_ex_post( $header );

		if ( $header->have_posts() ) {
			return $header;
		} else {

			return false;
		}
	}

	/**
	 * Template( header or Footer ) Width Conditon For Archive, Blog Page, Search Page.
	 *
	 * @param (string) $page_type | Type of page.
	 * @param (string) $type | Type of btf_builder custom post type.
	 * @return Mixed (object) $template or (Boolean) false.
	 */
	public function display_template( $page_type, $type = 'header' ) {
		if ( empty( $page_type ) ) {
			return false;
		}
		$args   = array(
			'post_type'           => 'btf_builder',
			'orderby'             => 'id',
			'order'               => 'DESC',
			'posts_per_page'      => 1,
			'ignore_sticky_posts' => 1,
			'meta_query'          => array( //phpcs:ignore
				array(
					'key'     => 'bhf_type',
					'compare' => 'LIKE',
					'value'   => $type,
				),
				array(
					'key'     => 'bhf_display',
					'compare' => 'LIKE',
					'value'   => $page_type,
				),
			),
		);
		$header = new \WP_Query( $args );

		if ( $header->have_posts() ) {
			return $header;
		} else {
			return false;
		}
	}

	/**
	 * Template( header or Footer ) Width Conditon For Archive, Blog Page, Search Page.
	 *
	 * @param (int)    $id | ID Single Post.
	 * @param (string) $post_type | Post Type Single Post.
	 * @param (string) $type | Type of btf_builder custom post type.
	 * @return Mixed (object) $template or (Boolean) false.
	 */
	public function current_single( $id, $post_type, $type = 'header' ) {
		$args = array(
			'post_type'           => 'btf_builder',
			'orderby'             => 'id',
			'order'               => 'DESC',
			'posts_per_page'      => 1,
			'ignore_sticky_posts' => 1,
			'meta_query'          => array( //phpcs:ignore
				array(
					'key'     => 'bhf_type',
					'compare' => 'LIKE',
					'value'   => $type,
				),
				array(
					'key'     => 'bhf_post_type',
					'compare' => 'LIKE',
					'value'   => $post_type,
				),
				array(
					'key'     => 'bhf_post',
					'compare' => 'LIKE',
					'value'   => $id,
				)
			),
		);

		$header = new \WP_Query( $args );


		if ( $header->have_posts() ) {
			$list_header = $header->posts;
			$current     = array();

			foreach ( $list_header as $key => $post ) {
				$list_id = get_post_meta( $post->ID, 'bhf_post', true );

				if ( ! empty( $list_id ) || 'all' != $list_id ) { // phpcs:ignore
					$post_id = explode( ',', $list_id );

					if ( in_array( $id, $post_id ) ) { // phpcs:ignore
						$current[0] = $post;
					}
				}
			}
			wp_reset_postdata();

			if ( empty( $current ) ) {

				return false;
			} else {
				$header->posts      = $current;
				$header->post_count = 1;

				return $header;
			}
		} else {

			return false;
		}
	}

	/**
	 * Template( header or Footer ) Width Conditon For Archive, Blog Page, Search Page.
	 *
	 * @param (int)    $post_id | ID Single Post.
	 * @param (string) $post_type | Post Type Single Post.
	 * @param (string) $type | Type of btf_builder custom post type.
	 * @return Mixed (object) $template or (Boolean) false.
	 */
	public function all_single( $post_id, $post_type, $type = 'header' ) {
		if ( ! is_singular() ) {
			return false;
		}

		$args   = array(
			'post_type'           => 'btf_builder',
			'orderby'             => 'id',
			'order'               => 'DESC',
			'posts_per_page'      => 1,
			'ignore_sticky_posts' => 1,
			'meta_query'          => array( //phpcs:ignore
				array(
					'key'     => 'bhf_type',
					'compare' => 'LIKE',
					'value'   => $type,
				),
				array(
					'key'     => 'bhf_post_type',
					'compare' => 'LIKE',
					'value'   => $post_type,
				),
				array(
					'key'     => 'bhf_post',
					'compare' => 'LIKE',
					'value'   => 'all',
				),
			),
		);
		$header = new \WP_Query( $args );

		if ( $header->have_posts() ) {

			while ( $header->have_posts() ) {
				$header->the_post();
				$id           = get_the_ID();
				$ex_post      = get_post_meta( $id, 'bhf_ex_post', true );
				$no_display   = get_post_meta( $id, 'bhf_no_display', true );
				$ex_post_type = get_post_meta( $id, 'bhf_ex_post_type', true );
				$list_ex_post = array();
				$post_type    = get_post_type( $post_id );
				$list_ex_post = array();
				if ( !empty( $ex_post ) ) {
					$list_ex_post = explode( ',', $ex_post );
				}

				if ( 'all' === $ex_post && $post_type === $ex_post_type ) {

					return false;
				}
				if ( ! empty( $list_ex_post ) && in_array( $post_id, $list_ex_post ) ) { //phpcs:ignore

					return false;
				}
			}
			wp_reset_postdata();

			return $header;
		} else {

			return false;
		}
	}

	/**
	 * Get page type.
	 */
	public function page_type() {
		global $post;
		$shop_id        = get_option( 'woocommerce_shop_page_id' );
		$page_type      = '';
		$checkshop_page = true;
		if ( class_exists( 'Woocommerce' ) && is_shop() ) {
			$checkshop_page = false;
		}
		if ( is_home() ) {
			$page_type = 'blog';
		} elseif ( is_archive() && $checkshop_page ) {
			$page_type = 'archive';
		} elseif ( is_search() ) {
			$page_type = 'search';
		} elseif ( is_404() ) {
			$page_type = 'not_found';
		}

		return $page_type;
	}

	/**
	 * Render template.
	 *
	 * @param (object) $header | ID Single Post.
	 * @param (string) $path | Post Type Single Post.
	 */
	public function render( $header, $path ) {
		if ( $header && $header->have_posts() ) {
			while ( $header->have_posts() ) {
				$header->the_post();
				load_template( $path );
			}
			wp_reset_postdata();
		}
	}

	/**
	 * Check condition exclude.
	 *
	 * @param (string) $header | Post Type Single Post.
	 * @return (boolean) false | return false if exclude
	 */
	public function check_ex_post( $header ) {
		$post_id = $this->post_id;
		if ( $header->have_posts() ) {
			while ( $header->have_posts() ) {
				$header->the_post();
				$id           = get_the_ID();
				$ex_post      = get_post_meta( $id, 'bhf_ex_post', true );
				$no_display   = get_post_meta( $id, 'bhf_no_display', true );
				$ex_post_type = get_post_meta( $id, 'bhf_ex_post_type', true );
				$list_ex_post = array();
				$post_type    = get_post_type( $post_id );

				if ( 'blog' === $no_display && is_home() ) {

					return false;
				}
				if ( 'archive' === $no_display && is_archive() ) {
					return false;
				}

				if ( 'search' === $no_display && is_search() ) {
					return false;
				}

				if ( 'not_found' === $no_display && is_404() ) {
					return false;
				}

				if ( ! empty( $ex_post ) && 'blog' !== $no_display && 'archive' !== $no_display && 'search' !== $no_display && 'not_found' !== $no_display ) {
					$list_ex_post = explode( ',', $ex_post );
					if ( 'all' === $ex_post && is_single() && $post_type === $ex_post_type ) {

						return false;
					}
					if ( in_array( $post_id, $list_ex_post ) ) { //phpcs:ignore
						return false;
					}
				}
			}
			wp_reset_postdata();
		}
	}

	/**
	 * Get Header Template ID.
	 *
	 * @return Mixed (int) $id or (Boolean) false | ID Header Teamplate or false if not found
	 */
	public function template_header_id() {
		global $post;
		$shop_id   = get_option( 'woocommerce_shop_page_id' );
		$page_type = $this->page_type();
		$id = false;
		$header = $this->display_all();

		$id = false;
		if (empty( $post ) &&  $page_type) {


			if ( $this->display_template( $page_type ) ) {
				$header = $this->display_template( $page_type );
			}		

			if ( ! $header ) {
				return false;
			}

			while ( $header->have_posts() ) {
				$header->the_post();
				$id = get_the_ID();
			}
			wp_reset_postdata();

			return $id;
		}


		if ( empty( $post ) && ! is_404() ) {
			return false;
		}


		$post_id   = $post->ID;
		$post_type = get_post_type( $post->ID );

		if ( class_exists( 'Woocommerce' ) && is_shop() ) {
			$post_id   = $shop_id;
			$post_type = get_post_type( $shop_id );
		}

		$maintenance_mode     = get_option( 'elementor_maintenance_mode_mode' );
		$maintenance_template = get_option( 'elementor_maintenance_mode_template_id' );
		if ( 'coming_soon' == $maintenance_mode && $maintenance_template == $post_id ) { //phpcs:ignore
			return false;
		}


		if ( $this->display_template( $page_type ) ) {
			$header = $this->display_template( $page_type );
		}
		if ( $this->all_single( $post_id, $post_type ) ) {
			$header = $this->all_single( $post_id, $post_type );
		}
		if ( $this->current_single( $post_id, $post_type ) ) {
			$header = $this->current_single( $post_id, $post_type );
		}

		if ( ! $header ) {
			return false;
		}

		while ( $header->have_posts() ) {
			$header->the_post();
			$id = get_the_ID();
		}
		wp_reset_postdata();

		return $id;
	}

	/**
	 * Get Footer Template ID.
	 *
	 * @return Mixed (int) $id or (Boolean) false | ID Footer Teamplate or False if not found
	 */
	public function template_footer_id() {
		global $post;
		$shop_id   = get_option( 'woocommerce_shop_page_id' );
		$page_type = $this->page_type();
		$footer    = $this->display_all( 'footer' );
		$id        = false;

		if (empty( $post ) &&  $page_type) {

			if ( $this->display_template( $page_type, 'footer' ) ) {
				$footer = $this->display_template( $page_type, 'footer' );
			}		

			if ( ! $footer ) {
				return false;
			}

			while ( $footer->have_posts() ) {
				$footer->the_post();
				$id = get_the_ID();
			}
			wp_reset_postdata();

			return $id;
		}
		
		if (empty( $post ) && ! is_404() ) {
			return false;
		}

		if ( class_exists( 'Woocommerce' ) && is_shop() ) {
			$this->post_id   = $shop_id;
			$this->post_type = get_post_type( $shop_id );
		}
		if ( ! empty( $post ) ) {
			$post_id   = $post->ID;
			$post_type = get_post_type( $post->ID );

		}

		$maintenance_mode     = get_option( 'elementor_maintenance_mode_mode' );
		$maintenance_template = get_option( 'elementor_maintenance_mode_template_id' );
		if ( 'coming_soon' == $maintenance_mode && $maintenance_template == $post_id ) { //phpcs:ignore
			return false;
		}

		if ( $this->display_all( 'footer' ) ) {
			
			$footer = $this->display_all( 'footer' );
		}
		if ( $this->display_template( $page_type, 'footer' ) ) {
			$footer = $this->display_template( $page_type, 'footer' );
		}
		if ( $this->all_single( $post_id, $post_type, 'footer' ) ) {
			$footer = $this->all_single( $post_id, $post_type, 'footer' );
		}
		if ( $this->current_single( $post_id, $post_type, 'footer' ) ) {

			$footer = $this->current_single( $post_id, $post_type, 'footer' );
		}



		if ( ! $footer ) {
			return false;
		}

		while ( $footer->have_posts() ) {
			$footer->the_post();
			$id = get_the_ID();
		}
		wp_reset_postdata();

		return $id;

	}
}

Template::instance();

