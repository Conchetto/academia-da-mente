<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package king
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php

$display_option = get_field( 'select_default_display_option', 'options' );
if ( 'king-grid-03' === $display_option ) {
	get_template_part( 'template-parts/content-templates/content-06' );
} elseif ( 'king-grid-04' === $display_option ) {
	get_template_part( 'template-parts/content-templates/content-02' );
} elseif ( 'king-grid-05' === $display_option ) {
	get_template_part( 'template-parts/content-templates/content-03' );
} elseif ( 'king-grid-07' === $display_option ) {
	get_template_part( 'template-parts/content-templates/content-05' );
} elseif ( 'king-grid-08' === $display_option ) {
	get_template_part( 'template-parts/content-templates/content-04' );
} elseif ( 'king-grid-09' === $display_option ) {
	get_template_part( 'template-parts/content-templates/content-07' );
} elseif ( 'king-grid-10' === $display_option ) {
	get_template_part( 'template-parts/content-templates/content-08' );
} else {
	get_template_part( 'template-parts/content-templates/content-01' );
}

?>
