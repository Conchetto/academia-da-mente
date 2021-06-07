<?php
/**
 * 
 *
 * Template Name: voucher
 */


if ( ! is_super_admin() ) {
	$url = home_url();
	header("Location: $url");
	die();
} 

if($_GET['csv'] != ''){
	$query = "select voucher, count(*) as qtd from wp_users_vouchers where voucher <> '' group by voucher";
	$consulta = $wpdb->get_results($query);


	$csv = array();

	foreach($consulta as $dado){
		// $csv[] = array($dado->voucher,$dado->qtd);
		print $dado->voucher.';'.$dado->qtd."\n";
	}

	// var_dump($csv);
	die();
}


// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>

<style>
	h1{margin:50px;font-size: 34px;color:#fff;line-height: 40px;text-align: center;display: block;}
	label{color:#fff;}
	#erro,#sucesso{display:none;}

	tr:hover{color:#000;}

</style>

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<h1>Gerador de voucher </h1>
		</div>
	</div>

	<div class="row">
		<div class="col-md-6 offset-md-3">
			<div class="row">
				<div class="col-md-12">
					<div class="alert alert-danger" id="erro" role="alert">
						Esse voucher já existe no sistema.
					</div>

					<div class="alert alert-success" id="sucesso" role="alert">
						
					</div>

					<form>
						<div class="form-group">
							<label>Para quem é esse voucher?</label>
							<input type="text" class="form-control" id="nome" placeholder="Nome do psicólogo ou empresa">
						</div>

						<div class="form-group">
							<label>Nome do voucher</label>
							<input type="text" class="form-control" id="codigo" placeholder="Digite aqui qual é o código do voucher">
							<small class="form-text text-muted">Deixe esse campo vazio se quiser gerar um código automático</small>
						</div>

						<button id="gerar" class="btn btn-block btn-success">Gerar Voucher</button>

					</form>

				</div>
			</div>
		</div>
	</div>

	<div class="row" style="margin-top:50px;">

		<div class="col-md-8 offset-md-2">
			<div class="row">
				<div class="col-md-12">
					<h3 style="color:#fff">Ativos</h3>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<table class="table-dark" style="color:#fff;text-align:center;">
						<tr>
							<td><b>identificação</b></td>
							<td><b>código</b></td>
							<td><b>registros</b></td>
							<td>#</td>
						</tr>
				<?php global $wpdb;

					$soma = 0;

					$select = "select * from wp_vouchers where deleted = false order by id desc";
					$consulta = $wpdb->get_results($select); 

					foreach($consulta as $voucher){

						$selectcounter = "select count(*) as qtd from wp_users_vouchers where voucher = '{$voucher->codigo}'";

						$querie = $wpdb->get_results($selectcounter); 
						$soma = $soma + $querie[0]->qtd;

						print "<tr>";
						print "<td>".$voucher->nome_psicologo."</td>";
						print "<td>".$voucher->codigo."</td>";
						print "<td>".$querie[0]->qtd."</td>";
						print "<td><a href='#' id='".$voucher->id."' class='deleted_voucher btn btn-danger btn-sm'>desativar</td>";
						print "</tr>";
					
					}

				?>
					<tr><td></td><td><b>Total -> </b></td><td><b><?php print $soma; ?></b></td><td></td></tr>
					</table>

			</div>
		</div>

		<!-- desativados -->

			<div class="row" style="margin-top:50px;">
				<div class="col-md-12">
					<h3 style="color:#fff">Desativados</h3>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<table class="table-dark" style="color:#fff;text-align:center;">
						<tr>
							<td><b>identificação</b></td>
							<td><b>código</b></td>
							<td><b>registros</b></td>
							<td>#</td>
						</tr>
				<?php global $wpdb;

					$select = "select * from wp_vouchers where deleted = true  order by id desc";
					$consulta = $wpdb->get_results($select); 

					foreach($consulta as $voucher){

						$selectcounter = "select count(*) as qtd from wp_users_vouchers where voucher = '{$voucher->codigo}' ";

						$querie = $wpdb->get_results($selectcounter); 

						print "<tr>";
						print "<td>".$voucher->nome_psicologo."</td>";
						print "<td>".$voucher->codigo."</td>";
						print "<td>".$querie[0]->qtd."</td>";
						print "<td><a href='#' id='".$voucher->id."' class='deleted_voucher btn btn-primary btn-sm'>ativar</td>";
						print "</tr>";
					
					}

				?>
					</table>

			</div>
		</div>


		<!-- Corporate -->

			<div class="row" style="margin-top:50px;">
				<div class="col-md-12">
					<h3 style="color:#fff">Corporate</h3>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<table class="table-dark" style="color:#fff;text-align:center;">
						<tr>
							<td><b>Empresa</b></td>
							<td><b>Cadastros</b></td>
							<td><b>Total pré-cadastrado</b></td>
						</tr>
				<?php global $wpdb;

					$select = "select corporate.id,empresa,count(*) as qtd from corporate inner join cpfs on id_empresa = corporate.id where id_user is not null group by id_empresa";
					$consulta = $wpdb->get_results($select); 

					foreach($consulta as $corporate){

						$selectcounter = "select count(*) as qtd from corporate inner join cpfs on id_empresa = {$corporate->id} group by id_empresa";


						$querie = $wpdb->get_results($selectcounter); 

						print "<tr>";
						print "<td>".$corporate->empresa."</td>";
						print "<td>".$corporate->qtd."</td>";
						print "<td>".$querie[0]->qtd."</td>";
						print "</tr>";
					
					}

				?>
					</table>

			</div>
		</div>

	</div>
</div>
</div>


<script>
	
	jQuery(".deleted_voucher").click(function(e){
		var id = jQuery(this).attr('id');
		e.preventDefault();

		var dados = {
			id: id
		}

		jQuery.post("<?php print admin_url( 'admin-ajax.php' ); ?>", {action:'deleted_voucher',offset:dados}, function(response) {

			console.log(response)
			
		}).done(function(){
			document.location.reload(true);
		});



		
	});

	jQuery("#gerar").click(function(e){
		e.preventDefault();
		
		var nome = jQuery('#nome').val();
		var codigo = jQuery('#codigo').val();

		if(nome == ''){alert('O campo nome é obrigatório!'); return false;}

		var dados = {
			nome: nome,
			codigo: codigo,
		}

		jQuery.post("<?php print admin_url( 'admin-ajax.php' ); ?>", {action:'gera_voucher',offset:dados}, function(response) {

			if(response == 'existe'){jQuery('#erro').show();}else{
				jQuery('#erro').hide();

				const editedText = response.slice(0, -1) //'abcde'

				jQuery('#sucesso').html("O código voucher é: "+editedText).show();

			}
			// console.log(response);
		});

	});
</script>

<?php get_footer(); ?>
