<?php
/**
 * The content part - meta.
 *
 * This is a content template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package king
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="article-meta-head">
	<header class="entry-header">
		<?php
		if ( is_single() ) {
			the_title( '<h1 class="entry-title">', '</h1>' );
		} else {
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		}
		?>
	</header><!-- .entry-header -->
	<div class="entry-content">
		<?php
		$content = strip_shortcodes( get_the_content() );
		$content = wp_strip_all_tags( $content );
		echo esc_attr( substr( $content, 0, 100 ) );

		wp_link_pages(
			array(
				'before' => '<div class="page-links">',
				'after'  => '</div>',
			)
		);
		?>
	</div><!-- .entry-content -->
	<?php king_entry_cat(); ?>
	<?php if ( get_post_status( get_the_ID() ) === 'draft' ) : ?>
		<a href="<?php echo esc_url( add_query_arg( 'term', get_the_ID(), home_url( $GLOBALS['king_updte'] . '/' ) ) ); ?>" class="king-fedit"><i class="fa fa-pencil" aria-hidden="true"></i><?php echo esc_html_e( 'Edit', 'king' ); ?></a>
	<?php endif; ?>
</div><!-- .article-meta-head -->
