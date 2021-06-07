<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package king
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! function_exists( 'king_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function king_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			esc_html_x( 'Posted on %s', 'post date', 'king' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		$byline = sprintf(
			esc_html_x( 'by %s', 'post author', 'king' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

	}
endif;

if ( ! function_exists( 'king_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function king_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			$post_author = get_post_field( 'post_author', get_the_ID() );
			$current_user = wp_get_current_user();
			if ( is_user_logged_in() && get_field( 'enable_post_edit', 'options' ) ) {
				if ( ( esc_attr( $post_author ) === esc_attr( $current_user->ID ) ) || is_super_admin() ) {
					if ( ( get_field( 'verified_edit_posts', 'options' ) && get_field( 'verified_account', 'user_' . $current_user->ID ) || ! get_field( 'verified_edit_posts', 'options' ) ) || ( get_field( 'enable_user_groups', 'options' ) && king_groups_permissions( 'groups_edit_their_own_posts' ) )  ) {
						echo '<a href="' . esc_url( add_query_arg( 'term', get_the_ID(), home_url( $GLOBALS['king_updte'] . '/' ) ) ) . '" class="king-fedit"><i class="fa fa-pencil" aria-hidden="true"></i>' . esc_html__( ' Edit', 'king' ) . '</a>';
					}
				}
			}
			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html__( ' ', 'king' ) );
			if ( $tags_list ) {
				printf( '<span class="tags-links">' . esc_html__( '%1$s', 'king' ) . '</span>', $tags_list ); // WPCS: XSS OK.
			}
		}
	}
endif;

if ( ! function_exists( 'king_entry_cat' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function king_entry_cat() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ' ', 'king' ) );
			if ( $categories_list && king_categorized_blog() ) {
				printf( '<span class="cat-links">' . esc_html__( '%1$s', 'king' ) . '</span>', $categories_list ); // WPCS: XSS OK.
			}
		}
	}
endif;
/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function king_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'king_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories(
			array(
				'fields'     => 'ids',
				'hide_empty' => 1,
				'number'     => 2,
			)
		);

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'king_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so king_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so king_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in king_categorized_blog.
 */
function king_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'king_categories' );
}
add_action( 'edit_category', 'king_category_transient_flusher' );
add_action( 'save_post',     'king_category_transient_flusher' );

if ( ! function_exists( 'king_user_groups' ) ) :
	/**
	 * User Groups.
	 *
	 * @param <type> $user_id  The user identifier.
	 *
	 * @return string  ( description_of_the_return_value )
	 */
	function king_user_groups( $user_id ) {
		$groups = get_field( 'user_groups', 'options' );
		$rtrn   = '';
		if ( $groups ) :
			$rtrn .= '<div class="user-group">';
			foreach ( $groups as $group ) :
				$usergroups = $group['group_users'];
				if ( $usergroups ) :
					foreach ( $usergroups as $usergroup ) :
						if ( $usergroup == $user_id ) :
							$rtrn .= '<span class="group-icon" title="' . $group['group_name'] . '" style="background-color: ' . $group['group_color'] . ';">' . $group['group_icon'] . '' . $group['group_name'] . '</span>';
						endif;
					endforeach;
				endif;
			endforeach;
			$rtrn .= '</div>';
		endif;
		return $rtrn;
	}
endif;
