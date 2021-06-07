<?php
/**
 * The template for displaying the Categories page
 *
 * Template Name: importação
 */




include 'excel_reader/excel_reader.php'; // include the class
$excel = new PhpExcelReader; // creates object instance of the class

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! is_super_admin() ) {
	$url = home_url();
	header("Location: $url");
	die();
}


$cont = 0;
$cadastrados=0;

if(isset($_POST['submit']))
{
	$id_empresa = $_POST['id_empresa'];

	$excel->read($_FILES['base']['tmp_name']);

	foreach($excel->sheets[0]['cells'] as $cpf){

		global $wpdb;
		$cpfVal = (string) $cpf[1];
		$qr = (string) "select * from cpfs where cpf = {$cpfVal}";
		$consultas = $wpdb->get_results($qr);

		if($consultas[0]->id != ''){
			$cont++;
		}else{
			$cadastrados++;
			$insert = "insert into cpfs values(null,{$cpfVal},{$id_empresa},null)";
			$inserDB = $wpdb->get_results($insert);
		}

	}
}


get_header();
?>

<style>
	h1{margin-top:50px;font-size: 34px;color:#fff;line-height: 40px;text-align: center;display: block;}
	label{color:#fff;font-size:20px;}
	.btn{
		display: block;
		width: 100%;
		padding:10px 0;
		background: #00ffff;
		color:#000 !important;
		font-size: 30px;
		margin-top:30px;
	}
	.resposta{color:#fff;font-size:30px;border:1px solid rgba(255,255,255,0.1); padding:10px;text-align:center;}
</style>

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<h1>Sistema de importação <br> de CPFs</h1>
		</div>
	</div>

	<div class="row">
		<div class="col-md-8 offset-md-2">
			<div class="row">
				<div class="col-md-12">
					<form action="" method="post" enctype="multipart/form-data">
						<div class="form-group">
							<label for="id_empresa">Empresa</label>
							<select name="id_empresa" id="" class="form-control">
								<option value="1">SulAmérica</option>
							</select>
						</div>

						<div class="form-group">
							<label for="id_empresa">Base (arquivo xls)</label>
							<input type="file" name="base" class="form-control">
						</div>
						<div class="form-group">
							<input type="submit" required value="IMPORTAR" name="submit" class="btn">
						</div>
					</form>
				</div>
			</div>

			<?php
			if(isset($_POST['submit'])):
				?>

				<div class="row">
					<div class="col-md-12">
						<div class="resposta">
							<?php 
							print $cont.' cpfs já estavam cadastrados. <br>';
							print $cadastrados.' cpfs novos foram cadastrados.';

							?>
						</div>
					</div>
				</div>

				<?php 
			endif;
			?>	
		</div>
	</div>
</div>



<?php get_footer(); ?>
