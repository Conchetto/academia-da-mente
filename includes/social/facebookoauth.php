<?php
/**
 * Facebook login oauth.
 *
 * @package King
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Ajax Social Authentication
 */
function king_facebook_oauth_redirect() {
	global $wp, $wp_query, $wp_the_query, $wp_rewrite, $wp_did_header;
	// construct URL and redirect.
	$app_id = get_field( 'facebook_app_id', 'option' );
	$redirect_url = get_site_url() . '/wp-admin/admin-ajax.php?action=king_facebook_oauth_callback';
	$permission = 'email, public_profile';

	$final_url = 'https://www.facebook.com/dialog/oauth?client_id=' . rawurlencode( $app_id ) . '&redirect_uri=' . rawurlencode( $redirect_url ) . '&scope=' . $permission;

	header( 'Location: ' . $final_url );
	die();
}

add_action( 'wp_ajax_king_facebook_oauth_redirect', 'king_facebook_oauth_redirect' );
add_action( 'wp_ajax_nopriv_king_facebook_oauth_redirect', 'king_facebook_oauth_redirect' );

/**
 * Facebook oauth callback.
 *
 * @return mixed
 */
function king_facebook_oauth_callback() {
	global $wp, $wp_query, $wp_the_query, $wp_rewrite, $wp_did_header;

	$secret_key = get_field( 'facebook_secret_key', 'option' );
	$app_id = get_field( 'facebook_app_id', 'option' );

	if ( isset( $_GET['code'] ) ) {
		$token_and_expire = wp_remote_get( 'https://graph.facebook.com/oauth/access_token?client_id=' . $app_id . '&redirect_uri=' . get_site_url() . '/wp-admin/admin-ajax.php?action=king_facebook_oauth_callback&client_secret=' . $secret_key . '&code=' . $_GET['code'] );
		$token_and_expire2 = wp_remote_retrieve_body( $token_and_expire );
		$respond = json_decode( $token_and_expire2 );

		if ( isset( $respond->access_token ) ) {
			$fields = array(
				'id',
				'name',
				'first_name',
				'last_name',
				'link',
				'website',
				'gender',
				'locale',
				'about',
				'email',
				'hometown',
				'location',
				'verified',
				'picture.type(large)',
			);
			$access_token = $respond->access_token;
			$user_information = wp_remote_get( 'https://graph.facebook.com/me?access_token=' . $access_token . '&fields=' . implode( ',', $fields ) . '' );
			$user_information2 = wp_remote_retrieve_body( $user_information );
			$user_information_array = json_decode( $user_information2 );

			$email = $user_information_array->email;
			$first_name = $user_information_array->first_name;
			$last_name = $user_information_array->last_name;
			$name = $first_name . $last_name;
			$id = $user_information_array->id;
			$fb_avatar = $user_information_array->picture->data->url;

			$user = '';
			$user2 = get_users( array( 'meta_key' => "facebook_id", 'meta_value' => $id, 'number' => 1 ) );
			if ( ! empty( $user2 ) ) {
				$user = end( $user2 );
			}
			if ( empty( $user ) ) {
				// user name should be unique.
				if ( username_exists( $name ) ) {
					$i = 1;
					$user_login_tmp = $name;
					do {
						$user_login_tmp = $name . '_' . ( $i++ );
					} while ( username_exists( $user_login_tmp ) );
						$name = $user_login_tmp;
				}

				// sanitize user login.
				$name = sanitize_user( $name, true );
				$name = strtolower( $name );
				// remove spaces and dots.
				$name = trim( str_replace( array( ' ', '.' ), '_', $name ) );
				$name = trim( str_replace( '__', '_', $name ) );
				// email should be unique.
				if ( email_exists( $email ) ) {
					do {
						$email = md5( uniqid( wp_rand( 10000, 99000 ) ) ) . '@example.com';
					} while ( email_exists( $email ) );
				}
				// create a new account and then login.
				$userdata = array(
				'user_login' => $name,
				'user_email' => $email,
				'user_pass' => wp_generate_password(),
				'first_name' => $first_name,
				'last_name' => $last_name,
				'role' => 'author',
				);

				$user_id = wp_insert_user( $userdata );
				if ( ! empty( $fb_avatar ) ) {
					$avatar_id = king_upload_facebook_user_avatar( $fb_avatar , $user_id );
					// set as profile picture.
					update_user_meta( $user_id,'_author_image','field_587be48552f9f' );
					update_user_meta( $user_id,'author_image',$avatar_id );
				}

				update_user_meta( $user_id, 'facebook_access_token', $access_token );
				update_user_meta( $user_id, 'facebook_id', $id );
				$user = get_user_by( 'id', $user_id );
			}

			if ( ! empty( $user ) ) {
				wp_set_auth_cookie( $user->ID );
				update_user_meta( $user->ID, 'facebook_access_token', $access_token );
				header( 'Location: ' . get_site_url() );
			}
		} else {
			header( 'Location: ' . get_site_url() );
		}
	} else {
		header( 'Location: ' . get_site_url() );
	}
	die();
}

add_action( 'wp_ajax_king_facebook_oauth_callback', 'king_facebook_oauth_callback' );
add_action( 'wp_ajax_nopriv_king_facebook_oauth_callback', 'king_facebook_oauth_callback' );

/**
 * Upload user avatar from Facebook profile.
 *
 * @param [type] $fb_avatar twi avatar.
 *
 * @param [type] $user_id User ID.
 *
 * @return mixed
 */
function king_upload_facebook_user_avatar( $fb_avatar, $user_id ) {
	// Need to require these files.
	if ( ! function_exists( 'media_handle_upload' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		require_once( ABSPATH . 'wp-admin/includes/media.php' );
	}

	$tmp = download_url( $fb_avatar );

	// If error storing temporarily, unlink.
	if ( is_wp_error( $tmp ) ) {
		@unlink( $file_array['tmp_name'] ); // clean up.
		$file_array['tmp_name'] = '';
		return $tmp; // output wp_error.
	}
	// image informations.
	$post_id = 0;
	$desc = 'avatarimg_user' . $user_id;
	$file_array = array();

	// Set variables for storage.
	// fix file filename for query strings.
	preg_match( '/[^\?]+\.(jpg|jpeg|gif|png)/i', $fb_avatar, $matches );
	// Image base name.
	$name = basename( $matches[0] );
	$url_type = wp_check_filetype( $name );
	// rename avatar image.
	$newfilename = 'avatarimg_user' . $user_id . '.jpg';
	$file_array['tmp_name'] = $tmp;
	$file_array['name'] = $newfilename;
	// do the validation and storage stuff.
	$id = media_handle_sideload( $file_array, $post_id, $desc );

	// If error storing permanently, unlink.
	if ( is_wp_error( $id ) ) {
		@unlink( $file_array['tmp_name'] );
		return $id;
	}
	return $id;
}
