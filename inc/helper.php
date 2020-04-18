<?php

/**
 * Define Script debug.
 *
 * @return     string $suffix
 */
function boostify_header_footer_suffix() {
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	return $suffix;
}


/**
 * Get search form .
 *
 * @return     form search
 */
function boostify_header_footer_search_form( $icon = 'ion-ios-search', $placeholder = 'Enter Keyword', $text = null ) {
	$url = esc_url( home_url( '/' ) );
	?>
		<div class="boostify--search-sidebar-wrapper" aria-expanded="false" role="form">
			<form action="<?php echo esc_url( $url ); ?>" class="search-form site-search-form" method="GET">

				<span class="screen-reader-text"><?php echo esc_html_x( 'Search for:', 'label', 'boostify' ); ?></span>

				<input type="search" class="search-field site-search-field" placeholder="<?php echo esc_attr( $placeholder ); ?>" name="s">
				<input type="hidden" name="post_type" value="post">
				<button type="submit" class="btn-boostify-search-form <?php echo esc_attr( $icon ); ?>">
					<?php
					if ( $text ) :
						echo esc_html( $text );
					endif;
					?>
					<span class="screen-reader-text"><?php echo esc_html__( 'Search', 'boostify' ); ?></span>
				</button>
			</form>
		</div><!-- .boostify-container -->
	<?php
}


/**
 * Get content single builder .
 *
 * @return     get content
 */

function boostify_header_footer_content() {
	$id   = get_the_ID();
	$type = get_post_meta( $id, 'bhf_type' );
	if ( empty( $type ) ) {
		$type[0] = 'header';
	}
	if ( 'header' === $type[0] ) {
		$path = BOOSTIFY_HEADER_FOOTER_PATH . 'templates/content/content-header.php';
	} elseif ( 'footer' === $type[0] ) {
		$path = BOOSTIFY_HEADER_FOOTER_PATH . 'templates/content/content-footer.php';
	} else {
		$path = BOOSTIFY_HEADER_FOOTER_PATH . 'templates/content/content-sub-menu.php';
	}
	load_template( $path );
}


/**
 * Get Sub Menu Mega
 *
 * @return List Sub Menu
 */
function boostify_header_footer_sub_menu() {
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
 * Return Header Template ID
 * @return (int) $id | ID header template
 */
function boostify_header_template_id() {
	$id = Boostify_Header_Footer\Template::instance()->template_header_id();

	return $id;
}

/**
 * Return Header Template
 * @return html | Header template
 */

function boostify_get_header_template() {
	echo Boostify_Header_Footer\Template_Render::get_header_template(); //phpcs:ignore
}


/**
 * Return Footer Template ID
 * @return (int) $id | ID Footer Template
 */
function boostify_footer_template_id() {
	$id = Boostify_Header_Footer\Template::instance()->template_footer_id();

	return $id;
}

/**
 * Return Footer Template
 * @return html | Footer template
 */
function boostify_get_footer_template() {
	echo Boostify_Header_Footer\Template_Render::get_footer_template(); //phpcs:ignore
}


function boostify_header_active() {
	$header_id = boostify_header_template_id();
	$status    = false;

	if ( $header_id ) {
		$status = true;
	}

	return apply_filters( 'boostify_header_active', $status );
}

function boostify_footer_active() {
	$header_id = boostify_footer_template_id();
	$status    = false;

	if ( $header_id ) {
		$status = true;
	}

	return apply_filters( 'boostify_footer_active', $status );
}