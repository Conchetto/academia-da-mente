<?php
/**
 * The template for displaying the Hot page
 *
 * Template Name: reactions
 *
 * @package King
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header(); ?>
<header class="page-top-header reactions">
	<h1 class="page-title"><?php esc_html_e( 'Reactions', 'king' ); ?></h1>
</header><!-- .page-header -->
<?php get_template_part( 'template-parts/king-header-nav' ); ?>
<div id="primary" class="content-area king-reactions-page">
			<div class="king-order-nav king-reaction-nav">
				<ul>
					<li>					
						<a class="king-reaction-item-icon king-reaction-like <?php if ( ! isset( $_GET['orderby'] ) ) { echo 'active'; } ?>" href="<?php echo esc_url( get_permalink() ); ?>" ></a>
					</li>
					<li>
						<a href="<?php echo esc_url( get_permalink() . '?orderby=love' ); ?>" class="king-reaction-item-icon king-reaction-love <?php if ( isset( $_GET['orderby'] ) && $_GET['orderby'] === 'love' ) {  echo 'active'; } ?>"></a>
					</li>
					<li>
						<a href="<?php echo esc_url( get_permalink() . '?orderby=haha' ); ?>" class="king-reaction-item-icon king-reaction-haha <?php if ( isset( $_GET['orderby'] ) && $_GET['orderby'] === 'haha' ) { echo 'active'; } ?>"></a>
					</li>   
					<li>
						<a href="<?php echo esc_url( get_permalink() . '?orderby=wow' ); ?>" class="king-reaction-item-icon king-reaction-wow <?php if ( isset( $_GET['orderby'] ) && $_GET['orderby'] === 'wow' ) { echo 'active'; } ?>"></a>
					</li>    
					<li>
						<a href="<?php echo esc_url( get_permalink() . '?orderby=sad' ); ?>" class="king-reaction-item-icon king-reaction-sad <?php if ( isset( $_GET['orderby'] ) && $_GET['orderby'] === 'sad' ) { echo 'active'; } ?>"></a>
					</li>	
					<li>
						<a href="<?php echo esc_url( get_permalink() . '?orderby=angry' ); ?>" class="king-reaction-item-icon king-reaction-angry <?php if ( isset( $_GET['orderby'] ) && $_GET['orderby'] === 'angry' ) { echo 'active'; } ?>"></a>
					</li>									   
				</ul>
			</div>
	<div id="switchview" class="site-main-top">
		<main id="main" class="site-main full-width">
			<ul class="king-posts">
				<li class="grid-sizer"></li>
			<?php
			if ( have_posts() ) :
				/* Start the Loop */

				$paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;

				if ( get_field( 'length_reaction', 'options' ) ) {
					$length_hot = get_field( 'length_reaction', 'option' );
				} else {
					$length_hot = '10';
				}

				if ( isset( $_GET['orderby'] ) &&  $_GET['orderby'] === 'love' ) { // input var okay; sanitization.

					$meta_key = 'king_reaction_love';

				} elseif ( isset( $_GET['orderby'] ) && $_GET['orderby'] === 'haha' ) { // input var okay; sanitization.

					$meta_key = 'king_reaction_haha';

				} elseif ( isset( $_GET['orderby'] ) && $_GET['orderby'] === 'wow' ) { // input var okay; sanitization.

					$meta_key = 'king_reaction_wow';

				} elseif ( isset( $_GET['orderby'] ) && $_GET['orderby'] === 'sad' ) { // input var okay; sanitization.

					$meta_key = 'king_reaction_sad';

				} elseif ( isset( $_GET['orderby'] ) && $_GET['orderby'] === 'angry' ) { // input var okay; sanitization.

					$meta_key = 'king_reaction_angry';

				} else {

					$meta_key = 'king_reaction_like';

				}
				$args = array(
					'posts_per_page' => $length_hot,
					'meta_key' => $meta_key,
					'orderby' => 'meta_value_num',
					'order' => 'DESC',
					'paged' => $paged,
					'post__not_in' => get_option( 'sticky_posts' ),
					);
				$query = new WP_Query( $args );

				while ( $query->have_posts() ) : $query->the_post();

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
								'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
								'format' => '?paged=%#%',
								'current' => max( 1, get_query_var( 'paged' ) ),
								'total' => $query->max_num_pages,
								'prev_next'    => true,
								'prev_text'          => '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
								'next_text'          => '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
							) );
								?>
							</div>
							<?php wp_reset_postdata(); ?>
							<?php else :

								get_template_part( 'template-parts/content', 'none' );

							endif; ?>
						</ul>
						</main><!-- #main -->
					</div>
				</div><!-- #primary -->

				<?php get_footer(); ?>
