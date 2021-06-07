<?php
/**
 * The header template-01.
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
<div id="page" class="site">
<header id="masthead" class="site-header">
	<div class="king-header">
		<span class="king-head-toggle" data-toggle="dropdown" data-target=".king-head-mobile" aria-expanded="false" role="button">
			<i class="fas fa-angle-double-right"></i>
		</span>	
		<?php get_template_part( 'template-parts/header-templates/header-parts/logo' ); ?>
		<?php get_template_part( 'template-parts/header-templates/header-parts/headnav' ); ?>
		<?php get_template_part( 'template-parts/header-templates/header-parts/user' ); ?>
		<?php get_template_part( 'template-parts/header-templates/header-parts/submit' ); ?>				
		<?php get_template_part( 'template-parts/header-templates/header-parts/notify' ); ?>
		<?php get_template_part( 'template-parts/header-templates/header-parts/search' ); ?>

	</div><!-- .king-header -->
	<?php get_template_part( 'template-parts/header-templates/header-parts/headnav2' ); ?>
	<?php get_template_part( 'template-parts/header-templates/header-parts/hmobile' ); ?>
</header><!-- #masthead -->
