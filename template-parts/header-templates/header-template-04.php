<?php
/**
 * The header template-04.
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
<div id="page" class="site header-template-04">
<?php
$hero = get_field( 'header_04_options', 'options' );
if ( $hero ) :
	?>
	<div class="king-top-ad top-header-04" <?php if ( $hero['background'] ) : ?> style="background-color:<?php echo esc_attr( $hero['background'] ); ?>;" <?php endif; ?>>
			<div class="king-top-ad-inner"><?php echo do_shortcode( $hero['code'] ); ?></div>
	</div><!-- .king-header -->
<?php endif; ?>	
<header id="masthead" class="site-header">

	<div class="king-header header-04">
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
	<?php get_template_part( 'template-parts/header-templates/header-parts/search-v2' ); ?>
	<?php get_template_part( 'template-parts/header-templates/header-parts/headnav2' ); ?>
</header><!-- #masthead -->
<?php get_template_part( 'template-parts/header-templates/header-parts/hmobile' ); ?>