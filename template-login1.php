<?php
/**
 * Login Page.
 *
 * @package King
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $post;
if ( is_user_logged_in() ) {
	wp_redirect( get_home_url() );
	exit;
}
$login = null;
// login.
if ( isset( $_POST['username'] ) && isset( $_POST['password'] ) && wp_verify_nonce( $_POST['login_form_nonce'], 'login_form' ) ) { // input var okay; sanitization.

	$username = sanitize_text_field( wp_unslash( $_POST['username'] ) );
	$password = sanitize_text_field( $_POST['password'] );
	$login = wp_signon(
		array(
			'user_login'    => $username,
			'user_password' => $password,
			'remember'      => ( ( isset( $_POST['rememberme'] ) && absint( $_POST['rememberme'] ) ) ? true : false ),
		)
	);

	if ( ! is_wp_error( $login ) ) {
		wp_set_current_user( $login->ID, esc_attr( $username ) );
		wp_set_auth_cookie( $login->ID );

		if ( get_field( 'enable_user_points', 'options' ) ) {
			king_user_points( $login->ID );
		}
		if ( isset( $_GET['loginto'] ) ) {
			wp_redirect( home_url( $_GET['loginto'] ) );
		} else {
			wp_redirect( get_home_url() );
		}
		exit;
	}
}
?>
<?php get_header(); ?>
<?php $GLOBALS['hide'] = 'hide'; ?>
<div id="primary" class="page-content-area">
	<main id="main" class="page-site-main">
				<?php if ( get_field( 'custom_message_login', 'options' ) ) : ?>
					<div class="king-custom-message">
						<?php the_field( 'custom_message_login', 'options' ); ?>
					</div>
				<?php endif; ?>
				<form action="" id="login-form" method="post">
					<?php if ( is_wp_error( $login ) ) { ?>
						<div class="alert alert-danger"><?php esc_html_e( 'Email ou senha errados.', 'king' ) ?></div>
					<?php } ?>
					<div class="king-form-group">
						<input type="text" name="username" id="username" class="bpinput" placeholder="<?php esc_html_e( 'email', 'king' ); ?>" maxlength="50"/>
					</div>
					<div class="king-form-group">
						<input type="password" name="password" id="password" class="bpinput" placeholder="<?php esc_html_e( 'senha', 'king' ); ?>" maxlength="50"/>
					</div>

					<!-- <div class="king-form-group">
						<input type="checkbox" name="rememberme" id="rememberme" />
						<label for="rememberme" class="rememberme-label"><?php esc_html_e( 'Remember me', 'king' ); ?></label>
					</div> -->
					<div class="king-form-group bwrap">
						<?php wp_nonce_field( 'login_form','login_form_nonce' ); ?>
						<input type="submit" class="king-submit-button" value="<?php esc_html_e( 'Login', 'king' ); ?>" id="king-submitbutton" name="login" /> 
					</div>
					</form>
		<div class="login-rl">
		<?php if ( get_option( 'users_can_register' ) ) : ?>
			<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_register'] ); ?>" class="login-register"><i class="fas fa-globe-africa"></i></i><?php esc_html_e( ' Register ', 'king' ); ?></a>
		<?php endif; ?>	
			<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_reset'] ); ?>" class="login-reset"><i class="fas fa-fish"></i><?php esc_html_e( 'Esqueceu sua senha ?', 'king' ); ?></a>
		</div>	
	<?php if ( get_field( 'enable_facebook_login', 'option' ) || get_field( 'enable_googleplus_login', 'option' ) ) : ?>
		<div class="social-login">
		<span class="social-or"><?php esc_html_e( 'OR', 'king' ); ?></span>
		<?php if ( get_field( 'enable_facebook_login', 'option' ) ) : ?>
			<a class="fb-login" href="<?php echo esc_url( site_url() . '/wp-admin/admin-ajax.php?action=king_facebook_oauth_redirect' ); ?>"><i class="fab fa-facebook"></i><?php esc_html_e( 'Connect w/', 'king' ); ?> Facebook</a>
		<?php endif; ?>		
		<?php if ( get_field( 'enable_googleplus_login', 'option' ) ) : ?>
			<a class="google-login google-login-js" href="#"><i class="fab fa-google-plus"></i><?php esc_html_e( 'Connect w/', 'king' ); ?> <b><?php esc_html_e( 'Google', 'king' ); ?></b></a>				
		<?php endif; ?>			
		</div>
	<?php endif; ?>

	</main><!-- #main -->
</div><!-- .main-column -->


<?php get_footer(); ?>
