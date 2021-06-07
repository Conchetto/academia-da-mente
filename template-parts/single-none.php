<?php
/**
 * Sible News page.
 *
 * @package King
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div id="primary" class="content-area">
	<main id="main" class="site-main post-page single-post no-permission">
		<?php if ( get_field( 'ads_above_content', 'option' ) ) : ?>
			<div class="ads-postpage"><?php $ad_above = get_field( 'ads_above_content', 'options' ); echo do_shortcode( $ad_above ); ?></div>
		<?php endif; ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<h3 class="entry-title">
						<i class="fas fa-lock fa-lg"></i>
						<?php esc_html_e( 'You do not have permission to see this post.', 'king' ); ?>
					</h3>
				</header><!-- .entry-header -->
				<?php if ( ! is_user_logged_in() ) : ?>
					<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_login'] ); ?>" class="king-alert-button"><?php esc_html_e( 'Log in ', 'king' ); ?></a>
					<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_register'] ); ?>"><?php esc_html_e( 'Register', 'king' ); ?></a>
				<?php endif; ?>
			</div>
		<?php get_template_part( 'template-parts/post-templates/single-parts/authorbox' ); ?>
		<?php endwhile; ?>
	<?php if ( get_field( 'display_related', 'options' ) ) : ?>
		<?php get_template_part( 'template-parts/related-posts' ); ?>
	<?php endif; ?>

<?php if ( get_post_status( $post->ID ) === 'pending' ) : ?>
	<div class="king-pending"><?php esc_html_e( 'This News post will be checked and approved shortly.', 'king' ); ?></div>
<?php endif; ?>

</main><!-- #main -->
<?php get_sidebar(); ?> 	

</div><!-- #primary -->
<?php get_footer(); ?>
