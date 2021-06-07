<?php
/**
 * The template for displaying the Categories page
 *
 * Template Name: logar
 */

get_header();
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
		.king-header{
			position: fixed;
		}
	</style>
</head>
<body>
	<div class="mt-0" id="" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="king-modal-content"></button>
		<form action="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_login'] . '?loginto=' . add_query_arg( array(),$wp->request ) ); ?>" id="login-form" method="post">
		<div class="king-modal-header"><h4 class="App-titleControl App-titleControl--text"><?php esc_html_e( 'Entrar', 'king' ) ?></h4></div>
		<div class="king-modal-form">
			<div class="king-form-group">
				<input type="text" name="username" id="username" class="bpinput" placeholder="<?php esc_html_e( 'seu email', 'king' ); ?>" maxlength="50"/>
			</div>
			<div class="king-form-group">
				<input type="password" name="password" id="password" class="bpinput" placeholder="<?php esc_html_e( 'sua senha', 'king' ); ?>" maxlength="50"/>
			</div>

			<!-- <div class="king-form-group">
				<input type="checkbox" name="rememberme" id="rememberme" />
				<label for="rememberme" class="rememberme-label"><?php esc_html_e( 'Remember me', 'king' ); ?></label>
			</div> -->
			<div class="king-form-group bwrap">
				<?php wp_nonce_field( 'login_form','login_form_nonce' ); ?>
				<input type="submit" class="king-submit-button" value="<?php esc_html_e( 'Entrar', 'king' ); ?>" id="king-submitbutton" name="login" /> 
			</div>
			</div>
			<div class="king-modal-footer">
				<p class="LogInModal-forgotPassword"><a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_reset'] ); ?>"><?php esc_html_e( 'Esqueceu sua senha?', 'king' ); ?></a></p><p class="LogInModal-signUp"><?php esc_html_e( 'Não tem uma conta?', 'king' ); ?> <a href="<?php echo esc_url( site_url() . '/registro') ?>"><?php esc_html_e( 'Clique aqui', 'king' ); ?></a></p></div>	
	<?php if ( get_field( 'enable_social_logins_in_modal','option' ) ) : ?>		
		<?php if ( get_field( 'enable_facebook_login','option' ) || get_field( 'enable_googleplus_login', 'option' ) ) : ?>
		<div class="modal-social-login">
		<?php if ( get_field( 'enable_facebook_login','option' ) ) : ?>
			<a class="fb-login" href="<?php echo esc_url( site_url() . '/wp-admin/admin-ajax.php?action=king_facebook_oauth_redirect' ); ?>"><i class="fab fa-facebook"></i><?php esc_html_e( 'Connect w/', 'king' ); ?><b><?php esc_html_e( ' Facebook', 'king' ); ?></b></a>
		<?php endif; ?>		
		<?php if ( get_field( 'enable_googleplus_login', 'option' ) ) : ?>
			<a class="google-login google-login-js" href="#"><i class="fab fa-google-plus"></i><?php esc_html_e( 'Connect w/', 'king' ); ?> <b><?php esc_html_e( 'Google+', 'king' ); ?></b></a>				
		<?php endif; ?>			
		</div>
		<?php endif; ?>
	<?php endif; ?>				
			</div>
		</form>
	</div>
</div><!-- .king-modal-login -->
</body>
</html>