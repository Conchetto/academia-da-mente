<?php
/**
 * The template for displaying the Categories page
 *
 * Template Name: registro
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>

<style>
	h1{margin-top:50px;font-size: 34px;color:#fff;line-height: 40px;text-align: center;display: block;}
	.btn{
		font-size: 15px;
		text-transform: uppercase;
		color:#fff;
		letter-spacing: 2px;
		line-height: 30px;
		text-align:center;
		font-weight: bold;
		margin-top:30px;
		background-color: #ff00ff;
	}
	.btn:hover{color:#fff;opacity: 0.8;}

	.btn:nth-child(1){background:#495057;}
	.btn:nth-child(2){background:#ff00ff;}
</style>

<div class="container">
	<div class="row">
		<div class="col-md-8 offset-md-2">
			<div class="row">
				<div class="col-md-12">
						<div class="king-modal-content mt-5">
							<form action="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_login'] . '?loginto=' . add_query_arg( array(),$wp->request ) ); ?>" id="login-form" method="post">
							<div class="king-modal-header"><h4 class="App-titleControl App-titleControl--text"><?php esc_html_e( 'Registrar', 'king' ) ?></h4></div>
							<div class="king-modal-form">
								<div class="king-form-group">
									<input required type="text" name="voucher" id="voucher" class="bpinput" placeholder="<?php esc_html_e( 'Voucher', 'king' ); ?>" maxlength="50"/>
								</div>
								<div class="king-form-group">
									<input required type="text" name="name" id="name" class="bpinput" placeholder="<?php esc_html_e( 'Nome', 'king' ); ?>" maxlength="50"/>
								</div>
								<div class="king-form-group">
									<input required type="text" name="cpf" id="cpf" onkeydown="javascript: fMasc( this, mCPF );" maxlength="14" class="bpinput" placeholder="<?php esc_html_e( 'CPF', 'king' ); ?>" maxlength="50"/>
								</div>
								<div class="king-form-group">
									<input required type="text" name="email" id="email" class="bpinput" placeholder="<?php esc_html_e( 'E-mail', 'king' ); ?>" maxlength="50"/>
								</div>
								<div class="king-form-group">
									<input required type="password" name="password" id="password" class="bpinput" placeholder="<?php esc_html_e( 'Senha', 'king' ); ?>" maxlength="50"/>
								</div>


								<div class="form-check" style="padding-left: 0px;">
									<input id="termos" type="checkbox" name="" class="">
									<label>Termos de Consentimentos</label>
									<label><a id="defaultUnchecked" href="#">clique aqui para ler</a></label>
								</div>

								<!--<div class="king-form-group">
									<input type="checkbox" name="rememberme" id="rememberme" />
									<label for="rememberme" class="rememberme-label"><?php esc_html_e( 'Termos de consentimento', 'king' ); ?></label>
								</div>-->
								<div class="king-form-group bwrap">
									<?php wp_nonce_field( 'login_form','login_form_nonce' ); ?>
									<input type="submit" class="king-submit-button" value="<?php esc_html_e( 'Cadastrar', 'king' ); ?>" id="king-submitbutton" name="login" /> 
								</div>
								</div>	
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
						<div class="modal active" id="modalExemplo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
						  <div class="modal-dialog" role="document">
						    <div class="modal-content">
						      <div class="modal-header">
						        <h5 class="modal-title" id="exampleModalLabel">Título do modal</h5>
						        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
						          <span aria-hidden="true">&times;</span>
						        </button>
						      </div>
						      <div class="modal-body">
						        ...
						      </div>
						      <div class="modal-footer">
						        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
						      </div>
						    </div>
						  </div>
						</div>
				</div>
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">
	jQuery("#voucher").blur(function(e){
		e.preventDefault();

		const voucher = jQuery('#voucher').val();

		if(voucher == '') {
			return;
		}

		jQuery.get("<?php print content_url('/themes/king/action-registro.php') ?>", {action: 'checkVoucher', content:voucher},  function(resultado){
		    if (resultado == 'false') {
		    	alert('Não encontramos esse voucher no sistema, por favor, verifique com seu psicólogo ou médico.');
		    	jQuery('#voucher').val('');
		    	jQuery('#voucher').focus();
		    }
		    return;
		})
	});

	jQuery("#king-submitbutton").click(function(e){
		e.preventDefault();
		
		var checkbox = jQuery('#termos:checked').length;
		console.log(checkbox);
		  
		if(checkbox === 0){
			alert('Para continuar, aceite os Termos de Consentimentos');
			return;
		}
		
		const user = {
			"voucher": jQuery('#voucher').val(),
			"name": jQuery('#name').val(),
			"cpf": jQuery('#cpf').val(),
			"email": jQuery('#email').val(),
			"password": jQuery('#password').val()
		};

		jQuery.get("<?php print content_url('/themes/king/action-registro.php') ?>", {action: 'addUser', content:user},  function(resultado){
			window.location.href = resultado;
			return;
		    if (objeto == 'false') {
		    	
		    }
		    return;
		})
	});


	jQuery("#email").blur(function(e){
		e.preventDefault();

		const email = jQuery('#email').val();

		if(email == '') {
			return;
		}

		jQuery.get("<?php print content_url('/themes/king/action-registro.php') ?>", {action: 'checkEmail', content:email},  function(resultado){
		    if (resultado == 'true') {
		    	alert('Este e-mail já está em uso. Verifique!');
		    	jQuery('#email').val('');
		    	jQuery('#email').focus();
		    	return;
		    }
		})
	});

	jQuery("#defaultUnchecked").click(function(e){
		e.preventDefault();

		jQuery('#modalExemplo').modal('show');
	});

    function fMasc(objeto,mascara) {
		obj=objeto
		masc=mascara
		setTimeout("fMascEx()",1)
	}
	function fMascEx() {
		obj.value=masc(obj.value)
	}
	function mTel(tel) {
		tel=tel.replace(/\D/g,"")
		tel=tel.replace(/^(\d)/,"($1")
		tel=tel.replace(/(.{3})(\d)/,"$1)$2")
		if(tel.length == 9) {
			tel=tel.replace(/(.{1})$/,"-$1")
		} else if (tel.length == 10) {
			tel=tel.replace(/(.{2})$/,"-$1")
		} else if (tel.length == 11) {
			tel=tel.replace(/(.{3})$/,"-$1")
		} else if (tel.length == 12) {
			tel=tel.replace(/(.{4})$/,"-$1")
		} else if (tel.length > 12) {
			tel=tel.replace(/(.{4})$/,"-$1")
		}
		return tel;
	}
	function mCNPJ(cnpj){
		cnpj=cnpj.replace(/\D/g,"")
		cnpj=cnpj.replace(/^(\d{2})(\d)/,"$1.$2")
		cnpj=cnpj.replace(/^(\d{2})\.(\d{3})(\d)/,"$1.$2.$3")
		cnpj=cnpj.replace(/\.(\d{3})(\d)/,".$1/$2")
		cnpj=cnpj.replace(/(\d{4})(\d)/,"$1-$2")
		return cnpj
	}
	function mCPF(cpf){
		cpf=cpf.replace(/\D/g,"")
		cpf=cpf.replace(/(\d{3})(\d)/,"$1.$2")
		cpf=cpf.replace(/(\d{3})(\d)/,"$1.$2")
		cpf=cpf.replace(/(\d{3})(\d{1,2})$/,"$1-$2")
		return cpf
	}
	function mCEP(cep){
		cep=cep.replace(/\D/g,"")
		cep=cep.replace(/^(\d{2})(\d)/,"$1.$2")
		cep=cep.replace(/\.(\d{3})(\d)/,".$1-$2")
		return cep
	}
	function mNum(num){
		num=num.replace(/\D/g,"")
		return num
	}
</script>



<?php get_footer(); ?>
