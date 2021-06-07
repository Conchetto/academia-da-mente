<?php
/**
 * The header template-03.
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
<div id="page" class="site header-template-03">
	<div class="king-top-header top-header-03">
		<div class="king-top-header-menu">
			<?php wp_nav_menu( array( 'theme_location' => 'top-header-menu' ) ); ?>
		</div>
		<div class="king-top-header-icons">
	<?php
		$group     = get_field( 'header_03_options', 'options' );
		$repeaters = $group['top_header_right_icons'];
	foreach ( $repeaters as $repeater ) {
		echo '<a href="' . esc_url( $repeater['link_url'] ) . '" >' . wp_kses_post( $repeater['icon_code'] ) . '</a>';
	}
	?>
		</div>
	</div><!-- .king-top-header -->
<header id="masthead" class="site-header sticky-header-03">
	<div class="king-header header-03">
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
