<?php
/**
 * The header part - logo.
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

<style>
	.site-header{background:#141e29 !important;padding-bottom: 10px;}
</style>
<div style="display:inline-block;margin-top:15px;background:#141e29">
	<?php 
		$corporate = logoCorporate();
		if($corporate):
	?>
	<img src="https://academia.clinicaecare.com.br/wp-content/uploads/2021/03/logo-sulamerica.png" alt="" style="display: block;">
	<?php endif;?>
</div>

<div class="site-branding" style="padding-bottom:10px;">
	<?php if ( get_field( 'page_logo', 'options' ) ) : $logo = get_field( 'page_logo', 'options' ); ?>
		<a href="<?php echo esc_url( site_url() ); ?>" class="king-logo">
			<img src="<?php echo esc_url( $logo['url'] ); ?>" alt="<?php echo esc_attr( $logo['alt'] ); ?>"/>
		</a>

		<?php if ( get_field( 'mobile_page_logo', 'options' ) ) : $mobile_logo = get_field( 'mobile_page_logo', 'options' ); ?>
			<a href="<?php echo esc_url( site_url() ); ?>" class="mobile-king-logo">
				<img src="<?php echo esc_url( $mobile_logo['url'] ); ?>" alt="<?php echo esc_attr( $mobile_logo['alt'] ); ?>"/>
			</a>	
		<?php endif; ?>

		<?php if ( get_field( 'enable_night_mode', 'options' ) ) : ?>
			<?php if ( get_field( 'night_mode_mobile_logo', 'options' ) ) : $mobile_night = get_field( 'night_mode_mobile_logo', 'options' ); ?>
				<a href="<?php echo esc_url( site_url() ); ?>" class="mobile-king-logo-night">
					<img src="<?php echo esc_url( $mobile_night['url'] ); ?>" alt="<?php echo esc_attr( $mobile_night['alt'] ); ?>"/>
				</a>	
			<?php endif; ?>
			<?php if ( get_field( 'night_mode_logo', 'options' ) ) : $logon = get_field( 'night_mode_logo', 'options' ); ?>
				<a href="<?php echo esc_url( site_url() ); ?>" class="king-logo-night">
					<img src="<?php echo esc_url( $logon['url'] ); ?>" alt="<?php echo esc_attr( $logon['alt'] ); ?>"/>
				</a>								
			<?php endif; ?>	
		<?php endif; ?>	
		<?php else : ?>
			<span class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></span>
			<?php
			$description = get_bloginfo( 'description', 'display' );
			if ( $description || is_customize_preview() ) : ?>
				<p class="site-description"><?php echo esc_attr( $description ); ?></p>
			<?php endif; ?>
		<?php endif; ?>

</div><!-- .site-branding -->


