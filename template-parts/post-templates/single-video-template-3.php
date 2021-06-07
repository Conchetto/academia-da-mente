<?php
/**
 * Sible video template-2 page.
 *
 * @package King
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div id="primary" class="content-area videotemplate-v3">
<?php if ( get_field( 'nsfw_post' ) && ! is_user_logged_in() ) : ?>
	<div class="post-video nsfw-post-page">
		<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_login'] ); ?>">
			<i class="fa fa-paw fa-3x"></i>
			<div><h1><?php echo esc_html_e( 'Not Safe For Work', 'king' ); ?></h1></div>
			<span><?php echo esc_html_e( 'Click to view this post.', 'king' ); ?></span>
		</a>	
	</div>
<?php else : ?>
	<?php
		$floating = '';
	if ( get_field( 'enable_floating_video', 'options' ) ) :
		$floating = 'floating-video';
	endif;
	?>
	<div class="post-video embed-responsive embed-responsive-16by9 <?php echo esc_attr( $floating ); ?>">	
		<?php
		if ( has_post_thumbnail() ) :
			$audio_thumb = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'large' );
		else :
			$audio_thumb['0'] = '';
		endif;
		if ( get_field( 'video_tab', get_the_ID() ) ) :
			$videofile = get_field( 'video_upload', get_the_ID() );
		if ( $videofile['type'] === 'audio' ) : ?>
			<audio id="king-audio" class="video-js vjs-default-skin" preload="auto" poster="<?php echo esc_url( $audio_thumb['0'] ); ?>" data-setup='{ "controls": true, "preload": "auto" }'>
				<source src="<?php echo esc_url( $videofile['url'] ); ?>" type='audio/mp3'/>
			</audio>
		<?php elseif ( $videofile['type'] === 'video' ) : ?>
			<video id="king-video" class="video-js" controls preload="auto" poster="<?php echo esc_url( $audio_thumb['0'] ); ?>" data-setup='{}'>
				<source src="<?php echo esc_url( $videofile['url'] ); ?>" type="video/mp4"></source>
			</video>
		<?php else : ?>	
			<?php the_field( 'media_embed_code', get_the_ID() ); ?>
		<?php endif; ?>
	<?php else :
	the_field( 'video-url', get_the_ID() );
	endif; ?>
		<?php if ( get_field( 'enable_ad_video','options' ) ) : ?>
			<div class="king-loading-ad">
				<div class="king-loading-ad-content">
					<?php the_field( 'video_load_ad','options' ); ?>
					<span class="skip-ad">
						<span id="notice"><?php echo esc_html_e( 'You can skip ad in', 'king' ) ?> <?php the_field( 'skip_ad_after','options' ); ?>s</span> 
						<span id="hidead" style="display: none" ><?php echo esc_html_e( 'Skip Ad', 'king' ) ?></span>
					</span>
				</div>
			</div>
		<?php endif; ?>
	</div>	
<?php endif; ?>		
	<main id="main" class="site-main post-page single-video videotemplate">	
<?php get_template_part( 'template-parts/post-templates/single-parts/share' ); ?>
<?php if ( get_field( 'ads_above_content', 'option' ) ) : ?>
	<div class="ads-postpage"><?php $ad_above = get_field( 'ads_above_content','options' ); echo do_shortcode( $ad_above ); ?></div>
<?php endif; ?>
<?php while ( have_posts() ) : the_post(); ?>
	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php if ( get_field( 'add_sponsor' ) ) : ?>
			<div class="add-sponsor"><a href="<?php the_field( 'post_sponsor_link' ); ?>" target="_blank"><img src="<?php the_field( 'post_sponsor_logo' ); ?>" /></a><span class="sponsor-label"><?php the_field( 'post_sponsor_description' ); ?></span></div>
		<?php endif; ?>			
		<header class="entry-header">
			<?php
			if ( is_single() ) {
				the_title( '<h1 class="entry-title">', '</h1>' );
			} else {
				the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
			}
			?>
		</header><!-- .entry-header -->
		<div class="post-page-featured-trending">
			<div class="post-like">
				<?php echo king_get_simple_likes_button( get_the_ID() ); ?>
				<?php  if ( ! is_user_logged_in() ) : ?>
					<div class="king-alert-like"><?php esc_html_e( 'Please ', 'king' ) ?><a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_login'] ); ?>"><?php esc_html_e( 'log in ', 'king' ) ?></a><?php esc_html_e( ' or ', 'king' ) ?><a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_register'] ); ?>"><?php esc_html_e( ' register ', 'king' ) ?></a><?php esc_html_e( ' to like posts. ', 'king' ) ?></div>
				<?php endif; ?>
			</div><!-- .post-like -->
			<a class="video-entry-format entry-format" href="<?php echo esc_url( get_post_format_link( 'video' ) ); ?>"><?php echo esc_html_e( 'Video', 'king' ) ?></a>
			<?php if ( get_field( 'featured-post' )  ||  get_field( 'keep_trending' ) ) :?>		
				<?php if ( get_field( 'featured-post' ) ) : ?>
					<div class="featured"><i class="fa fa-rocket fa-lg" aria-hidden="true"></i><span><?php echo esc_html_e( 'featured', 'king' ) ?></span></div><!-- .featured -->
				<?php endif; ?>
				<?php if ( get_field( 'keep_trending' ) ) : ?>
					<div class="trending"><i class="fa fa-bolt fa-lg" aria-hidden="true"></i><span><?php echo esc_html_e( 'trending', 'king' ) ?></span></div><!-- .trending -->
				<?php endif; ?>
			<?php endif; ?>
		</div><!-- .post-page-featured-trending -->
		<div class="entry-content">
			<?php
			the_content( sprintf(
				/* translators: %s: Name of current post. */
				wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'king' ), array( 'span' => array( 'class' => array() ) ) ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			) );

			wp_link_pages( array(
				'before' => '<div class="page-links">',
				'after'  => '</div>',
			) );
				?>
			</div><!-- .entry-content -->

			<footer class="entry-footer">
				<?php king_entry_footer(); ?>		
				<div class="post-meta">
					<span class="post-views"><i class="fa fa-eye" aria-hidden="true"></i><?php echo esc_attr( king_postviews( get_the_ID(), 'display' ) ); ?></span>
					<span class="post-comments"><i class="fa fa-comment" aria-hidden="true"></i><?php comments_number( ' 0 ', ' 1 ', ' % ' ); ?></span>
					<span class="post-time"><i class="far fa-clock"></i><?php the_time( 'F j, Y' ); ?></span>
				</div>
				<?php
				if ( is_super_admin() ) :
					edit_post_link(
						sprintf(
							/* translators: %s: Name of current post */
							esc_html__( 'Edit %s', 'king' ),
							the_title( '<span class="screen-reader-text">"', '"</span>', false )
						),
						'<span class="edit-link">',
						'</span>'
					);
						endif;	?>
				<div class="post-nav post-nav-mobile">
					<?php
					if ( ! empty( $next_post ) ) : ?>
						<a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" title="<?php echo esc_attr( $next_post->post_title ); ?>" class="prev-link" ><i class="fa fa-angle-left"></i></a>
					<?php endif; ?>
					<?php
					if ( ! empty( $prev_post ) ) : ?>
						<a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>" title="<?php echo esc_attr( $prev_post->post_title ); ?>" class="prev-link" ><i class="fa fa-angle-right"></i></a>
					<?php endif; ?>
				</div><!-- .post-nav-mobile -->				
					</footer><!-- .entry-footer -->

				</div><!-- #post-## -->
	<?php get_template_part( 'template-parts/post-templates/single-parts/single-boxes' ); ?>
	<?php
			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
		?>
	<?php if ( get_field( 'display_related', 'options' ) ) : ?>
		<?php get_template_part( 'template-parts/related-posts' ); ?>
	<?php endif; ?>
<?php if ( get_post_status( $post->ID ) === 'pending' ) : ?>
	<div class="king-pending"><?php esc_html_e( 'This Video post will be checked and approved shortly.', 'king' ) ?></div>
<?php endif; ?>		
<span class="remove-fixed"></span>
</main><!-- #main -->
<?php get_sidebar(); ?> 	

</div><!-- #primary -->
<?php get_footer(); ?>
