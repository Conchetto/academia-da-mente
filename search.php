<?php
/**
 * The template for displaying search results pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package king
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header(); ?>

<header class="page-top-header">
	<h1 class="page-title"><?php printf( esc_html__( 'Search Results for: %s', 'king' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
</header><!-- .page-top-header -->
<?php get_template_part( 'template-parts/king-header-nav' ); ?>
<?php
if ( get_field( 'pagination_type', 'options' ) ) {
	$pagination_id = get_field( 'pagination_type', 'options' );
} else {
	$pagination_id = 'king-pagination-04';
}
	// Sidebar templates.
if ( get_field( 'search_page_sidebar_template', 'options' ) ) {
	$sidebar = get_field( 'search_page_sidebar_template', 'options' );
} else {
	$sidebar = 'king-sidebar-04';
}
?>
<section id="primary" class="content-area">
	<div id="<?php echo esc_attr( $pagination_id ); ?>" class="site-main-top <?php echo esc_attr( $sidebar ); ?>">

		<?php
		if ( get_field( 'show_user_results_in_search', 'options' ) ) :
			$keyword       = esc_attr( get_search_query() );
			$args          = array(
				'order'          => 'ASC',
				'search'         => '*' . $keyword . '*',
				'search_columns' => array(
					'user_login',
					'user_nicename',
					'display_name',
				),
			);
			$wp_user_query = new WP_User_Query( $args );
			$authors       = $wp_user_query->get_results();


			if ( ! empty( $authors ) ) : ?>
				<div class="usearch-page">
					<?php
					foreach ( $authors as $author ) :
						$author_info = get_userdata( $author->ID ); ?>

						<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_account'] . '/' . $author_info->user_login ); ?>">
							<?php if ( get_field( 'author_image', 'user_' . $author->ID ) ) :
								$image = get_field( 'author_image', 'user_' . $author->ID );
								?>
								<img src="<?php echo esc_url( $image['sizes']['thumbnail'] ); ?>" alt="profile" />
								<?php else : ?>
									<span class="usearch-noavatar"></span>
								<?php endif; ?>
								<?php echo esc_attr( $author_info->user_login ); ?>
							</a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			<?php endif; ?>
			<?php
			if ( ( $sidebar === 'king-sidebar-02' ) || ( $sidebar === 'king-sidebar-03' ) ) {
				get_sidebar('2');
			}
			?>
			<main id="main" class="site-main">		
				<ul class="king-posts">
					<li class="grid-sizer"></li>
					<?php
					if ( have_posts() ) : ?>
						<?php
						/* Start the Loop */
						while ( have_posts() ) : the_post();

						/**
						 * Run the loop for the search to output the results.
						 * If you want to overload this in a child theme then include a file
						 * called content-search.php and that will be used instead.
						 */
						get_template_part( 'template-parts/content', get_post_format() );

					endwhile;

					get_template_part( 'template-parts/king-pagination' );

				else :

					get_template_part( 'template-parts/content', 'none' );

				endif; ?>
			</ul>
		</main><!-- #main -->
		<?php
		if ( ( $sidebar === 'king-sidebar-01' ) || ( $sidebar === 'king-sidebar-03' ) ) {
			get_sidebar();
		}
		?>
	</div>	
</section><!-- #primary -->

<?php get_footer(); ?>
