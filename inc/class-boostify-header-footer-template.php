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

defined( 'ABSPATH' ) || exit;

/**
 * Boostify Header Footer Template Class.
 */
if ( ! class_exists( 'Boostify_Header_Footer_Template' ) ) {


	class Boostify_Header_Footer_Template {

		private static $instance;

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
		 * Hook in methods.
		 */
		public function __construct() {
			add_filter( 'single_template', array( $this, 'single_template' ) );
			add_action( 'get_header', array( $this, 'render_header' ) );
			add_action( 'get_footer', array( $this, 'render_footer' ) );
			// Get header Template
			add_action( 'boostify_hf_get_header', array( $this, 'header_template' ), 10 );

			// Get Footer Template
			add_action( 'boostify_hf_get_footer', array( $this, 'footer_template' ), 10 );
		}

		// Template single post type
		public function single_template( $single_template ) {
			if ( 'btf_builder' == get_post_type() ) { // phpcs:ignore
				$single_template = BOOSTIFY_HEADER_FOOTER_PATH . 'templates/hf.php';
			}

			return $single_template;
		}

		// Get Header Builder
		public function render_header() {
			$page_type = $this->page_type();
			if ( $this->display_all() || $this->display_template( $page_type ) || $this->current_single() || $this->all_single() ) {
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

		// Get Footer Builder
		public function render_footer() {
			$page_type = $this->page_type();
			if ( $this->display_all( 'footer' ) || $this->display_template( $page_type, 'footer' ) || $this->current_single( 'footer' ) || $this->all_single( 'footer' ) ) {
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

		public function header_template() {
			$path      = BOOSTIFY_HEADER_FOOTER_PATH . 'templates/content/content-header.php';
			$page_type = $this->page_type();
			if ( ! empty( $page_type ) ) {
				$header = $this->display_template( $page_type );
				if ( ! $header ) {
					$header = $this->display_all();
				}
				$this->render( $header, $path );
			}

			if ( is_single() || is_page() ) {

				$header = $this->current_single();

				if ( ! $header ) {
					$header = $this->all_single();

					if ( ! $header ) {
						$header = $this->display_all();
					}
				}

				$this->render( $header, $path );
			}
		}

		// Get hf_builder Footer Template
		public function footer_template() {

			$path      = BOOSTIFY_HEADER_FOOTER_PATH . 'templates/content/content-footer.php';
			$page_type = $this->page_type();
			if ( ! empty( $page_type ) ) {
				$footer = $this->display_template( $page_type, 'footer' );
				if ( ! $footer ) {
					$footer = $this->display_all( 'footer' );
				}
				$this->render( $footer, $path );
			}

			if ( is_single() || is_page() ) {
				$footer = $this->current_single( 'footer' );

				if ( ! $footer ) {
					$footer = $this->all_single( 'footer' );
					if ( ! $footer ) {
						$footer = $this->display_all( 'footer' );
					}
				}

				$this->render( $footer, $path );
			}

		}

		// For Template All Page
		public function display_all( $type = 'header' ) {
			$post_id = get_the_ID();
			$args = array(
				'post_type'           => 'btf_builder',
				'orderby'             => 'id',
				'order'               => 'DESC',
				'posts_per_page'      => 1,
				'ignore_sticky_posts' => 1,
				'meta_query'          => array(
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

			$header = new WP_Query( $args );

			$this->check_ex_post( $header );

			if ( $header->have_posts() ) {
				return $header;
			} else {

				return false;
			}
		}

		// For Archive, Blog Page, Search Page
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
				'meta_query'          => array(
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
			$header = new WP_Query( $args );

			if ( $header->have_posts() ) {

				return $header;
			} else {
				return false;
			}
		}

		// Get Template Builder For Current Single
		public function current_single( $type = 'header' ) {
			if ( ! is_single() && ! is_page() ) {
				return false;
			}
			$id = get_the_ID();

			$args = array(
				'post_type'           => 'btf_builder',
				'orderby'             => 'id',
				'order'               => 'DESC',
				'posts_per_page'      => -1,
				'ignore_sticky_posts' => 1,
				'meta_query'          => array(
					array(
						'key'     => 'bhf_type',
						'compare' => 'LIKE',
						'value'   => $type,
					),
					array(
						'key'     => 'bhf_post_type',
						'compare' => 'LIKE',
						'value'   => get_post_type(),
					),
				),
			);

			$header = new WP_Query( $args );

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

		// Get Template Builder For All Single
		public function all_single( $type = 'header' ) {
			if ( ! is_single() && ! is_page() ) {
				return false;
			}
			$args   = array(
				'post_type'           => 'btf_builder',
				'orderby'             => 'id',
				'order'               => 'DESC',
				'posts_per_page'      => 1,
				'ignore_sticky_posts' => 1,
				'meta_query'          => array(
					array(
						'key'     => 'bhf_type',
						'compare' => 'LIKE',
						'value'   => $type,
					),
					array(
						'key'     => 'bhf_post_type',
						'compare' => 'LIKE',
						'value'   => get_post_type(),
					),
					array(
						'key'     => 'bhf_post',
						'compare' => 'LIKE',
						'value'   => 'all',
					),
				),
			);
			$header = new WP_Query( $args );

			$post_id = get_the_ID();

			if ( $header->have_posts() ) {

				while ( $header->have_posts() ) {
					$header->the_post();
					$id           = get_the_ID();
					$ex_post      = get_post_meta( $id, 'bhf_ex_post', true );
					$no_display   = get_post_meta( $id, 'bhf_no_display', true );
					$ex_post_type = get_post_meta( $id, 'bhf_ex_post_type', true );
					$list_ex_post = array();
					$post_type    = get_post_type( $post_id );
					$list_ex_post = explode( ',', $ex_post );
					if ( 'all' === $ex_post && $post_type === $ex_post_type ) {

						return false;
					}
					if ( in_array( $post_id, $list_ex_post ) ) { //phpcs:ignore

						return false;
					}
				}
				wp_reset_postdata();


				return $header;
			} else {

				return false;
			}
		}

		// Return Page Type.
		public function page_type() {
			$page_type = '';
			if ( is_home() ) {
				$page_type = 'blog';
			} elseif ( is_archive() ) {
				$page_type = 'archive';
			} elseif ( is_search() ) {
				$page_type = 'search';
			} elseif ( is_404() ) {
				$page_type = 'not_found';
			}

			return $page_type;
		}

		// Get current Template builder.
		public function render( $header, $path ) {
			if ( $header->have_posts() ) {
				while ( $header->have_posts() ) {
					$header->the_post();
					load_template( $path );
				}
				wp_reset_postdata();
			}
		}

		public function check_ex_post( $header ) {

			$post_id = get_the_ID();

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
	}

	Boostify_Header_Footer_Template::instance();
}
