<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
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
</div><!-- #content -->
	<footer id="colophon" class="site-footer">
	<?php if ( get_field( 'ad_in_footer', 'options' ) ) : ?>
		<div class="king-ads footer-ad"><?php $ad_footer = get_field( 'ad_in_footer', 'options' ); echo do_shortcode( $ad_footer ); ?></div>
	<?php endif; ?>		
	<?php
	if ( is_active_sidebar( 'first-footer-widget-area' ) && is_active_sidebar( 'second-footer-widget-area' ) && is_active_sidebar( 'third-footer-widget-area' ) && is_active_sidebar( 'fourth-footer-widget-area' )	) :	?>
	<aside class="fatfooter" role="complementary">
		<div class="first quarter left widget-area">
			<?php dynamic_sidebar( 'first-footer-widget-area' ); ?>
		</div><!-- .first .widget-area -->
		<div class="second quarter widget-area">
			<?php dynamic_sidebar( 'second-footer-widget-area' ); ?>
		</div><!-- .second .widget-area -->
		<div class="third quarter widget-area">
			<?php dynamic_sidebar( 'third-footer-widget-area' ); ?>
		</div><!-- .third .widget-area -->
		<div class="fourth quarter right widget-area">
			<?php dynamic_sidebar( 'fourth-footer-widget-area' ); ?>
		</div><!-- .fourth .widget-area -->
	</aside><!-- #fatfooter -->

<?php
	//end of check for all four sidebars. Next we check if there are three sidebars with widgets.
	elseif ( is_active_sidebar( 'first-footer-widget-area' ) && is_active_sidebar( 'second-footer-widget-area' ) && is_active_sidebar( 'third-footer-widget-area' )	&& ! is_active_sidebar( 'fourth-footer-widget-area' ) ) : ?>
	<aside class="fatfooter" role="complementary">
		<div class="first one-third left widget-area">
			<?php dynamic_sidebar( 'first-footer-widget-area' ); ?>
		</div><!-- .first .widget-area -->
		<div class="second one-third widget-area">
			<?php dynamic_sidebar( 'second-footer-widget-area' ); ?>
		</div><!-- .second .widget-area -->
		<div class="third one-third right widget-area">
			<?php dynamic_sidebar( 'third-footer-widget-area' ); ?>
		</div><!-- .third .widget-area -->
	</aside><!-- #fatfooter -->

	<?php
	//end of check for three sidebars. Next we check if there are two sidebars with widgets.
	elseif ( is_active_sidebar( 'first-footer-widget-area' ) && is_active_sidebar( 'second-footer-widget-area' ) && ! is_active_sidebar( 'third-footer-widget-area' ) && ! is_active_sidebar( 'fourth-footer-widget-area' )	) : ?>
	<aside class="fatfooter" role="complementary">
		<div class="first half left widget-area">
			<?php dynamic_sidebar( 'first-footer-widget-area' ); ?>
		</div><!-- .first .widget-area -->
		<div class="second half right widget-area">
			<?php dynamic_sidebar( 'second-footer-widget-area' ); ?>
		</div><!-- .second .widget-area -->
	</aside><!-- #fatfooter -->
	<?php
	//end of check for two sidebars. Finally we check if there is just one sidebar with widgets.
	elseif ( is_active_sidebar( 'first-footer-widget-area' ) && ! is_active_sidebar( 'second-footer-widget-area' ) && ! is_active_sidebar( 'third-footer-widget-area' ) & ! is_active_sidebar( 'fourth-footer-widget-area' ) ) :
	?>
	<aside class="fatfooter" role="complementary">
		<div class="first full-width widget-area">
			<?php dynamic_sidebar( 'first-footer-widget-area' ); ?>
		</div><!-- .first .widget-area -->
	</aside><!-- #fatfooter -->
	<?php endif; ?>
	<div class="footer-info">
		<div class="site-info">
		© <?php print date('Y');?> ACADEMIA DA MENTE
		</div><!-- .site-info -->
		<!-- <div class="king-footer-social">
			<ul>
			<?php if ( get_field( 'footer_facebook_link', 'options' ) ) : ?>
			<li><a href="<?php the_field( 'footer_facebook_link','options' ); ?>" target="_blank"><i class="fab fa-facebook"></i></a></li>
			<?php endif;?>
			<?php if ( get_field( 'footer_linkedin_link', 'options' ) ) : ?>
			<li><a href="<?php the_field( 'footer_linkedin_link','options' ); ?>"><i class="fab fa-linkedin"></i></a></li>
			<?php endif;?>
			<?php if ( get_field( 'footer_twitter_link', 'options' ) ) : ?>
			<li><a href="<?php the_field( 'footer_twitter_link', 'options' ); ?>"><i class="fab fa-twitter"></i></a></li>
			<?php endif;?>
			<?php if ( get_field( 'footer_google_link', 'options' ) ) : ?>
			<li><a href="<?php the_field( 'footer_google_link', 'options' ); ?>"><i class="fab fa-google-plus-g"></i></a></li>
			<?php endif;?>
			<?php if ( get_field( 'footer_custom_link', 'options' ) ) : ?>
			<?php the_field( 'footer_custom_link', 'options' ); ?>
			<?php endif;?>			
			</ul>
		</div> -->
	</div>			
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>
	<?php get_template_part( 'template-parts/king-header-login' ); ?>
	<?php
	if ( get_field( 'enable_lightbox_gallery', 'option' ) && is_single() ) {
		get_template_part( 'template-parts/post-templates/single-parts/gallery' );
	}
	?>
</body>
</html>
