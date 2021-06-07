<?php
/**
 * The header part - left menu.
 *
 * This is the header template part.
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

<div class="king-leftmenu">
	<?php if ( ! get_field( 'hide_news_Link', 'options' ) ) : ?>
		<a href="<?php echo esc_url( get_post_format_link( 'quote' ) ); ?>"><span class="nav-icon nav-news" ></span><?php echo esc_html_e( 'News', 'king' ); ?></a><?php endif; ?>
		<?php if ( ! get_field( 'hide_video_Link', 'options' ) ) : ?>
			<a href="<?php echo esc_url( get_post_format_link( 'video' ) ); ?>"><span class="nav-icon nav-video" ></span><?php echo esc_html_e( 'Video', 'king' ); ?></a><?php endif; ?>
			<?php if ( ! get_field( 'hide_image_Link', 'options' ) ) : ?>
				<a href="<?php echo esc_url( get_post_format_link( 'image' ) ); ?>"><span class="nav-icon nav-image" ></span><?php echo esc_html_e( 'Image', 'king' ); ?></a><?php endif; ?>
				<?php if ( have_rows( 'add_new_links_to_header', 'option' ) ) : ?>
					<?php
					while ( have_rows( 'add_new_links_to_header', 'option' ) ) :
						the_row();
						?>
						<a href="<?php the_sub_field( 'header_nav_url' ); ?>"><?php the_sub_field( 'header_nav_icon' ); ?><?php the_sub_field( 'header_nav_text' ); ?></a>
					<?php endwhile; ?>
				<?php endif; ?>
				<div class="king-cat-list-mobile">
					<?php wp_nav_menu( array( 'theme_location' => 'header-menu' ) ); ?>
				</div>
</div><!-- .king-head-mobile -->
