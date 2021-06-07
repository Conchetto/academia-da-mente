<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package king
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$nothumb = '';
?>
<li class="king-post-item">
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<a href="<?php the_field( 'ad_link' ); ?>" target="_blank" class="entry-image-link">
			<?php if ( has_post_thumbnail() ) :
				$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' ); ?>
				<div class="entry-image" style="background-image: url('<?php echo esc_url( $thumb['0'] ); ?>'); height:<?php echo esc_attr( $thumb[2] . 'px;' ); ?>"></div>
			<?php else :
				$nothumb = 'nothumb';
				?>
				<span class="entry-no-thumb"></span>
			<?php endif; ?>
		</a>
		<?php if ( get_field( 'sponsored_text' ) ) : ?>
			<div class="link-sponsored">
				<?php the_field( 'sponsored_text' ); ?>
			</div><!-- .link-content -->
		<?php endif; ?>	

		<div class="article-meta <?php echo esc_attr( $nothumb ); ?>">
			<div class="article-meta-head">		
				<header class="entry-header">
					<?php
					if ( is_single() ) {
						the_title( '<h1 class="entry-title">', '</h1>' );
					} else {
						the_title( '<h2 class="entry-title"><a href="' . esc_url( get_field( 'ad_link' ) ) . '" rel="bookmark">', '</a></h2>' );
					}
					?>
				</header><!-- .entry-header -->
				<?php if ( get_field( 'ad_extra_field' ) ) : ?>
					<div class="link-content">
						<?php the_field( 'ad_extra_field' ); ?>
					</div><!-- .link-content -->
				<?php endif; ?>			
			</div>
			<?php if ( get_field( 'ad_button_value' ) ) : ?>
				<a class="link-button" href="<?php the_field( 'ad_link' ); ?>" target="_blank">
					<?php the_field( 'ad_button_value' ); ?>
				</a><!-- .link-button -->			
			<?php endif; ?>	
		</div><!-- .article-meta -->	
	</article><!--#post-##-->
</li>
