<?php
/**
 * The header part - headnav.
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
<div class="king-head-nav">
	<?php if ( ! get_field( 'hide_news_Link', 'options' ) ) : ?>
		<a href="<?php echo esc_url( get_post_format_link( 'quote' ) ); ?>" class="king-head-nav-a"><span class="nav-icon nav-news" ></span><?php echo esc_html_e( 'News', 'king' ) ?></a><?php endif; ?>
		<?php if ( ! get_field( 'hide_video_Link', 'options' ) ) : ?>
			<a href="<?php echo esc_url( get_post_format_link( 'video' ) ); ?>" class="king-head-nav-a"><span class="nav-icon nav-video" ></span><?php echo esc_html_e( 'Video', 'king' ) ?></a><?php endif; ?><?php if ( ! get_field( 'hide_image_Link', 'options' ) ) : ?>
			<a href="<?php echo esc_url( get_post_format_link( 'image' ) ); ?>" class="king-head-nav-a"><span class="nav-icon nav-image" ></span><?php echo esc_html_e( 'Image', 'king' ) ?></a><?php endif; ?>
			<?php if ( have_rows( 'add_new_links_to_header', 'option' ) ) : ?>
				<?php while ( have_rows( 'add_new_links_to_header', 'option' ) ) : the_row(); ?>
					<a href="<?php the_sub_field( 'header_nav_url' ); ?>" class="king-head-nav-a"><?php the_sub_field( 'header_nav_icon' ); ?><?php the_sub_field( 'header_nav_text' ); ?></a><?php endwhile; ?>
				<?php endif; ?>			
				<?php
				if ( ! get_field( 'hide_categories', 'options' ) ) :
					$hmenutemplate = get_field_object( 'header_menu_layout', 'options' );
					?>
					<div class="king-hmenu">
						<span class="king-cat-dots" data-toggle="dropdown" data-target=".king-cat-list" aria-expanded="false" role="button">...</span>
						<?php if ( $hmenutemplate['value'] == 'hmenu-template-1' ) : ?>
							<div class="king-cat-list">
								<?php wp_nav_menu( array( 'theme_location' => 'header-menu' ) ); ?>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>
</div><!-- .king-head-nav -->
