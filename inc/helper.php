<?php
/**
 * Boostify Header Footer function
 *
 * Handle comments (reviews and order notes).
 *
 * @package Boostify_Header_Footer_Template
 *
 * Written by ptp
 */

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
 * List type builder.
 *
 * @return     array $type
 */
function boostify_type_builder() {
	$type = array(
		'header'   => __( 'Header', 'boostify' ),
		'footer'   => __( 'Footer', 'boostify' ),
		'sub_menu' => __( 'Sub Mega Menu', 'boostify' ),
	);

	return $type;
}

/**
 * List post type support builder.
 *
 * @return     array $options
 */
function boostify_pt_support() {
	$post_types       = get_post_types();
	$post_types_unset = array(
		'attachment'          => 'attachment',
		'revision'            => 'revision',
		'nav_menu_item'       => 'nav_menu_item',
		'custom_css'          => 'custom_css',
		'customize_changeset' => 'customize_changeset',
		'oembed_cache'        => 'oembed_cache',
		'user_request'        => 'user_request',
		'wp_block'            => 'wp_block',
		'elementor_library'   => 'elementor_library',
		'btf_builder'         => 'btf_builder',
		'elementor-hf'        => 'elementor-hf',
		'elementor_font'      => 'elementor_font',
		'elementor_icons'     => 'elementor_icons',
		'wpforms'             => 'wpforms',
		'wpforms_log'         => 'wpforms_log',
		'acf-field-group'     => 'acf-field-group',
		'acf-field'           => 'acf-field',
		'booked_appointments' => 'booked_appointments',
		'wpcf7_contact_form'  => 'wpcf7_contact_form',
		'scheduled-action'    => 'scheduled-action',
		'shop_order'          => 'shop_order',
		'shop_order_refund'   => 'shop_order_refund',
		'shop_coupon'         => 'shop_coupon',
	);
	$diff             = array_diff( $post_types, $post_types_unset );
	$default          = array(
		'all'       => 'All',
		'blog'      => 'Blog Page',
		'archive'   => 'Archive Page',
		'search'    => 'Search Page',
		'not_found' => '404 Page',
	);
	$options          = array_merge( $default, $diff );

	return $options;
}

/**
 * Get search form .
 *
 * @param (string) $icon | icon class.
 * @param (string) $placeholder | input placeholder class.
 * @param (string) $text | button label.
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
		'meta_query'          => array( //phpcs:ignore
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
 *
 * @return (int) $id | ID header template
 */
function boostify_header_template_id() {
	$id = Boostify_Header_Footer\Template::instance()->template_header_id();

	return $id;
}

/**
 * Return Header Template
 */
function boostify_get_header_template() {
	echo Boostify_Header_Footer\Template_Render::get_header_template(); //phpcs:ignore
}


/**
 * Return Footer Template ID
 *
 * @return (int) $id | ID Footer Template
 */
function boostify_footer_template_id() {
	$id = Boostify_Header_Footer\Template::instance()->template_footer_id();

	return $id;
}

/**
 * Return Footer Template
 */
function boostify_get_footer_template() {
	echo Boostify_Header_Footer\Template_Render::get_footer_template(); //phpcs:ignore
}

/**
 * Check header active
 */
function boostify_header_active() {
	$header_id = boostify_header_template_id();
	$status    = false;

	if ( $header_id ) {
		$status = true;
	}

	return apply_filters( 'boostify_header_active', $status );
}

/**
 * Check footer active
 */
function boostify_footer_active() {
	$header_id = boostify_footer_template_id();
	$status    = false;
	if ( $header_id ) {
		$status = true;
	}

	return apply_filters( 'boostify_footer_active', $status );
}
