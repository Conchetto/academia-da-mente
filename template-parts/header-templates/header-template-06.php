<?php
/**
 * The header template-06.
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
<div id="page" class="site header-template-06">
	<header id="masthead" class="site-header">
		<div class="king-header header-06">
			<div class="king-leftmenu-toggle-v2" data-toggle="dropdown" data-target=".king-leftmenu" aria-expanded="false" role="button"><span class="leftmenu-toggle-line"></span></div>	
			<?php get_template_part( 'template-parts/header-templates/header-parts/logo' ); ?>
			<?php get_template_part( 'template-parts/header-templates/header-parts/search' ); ?>
			<?php get_template_part( 'template-parts/header-templates/header-parts/user' ); ?>
			<?php get_template_part( 'template-parts/header-templates/header-parts/submit' ); ?>				
			<?php get_template_part( 'template-parts/header-templates/header-parts/notify' ); ?>
		</div><!-- .king-header -->

		<?php get_template_part( 'template-parts/header-templates/header-parts/leftmenu' ); ?>
	</header><!-- #masthead -->
