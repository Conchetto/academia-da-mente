<?php
/**
 * Template part for displaying results in profile page.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package king
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$snothumb = '';
?>
<div class="king-simple-post">
	<?php if ( get_field( 'nsfw_post' ) && ! is_user_logged_in() ) : ?>
	<div class="nsfw-post-simple">
		<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_login'] ); ?>">
			<i class="fa fa-paw fa-3x"></i>
			<div><h1><?php echo esc_html_e( 'Not Safe For Work', 'king' ); ?></h1></div>
			<span><?php echo esc_html_e( 'Click to view this post.', 'king' ); ?></span>
		</a>    
	</div>
	<?php else : ?> 
		<a href="<?php the_permalink(); ?>" class="simple-post-thumb">
			<?php
			if ( has_post_thumbnail() ) :
				$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' );
				?>
				<div class="simple-post-image" style="background-image: url('<?php echo esc_url( $thumb['0'] ); ?>')"></div>
				<?php else : ?>
					<?php $snothumb = ' simple-nothumb'; ?>
				<?php endif; ?> 
			</a>	
			<?php if ( get_field( 'featured-post' ) || get_field( 'keep_trending' ) ) : ?>  
			<div class="simplepost-featured-trending<?php echo esc_attr( $snothumb ); ?>">       
				<?php if ( get_field( 'featured-post' ) ) : ?>
					<div class="featured"><i class="fa fa-rocket fa-lg" aria-hidden="true"></i><span><?php echo esc_html_e( 'featured', 'king' ); ?></span></div><!-- .featured -->
				<?php endif; ?>
				<?php if ( get_field( 'keep_trending' ) ) : ?>
					<div class="trending"><i class="fa fa-bolt fa-lg" aria-hidden="true"></i><span><?php echo esc_html_e( 'trending', 'king' ); ?></span></div><!-- .trending -->
				<?php endif; ?>
			</div>
		<?php endif; ?>
	<?php endif; ?> 			
	<header class="simple-post-header<?php echo esc_attr( $snothumb ); ?>">
		<?php the_title( sprintf( '<span class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></span>' ); ?>
		<?php if ( has_post_format( 'quote' ) ) : ?>
			<a class="simple-post-entry-format" href="<?php echo esc_url( get_post_format_link( 'quote' ) ); ?>"><?php echo esc_html_e( 'News', 'king' ); ?></a>
			<?php elseif ( has_post_format( 'video' ) ) : ?>
				<a class="simple-post-entry-format" href="<?php echo esc_url( get_post_format_link( 'video' ) ); ?>"><?php echo esc_html_e( 'Video', 'king' ); ?></a>
				<?php elseif ( has_post_format( 'image' ) ) : ?>
					<a class="simple-post-entry-format" href="<?php echo esc_url( get_post_format_link( 'image' ) ); ?>"><?php echo esc_html_e( 'Image', 'king' ); ?></a>
				<?php endif; ?>
			</header><!-- .entry-header -->
			<div class="entry-meta">
				<span class="post-likes"><i class="fa fa-thumbs-up" aria-hidden="true"></i>
					<?php
					$slikes = get_post_meta( get_the_ID(), '_post_like_count', true );
					if ( ! empty( $slikes ) ) {
						echo esc_attr( $slikes );
					} else {
						echo '0';
					}
					?>
				</span>
				<span class="post-views"><i class="fa fa-eye" aria-hidden="true"></i><?php echo esc_attr( king_postviews( get_the_ID(), 'display' ) ); ?></span>
				<span class="post-comments"><i class="fa fa-comment" aria-hidden="true"></i><?php comments_number( ' 0 ', ' 1 ', ' % ' ); ?></span>
				<span class="post-time"><i class="far fa-clock"></i><?php the_time( 'F j, Y' ); ?></span>
			</div><!-- .entry-meta -->	
		</div><!-- #post-## -->
