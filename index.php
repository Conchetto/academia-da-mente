<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package king
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header(); ?>
<?php
// Slider.
if ( is_front_page() && is_home() && get_field( 'display_slider', 'options' ) && ( get_field( 'slider_template', 'options' ) === 'slider-template-1' || get_field( 'slider_template', 'options' ) === 'slider-template-2' ) ) :
	get_template_part( 'template-parts/king-featured-posts' );
elseif ( is_front_page() && is_home() && get_field( 'display_slider', 'options' ) && get_field( 'slider_template', 'options' ) === 'slider-template-3' ) :
	get_template_part( 'template-parts/king-featured-video' );
endif;

// Header Navigation.



// Navigation page id.
if ( get_field( 'pagination_type', 'options' ) ) {
	$pagination_id = get_field( 'pagination_type', 'options' );
} else {
	$pagination_id = 'king-pagination-01';
}
// Sidebar templates.
if ( get_field( 'sidebar_templates', 'options' ) ) {
	$sidebar = get_field( 'sidebar_templates', 'options' );
} else {
	$sidebar = 'king-sidebar-01';
}
?>
<div id="primary" class="content-area">
	<div class="row">
		<?php
		$categories = get_categories( array(
			'orderby' => 'count',
			'hide_empty' => false,
			'parent'=>141
		) );
		foreach ( $categories as $cat ) :
			$color = get_field( 'category_colors', 'category_' . $cat->term_id );
			$catlogo = get_field( 'category_logo', 'category_' . $cat->term_id );
			$size = 'thumbnail';
			$thumb = $catlogo['sizes'][ $size ];
			$bgimage = get_field( 'category_background_image', 'category_' . $cat->term_id );
			if ( $bgimage ) {
				$bgimage = 'background-image:url(' . $bgimage . ');';
			}


			?>  


				<div class="col-md-4" style="margin-top:30px;">
					<a href="<?php print site_url();?>/trilha/?id=<?php print $cat->term_id; ?>" class="king-page-cat-links" style="display: block;text-decoration:none;">
								<?php if( $color || $bgimage || $catlogo ) : ?>
									<div class="box-home" style="background-color: <?php echo esc_attr( $color ); ?>; <?php echo esc_attr( $bgimage ); ?>" >
										<!-- <?php if( $catlogo ) : ?>
											<img src="<?php echo esc_attr( $thumb ); ?>" class="cat-logo">
										<?php endif; ?> -->
								<?php else : ?>
										<div class="box-home">
									<?php endif; ?>	
												<div class="king-categories-desc">
													<div class="king-categories-head-2">
														<?php echo esc_attr( $cat->name ); ?>						
													</div>
													<?php  esc_attr( $cat->description ); ?>					
												</div>	
																	
									</div>

									<?php 
									if(get_current_user_id()):

									$args = array(
									  'cat' =>  $cat->term_id
									);
									$the_query = new WP_Query( $args );
									$qtdCat = $the_query->found_posts;

									$id_user = get_current_user_id();

										

									$select = "select count(*) as qtd from conteudo_concluido where id_user = '{$id_user}' and id_categoria={$cat->term_id}";
									$consulta = $wpdb->get_results($select);
									$qtd = $consulta[0]->qtd;
									$conta = 0;
									if($qtd+$qtdCat != 0){
									$conta = $qtd/$qtdCat*100;

									}
									
									?>	
batatinha
									<?php if($conta > 0): ?>
									<div class="barra">
										<div class="progresso" style="width:<?php print $conta;?>%"></div>
									</div>
								<?php endif; endif;?>
					</a>
				</div>

			<?php endforeach; ?>
		</div>
</div><!-- #primary -->

<?php get_footer(); ?>
