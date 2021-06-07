<?php
/**
 * Single post page template v1.
 *
 * @package King
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if(get_current_user_id()){
	

}else{
	$url = home_url().'/registro/';
	print "<script>window.location.href ='".$url."'</script>";
}

$idAbsoluto = get_the_ID();

?>

<style>
	a:hover, a:focus, a:active{color:#0e6731;}
	.entry-title{display: inline-block;}
	.btn-outline-sucess, .btn-outline-sucess:focus{color:#0e6731 !important;}
	.btn-success, .btn-success:focus{color:#fff !important;}

	.avaliacao-conteudo ul li {
    	display: inline-block;
    	padding-right: 4px;
	}

	.estrelas input[type=radio] {
	  display: none;
	}
	.estrelas label i.fa:before {
	  content:'\f005';
	  color: #FC0;
	}
	.estrelas input[type=radio]:checked ~ label i.fa:before {
	  color: #CCC;
	}

	ul li::before {
	  color: red; /* Also needed for space (tweak if needed) */
	}

	.header-nav{display: none !important;}
</style>
	<?php if ( has_post_thumbnail() ) :
	$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' ); ?>
		<div class="single-post-back" style="background-image: url('<?php echo esc_url( $thumb['0'] ); ?>');"></div>
	<?php elseif ( get_field( 'default_background', 'option' ) ) : $dbackground = get_field( 'default_background', 'options' ); ?>
		<div class="single-post-back" style="background-image: url('<?php echo esc_url( $dbackground ); ?>');"></div>
	<?php else : ?>
		<div class="single-post-back-no"></div>
	<?php endif; ?> 

<div id="primary" class="content-area sing-template-4 col-md-8">
	
		
	<main id="main" style="text-align: justify; padding-bottom: 0px;" class="site-main post-page single-post">
		
		<?php if ( get_field( 'ads_above_content', 'option' ) ) : ?>
			<div class="ads-postpage"><?php $ad_above = get_field( 'ads_above_content','options' ); echo do_shortcode( $ad_above ); ?></div>
		<?php endif; ?>
		<?php while ( have_posts() ) : the_post(); ?>



		
		<div style="margin-bottom: 0px;" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		
		<?php get_template_part( 'template-parts/post-templates/single-parts/share' ); ?>
		<header class="entry-header">
			
			<input onClick="window.location='<?php print home_url();?>/trilha/?id=<?php print get_the_category(get_the_ID())[0]->term_id ?>'" type=image src="<?php print content_url().'/bt_voltar_hover.png'?>"> 
			
			<?php
			$id_user = get_current_user_id();
			$id_conteudo =  get_the_ID();
 			 global $wpdb;
				  	$select = "select id from conteudo_concluido where id_user = '{$id_user}' and id_conteudo = '{$id_conteudo}'";
					$consulta = $wpdb->get_results($select);

		  	if($consulta): ?>
		  		<a href="#" class="" id="concluido">
		  			<img src="<?php print content_url().'/bt_concluido.png'?>" width="166" height="50">
		  		</a>
		  	<?php else: ?>
  				<a href="#" class=""  id="concluido">
		  			<img src="<?php print content_url().'/bt_marcar_como_concluido.png'?>" width="166" height="50">
		  		</a>	
		  	<?php endif; ?>

		  	<br>
		  	<br>
		  	<?php
				// if ( is_single() ) {
				// 	the_title( '<h1 class="entry-title">', '</h1>' );
				// } else {
				// 	the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
				// }

				if (get_the_ID() == 864 || get_the_ID() == 869) {
					the_title( '<h1 class="entry-title">', '</h1>' );
				}
			?>
		  	
		</header><!-- .entry-header -->


<?php if ( get_field( 'nsfw_post' ) && ! is_user_logged_in() ) : ?>
	<div class="post-video nsfw-post-page">
		<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_login'] ); ?>">
			<i class="fa fa-paw fa-3x"></i>
			<div><h1><?php echo esc_html_e( 'Not Safe For Work', 'king' ) ?></h1></div>
			<span><?php echo esc_html_e( 'Click to view this post.', 'king' ) ?></span>
		</a>	
	</div>
