<?php
/**
 * Post Templates 04.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package king
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<li class="king-post-item">
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="entry-08-meta">
			<span class="content-08-avatar">
				<?php
				$author    = get_the_author_meta( 'user_nicename' );
				$author_id = $post->post_author;
				if ( get_field( 'author_image', 'user_' . $author_id ) ) :
					$image = get_field( 'author_image', 'user_' . $author_id );
					?>
				<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_account'] . '/' . $author ); ?>">
					<img class="content-author-avatar" src="<?php echo esc_url( $image['sizes']['thumbnail'] ); ?>" />
				</a>	
				<?php endif; ?>
			</span>
			<span class="content-08-name">
				<a class="content-08-user" href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_account'] . '/' . $author ); ?>" ><?php echo esc_attr( $author ); ?></a>
				<span class="content-08-post-time"><?php the_time( 'F j, Y' ); ?></span>
			</span>
			<span class="king-post-format">
				<?php if ( has_post_format( 'quote' ) ) : ?>
					<a class="entry-format-news" href="<?php echo esc_url( get_post_format_link( 'quote' ) ); ?>"><?php echo esc_html_e( 'News', 'king' ); ?></a>
				<?php elseif ( has_post_format( 'video' ) ) : ?>
					<a class="entry-format-video" href="<?php echo esc_url( get_post_format_link( 'video' ) ); ?>"><?php echo esc_html_e( 'Video', 'king' ); ?></a>
				<?php elseif ( has_post_format( 'image' ) ) : ?>
					<a class="entry-format-image" href="<?php echo esc_url( get_post_format_link( 'image' ) ); ?>"><?php echo esc_html_e( 'Image', 'king' ); ?></a>
				<?php endif; ?>
			</span><!-- .king-post-format -->
		</div>
	<?php get_template_part( 'template-parts/content-templates/content-parts/content-thumb' ); ?>
	<header class="entry-header">
		<?php
		if ( is_single() ) {
			the_title( '<h1 class="entry-title">', '</h1>' );
		} else {
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		}
		?>
	</header><!-- .entry-header -->
	<div class="article-meta-08">

		<div class="entry-meta-left">
			<span class="post-likes"><i class="fas fa-heart"></i>
				<?php
				$likes = get_post_meta( get_the_ID(), '_post_like_count', true );
				$likes = ( isset( $likes ) && is_numeric( $likes ) ) ? $likes : 0;
				echo esc_attr( $likes );
				?>
			</span>
			<span class="post-views"><i class="fa fa-eye" aria-hidden="true"></i><?php echo esc_attr( king_postviews( get_the_ID(), 'display' ) ); ?></span>
			<span class="post-comments"><i class="fa fa-comment" aria-hidden="true"></i><?php comments_number( ' 0 ', ' 1 ', ' % ' ); ?></span>
		</div>
	</div><!-- .article-meta -->	
</article><!--#post-##-->
</li>
