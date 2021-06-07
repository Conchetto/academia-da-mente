<?php
/**
 * The header template-05.
 *
 * This is the header template.
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
<div id="page" class="site header-template-05">
<header id="masthead" class="site-header">
	<div class="king-header header-05">
		<span class="king-leftmenu-toggle" data-toggle="dropdown" data-target=".king-leftmenu" aria-expanded="false" role="button">
		</span>	
		<?php get_template_part( 'template-parts/header-templates/header-parts/logo' ); ?>
	<?php if ( ! is_user_logged_in() ) : ?>

		<div class="king-login-buttons-template-05">
			<?php if ( get_option( 'permalink_structure' ) ) :
				global $wp;
				?>
				<a data-toggle="modal" data-target="#myModal" href="#" class="header-login"><i class="fa fa-user" aria-hidden="true"></i></a>
				<?php else : ?>
					<a href="<?php echo esc_url( wp_login_url( home_url() ) ); ?>" class="header-login"><i class="fa fa-user" aria-hidden="true"></i></a>
				<?php endif; ?>
				<?php if ( get_option( 'users_can_register' ) && get_option( 'permalink_structure' ) ) : ?>
				<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_register'] ); ?>" class="header-register"><i class="fas fa-globe-africa"></i></a>									
			<?php endif; ?>
		</div>
		<?php endif; ?>
		<?php get_template_part( 'template-parts/header-templates/header-parts/user' ); ?>
		<?php if ( get_field( 'disable_users_submit', 'options' ) !== true ) : ?>
			<?php if ( get_option( 'permalink_structure' ) ) : ?>
				<div class="king-submit-v2-open"  data-toggle="modal" data-target="#submitmodal" role="button"><i class="fa fa-plus fa-lg" aria-hidden="true"></i></div>
			<?php endif; ?>
		<?php endif; ?>				
		<?php get_template_part( 'template-parts/header-templates/header-parts/notify' ); ?>
		<div id="searchv2-button"><i class="fa fa-search fa-lg" aria-hidden="true"></i></div>

	</div><!-- .king-header -->
	<?php get_template_part( 'template-parts/header-templates/header-parts/submit-v2' ); ?>
	<?php get_template_part( 'template-parts/header-templates/header-parts/search-v2' ); ?>
	<?php get_template_part( 'template-parts/header-templates/header-parts/leftmenu' ); ?>
</header><!-- #masthead -->
