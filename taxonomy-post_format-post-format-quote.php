<?php
/**
 * The template for displaying quote posts.
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
<header class="page-top-header">
	<h1 class="page-title"><?php echo esc_html_e( 'News', 'king' ); ?></h1>
	<?php the_archive_description( '<div class="taxonomy-description">', '</div>' ); ?>
</header><!-- .page-header -->
<?php get_template_part( 'template-parts/king-header-nav' ); ?>	
<?php
if ( get_field( 'pagination_type', 'options' ) ) {
	$pagination_id = get_field( 'pagination_type', 'options' );
} else {
	$pagination_id = 'king-pagination-01';
}
// Sidebar templates.
if ( get_field( 'sidebar_news_template', 'options' ) ) {
	$sidebar = get_field( 'sidebar_news_template', 'options' );
} else {
	$sidebar = 'king-sidebar-04';
}
?>
<div id="primary" class="content-area">
	<div id="<?php echo esc_attr( $pagination_id ); ?>" class="site-main-top <?php echo esc_attr( $sidebar ); ?>">
		<?php
		if ( ( $sidebar === 'king-sidebar-02' ) || ( $sidebar === 'king-sidebar-03' ) ) {
			get_sidebar('2');
		}
		?>
		<main id="main" class="site-main">
			<ul class="king-posts">
				<li class="grid-sizer"></li>
				<?php
				if ( have_posts() ) :
					/* Start the Loop */
					while ( have_posts() ) :
						the_post();

					/*
					 * Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'template-parts/content', get_post_format() );

				endwhile;

				get_template_part( 'template-parts/king-pagination' );

			else :

				get_template_part( 'template-parts/content', 'none' );

			endif;
			?>
		</ul>
	</main><!-- #main -->
	<?php
	if ( ( $sidebar === 'king-sidebar-01' ) || ( $sidebar === 'king-sidebar-03' ) ) {
		get_sidebar();
	}
	?>
</div> 		
</div><!-- #primary -->

<?php get_footer(); ?>
