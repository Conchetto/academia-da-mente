<?php
/**
 * The template for displaying the Categories page
 *
 * Template Name: aterrisagem
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
		font-size: 20px;
		text-transform: uppercase;
		color:#fff;
		letter-spacing: 2px;
		display: block;
		height: 120px;
		padding:0 80px;
		border-radius: 10px;
		line-height: 120px;
		text-align:center;
		font-weight: bold;
		margin-top:30px;
	}
	.btn:hover{color:#fff;opacity: 0.8;}

	.btn:nth-child(1){background:#ff0000;}
	.btn:nth-child(2){background:#ff00ff;}
</style>

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<h1>Qual tipo de conteúdo <br> você quer ver?</h1>
		</div>
	</div>

	<div class="row">
		<div class="col-md-8 offset-md-2">
			<div class="row">
				<div class="col-md-12">
					<a href="#" class="btn">produtividade</a>
					<a href="#" class="btn">produtividade</a>
				</div>
			</div>
		</div>
	</div>
</div>



<?php get_footer(); ?>
