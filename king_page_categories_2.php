<?php
/**
 * The template for displaying the Categories page
 *
 * Template Name: categories 2
 *
 * @package King
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header(); ?>
<?php $GLOBALS['hide'] = 'hide'; ?>
<header class="page-top-header categories">
	<h1 class="page-title"><?php esc_html_e( 'Categorias', 'king' ); ?> <i class="fa fa-sliders fa-lg" aria-hidden="true"></i></h1>
</header><!-- .page-header -->
<?php get_template_part( 'template-parts/king-header-nav' ); ?>
<div id="primary" class="content-area">
	<main id="main" class="site-main full-width">

		<div class="king-categories-page">
			<?php
			$categories = get_categories( array(
				'orderby' => 'count',
				'hide_empty' => false,
				'order' => 'DESC',
				'parent'=>141
			) );
			foreach ( $categories as $cat ) :
				$color = get_field( 'category_colors', 'category_' . $cat->term_id );
				$catlogo = get_field( 'category_logo', 'category_' . $cat->term_id );
				$size = 'thumbnail';
				$thumb = $catlogo['sizes'][ $size ];
				$bgimage = get_field( 'category_background_image', 'category_' . $cat->term_id );
				if ( $bgimage ) {
					$bgimage = 'background-image:url(' . $bgimage . ');';
				}
				?>    
				<a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" class="king-page-cat-links">
				<?php if( $color || $bgimage || $catlogo ) : ?>
					<div class="king-page-badge" style="background-color: <?php echo esc_attr( $color ); ?>; <?php echo esc_attr( $bgimage ); ?>" >
						<?php if( $catlogo ) : ?>
							<img src="<?php echo esc_attr( $thumb ); ?>" class="cat-logo">
						<?php endif; ?>
				<?php else : ?>
					<div class="king-page-badge" >
				<?php endif; ?>					
						<div class="king-categories-head-2">
							<?php echo esc_attr( $cat->name ); ?>
						</div>
						<div class="king-categories-desc">
							<?php echo esc_attr( $cat->description ); ?>
						</div>						
					</div>
				</a>	
				<?php endforeach; ?>
			</div>
		</main><!-- #main -->
	</div><!-- #primary -->

	<?php get_footer(); ?>
