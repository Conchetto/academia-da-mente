<?php
/**
 * The template for displaying the NSFW page
 *
 * Template Name: nsfw
 *
 * @package King
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header(); ?>
<header class="page-top-header nsfw">
	<h1 class="page-title"><?php esc_html_e( 'NSFW', 'king' ); ?> <i class="fa fa-paw"></i></h1>
</header><!-- .page-header -->
<?php get_template_part( 'template-parts/king-header-nav' ); ?>
<div id="primary" class="content-area king-nsfw">
	<div id="king-pagination-02" class="site-main-top">
		<main id="main" class="site-main full-width" role="main">
			<ul class="king-posts">
				<li class="grid-sizer"></li>
				<?php
				if ( have_posts() ) :
					/* Start the Loop */
					$paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;

					if ( get_field( 'length_nsfw', 'options' ) ) {
						$length_nsfw = get_field( 'length_nsfw', 'option' );
					} else {
						$length_nsfw = '10';
					}

					$args = array(
						'posts_per_page' => $length_nsfw,
						'meta_key'       => 'nsfw_post',
						'meta_value'     => '1',
						'orderby'        => 'modified',
						'order'          => 'DESC',
						'paged'          => $paged,
						'post__not_in'   => get_option( 'sticky_posts' ),
					);

					$nsfw = new WP_Query( $args );

					while ( $nsfw->have_posts() ) : $nsfw->the_post();

					/*
					 * Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'template-parts/content', get_post_format() );

				endwhile;

				?>
				<div class="king-pagination">
					<?php

							$big = 999999999; // need an unlikely integer.
							echo paginate_links( array(
								'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
								'format'    => '?paged=%#%',
								'current'   => max( 1, get_query_var( 'paged' ) ),
								'total'     => $nsfw->max_num_pages,
								'prev_next' => true,
								'prev_text' => '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
								'next_text' => '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
							) );
							?>
						</div>
						<?php
					else :

						get_template_part( 'template-parts/content', 'none' );

					endif;
					?>
				</ul>
			</main><!-- #main -->
		</div>
	</div><!-- #primary -->

	<?php get_footer(); ?>
