<?php
/**
 * The template for displaying the Trend page
 *
 * Template Name: trend
 *
 * @package King
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header(); ?>
<?php $GLOBALS['hide'] = 'hide'; ?>
<header class="page-top-header trend">
	<h1 class="page-title"><?php esc_html_e( 'Trending', 'king' ); ?> <i class="fa fa-bolt fa-lg" aria-hidden="true"></i></h1>
</header><!-- .page-header -->
<?php get_template_part( 'template-parts/king-header-nav' ); ?>

<div id="primary" class="content-area">
	<div class="site-main-top">
		<main id="main" class="site-main full-width">
			<ul class="king-posts">
				<li class="grid-sizer"></li>
			<?php
			if ( have_posts() ) :
				/* Start the Loop */

				if ( get_field( 'length_trend', 'options' ) ) {
					$length_trend = get_field( 'length_trend', 'option' );
				} else {
					$length_trend = '10';
				}

				$args = array(
					'posts_per_page' => $length_trend,
					'meta_key'       => 'keep_trending',
					'meta_value'     => '1',
					'orderby'        => 'modified',
					'order'          => 'DESC',
					'post__not_in'   => get_option( 'sticky_posts' ),
				);

				$trend = new WP_Query( $args );

				while ( $trend->have_posts() ) :
					$trend->the_post();

					/*
					 * Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'template-parts/content', get_post_format() );


				endwhile;

			else :

				get_template_part( 'template-parts/content', 'none' );

			endif;
			?>
			<?php wp_reset_postdata(); ?>
			</ul>
		</main><!-- #main -->
	</div>
</div><!-- #primary -->

<?php get_footer(); ?>