<?php else : ?>
	<div class="row">
		<div class="col-md-12">
			<div class="entry-content">

					<!-- <?php if(!is_user_logged_in()): ?>
						<div style="text-align:center;background:rgba(0,0,0,0.5);width:100%;height: 40%;position: absolute;bottom:100px;right:0;background-color: rgba(0, 0, 0, 0.5);
				  -webkit-backdrop-filter: blur(5px);
				  backdrop-filter: blur(5px);
				  padding: 50px;">

				  	<p style="color:#fff;font-size: 35px;line-height: 50px;margin-top:20%">
				  		Você precisa fazer login <br>  para ter acesso a todo o conteúdo.
				  	</p>
				  	<a data-toggle="modal" class="header-login" data-target="#myModal" style="margin-top:10px;display:inline-block;background: #fff;padding:15px 20px;font-size:30px;border-radius: 10px;">clique aqui</a>

				  </div>
					<?php endif; ?> -->

				<?php
				

				the_content( sprintf(
					/* translators: %s: Name of current post. */
					wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'king' ), array( 'span' => array( 'class' => array() ) ) ),
					the_title( '<span class="screen-reader-text">"', '"</span>', false )
				) );

				wp_link_pages( array(
					'before' => '<div class="page-links">',
					'after'  => '</div>',
				) );
					?>
			</div><!-- .entry-content -->
		</div>
	</div>

		<?php if ( have_rows( 'news_list_items' ) ) : ?>

			<div class="king-lists">
				<?php $i = 1; ?>
				<?php while ( have_rows( 'news_list_items' ) ) : the_row();
					$image = get_sub_field( 'news_list_image' );
					$media = get_sub_field( 'news_list_media' );
					$title = get_sub_field( 'news_list_title' );
					$content = get_sub_field( 'news_list_content' );
				?>
				<div class="list-item">

					<span class="list-item-title"><span class="list-item-number"><?php echo esc_html( $i ); ?></span><h3><?php echo esc_html( $title ); ?></h3></span>
					<?php if ( $image ) : ?>
						<span class="list-item-image">
							<img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_html( $image['alt'] ) ?>" />
						</span>
					<?php endif; ?>
					
					<?php if ( $media ) : ?>
						<span class="list-item-media">
							<?php echo get_sub_field( 'news_list_media' ); ?>
						</span>
					<?php endif; ?>
					<span class="list-item-content">
						<?php echo wp_kses_data( $content ); ?>
					</span>

				</div>

				<?php $i++;
				endwhile; ?>

			</div>

		<?php endif; ?>
		
	<?php endif; ?>
	<!-- <div class="consulta" style="width: 100%;display:block;text-align: center;">
		<div style="color:#000;width:100%;display:inline-block;font-size:40px;line-height: 40px;border:2px solid rgba(0,0,0,0.1);padding:20px;border-radius: 10px;background: #f2f2f2">
			<span style="margin-bottom:30px;border:2px solid #000;display:inline-block;border-radius: 100%;">
				<img src="https://image.flaticon.com/icons/svg/2966/2966313.svg" style="margin:10px;" width="55px" height="50px" alt="">
			</span>
			<br>
			Precisa da ajuda de um especialista?
			<br>
			<a href="http://tiny.cc/ecaresaude" target="_blank" style="display: inline-block;font-size: 14px;background: #52b8a6;color:#fff;padding:5px 20px;border-radius: 5px;margin-top:30px;text-transform: uppercase;font-weight: bold;letter-spacing: 1px;">agende uma consulta</a>
		</div>
	</div> -->
	<div class="row">
		<div class="col-md-12 text-center">
			<?php
			$idCat = get_the_category(get_the_ID())[0]->term_id;
			$pos = get_post_meta( get_the_ID(), 'pos', true );
			$pos = $pos+1;

			?>

			<?php
			global $post;
			$args = array(
				'category' => $idCat,
				'meta_key'=>'pos',
				'order' => 'ASC',
				'posts_per_page' => '-1',
				'meta_value'=>$pos
			);
				$myposts = get_posts( $args );


			foreach ( $myposts as $postD ) : setup_postdata( $postD ); 
				?>
			
				<a href="<?php print get_permalink($postD->ID);?>" class="btn-proximo-conteudo">próximo conteúdo: <?php print $postD->post_title; ?></a>
			<?php endforeach;?>



		</div>
	</div>
	<footer class="entry-footer">
		<?php king_entry_footer(); ?>
		<div class="post-meta">
			<span class="post-views"><i class="fa fa-eye" aria-hidden="true"></i><?php echo esc_attr( king_postviews( get_the_ID(), 'display' ) ); ?></span>
			<span class="post-comments"><i class="fa fa-comment" aria-hidden="true"></i><?php comments_number( ' 0 ', ' 1 ', ' % ' ); ?></span>
			<span class="post-time"><i class="far fa-clock"></i><?php the_time( 'F j, Y' ); ?></span>
		</div>		
		<?php
		if ( is_super_admin() ) :
			edit_post_link(
				sprintf(
					/* translators: %s: Name of current post */
					esc_html__( 'Edit %s', 'king' ),
					the_title( '<span class="screen-reader-text">"', '"</span>', false )
				),
				'<span class="edit-link">',
				'</span>'
			);
				endif;	?>
				<div class="post-nav post-nav-mobile">
					<?php
					if ( ! empty( $next_post ) ) : ?>
						<a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" title="<?php echo esc_attr( $next_post->post_title ); ?>" class="prev-link" ><i class="fa fa-angle-left"></i></a>
					<?php endif; ?>
					<?php
					if ( ! empty( $prev_post ) ) : ?>
						<a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>" title="<?php echo esc_attr( $prev_post->post_title ); ?>" class="prev-link" ><i class="fa fa-angle-right"></i></a>
					<?php endif; ?>
				</div><!-- .post-nav-mobile -->
							
			</footer><!-- .entry-footer -->

		</div><!-- #post-## -->
	<?php get_template_part( 'template-parts/post-templates/single-parts/single-boxes' ); ?>

	<?php

			// If comments are open or we have at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) :
		comments_template();
	endif;

		endwhile; // End of the loop.
		?>

	<?php if ( get_field( 'display_related', 'options' ) ) : ?>
		<?php get_template_part( 'template-parts/related-posts' ); ?>
	<?php endif; ?>	

