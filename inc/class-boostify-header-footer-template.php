<?php
/**
 * Comments
 *
 * Handle comments (reviews and order notes).
 *
 * @package Boostify_Header_Footer_Template
 *
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
			if ( 'btf_builder' == get_post_type() ) {
				$single_template = HT_HF_PATH . 'templates/hf.php';
			}

			return $single_template;
		}

		public function render_header() {
			$page_type = $this->page_type();


			if ( $this->display_all() || $this->display_template( $page_type ) || $this->current_single() || $this->all_single() ) {
				require HT_HF_PATH . 'templates/default/header.php';
				$templates   = [];
				$templates[] = 'header.php';
				// Avoid running wp_head hooks again.
				remove_all_actions( 'wp_head' );
				ob_start();
				locate_template( $templates, true );
				ob_get_clean();
			}

		}

		public function render_footer() {

			$page_type = $this->page_type();

			if ( $this->display_all( 'footer' ) || $this->display_template( $page_type, 'footer' ) || $this->current_single( 'footer' ) || $this->all_single( 'footer' ) ) {
				require HT_HF_PATH . 'templates/default/footer.php';
				$templates   = [];
				$templates[] = 'footer.php';
				// Avoid running wp_footer hooks again.
				remove_all_actions( 'wp_footer' );
				ob_start();
				locate_template( $templates, true );
				ob_get_clean();
			}
		}

		public function header_template() {
			$path      = HT_HF_PATH . 'templates/content/content-header.php';
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

			$path      = HT_HF_PATH . 'templates/content/content-footer.php';
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


		public function display_all( $type = 'header' ) {
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

			if ( $header->have_posts() ) {

				return $header;
			} else {

				return false;
			}
		}

		public function display_template( $page_type, $type = 'header' ) {
			if ( empty( $page_type ) ) {
				return false;
			}
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
				$current     = [];

				foreach ( $list_header as $key => $post ) {
					$list_id = get_post_meta( $post->ID, 'bhf_post', true );
					if ( ! empty( $list_id ) || 'all' != $list_id ) {
						$post_id = explode( ',', $list_id );
						if ( in_array( $id, $post_id ) ) {
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

		public function all_single( $type = 'header' ) {
			if ( ! is_single() && ! is_page() ) {
				return false;
			}
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
			wp_reset_postdata();

			if ( $header->have_posts() ) {

				return $header;
			} else {

				return false;
			}
		}

		public function page_type() {
			$page_type = '';
			if ( is_home() ) {
				$page_type = 'blog';
			} elseif ( is_archive() ) {
				$page_type = 'archive';
			} elseif ( is_search() ) {
				$page_type = 'search';
			}

			return $page_type;
		}

		public function render( $header, $path ) {
			if ( $header->have_posts() ) {
				while ( $header->have_posts() ) {
					$header->the_post();
					load_template( $path );
				}
				wp_reset_postdata();
			}
		}
	}

	Boostify_Header_Footer_Template::instance();
}
