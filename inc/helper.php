<?php

// Load Content
if ( ! function_exists( 'boostify_hf_content' ) ) {
	function boostify_hf_content() {
		$id   = get_the_ID();
		$type = get_post_meta( $id, 'bhf_type' );
		if ( 'header' == $type[0] ) {
			$path = HT_HF_PATH . 'templates/content/content-header.php';
		} else {
			$path = HT_HF_PATH . 'templates/content/content-footer.php';
		}
		load_template( $path );
	}
}

// Check script debug active
if ( ! function_exists( 'boostify_hf_suffix' ) ) {
	/**
	 * Define Script debug.
	 *
	 * @return     string $suffix
	 */
	function boostify_hf_suffix() {
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		return $suffix;
	}
}

// Get form search

if ( ! function_exists( 'boostify_hf_search_form' ) ) {
	function boostify_hf_search_form() {
		$url = esc_url( home_url( '/' ) );
		?>
			<div class="boostify--search-sidebar-wrapper" aria-expanded="false" role="form">
				<form action="<?php echo esc_url( $url ); ?>" class="search-form site-search-form" method="GET">

					<span class="screen-reader-text"><?php echo esc_html_x( 'Search for:', 'label', 'boostify' ); ?></span>

					<input type="search" id="site-search-field" class="search-field site-search-field" placeholder="<?php echo esc_attr__( 'Enter Keyword', 'boostify' ); ?>" name="s">
					<input type="hidden" name="post_type" value="post">
					<button type="submit" class="btn-boostify-search-form ion-ios-search">
						<span class="screen-reader-text"><?php echo esc_html__( 'Search', 'boostify' ); ?></span>
					</button>
				</form>
			</div><!-- .boostify-container -->
		<?php
	}
}

// Get Header Array
if ( ! function_exists( 'boostify_hf_header_option' ) ) {
	function boostify_hf_header_option() {
		$args = array(
			'post_type'      => 'btf_builder',
			'orderby'        => 'name',
			'order'          => 'ASC',
			'posts_per_page' => -1,
			'meta_query'     => array(
				array(
					'key'     => 'bhf_type',
					'compare' => 'LIKE',
					'value'   => 'header',
				),
			),
		);

		$header       = new WP_Query( $args );
		$header_posts = [
			'none' => 'Select Header',
		];

		while ( $header->have_posts() ) {
			$header->the_post();
			$header_posts[ get_the_ID() ] = get_the_title();
		}
		wp_reset_postdata();

		return $header_posts;
	}
}

// Get Footer Array
if ( ! function_exists( 'boostify_hf_footer_option' ) ) {
	function boostify_hf_footer_option() {
		$args = array(
			'post_type'      => 'btf_builder',
			'orderby'        => 'name',
			'order'          => 'ASC',
			'posts_per_page' => -1,
			'meta_query'     => array(
				array(
					'key'     => 'bhf_type',
					'compare' => 'LIKE',
					'value'   => 'footer',
				),
			),
		);

		$footer = new WP_Query( $args );

		$footer_posts = [
			'none' => 'Select Footer',
		];
		while ( $footer->have_posts() ) {
			$footer->the_post();
			$footer_posts[ get_the_ID() ] = get_the_title();
		}
		wp_reset_postdata();

		return $footer_posts;
	}
}

// Get hf_builder header

if ( ! function_exists( 'boostify_hf_get_post' ) ) {
	function boostify_hf_get_post( $id ) {

		$args   = array(
			'post_type'      => 'btf_builder',
			'orderby'        => 'name',
			'order'          => 'ASC',
			'posts_per_page' => -1,
			'p'              => $id,
		);
		$post = new WP_Query( $args );

		return $post;
	}
}

// Get hf_builder Header Template

if ( ! function_exists( 'boostify_hf_header_template' ) ) {
	function boostify_hf_header_template() {

		$header_default = get_option( 'bhf_default_header' );
		$id             = get_the_ID();
		$current        = get_post_meta( $id, 'bhf_display_header', true );
		$header         = [];
		$path           = HT_HF_PATH . 'templates/content/content-header.php';

		if ( ! empty( $current ) && 'none' !== $current ) {
			$current = (int) $current;
			$header  = boostify_hf_get_post( $current );
		} else {
			if ( 'none' !== $header_default ) {
				$header_default = (int) $header_default;
				$header         = boostify_hf_get_post( $current );
			}
		}

		if ( ! empty( $header ) && $header->have_posts() ) {
			while ( $header->have_posts() ) {
				$header->the_post();
				load_template( $path );
			}
			wp_reset_postdata();
		}

	}
}


// Get hf_builder Footer Template

if ( ! function_exists( 'boostify_hf_footer_template' ) ) {
	function boostify_hf_footer_template() {

		$footer_default = get_option( 'bhf_default_footer' );
		$id             = get_the_ID();
		$current        = get_post_meta( $id, 'bhf_display_footer', true );
		$footer         = [];
		$path           = HT_HF_PATH . 'templates/content/content-footer.php';

		if ( ! empty( $current ) && 'none' !== $current ) {
			$current = (int) $current;
			$footer  = boostify_hf_get_post( $current );
		} else {
			if ( 'none' !== $footer_default ) {
				$footer_default = (int) $footer_default;
				$footer         = boostify_hf_get_post( $current );
			}
		}

		if ( ! empty( $footer ) && $footer->have_posts() ) {
			while ( $footer->have_posts() ) {
				$footer->the_post();
				load_template( $path );
			}
			wp_reset_postdata();
		}

	}
}