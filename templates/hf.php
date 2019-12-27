<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<section id="primary" class="tour-area content-tour">
		<main id="main" class="site-main">

		<?php
		while ( have_posts() ) :
			the_post();

			?>

			<section id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="wt-listing">
					<?php the_content(); ?>
				</div>
			</section>
		<?php
			// If comments are open or we have at least one comment, load up the comment template.


		endwhile; // End of the loop.
		wp_reset_postdata();
		?>

		</main><!-- #main -->
	</section><!-- #primary -->
</body>
<?php
wp_footer();

