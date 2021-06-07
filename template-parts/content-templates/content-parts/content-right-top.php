<?php
/**
 * The content part - right top.
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
<div class="content-right-top">
	<span class="king-post-format">
		<?php if ( has_post_format( 'quote' ) ) : ?>
			<a class="entry-format-news" href="<?php echo esc_url( get_post_format_link( 'quote' ) ); ?>"><?php echo esc_html_e( 'News', 'king' ); ?></a>
		<?php elseif ( has_post_format( 'video' ) ) : ?>
			<a class="entry-format-video" href="<?php echo esc_url( get_post_format_link( 'video' ) ); ?>"><?php echo esc_html_e( 'Video', 'king' ); ?></a>
		<?php elseif ( has_post_format( 'image' ) ) : ?>
			<a class="entry-format-image" href="<?php echo esc_url( get_post_format_link( 'image' ) ); ?>"><?php echo esc_html_e( 'Image', 'king' ); ?></a>
		<?php endif; ?>
	</span><!-- .king-post-format -->
	<div class="content-middle">
		<span class="content-share-counter">
			<span class="content-middle-open" data-toggle="dropdown" data-target=".content-m-<?php the_ID(); ?>" aria-expanded="false" role="button">
				<i class="fas fa-share-alt"></i>
			</span>
			<span><?php echo esc_attr( get_post_meta( get_the_ID(), 'share_counter', true ) ); ?></span>
		</span>	
		<div class="content-m-<?php the_ID(); ?> content-middle-content">
			<?php echo esc_attr( king_social_share() ); ?>	
		</div>		
	</div><!-- .content-middle -->
	<span class="content-avatar">
		<?php
		$author    = get_the_author_meta( 'user_nicename' );
		$author_id = $post->post_author;
		if ( get_field( 'author_image', 'user_' . $author_id ) ) {
			$image = get_field( 'author_image', 'user_' . $author_id );
			?>
		<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_account'] . '/' . $author ); ?>">
			<img class="content-author-avatar" src="<?php echo esc_url( $image['sizes']['thumbnail'] ); ?>" />
		</a>	
		<?php } ?>
	</span>
</div>

