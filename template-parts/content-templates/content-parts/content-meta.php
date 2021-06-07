<?php
/**
 * The content part - meta.
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
<div class="entry-meta">
	<span class="post-likes"><i class="fa fa-thumbs-up" aria-hidden="true"></i>
		<?php
		$likes = get_post_meta( get_the_ID(), '_post_like_count', true );
		$likes = ( isset( $likes ) && is_numeric( $likes ) ) ? $likes : 0;
		echo esc_attr( $likes );
		?>
	</span>
	<span class="post-views"><i class="fa fa-eye" aria-hidden="true"></i><?php echo esc_attr( king_postviews( get_the_ID(), 'display' ) ); ?></span>
	<span class="post-comments"><i class="fa fa-comment" aria-hidden="true"></i><?php comments_number( ' 0 ', ' 1 ', ' % ' ); ?></span>
	<span class="post-time"><i class="far fa-clock"></i><?php the_time( 'F j, Y' ); ?></span>
</div><!-- .entry-meta -->

