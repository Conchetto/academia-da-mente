<?php
/**
 * Navigation in header theme part.
 *
 * @package King
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<nav id="site-navigation" class="main-navigation">
	<span class="king-menu-toggle"  data-toggle="dropdown" data-target=".header-nav" aria-expanded="false" role="button"><i class="fa fa-align-center fa-lg" aria-hidden="true"></i></span>
	<?php if ( get_field( 'enable_night_mode', 'options' ) ) : ?>
		<input type="checkbox" id="king-night" name="king-night"> <label for="king-night" class="king-night-box"></label>	
	<?php endif; ?>
	<?php if ( king_plugin_active( 'WooCommerce' ) ) : ?>
		<?php $king_shop_count = WC()->cart->get_cart_contents_count(); ?>
		<div class="king-cart">
			<span class="king-cart-toggle"  data-toggle="modal" data-target=".king-cart-content" aria-expanded="false" role="button">
				<?php if ( $king_shop_count ) : ?>
					<span class="king-cart-badge"><?php echo (int) $king_shop_count; ?></span>
				<?php else : ?>
					<span class="king-cart-badge hide">0</span>
				<?php endif; ?>
				<i class="fa fa-shopping-bag" aria-hidden="true"></i>
			</span>
			<div class="king-cart-content">
			<button type="button" class="king-cart-close" data-dismiss="modal" aria-label="Close"><i class="icon fa fa-fw fa-times"></i></button>
				<?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>
			</div>
		</div>
	<?php endif; ?>	  
	<div class="header-nav">
		<?php
		// Primary navigation menu.
		wp_nav_menu( array(
			'menu_id'     => 'primary-menu',
			'theme_location' => 'primary',
		) );
			?>
	</div>
</nav><!-- #site-navigation -->
