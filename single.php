<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package king
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header(); ?>
<?php
	// Hide Theme Switch in single page.
$GLOBALS['hide'] = 'hide';
?>
<?php
	// Display Navbar.
get_template_part( 'template-parts/king-header-nav' );
?>
<?php
if ( get_field( 'enable_user_groups', 'options' ) && ! king_groups_permissions( 'groups_view_posts' ) && ! is_super_admin() ) {
	get_template_part( 'template-parts/single-none' );
} elseif ( has_post_format( 'video' ) ) {
	$videotemplate  = get_field_object( 'video_posts_templates', 'options' );
	$videotemplate2 = get_field_object( 'video_template' );

	if ( ! empty( $videotemplate2['value'] ) ) {
		get_template_part( 'template-parts/post-templates/single', $videotemplate2['value'] );
	} elseif ( ! empty( $videotemplate['value'] ) ) {
		get_template_part( 'template-parts/post-templates/single', $videotemplate['value'] );
	} else {
		get_template_part( 'template-parts/single', 'video' );
	}
} elseif ( has_post_format( 'image' ) ) {
	$videotemplate  = get_field_object( 'image_posts_templates', 'options' );
	$videotemplate2 = get_field_object( 'image_template' );

	if ( ! empty( $videotemplate2['value'] ) ) {
		get_template_part( 'template-parts/post-templates/single', $videotemplate2['value'] );
	} elseif ( ! empty( $videotemplate['value'] ) ) {
		get_template_part( 'template-parts/post-templates/single', $videotemplate['value'] );
	} else {
		get_template_part( 'template-parts/single', 'image' );
	}
} else {

	$template  = get_field_object( 'single_post_templates', 'options' );
	$template2 = get_field_object( 'post_template' );

	if ( ! empty( $template2['value'] ) ) {
		get_template_part( 'template-parts/post-templates/single', 'post-' . $template2['value'] );
	} elseif ( ! empty( $template['value'] ) ) {
		get_template_part( 'template-parts/post-templates/single', 'post-' . $template['value'] );
	} else {

		get_template_part( 'template-parts/single', 'post' );
	}
}
?>
<?php
	// Social Share Function.
king_social_shares( get_the_ID() );
?>
<?php
	// Count Post Views.
king_postviews( get_the_ID(), 'count' );
?>
