<?php
/**
 *
 *
 * Template Name: aprendizagem
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>

<style>
	@import url("https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700");
	body {
	  font-family: "Open Sans", sans-serif;
	  margin: 0;
	}


	main {
	  padding:25px;
	}

	main .bloco {
	  font-size: 1em;
	  line-height: 25px;
	  border-top: 3px solid;
	  -webkit-border-image: -webkit-gradient(linear, left top, right top, from(#57b8a6), to(#21adff));
	  -webkit-border-image: linear-gradient(to right, #57b8a6 0%, #21adff 100%);
	       -o-border-image: linear-gradient(to right, #57b8a6 0%, #21adff 100%);
	          border-image: -webkit-gradient(linear, left top, right top, from(#57b8a6), to(#21adff));
	          border-image: linear-gradient(to right, #57b8a6 0%, #21adff 100%);
	  border-image-slice: 1;
	  border-width: 3px;
	  margin: 0;
	  padding: 40px;
	  counter-increment: section;
	  position: relative;
	  color: #fff;
	  text-align: justify;
	}
	main .bloco:before {
	  content: counter(section);
	  position: absolute;
	  border-radius: 50%;
	  top:50%;
	  margin-top:-20px;
	  padding: 10px;
	  height: 40px;
	  width: 40px;
	  background-color: #2b435f;
	  text-align: center;
	  color: #fff;
	  font-weight: bold;
	  line-height: 20px;
	}


	main .bloco.active:before{background-color: #57b8a6;color:#000;}

	main .bloco:nth-child(odd) {
	  border-right: 3px solid;
	  padding-left: 0;
	}
	main .bloco:nth-child(odd):before {
	  left: 100%;
	  margin-left: -20px;
	}

	main .bloco:nth-child(even) {
	  border-left: 3px solid;
	  padding-right: 0;
	}
	main .bloco:nth-child(even):before {
	  right: 100%;
	  margin-right: -20px;
	}

	main .bloco:first-child {
	  border-top: 0;
	  border-top-right-radius: 0;
	  border-top-left-radius: 0;
	}

	main .bloco:last-child {
	  border-bottom-right-radius: 0;
	  border-bottom-left-radius: 0;
	}

	.header-trilha h1{margin-top:40px;}
	.header-trilha{color:#fff;}

	.btn-outline-primary{border-color: #fff !important;color:#fff;}
	h2{font-size:20px;margin-bottom: 10px;font-weight: bold;}
	.img-concluido{margin-top:-5px;margin-left:10px;width: 20px;display: none;}
  	main .bloco.active .img-concluido{display: inline-block;}

  	.btn-outline-primary:hover{background-color: rgba(0,0,0,0.4);}
</style>

<div class="container">
	<div class="row">
		<div class="col-md-12 text-center header-trilha">
			<h1><?php print get_category($_GET['id'])->name; ?></h1>
			<?php
			 if(!empty(get_category($_GET['id'])->category_description)):?>
			<video width="680" height="385" controls style="margin-top:40px;">
				  <source src="<?php print get_category($_GET['id'])->category_description; ?>" type="video/mp4">
				</video>
			<?php endif;?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-8 offset-md-2">
			
			<main>
				<?php
				global $post;
				$args = array(
					'category' => $_GET['id'],
					'meta_key'=>'pos',
					'orderby'=>'meta_value',
					'order' => 'ASC',
					'posts_per_page' => '-1'
				); 

				$myposts = get_posts( $args );
				   foreach ( $myposts as $post ) : setup_postdata( $post ); 

				   	$intro = get_post_meta( get_the_ID(), 'intro', true );

				   	?>


				<div class="bloco" bloco-id="<?php print $post->ID; ?>">
						<h2>
							<?php the_title(); ?>
							<img src="<?php print content_url(); ?>/tick.svg" alt="Concluído" class="img-concluido" title="Concluído">
							</h2>
							<p>
						<?php print $intro; ?>
								
							</p>
						<a href="<?php print get_permalink();?>" class="btn-conteudo-completo"></a>
				</div>
			<?php endforeach;?>
		</div>
	</div>

</div>

<?php 

$id_user = get_current_user_id();
$select = "select id_conteudo from conteudo_concluido where id_user = '{$id_user}'";
$consulta = $wpdb->get_results($select);
?>

<script>
		<?php foreach($consulta as $dado):?>
		jQuery(".bloco[bloco-id='<?php print $dado->id_conteudo;?>']").addClass('active');
	<?php endforeach;?>
</script>


<?php get_footer(); ?>

