<?php
/**
 * The header template-02.
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
<?php $hero = get_field( 'header_02_options', 'options' ); ?>
<div id="page" class="site header-template-02" <?php if ( $hero['margin'] ) : ?> style="margin:0 <?php echo esc_attr( $hero['margin'] ); ?>px;" <?php endif; ?>>
<header id="masthead" class="site-header">
	<div class="king-header header-02">
		<span class="king-head-toggle" data-toggle="dropdown" data-target=".king-head-mobile" aria-expanded="false" role="button">
			<i class="fas fa-angle-double-right"></i>
		</span>	
		<?php get_template_part( 'template-parts/header-templates/header-parts/logo' ); ?>
		<?php get_template_part( 'template-parts/header-templates/header-parts/headnav' ); ?>
		<?php get_template_part( 'template-parts/header-templates/header-parts/user' ); ?>
		<?php get_template_part( 'template-parts/header-templates/header-parts/submit' ); ?>				
		<?php get_template_part( 'template-parts/header-templates/header-parts/notify' ); ?>
		<div id="searchv2-button"><i class="fa fa-search fa-lg" aria-hidden="true"></i></div>

	</div><!-- .king-header -->
	<?php get_template_part( 'template-parts/header-templates/header-parts/headnav2' ); ?>
	<?php get_template_part( 'template-parts/header-templates/header-parts/search-v2' ); ?>
	<?php get_template_part( 'template-parts/header-templates/header-parts/hmobile' ); ?>
</header><!-- #masthead -->
