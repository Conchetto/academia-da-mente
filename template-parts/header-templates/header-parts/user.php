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
<div class="king-logged-user">
	<?php if ( ! is_user_logged_in() ) : ?>

		<div class="king-login-buttons">
			<?php if ( get_option( 'permalink_structure' ) ) :
				global $wp;
				?>
				<a data-toggle="modal" data-target="#myModal" href="#" class="header-login"><i class="fas fa-user-circle"></i><?php esc_html_e( ' Entrar ', 'king' ) ?></a>
				<?php else : ?>
					<a href="<?php echo esc_url( wp_login_url( home_url() ) ); ?>" class="header-login"><i class="fas fa-user-circle"></i> <?php esc_html_e( ' Entrar ', 'king' ) ?></a>
				<?php endif; ?>
				<?php if ( get_option( 'users_can_register' ) && get_option( 'permalink_structure' ) ) : ?>
				<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_register'] ); ?>" class="header-register"><i class="fas fa-globe-africa"></i><?php esc_html_e( ' Register ', 'king' ) ?></a>									
			<?php endif; ?>
		</div>

	<?php else :
		global $current_user;
		wp_get_current_user();
		?>

		<div class="king-username">
			<?php if ( get_field( 'author_image','user_' . get_current_user_id() ) ) : $image = get_field( 'author_image','user_' . get_current_user_id() ); ?>
				<img class="user-header-avatar" src="<?php echo esc_url( $image['sizes']['thumbnail'] ); ?>" data-toggle="dropdown" data-target=".user-header-menu" aria-expanded="false"/>
				<?php else : ?>
					<span class="user-header-noavatar" data-toggle="dropdown" data-target=".user-header-menu" aria-expanded="false"></span>
				<?php endif; ?>
				<?php $prvt_msg = get_user_meta( $current_user->ID, 'king_prvtmsg_notify', true );
				if ( $prvt_msg ) {
					echo '<i class="prvt-dote"></i>';
				}
				?>
				<div class="user-header-menu">
					<?php if ( get_option( 'permalink_structure' ) ) : ?>
						<div class="user-header-profile" >
							<a href="#" ><?php echo esc_attr( $current_user->display_name ); ?></a>
							<?php if ( get_field( 'enable_user_points', 'options' ) ) : ?>
								<div class="king-points" title="<?php echo esc_html_e( 'Points','king' ); ?>"><i class="fa fa-star" aria-hidden="true"></i> <?php echo get_user_meta( $current_user->ID, 'king_user_points', true ); ?></div>
							<?php endif; ?>
						</div>
						<!-- <a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_account'] . '/settings' ); ?>" class="user-header-settings"><?php echo esc_html_e( 'My Settings','king' ); ?></a> -->
						<?php if ( get_field( 'enable_private_messages', 'options' ) ) : ?>
							<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_prvtmsg'] ); ?>" class="user-header-prvtmsg"><?php echo esc_html_e( 'Inbox','king' ); ?><?php if ( $prvt_msg ) : ?><span class="header-prvtmsg-nmbr"><?php echo esc_attr( $prvt_msg ); ?></span><?php endif; ?></a>
						<?php endif; ?>
						<!-- <a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_account'] ); ?>" class="user-header-dashboard"><?php echo esc_html_e( 'Minha área','king' ); ?></a> -->
					<?php endif; ?>	
					<?php if ( is_super_admin() || current_user_can( 'editor' ) ) : ?>
					<a href="<?php echo esc_url( get_admin_url() ); ?>" class="user-header-admin"><?php echo esc_html_e( 'Admin Panel','king' ); ?></a>
				<?php endif; ?>
				<a href="<?php echo esc_url( wp_logout_url( site_url() ) ); ?>" class="user-header-logout"><?php echo esc_html_e( 'Sair','king' ); ?></a>
			</div>
		</div>
	<?php endif; ?>
</div><!-- .king-logged-user -->