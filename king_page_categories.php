<?php
/**
 * The template for displaying the Categories page
 *
 * Template Name: categories
 *
 * @package King
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header(); ?>
<?php $GLOBALS['hide'] = 'hide'; ?>
<header class="page-top-header categories">
	<h1 class="page-title"><?php esc_html_e( 'Categories', 'king' ); ?> <i class="fa fa-sliders fa-lg" aria-hidden="true"></i></h1>
</header><!-- .page-header -->
<?php get_template_part( 'template-parts/king-header-nav' ); ?>
<div id="primary" class="content-area">
	<main id="main" class="site-main">
	
		<div class="king-categories-page">
			<?php

			$categories = get_categories( array(
				'orderby' => 'count',
				'hide_empty' => false,
				'order' => 'DESC',
			) );
			foreach ( $categories as $cat ) {
				if ( 0 !== $cat->category_parent ) {
					echo '<span class="king-subcat">';
				}
				?>    

				<div class="king-categories">
					<div class="king-categories-head">
						<a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>"> <?php echo esc_attr( $cat->name ); ?> </a>
						<i><?php echo esc_attr( $cat->category_count ); ?></i>
					</div>
					<div class="categories-posts">
						<?php
						global $post;
						$args = array( 'posts_per_page' => '4', 'category' => $cat->term_id, 'orderby' => 'date', 'order' => 'DESC' ); // include category 9.
						$custom_posts = get_posts( $args );
						if ( $custom_posts ) :
							foreach ( $custom_posts as $post ) : setup_postdata( $post );
						?>
		<div class="categories-post">
			<?php if ( get_field( 'nsfw_post' ) && ! is_user_logged_in() ) : ?>
				<div class="nsfw-users-post">
					<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_login'] ); ?>">
						<i class="fa fa-paw fa-3x"></i>
						<div><h1><?php echo esc_html_e( 'Not Safe For Work', 'king' ) ?></h1></div>
						<span><?php echo esc_html_e( 'Click to view this post.', 'king' ) ?></span>
					</a>    
				</div>
			<?php else : ?>
				<a href="<?php the_permalink(); ?>">
					<?php if ( has_post_thumbnail() ) :
							$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' ); ?>
					<div class="categories-post-img" style="background-image: url('<?php echo esc_url( $thumb['0'] ); ?>')"></div>
				<?php else : ?>
					<span class="categories-post-no-thumb"></span>
				<?php endif; ?>          
				<div class="categories-post-in">        
					<span class="categories-post-title" ><?php the_title(); ?></span>
					<span class="categories-post-date" ><?php the_time( 'F j, Y' ); ?></span>
				</div>
			</a> 
		<?php endif; ?>     
	</div> 
<?php endforeach; ?>
<?php else : ?>
	<span class="categories-noposts"><?php echo esc_html_e( 'No posts','king' ); ?> </span>
<?php endif; ?>
</div>

</div>

<?php
if ( 0 !== $cat->category_parent ) {
	echo '</span>';
}
			}
?>

</div>

</main><!-- #main -->
<?php get_sidebar(); ?> 
</div><!-- #primary -->

<?php get_footer(); ?>
