<?php
/**
 * Gallery View.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package king
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="king-gallery king-modal-login modal" id="gallery" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<?php while ( have_posts() ) : the_post(); ?>
		<div class="king-gallery-container">
			<div class="king-gallery-header">
				<?php
				if ( get_field( 'page_logo', 'options' ) ) :
					$logo = get_field( 'page_logo', 'options' );
					?>
					<a href="<?php echo esc_url( site_url() ); ?>" class="king-gallery-logo">
						<img src="<?php echo esc_url( $logo['url'] ); ?>" alt="<?php echo esc_attr( $logo['alt'] ); ?>"/>
					</a>
				<?php endif; ?>
				<div class="king-gallery-title">
					<header><?php echo wp_kses_data( the_title( '<h3 class="entry-title">', '</h3>' ) ); ?></header>
					<?php
					$author = get_the_author_meta( 'user_nicename' );
					?>
					<?php esc_html_e( 'by ', 'king' ); ?><a class="king-gallery-author" href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_account'] . '/' . $author ); ?>"><?php echo esc_attr( $author ); ?></a>
				</div>
				<div class="king-gallery-thumbs">
					<?php if ( has_post_thumbnail() ) : ?>
						<a href="<?php echo esc_url( '#first' ); ?>" class="king-gallery-thumb">
							<?php echo get_the_post_thumbnail( get_the_ID(), 'thumbnail' ); ?>
						</a>
					<?php endif; ?>
					<?php if ( get_field( 'enable_lightbox_ad', 'option' ) ) : ?>
						<a href="<?php echo esc_url( '#second' ); ?>" class="king-gallery-thumb gallery-ad"></a>
					<?php endif; ?>
					<?php if ( have_rows( 'images_lists' ) ) : ?>
						<?php
						while ( have_rows( 'images_lists' ) ) :
							the_row();
							$image = get_sub_field( 'images_list' );
							$thumb = $image['sizes']['thumbnail'];
							?>
							<a href="<?php echo esc_url( '#' . $image['ID'] ); ?>" class="king-gallery-thumb" >				
								<?php if ( $image ) : ?>
									<img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" />
								<?php endif; ?>
							</a>
						<?php endwhile; ?>
					<?php endif; ?>
				</div>
				<button type="button" class="king-gallery-close" data-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
			</div>
			<div class="king-gallery-images owl-carousel">
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="king-gallery-image" data-hash="first">
						<?php echo get_the_post_thumbnail( get_the_ID(), 'full' ); ?>
					</div>
				<?php endif; ?>
				<?php if ( get_field( 'enable_lightbox_ad', 'option' ) ) : ?>
					<div class="king-gallery-image" data-hash="second">
						<?php echo do_shortcode( the_field( 'lightbox_gallery_ad_code', 'options' ) ); ?>
					</div>
				<?php endif; ?>
				<?php if ( have_rows( 'images_lists' ) ) : ?>
					<?php
					while ( have_rows( 'images_lists' ) ) :
						the_row();
						$image = get_sub_field( 'images_list' );
						?>
							<div class="king-gallery-image" data-hash="<?php echo esc_attr( $image['ID'] ); ?>">				
								<?php if ( $image ) : ?>
									<img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" />
								<?php endif; ?>
							</div>
					<?php endwhile; ?>
				<?php endif; ?>	
			</div>
		</div>
	<?php endwhile; ?>
</div>
