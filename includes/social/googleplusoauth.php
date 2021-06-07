<?php
/**
 * GooglePlus login oauth.
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
function king_googleplus_oauth_callback() {
	check_ajax_referer( 'social_ajax_nonce', '_nonce' );

	$me = array();
	$googleplus = array();
	$googleplus['error'] = '';
	$googleplus['action'] = 'login';

	$access_token = $_POST['access_token'];

	$me = wp_remote_get( 'https://www.googleapis.com/oauth2/v1/userinfo?alt=json&access_token=' . $access_token );

	if ( ! empty( $me ) ) {

		if ( 200 !== $me['response']['code'] ) {
			$googleplus['error'] = esc_html__( 'Failed to validate token', 'king' );
			echo json_encode( $googleplus );
			die();
		}

		$me = json_decode( $me['body'], true );

		if ( empty( $me['id'] ) || empty( $me['email'] ) ) {
			$googleplus['error'] = esc_html__( 'Email missing', 'king' );
			echo json_encode( $googleplus );
			die();
		}
		// get user informations.
		$id         = $me['id'];
		$email      = $me['email'];
		$first_name = $me['given_name'];
		$last_name  = $me['family_name'];
		$name = $me['given_name'];
		$avatar_url    = '';
		$user = '';
		// check if user exists.
		$user2 = get_users( array( 'meta_key' => 'googleplus_id', 'meta_value' => $id, 'number' => 1 ) );
		if ( ! empty( $user2 ) ) {
			$user = end( $user2 );
		}

		if ( empty( $user ) ) {
			if ( ! get_option( 'users_can_register' ) ) {
				$googleplus['error'] = esc_html__( 'Registering new users is currently not allowed.', 'king' );
			}
			// user name should be unique.
			if ( username_exists( $name ) ) {
				$i = 1;
				$user_login_tmp = $name;

				do {
					$user_login_tmp = $name . '_' . ( $i++ );
				} while( username_exists( $user_login_tmp ) );

				$name = $user_login_tmp;
			}
			// sanitize user login.
			$name = sanitize_user( $name, true );
			$name = strtolower( $name );
			// remove spaces and dots.
			$name = trim( str_replace( array( ' ', '.' ), '_', $name ) );
			$name = trim( str_replace( '__', '_', $name ) );
			// email adress must be unique.
			if ( email_exists( $email ) ) {
				do {
					$email = md5( uniqid( wp_rand( 10000, 99000 ) ) ) . '@example.com';
				} while ( email_exists( $email ) );
			}
			// create a new account and then login.
			$userme = array(
				'user_login' => $name,
				'user_email' => $email,
				'user_pass' => wp_generate_password(),
				'role' => 'author',
				);

			$user_id = wp_insert_user( $userme );

			// get avatar from google plus profile.
			$avatar_url = $me['picture'];
			if ( ! empty( $avatar_url ) ) {
				$avatar_id = king_google_upload_user_avatar( $avatar_url , $user_id );
				// set as profile picture.
				update_user_meta( $user_id,'_author_image','field_587be48552f9f' );
				update_user_meta( $user_id,'author_image',$avatar_id );
			}
			// update google access token code.
			update_user_meta( $user_id, 'googleplus_access_token', $access_token );
			update_user_meta( $user_id, 'googleplus_id', $id );

			$user = get_user_by( 'id', $user_id );
		}

		if ( ! empty( $user ) ) {
			wp_set_current_user( $user->ID );
			wp_set_auth_cookie( $user->ID );
			update_user_meta( $user->ID, 'googleplus_access_token', $access_token );

			if ( ! empty( $first_name ) && ! empty( $last_name ) ) {
				wp_update_user( array(
					'ID'         => $user->ID,
					'first_name' => $first_name,
					'last_name'  => $last_name
				) );
			}
		}
	}
	echo json_encode( $googleplus );
	die();
}
add_action( 'wp_ajax_nopriv_king_googleplus_oauth_callback', 'king_googleplus_oauth_callback' );

/**
 * Upload user avatar from google plus profile.
 *
 * @param [type] $avatar_url twi avatar.
 *
 * @param [type] $user_id User ID.
 *
 * @return mixed
 */
function king_google_upload_user_avatar( $avatar_url, $user_id ) {
	// Need to require these files.
	if ( ! function_exists( 'media_handle_upload' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		require_once( ABSPATH . 'wp-admin/includes/media.php' );
	}

	$tmp = download_url( $avatar_url );

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
	preg_match( '/[^\?]+\.(jpg|jpeg|gif|png)/i', $avatar_url, $matches );
	// Image base name.
	$name = basename( $matches[0] );
	$url_type = wp_check_filetype( $name );
	// rename avatar image.
	$newfilename = 'avatarimg_user' . $user_id . '.' . $url_type['ext'];
	$file_array = array(
		'name'     => $newfilename,
		'tmp_name' => $tmp,
		);
	// do the validation and storage stuff.
	$id = media_handle_sideload( $file_array, $post_id, $desc );

	// If error storing permanently, unlink.
	if ( is_wp_error( $id ) ) {
		@unlink( $file_array['tmp_name'] );
		return $id;
	}
	return $id;
}
