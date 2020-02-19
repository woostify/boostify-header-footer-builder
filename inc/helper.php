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
	if ( 'header' === $type[0] ) {
		$path = BOOSTIFY_HEADER_FOOTER_PATH . 'templates/content/content-header.php';
	} else {
		$path = BOOSTIFY_HEADER_FOOTER_PATH . 'templates/content/content-footer.php';
	}
	load_template( $path );
}
