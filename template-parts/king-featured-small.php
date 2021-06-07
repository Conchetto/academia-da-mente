<?php
/**
 * Featured Posts Mini Slider.
 *
 * @package King
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php
if ( get_field( 'show_in_mini_slider', 'options' ) === 'featured-post' ) {
	$meta_key = 'featured-post';
} elseif ( get_field( 'show_in_mini_slider', 'options' ) === 'keep_trending' ) {
	$meta_key = 'keep_trending';
} else {
	$meta_key = 'editors_choice';
}
if ( get_field( 'display_mini_slider', 'options' ) ) {
	$posts_per_page = get_field( 'mini_items_length', 'options' );
}
// get posts.
$featured = get_posts(array(
	'posts_per_page'    => $posts_per_page,
	'meta_key'          => $meta_key,
	'meta_value'        => '1',
	'orderby'           => 'modified',
	'order'             => 'DESC',
));

if ( $featured ) : ?>

	<div class="king-editorschoice">
		<div class="king-featured-small owl-carousel">
			<?php foreach ( $featured as $post ) : ?>
				<div class="editorschoice-post">
					<?php if ( get_field( 'nsfw_post' ) && ! is_user_logged_in() ) : ?>
					<div class="nsfw-users-post">
						<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_login'] ); ?>" >
							<i class="fa fa-paw fa-3x"></i>
							<div><h1><?php echo esc_html_e( 'Not Safe For Work', 'king' ) ?></h1></div>
							<span><?php echo esc_html_e( 'Click to view this post.', 'king' ) ?></span>
						</a>    
					</div>
					<?php else : ?>
						<?php if ( has_post_thumbnail() ) :
							$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' ); ?>
							<div class="editorschoice-post-img" style="background-image: url('<?php echo esc_url( $thumb['0'] ); ?>')"></div>
							<?php else : ?>
								<span class="editorschoice-post-no-thumb"></span>
							<?php endif; ?>     
								<div class="editorschoice-post-in">
								<?php king_entry_cat(); ?>     
									<span class="editorschoice-post-title" ><a href="<?php the_permalink(); ?>"> <?php the_title(); ?></a> </span>
								</div>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php wp_reset_postdata(); ?>
	<?php endif; ?>