<?php if ( get_post_status( $post->ID ) === 'pending' ) : ?>
	<div class="king-pending"><?php esc_html_e( 'This News post will be checked and approved shortly.', 'king' ) ?></div>
<?php endif; ?>

</main><!-- #main -->

<div style="color: #FFFFFF; padding:30px;background-color: #17222f; border-radius: 0 0 10px 10px;margin-top:-5px;" class="container">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-lg-12">
			<div class="text-center">
				<div>
					<div class="row">
						<div class="col-md-8">
							<label>Avalie esse conteúdo e ajude a Academia da Mente evoluir! </label>
						</div>

						<?php 


							$estrelas;
							function getVotacao() {
								global $wpdb;

								$user = get_current_user_id();
								$conteudo = get_the_ID();

							  	// $select = "select id from conteudo_concluido where id_user = '{$_POST['offset']['id_user']}' and id_conteudo = '{$_POST['offset']['id_conteudo']}'";
							  	$select = "select estrelas from wp_votacao where id_user = '$user' and id_conteudo = '$conteudo'";
								$consulta = $wpdb->get_results($select);

								if($consulta)
									$estrelas = $consulta[0]->estrelas;

								return $estrelas;
							}

							$estrelas = getVotacao();

						?>

						<div class="col-md-4">
							<!-- <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css"> -->
							<div class="estrelas">
							  <input type="radio" id="cm_star-empty" name="fb" value="" checked/>
							  <label for="cm_star-1"><i class="fa"></i></label>
							  <input <?php $estrelas == '1' ? print 'checked' : ''; ?> class="votacao" type="radio" id="cm_star-1" name="fb" value="1"/>
							  <label for="cm_star-2"><i class="fa"></i></label>
							  <input <?php $estrelas == '2' ? print 'checked' : ''; ?> class="votacao" type="radio" id="cm_star-2" name="fb" value="2"/>
							  <label for="cm_star-3"><i class="fa"></i></label>
							  <input <?php $estrelas == '3' ? print 'checked' : ''; ?> class="votacao" type="radio" id="cm_star-3" name="fb" value="3"/>
							  <label for="cm_star-4"><i class="fa"></i></label>
							  <input <?php $estrelas == '4' ? print 'checked' : ''; ?> class="votacao" type="radio" id="cm_star-4" name="fb" value="4"/>
							  <label for="cm_star-5"><i class="fa"></i></label>
							  <input <?php $estrelas == '5' ? print 'checked' : ''; ?> class="votacao" type="radio" id="cm_star-5" name="fb" value="5"/>
							</div>
						</div>
					</div>
				</div>
				
			</div>		
		</div>
	</div>
