<?php
/**
 * Header Site
 *
 * Handle header Site.
 *
 * @package Boostify_Header_Footer_Template
 *
 * Written by ptp
 */

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php
	if ( function_exists( 'wp_body_open' ) ) {
		wp_body_open();
	}
	do_action( 'boostify_hf_header' );
?>

<div id="page" class="bhf-site">
<?php

do_action( 'boostify_hf_get_header' );
