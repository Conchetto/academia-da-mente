<?php
/**
 * The header part - user menu.
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
<?php if ( get_field( 'enable_notification', 'options' ) && is_user_logged_in() ) : ?>
<div class="king-notify">
	<?php 
	$notify = get_user_meta( get_current_user_id(), 'king_notify_count', true );
	$notifyclass = '';
	if ( $notify ) { $notifyclass = 'notify'; }
	?>
	<div class="king-notify-box <?php echo esc_attr( $notifyclass ); ?>">
		<div class="king-notify-toggle" data-toggle="dropdown" data-target=".king-notify-menu" aria-expanded="true"><i class="far fa-bell fa-lg"></i><span class="king-notify-num"><?php echo esc_attr( $notify ); ?></span></div>
		<div class="king-notify-menu">
			<ul id="king-notify-inside"><li class="king-clean-center"><div class="loader"></div></li></ul>
		</div>
	</div>
</div>
<?php endif; ?>