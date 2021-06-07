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


?>

<style>
	a:hover, a:focus, a:active{color:#0e6731;}
	.entry-title{display: inline-block;}
	.btn-outline-sucess, .btn-outline-sucess:focus{color:#0e6731 !important;}
	.btn-success, .btn-success:focus{color:#fff !important;}

</style>
	<?php if ( has_post_thumbnail() ) :
	$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' ); ?>
		<div class="single-post-back" style="background-image: url('<?php echo esc_url( $thumb['0'] ); ?>');"></div>
	<?php elseif ( get_field( 'default_background', 'option' ) ) : $dbackground = get_field( 'default_background', 'options' ); ?>
		<div class="single-post-back" style="background-image: url('<?php echo esc_url( $dbackground ); ?>');"></div>
	<?php else : ?>
		<div class="single-post-back-no"></div>
	<?php endif; ?> 

<div id="primary" style="width: 920px;" class="content-area sing-template-4">
	
		
	<main id="main" style="text-align: justify; padding-bottom: 0px;" class="site-main post-page single-post">
		
		<?php if ( get_field( 'ads_above_content', 'option' ) ) : ?>
			<div class="ads-postpage"><?php $ad_above = get_field( 'ads_above_content','options' ); echo do_shortcode( $ad_above ); ?></div>
		<?php endif; ?>
		<?php while ( have_posts() ) : the_post(); ?>



		
		<div style="margin-bottom: 0px;" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php if ( get_field( 'add_sponsor' ) ) : ?>
			<div class="add-sponsor"><a href="<?php the_field( 'post_sponsor_link' ); ?>" target="_blank"><img src="<?php the_field( 'post_sponsor_logo' ); ?>" /></a><span class="sponsor-label"><?php the_field( 'post_sponsor_description' ); ?></span></div>
		<?php endif; ?>			
		<?php get_template_part( 'template-parts/post-templates/single-parts/share' ); ?>
		<header class="entry-header">
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
			
			<?php
			$id_user = get_current_user_id();
			$id_conteudo =  get_the_ID();
 			 global $wpdb;
				  	$select = "select id from conteudo_concluido where id_user = '{$id_user}' and id_conteudo = '{$id_conteudo}'";
					$consulta = $wpdb->get_results($select);

		  	if($consulta): ?>
		  		<a href="#" class="btn btn-success" style="float:right" id="concluido">
		  			<img src="<?php print content_url().'/botao-concluido-on.png'?>">
		  		</a>
		  	<?php else: ?>
		  		<a href="#" class="" style="float:right" id="concluido">
		  			<img src="<?php print content_url().'/botao-concluido-off.png'?>">
		  		</a>
		  	<?php endif; ?>
		  	
		</header><!-- .entry-header -->
			<div class="post-page-featured-trending">
					<div class="post-like">
						<?php echo king_get_simple_likes_button( get_the_ID() ); ?>
						<?php  if ( ! is_user_logged_in() ) : ?>
							<div class="king-alert-like"><?php esc_html_e( 'Please ', 'king' ) ?><a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_login'] ); ?>"><?php esc_html_e( 'log in ', 'king' ) ?></a><?php esc_html_e( ' or ', 'king' ) ?><a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_register'] ); ?>"><?php esc_html_e( ' register ', 'king' ) ?></a><?php esc_html_e( ' to like posts. ', 'king' ) ?></div>
						<?php endif; ?>
					</div><!-- .post-like -->
				<a class="news-entry-format entry-format" href="<?php echo esc_url( get_post_format_link( 'quote' ) ); ?>"><?php echo esc_html_e( 'News', 'king' ) ?></a>
				<?php if ( get_field( 'featured-post' )  ||  get_field( 'keep_trending' ) ) :?>		
					<?php if ( get_field( 'featured-post' ) ) : ?>
						<div class="featured"><i class="fa fa-rocket fa-lg" aria-hidden="true"></i><span><?php echo esc_html_e( 'featured', 'king' ) ?></span></div><!-- .featured -->
					<?php endif; ?>
					<?php if ( get_field( 'keep_trending' ) ) : ?>
						<div class="trending"><i class="fa fa-bolt fa-lg" aria-hidden="true"></i><span><?php echo esc_html_e( 'trending', 'king' ) ?></span></div><!-- .trending -->
					<?php endif; ?>
				<?php endif; ?>
			</div><!-- .post-page-featured-trending -->

<?php if ( get_field( 'nsfw_post' ) && ! is_user_logged_in() ) : ?>
	<div class="post-video nsfw-post-page">
		<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_login'] ); ?>">
			<i class="fa fa-paw fa-3x"></i>
			<div><h1><?php echo esc_html_e( 'Not Safe For Work', 'king' ) ?></h1></div>
			<span><?php echo esc_html_e( 'Click to view this post.', 'king' ) ?></span>
		</a>	
	</div>
<?php else : ?>
	<?php if(get_the_ID() != 869 && get_the_ID() != 864) { ?>
	<div class="row">
		<div class="col-md-10">
			<div class="entry-content">

					<?php if(!is_user_logged_in()): ?>
						<div style="text-align:center;background:rgba(0,0,0,0.5);width:100%;height: 40%;position: absolute;bottom:100px;right:0;background-color: rgba(0, 0, 0, 0.5);
				  -webkit-backdrop-filter: blur(5px);
				  backdrop-filter: blur(5px);
				  padding: 50px;">

				  	<p style="color:#fff;font-size: 35px;line-height: 50px;margin-top:20%">
				  		Você precisa fazer login <br>  para ter acesso a todo o conteúdo.
				  	</p>
				  	<a data-toggle="modal" class="header-login" data-target="#myModal" style="margin-top:10px;display:inline-block;background: #fff;padding:15px 20px;font-size:30px;border-radius: 10px;">clique aqui</a>

				  </div>
					<?php endif; ?>

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
	<?php } else { ?>
		<div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
		  <div class="carousel-inner">
		    <div class="carousel-item active">
		      <img class="d-block w-100" src="..." alt="First slide">
		    </div>
		    <div class="carousel-item">
		      <img class="d-block w-100" src="..." alt="Second slide">
		    </div>
		    <div class="carousel-item">
		      <img class="d-block w-100" src="..." alt="Third slide">
		    </div>
		  </div>
		  <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
		    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
		    <span class="sr-only">Previous</span>
		  </a>
		  <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
		    <span class="carousel-control-next-icon" aria-hidden="true"></span>
		    <span class="sr-only">Next</span>
		  </a>
		</div>
	<?php } ?>

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

<div style="color: #FFFFFF; width: 100%; height: 100px; background-color: #17222f; border-radius: 10px;">
	<div class="text-center">
		<div style="padding-top: 40px;">
			<div class="row">
				<div class="col-md-10">
					<div class="row">
						<div class="col-md-10">
							<div class="row">
								<div class="col-md-10">
									<label> AJÚDE-NOS A EVOLUIR, AVALIANDO ESTE CONTEÚDO</label>
								</div>
								<div class="col-md-2">
									<img width="20" src="<?php print content_url().'/estrela_vazia.svg' ?>">	
								</div>
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
			id_conteudo: "<?php print get_the_ID(); ?>",
		}

		jQuery.post("<?php print admin_url( 'admin-ajax.php' ); ?>", {action:'concluido',offset:concluido}, function(response) {
		    console.log(response);

		    if(response == 'adicionado0'){
		    	jQuery("#concluido").html('<img src="<?php print content_url().'/botao-concluido-on.png'?>">');
		    	jQuery("#concluido").removeClass('');
		    	jQuery("#concluido").addClass('');
		    }
		    if(response == 'removido0'){
		    	jQuery("#concluido").html('<img src="<?php print content_url().'/botao-concluido-off.png'?>">');
		    	jQuery("#concluido").addClass('');
		    	jQuery("#concluido").removeClass('');
		    }

		});

	});
</script>
<?php get_footer(); ?>
