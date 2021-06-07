<?php
/**
 * The content part - thumb.
 *
 * This is a content template part.
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
<?php if ( get_field( 'nsfw_post' ) && ! is_user_logged_in() ) : ?>
<div class="nsfw-post">
	<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_login'] ); ?>">
		<i class="fa fa-paw fa-3x"></i>
		<div><h1><?php echo esc_html_e( 'Not Safe For Work', 'king' ); ?></h1></div>
		<span><?php echo esc_html_e( 'Click to view this post.', 'king' ); ?></span>
	</a>	
</div>
<?php else : ?> 	
	<a href="<?php the_permalink(); ?>" class="entry-image-link">
		<?php if ( has_post_thumbnail() ) :
			$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' ); ?>
			<div class="entry-image" style="background-image: url('<?php echo esc_url( $thumb['0'] ); ?>'); height:<?php echo esc_attr( $thumb[2] . 'px;' ); ?>"></div>
		<?php else : ?>
			<span class="entry-no-thumb"></span>
		<?php endif; ?>
		<?php if ( get_field( 'editors_choice' ) ) : ?>
			<div class="editors-badge">
				<?php
				if ( get_field( 'editors_choice_title', 'option' ) ) {
					the_field( 'editors_choice_title', 'option' );
				} else {
					echo esc_html_e( 'Editors\' Choice', 'king' );
				}
				?>
			</div>
		<?php endif; ?>			
	</a>
<?php endif; ?>
<div class="post-featured-trending">	
	<?php if ( get_field( 'featured-post' ) ) : ?>
		<div class="featured"><i class="fa fa-rocket fa-lg" aria-hidden="true"></i><span><?php echo esc_html_e( 'featured', 'king' ); ?></span></div><!-- .featured -->
	<?php endif; ?>
	<?php if ( get_field( 'keep_trending' ) ) : ?>
		<div class="trending"><i class="fa fa-bolt fa-lg" aria-hidden="true"></i><span><?php echo esc_html_e( 'trending', 'king' ); ?></span></div><!-- .trending -->
	<?php endif; ?>
	<?php if ( is_sticky() ) : ?>
		<div class="trending sticky"><i class="fa fa-paperclip fa-lg" aria-hidden="true"></i><span><?php echo esc_html_e( 'sticky', 'king' ); ?></span></div><!-- .trending -->
	<?php endif; ?>
</div>