</div>
</div><!-- #primary -->

<script>
	

	jQuery("#concluido").click(function(e){
		e.preventDefault();
		
		var concluido = {
			id_user: "<?php print get_current_user_id(); ?>",
			id_conteudo: "<?php print $idAbsoluto;  ?>",
			id_categoria: "<?php print get_the_category(get_the_ID())[0]->term_id ?>"
		}

		jQuery.post("<?php print admin_url( 'admin-ajax.php' ); ?>", {action:'concluido',offset:concluido}, function(response) {
		    console.log(response);

		    if(response == 'adicionado0'){
		    	jQuery("#concluido").html('<img width="166" height="50" src="<?php print content_url().'/bt_concluido.png'?>">');
		    	jQuery("#concluido").removeClass('');
		    	jQuery("#concluido").addClass('');
		    }
		    if(response == 'removido0'){
		    	jQuery("#concluido").html('<img width="166" height="50" src="<?php print content_url().'/bt_marcar_como_concluido.png'?>">');
		    	jQuery("#concluido").addClass('');
		    	jQuery("#concluido").removeClass('');
		    }

		});

	});

	jQuery(".votacao").click(function(e){
		//e.preventDefault();

		const qtdEstrelas = jQuery(this).val();
		const id_user = "<?php print get_current_user_id(); ?>";
		console.log(qtdEstrelas);

		if (id_user == 0) {
			alert('Faça login para realizar uma avaliação!');
			return;
		}
		
		var votacao = {
			id_user: "<?php print get_current_user_id(); ?>",
			id_conteudo: "<?php print $idAbsoluto; ?>",
			estrelas: jQuery(this).val()
		}


		jQuery.post("<?php print content_url('/themes/king/action-votacao.php') ?>", {action:'votar',offset:votacao}, function(response) {
		    console.log(response);

		    if(response == 'jaVotou') {
		    	alert('Você já realizou uma avaliação anterior para este conteúdo!');
		    	location.reload();
		    }else{
		    	location.reload();
		    }
		    // if(response == 'adicionado0'){
		    // 	jQuery("#concluido").html('<img src="<?php print content_url().'/botao-concluido-on.png'?>">');
		    // 	jQuery("#concluido").removeClass('');
		    // 	jQuery("#concluido").addClass('');
		    // }
		    // if(response == 'removido0'){
		    // 	jQuery("#concluido").html('<img src="<?php print content_url().'/botao-concluido-off.png'?>">');
		    // 	jQuery("#concluido").addClass('');
		    // 	jQuery("#concluido").removeClass('');
		    // }

		});

	});

	jQuery( document ).ready(function() {
	    console.log( "ready!" );
	});
</script>
<?php get_footer(); ?>
