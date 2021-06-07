<?php
/**
 * Theme options.
 *
 * @package King.
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( get_option( 'permalink_structure' ) ) :

	/**
	 * Redirect logged in users to the right page
	 */
	function king_template_redirect() {
		if ( is_page( 'login' ) && is_user_logged_in() ) {
			wp_redirect( home_url( '/profile/' ) );
			exit();
		}
		if ( is_page( 'user' ) && ! is_user_logged_in() ) {
			wp_redirect( home_url( '/login/' ) );
			exit();
		}
	}
	add_action( 'template_redirect', 'king_template_redirect' );

	/**
	 * Login page redirect.
	 *
	 * @param <type> $redirect_to The redirect to.
	 * @param <type> $url The url.
	 * @param <type> $user The user.
	 *
	 * @return <type>  ( description_of_the_return_value ).
	 */
	function king_login_redirect( $redirect_to, $url, $user ) {
		if ( ! isset( $user->errors ) ) {
			return $redirect_to;
		}
		wp_safe_redirect( home_url( '/login/' ) . '?action=login&failed=1' );
		exit;
	}
	add_filter( 'login_redirect', 'king_login_redirect', 10, 3 );

	/**
	 * Registration page redirect
	 */
	function king_registration_redirect() {
		// don't lose your time with spammers, redirect them to a success page.
		wp_safe_redirect( home_url( '/register/' ) );
		exit;
	}
	add_filter( 'registration_redirect', 'king_registration_redirect', 10, 3 );

	/**
	 * Password Reset Validate
	 */
	function king_validate_reset() {
		if ( isset( $_POST['user_login'] ) && ! empty( $_POST['user_login'] ) ) {
			$email_address = $_POST['user_login'];
			if ( filter_var( $email_address, FILTER_VALIDATE_EMAIL ) ) {
				if ( ! email_exists( $email_address ) ) {
					wp_redirect( 'reset/?action=userexist' );
					exit;
				}
			} else {
				$username = $_POST['user_login'];
				if ( ! username_exists( $username ) ) {
					wp_redirect( 'reset/?action=userexist' );
					exit;
				}
			}
		} else {
			wp_redirect( 'reset/?action=userexist' );
			exit;
		}
	}
	add_action( 'lostpassword_post', 'king_validate_reset', 99, 3 );

	/**
	 * Hide admin panel for users.
	 */
	function king_blockusers_init() {
		if ( is_admin() && ! current_user_can( 'administrator' ) && ! current_user_can( 'editor' ) && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
			wp_redirect( home_url() );
			exit;
		}
	}
	add_action( 'admin_init', 'king_blockusers_init' );

endif;
/**
 * Disable authors see all images
 *
 * @param where $where where.
 *
 * @return mixed
 */
function king_wpquery_where( $where ) {
	global $current_user;

	if ( is_user_logged_in() ) {
		// logged in user, but are we viewing the library?
		if ( isset( $_POST['action'] ) && ( $_POST['action'] === 'query-attachments' ) ) { // input var okay; sanitization okay
			$where .= ' AND post_author=' . $current_user->data->ID;
		}
	}

	return $where;
}
add_filter( 'posts_where', 'king_wpquery_where' );

/**
 * Disable authors see all images
 *
 * @param query $query query.
 *
 * @return mixed
 */
function king_authored_content( $query ) {
	// Get current user info to see if they are allowed to access ANY posts and pages.
	$current_user = wp_get_current_user();
	// Set current user to $is_user.
	$is_user = $current_user->user_login;

	// If is admin or 'is_user' does not equal #username.
	if ( is_admin() && ! current_user_can( 'administrator' ) && ! current_user_can( 'editor' ) && ! current_user_can( 'manage_options' ) ) {
		// If in the admin panel.
		if ( $query->is_admin ) {
			global $user_ID;
			$query->set( 'author',  $user_ID );
		}
		return $query;
	}
	return $query;
}
add_filter( 'pre_get_posts', 'king_authored_content' );

/**
* Processes like/unlike
* @since    0.5
*/
add_action( 'wp_ajax_nopriv_king_process_simple_like', 'king_process_simple_like' );
add_action( 'wp_ajax_king_process_simple_like', 'king_process_simple_like' );
function king_process_simple_like() {
	// Security.
	$nonce = isset( $_REQUEST['nonce'] ) ? sanitize_text_field( $_REQUEST['nonce'] ) : 0; // input var okay; sanitization okay.
	if ( ! wp_verify_nonce( $nonce, 'simple-likes-nonce' ) ) {
		exit( esc_attr( 'Not permitted', 'king' ) );
	}
	// Test if javascript is disabled.
	$disabled = ( isset( $_REQUEST['disabled'] ) && $_REQUEST['disabled'] === true ) ? true : false;
	// input var okay; sanitization okay.
	$post_id = ( isset( $_REQUEST['post_id'] ) && is_numeric( $_REQUEST['post_id'] ) ) ? $_REQUEST['post_id'] : '';
	// input var okay; sanitization okay.
	$result = array();
	$post_users = null;
	$like_count = 0;
	// Get plugin options.
	if ( $post_id !== '' ) {
		$count = get_post_meta( $post_id, '_post_like_count', true ); // like count
		$count = ( isset( $count ) && is_numeric( $count ) ) ? $count : 0;
		if ( ! king_already_liked( $post_id ) ) { // Like the post
			if ( is_user_logged_in() ) { // user is logged in
				$user_id = get_current_user_id();
				$post_users = king_post_user_likes( $user_id, $post_id );

				$user_like_count = get_user_option( '_user_like_count', $user_id );
				$user_like_count = ( isset( $user_like_count ) && is_numeric( $user_like_count ) ) ? $user_like_count : 0;
				update_user_option( $user_id, '_user_like_count', ++$user_like_count );
				if ( $post_users ) {
					update_post_meta( $post_id, '_user_liked', $post_users );
					if ( get_field( 'enable_notification', 'options' ) )  {
						$knotify_comment = array('type' => 'like', 'postid' => $post_id, 'who' => $user_id );
						$userid = get_post_field( 'post_author', $post_id );
						add_user_meta( $userid, 'king_notify', $knotify_comment );
						$nocount = get_user_meta($userid, 'king_notify_count', true);
						if (empty($count) || $count == '') {
							$count = 0;
						}
						$nototal = (int) $nocount + 1;
						update_user_meta( $userid, 'king_notify_count', $nototal );
					}
				}
			}
			$like_count         = ++$count;
			$response['status'] = 'liked';
			$response['icon']   = king_get_liked_icon();
		} else { // Unlike the post.
			if ( is_user_logged_in() ) { // user is logged in.
				$user_id    = get_current_user_id();
				$post_users = king_post_user_likes( $user_id, $post_id );
				// Update User.
				$user_like_count = get_user_option( '_user_like_count', $user_id );
				$user_like_count = ( isset( $user_like_count ) && is_numeric( $user_like_count ) ) ? $user_like_count : 0;
				if ( $user_like_count > 0 ) {
					update_user_option( $user_id, '_user_like_count', --$user_like_count );
				}
				// Update Post.
				if ( $post_users ) {
					$uid_key = array_search( $user_id, $post_users );
					unset( $post_users[ $uid_key ] );

					update_post_meta( $post_id, '_user_liked', $post_users );
					if ( get_field( 'enable_notification', 'options' ) )  {
						$knotify_comment = array('type' => 'like', 'postid' => $post_id, 'who' => $user_id );
						$userid = get_post_field( 'post_author', $post_id );
						delete_user_meta( $userid, 'king_notify', $knotify_comment );
						$kncount = get_user_meta($userid, 'king_notify_count', true);
						if (empty($kncount) || $kncount == '') {
							$kncount = 0;
						} else {
							$kntotal = (int) $kncount - 1;
						}
						update_user_meta( $userid, 'king_notify_count', $kntotal );
					}
				}
			}
			$like_count         = ( $count > 0 ) ? --$count : 0; // Prevent negative number.
			$response['status'] = 'unliked';
			$response['icon']   = king_get_liked_icon();
		}

		update_post_meta( $post_id, '_post_like_count', $like_count );
		update_post_meta( $post_id, '_post_like_modified', date( 'Y-m-d H:i:s' ) );

		$response['count'] = king_get_like_count( $like_count );
		if ( true === $disabled ) {
			wp_redirect( get_permalink( $post_id ) );
			exit();
		} else {
			wp_send_json( $response );
		}
	}
}

/**
 * Utility to test if the post is already liked.
 *
 * @param <type> $post_id The post identifier.
 * @return boolean ( description_of_the_return_value ).
 */
function king_already_liked( $post_id ) {
	$post_users = null;
	$user_id    = null;
	if ( is_user_logged_in() ) { // user is logged in.
		$user_id         = get_current_user_id();
		$post_meta_users = get_post_meta( $post_id, '_user_liked' );
		if ( count( $post_meta_users ) !== 0 ) {
			$post_users = $post_meta_users[0];
		}
	}
	if ( is_array( $post_users ) && in_array( $user_id, $post_users ) ) {
		return true;
	} else {
		return false;
	}
} // king_already_liked.

/**
 * Output the like button.
 *
 * @param string $post_id The post identifier.
 *
 * @return string ( description_of_the_return_value ).
 */
function king_get_simple_likes_button( $post_id ) {
	$output        = '';
	$nonce         = wp_create_nonce( 'simple-likes-nonce' ); // Security.
	$post_id_class = esc_attr( ' king-like-button-' . $post_id );
	$like_count    = get_post_meta( $post_id, '_post_like_count', true );
	$like_count    = ( isset( $like_count ) && is_numeric( $like_count ) ) ? $like_count : 0;
	$count         = king_get_like_count( $like_count );
	$icon_empty    = king_get_liked_icon();
	$icon_full     = king_get_liked_icon();
	// Loader.
	$loader = '<span id="sl-loader"></span>';
	// Liked/Unliked Variables.
	if ( king_already_liked( $post_id ) ) {
		$class = esc_attr( ' liked' );
		$title = esc_html__( 'Unlike', 'king' );
		$icon  = $icon_full;
	} else {
		$class = '';
		$title = esc_html__( 'Like', 'king' );
		$icon  = $icon_empty;
	}
	if ( is_user_logged_in() ) {
		$output = '<div class="king-like"><a href="' . admin_url( 'admin-ajax.php?action=king_process_simple_like' . '&post_id=' . $post_id . '&nonce=' . $nonce . '&disabled=true' ) . '" class="king-like-button' . $post_id_class . $class . '" data-nonce="' . $nonce . '" data-post-id="' . $post_id . '" title="' . $title . '" role="link">' . $icon . $count . '</a>' . $loader . '</div>';
	} else {
		$output = '<div class="king-like" data-toggle="dropdown" data-target=".king-alert-like" aria-expanded="false" role="link"><a>' . $icon . $count . '</a></div>';
	}
	return $output;
} // get_simple_likes_button.

/**
 * Utility retrieves post meta user likes.
 *
 * @param <type> $user_id The user identifier.
 * @param <type> $post_id The post identifier.
 *
 * @return array|string ( description_of_the_return_value ).
 */
function king_post_user_likes( $user_id, $post_id ) {
	$post_users      = '';
	$post_meta_users = get_post_meta( $post_id, '_user_liked' );
	if ( count( $post_meta_users ) !== 0 ) {
		$post_users = $post_meta_users[0];
	}
	if ( ! is_array( $post_users ) ) {
		$post_users = array();
	}
	if ( ! in_array( $user_id, $post_users ) ) {
		$post_users[ 'user-' . $user_id ] = $user_id;
	}
	return $post_users;
} // king_post_user_likes.

/**
 * Utility returns the button icon for "like" action
 * @since    0.5
 */
function king_get_liked_icon() {
	/* If already using Font Awesome with your theme, replace svg with: <i class="fa fa-heart"></i> */
	$icon = '<i class="fa fa-thumbs-up" aria-hidden="true"></i>';
	return $icon;
} // king_get_liked_icon.

/**
 * Utility function to format the button count,
 * appending "K" if one thousand or greater,
 * "M" if one million or greater,
 * and "B" if one billion or greater (unlikely).
 * $precision = how many decimal points to display (1.25K)
 * @since    0.5
 */
function king_sl_format_count( $number ) {
	$precision = 2;
	if ( $number >= 1000 && $number < 1000000 ) {
		$formatted = number_format( $number / 1000, $precision ) . 'K';
	} elseif ( $number >= 1000000 && $number < 1000000000 ) {
		$formatted = number_format( $number / 1000000, $precision ) . 'M';
	} elseif ( $number >= 1000000000 ) {
		$formatted = number_format( $number / 1000000000, $precision ) . 'B';
	} else {
		$formatted = $number; // Number is less than 1000.
	}
	$formatted = str_replace( '.00', '', $formatted );
	return $formatted;
} // king_sl_format_count.

/**
 * Utility retrieves count plus count options.
 *
 * @param integer $like_count  The like count.
 *
 * @return string ( description_of_the_return_value )
 */
function king_get_like_count( $like_count ) {
	$like_text = esc_html__( 'Like', 'king' );
	if ( is_numeric( $like_count ) && $like_count > 0 ) {
		$number = king_sl_format_count( $like_count );
	} else {
		$number = $like_text;
	}
	$count = '<span class="sl-count">' . $number . '</span>';
	return $count;
} // king_get_like_count.

/**
 * Display or Count how many times a post has been viewed.
 *
 * @param <type> $id The identifier.
 * @param <type> $action The action.
 */
function king_postviews( $id, $action ) {
	$kingcountmeta = '_post_views';
	$kingcount = get_post_meta( $id, $kingcountmeta, true );
	if ( '' === $kingcount ) {
		if ( 'count' === $action ) {
			$kingcount = 0;
		}
		delete_post_meta( $id, $kingcountmeta );
		add_post_meta( $id, $kingcountmeta, 0 );
		if ( 'display' === $action ) {
			echo '0';
		}
	} else {
		if ( 'count' === $action ) {
			$kingcount++;
			update_post_meta( $id, $kingcountmeta, $kingcount );
		} else {
			if ( $kingcount > 999 ) {
				$postwiews = round( $kingcount / 1000 ) . 'k';
			} else {
				$postwiews = $kingcount;
			}
			echo esc_attr( $postwiews . '' );
		}
	}
}

/**
 * Social Share buttons.
 */
function king_social_share() {
	?>
	<div class="share-buttons">
		<a class="social-icon share-wapp" style="display:inline-block !important" href="whatsapp://send?text=<?php echo rawurlencode( get_permalink( get_the_ID() ) ); ?>" data-action="share/whatsapp/share" title="<?php esc_html_e( 'Share on whatsapp', 'king' ); ?>"><i class="fab fa-whatsapp-square"></i>

		<a class="post-share share-fb" title="<?php esc_html_e( 'Share on Facebook', 'king' ); ?>" href="#" target="_blank" rel="nofollow" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u=<?php echo rawurlencode( get_permalink( get_the_ID() ) ); ?>','facebook-share-dialog','width=626,height=436');return false;"><i class="fab fa-facebook-square"></i></i></a>
		<a class="social-icon share-tw" href="#" title="<?php esc_html_e( 'Share on Twitter', 'king' ); ?>" rel="nofollow" target="_blank" onclick="window.open('http://twitter.com/share?text=<?php echo rawurlencode( html_entity_decode( get_the_title( get_the_ID() ), ENT_COMPAT, 'UTF-8' ) ); ?>&amp;url=<?php echo rawurlencode( get_permalink( get_the_ID() ) ); ?>','twitter-share-dialog','width=626,height=436');return false;"><i class="fab fa-twitter"></i></a>
		<?php if ( get_field( 'display_pinterest_share_button', 'options' ) ) : ?>      
			<a class="social-icon share-pin" href="#" title="<?php esc_html_e( 'Pin this', 'king' ); ?>" rel="nofollow" target="_blank" onclick="window.open('//pinterest.com/pin/create/button/?url=<?php echo rawurlencode( get_permalink( get_the_ID() ) ); ?>&amp;media=<?php echo rawurlencode( wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ) ); ?>&amp;description=<?php echo rawurlencode( html_entity_decode( get_the_title( get_the_ID() ), ENT_COMPAT, 'UTF-8' ) ); ?>','pin-share-dialog','width=626,height=436');return false;"><i class="fab fa-pinterest-square"></i></a>
		<?php endif; ?>
		<a class="social-icon share-em" href="mailto:?subject=<?php echo rawurlencode( html_entity_decode( get_the_title( get_the_ID() ), ENT_COMPAT, 'UTF-8' ) ); ?>&amp;body=<?php echo rawurlencode( get_permalink( get_the_ID() ) ); ?>" title="<?php esc_html_e( 'Email this','king' ); ?>"><i class="fas fa-envelope"></i></a>
		<?php if ( get_field( 'display_thumblr_share_button', 'option' ) || get_field( 'display_linkedin_share_button', 'options' ) || get_field( 'display_vk_share_button', 'options' ) ) : ?>
		<button class="king-share-dropdown" type="button" data-toggle="dropdown" data-target=".king-extra-shares" aria-expanded="false"><i class="fa fa-plus fa-lg" aria-hidden="true"></i></button>
		<div class="king-extra-shares">
			<?php if ( get_field( 'display_thumblr_share_button', 'option' ) ) : ?>
				<a class="social-icon share-tb" href="#" title="<?php esc_html_e( 'Share on Tumblr', 'king' ); ?>" rel="nofollow" target="_blank" onclick="window.open( 'http://www.tumblr.com/share/link?url=<?php echo rawurlencode( get_permalink( get_the_ID() ) ); ?>&amp;name=<?php echo rawurlencode( html_entity_decode( get_the_title( get_the_ID() ), ENT_COMPAT, 'UTF-8' ) ); ?>','tumblr-share-dialog','width=626,height=436' );return false;"><i class="fab fa-tumblr-square"></i></a>
			<?php endif; ?>    
			<?php if ( get_field( 'display_linkedin_share_button', 'options' ) ) : ?>    
				<a class="social-icon share-link" href="#" title="<?php esc_html_e( 'Share on LinkedIn', 'king' ); ?>" rel="nofollow" target="_blank" onclick="window.open( 'http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo rawurlencode( get_permalink( get_the_ID() ) ); ?>&amp;title=<?php echo rawurlencode( html_entity_decode( get_the_title( get_the_ID() ), ENT_COMPAT, 'UTF-8' ) ); ?>&amp;source=<?php echo rawurlencode( get_bloginfo( 'name' ) ); ?>','linkedin-share-dialog','width=626,height=436');return false;"><i class="fab fa-linkedin"></i></a>
			<?php endif; ?>      

			<?php if ( get_field( 'display_vk_share_button', 'options' ) ) : ?>    
				<a class="social-icon share-vk" href="#" title="<?php esc_html_e( 'Share on Vk', 'king' ); ?>" rel="nofollow" target="_blank" onclick="window.open('http://vkontakte.ru/share.php?url=<?php echo rawurlencode( get_permalink( get_the_ID() ) ); ?>','vk-share-dialog','width=626,height=436');return false;"><i class="fab fa-vk"></i></a> 
			<?php endif; ?>  
			<?php if ( get_field( 'display_wapp_share_button', 'options' ) ) : ?> 
				<a class="social-icon share-wapp" href="whatsapp://send?text=<?php echo rawurlencode( get_permalink( get_the_ID() ) ); ?>" data-action="share/whatsapp/share" title="<?php esc_html_e( 'Share on whatsapp', 'king' ); ?>"><i class="fab fa-whatsapp-square"></i></a>
			<?php endif; ?>


			</a>
		</div>    
	<?php endif; ?>
</div>
<?php }

/**
 * Calculate total share count
 *
 * @param $post_id.
 *
 * @return mixed
 */
function king_social_shares( $post_id ) {
	$fb = king_facebook_share( $post_id );
	$totalcounts = $fb;
	$sharecounter = get_post_meta( $post_id, 'share_counter', true );
	if ( $totalcounts !== $sharecounter  ) {
		update_post_meta( $post_id, 'share_counter', $totalcounts );
	}
}

/**
 * Get Facebook Access Token
 *
 */
function king_get_fb_access_token() {
	$apsc_id = get_field( 'facebook_share_app_id', 'option' );
	$apsc_secret = get_field( 'facebook_share_secret_key', 'option' );

	$api_url = 'https://graph.facebook.com/';
	$url = sprintf(
		'%soauth/access_token?client_id=%s&client_secret=%s&grant_type=client_credentials',
		$api_url,
		$apsc_id,
		$apsc_secret
	);
	$access = wp_remote_get( $url, array( 'timeout' => 60 ) );
	$access_body = wp_remote_retrieve_body( $access );
	$access_result = json_decode( $access_body );
	if ( is_wp_error( $access_result ) || ( ! isset( $access_result->access_token ) ) ) {
		return '';
	} else {
		update_field( 'field_5945ad8eec21b', $access_result->access_token, 'option' );
	}
}
add_action( 'acf/save_post', 'king_get_fb_access_token' );

/**
 * Facebook Share counter
 *
 * @param $post_id
 *
 * @return $str
 */
function king_facebook_share( $post_id ) {
	$url = get_permalink( $post_id );
	$access_token = get_field( 'facebook_access_token', 'option' );
	$api_url = 'https://graph.facebook.com/v3.2/?id=' . $url . '&fields=engagement&access_token=' . $access_token . '';

	$connection = wp_remote_get( $api_url, array( 'timeout' => 60 ) );

	if ( is_wp_error( $connection ) || ( isset( $connection['response']['code'] ) && 200 !== $connection['response']['code'] ) ) {
		$total = 0;
	} else {
		$_data = json_decode( $connection['body'], true );

		if ( isset( $_data['engagement']['share_count'] ) ) {
			$count = intval( $_data['engagement']['share_count'] );

			$total = $count;
		} else {
			$total = 0;
			$fb_graph_url = 'https://graph.facebook.com/?id=' . rawurlencode( $url ) . '&scrape=true';
			$result = wp_remote_post( $fb_graph_url );
		}
	}
	return $total;
}

/**
 * Custom comment box
 *
 */
if ( ! function_exists( 'king_comment' ) ) :
	function king_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' :
				// Display trackbacks differently than normal comments.
			?>
			<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
				<p><?php esc_html_e( 'Pingback:', 'king' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( esc_html__( '(Edit)', 'king' ), '<span class="edit-link">', '</span>' ); ?></p>
				<?php
				break;
				default :
				// Proceed with normal comments.
				global $post;
				?>
				<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
					<article id="comment-<?php comment_ID(); ?>" class="comment comment-box">

						<div class="comment-meta comment-author">
							<div class="avatar-wrap">
								<?php
								$id = $comment->user_id;

								if ( get_field( 'author_image','user_' . $id ) ) :
									$image = get_field( 'author_image','user_' . $id ); ?>
									<img class="user-comment-avatar" src="<?php  echo esc_url( $image['sizes']['thumbnail'] ); ?>"/>
									<?php else : ?>
										<span class="user-comment-noavatar" ><?php echo get_avatar( $comment, '60' ); ?></span>
									<?php endif; ?>
								</div>
								<span class="author-date">
									<?php if ( $comment->user_id ) : ?>
										<?php if ( get_option( 'permalink_structure' ) ) : ?>
											<?php $user_info = get_userdata( $id ); $cusernanme = $user_info->user_login; ?>
											<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_account'] . '/' . $cusernanme ) ?>" class="user-header-settings">
											<?php endif; ?>
											<?php
											printf( '<cite class="fn">%1$s</cite> ', get_comment_author_link() );
											?>
											<?php if ( get_option( 'permalink_structure' ) ) : ?>
											</a>
										<?php endif; ?>
										<?php else : ?>

											<?php
											printf( '<cite class="fn">%1$s</cite> ', get_comment_author_link() );
											?>
										<?php endif; ?>
										<?php
										printf( '<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
											esc_url( get_comment_link( $comment->comment_ID ) ),
											get_comment_time( 'c' ),
											/* translators */
											sprintf( esc_attr( '%1$s  ', 'king' ), get_comment_date(), get_comment_time() )
										); ?>
									</span>
								</div><!-- .comment-meta -->
								<div class="comment-content">
									<?php if ( '0' === $comment->comment_approved ) : ?>
										<p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'king' ); ?></p>
									<?php endif; ?>
									<?php comment_text(); ?>
								</div><!-- .comment-content -->
								<div class="comment-footer">
									<?php
									$field = get_field_object( 'comments_reactions', $comment );
									$value = $field['value'];
							// check.
									if ( $value ) : ?>
										<?php if ( get_field( 'enable_reactions', 'option' ) ) : ?>
											<div class="king-reactions-comment">
												<span class="king-reaction-<?php echo esc_attr( $value ); ?>" title="<?php echo esc_attr( $value ); ?>" ></span>
											</div>
										<?php endif; ?>
									<?php endif; ?>						
									<?php comment_reply_link( array_merge( $args, array( 'reply_text' => esc_html__( 'Reply', 'king' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
									<!-- .reply -->
									<?php if ( is_super_admin() ) : ?>
										<section class="comment-edit">
											<?php
											edit_comment_link( '<i class="fas fa-pen-square"></i>' ); ?>
										</section><!-- .comment-edit -->
									<?php endif; ?>
								</div><!-- .comment-footer -->
							</article><!-- #comment-## -->
							<?php
							break;
	endswitch; // end comment_type check.
}
endif;

/**
 * Processes follow/unfollow
 * @since    0.5
 */
add_action( 'wp_ajax_nopriv_king_process_simple_follow', 'king_process_simple_follow' );
add_action( 'wp_ajax_king_process_simple_follow', 'king_process_simple_follow' );
function king_process_simple_follow() {
	// Security.
	$nonce = isset( $_REQUEST['nonce'] ) ? sanitize_text_field( $_REQUEST['nonce'] ) : 0; // input var okay; sanitization okay.
	if ( ! wp_verify_nonce( $nonce, 'simple-follows-nonce' ) ) {
		exit( esc_attr( 'Not permitted', 'king' ) );
	}
	// Test if javascript is disabled
	$disabled = ( isset( $_REQUEST['disabled'] ) && $_REQUEST['disabled'] === true ) ? true : false; // input var okay; sanitization okay.
	// Base variables
	$post_id = ( isset( $_REQUEST['post_id'] ) && is_numeric( $_REQUEST['post_id'] ) ) ? $_REQUEST['post_id'] : ''; // input var okay; sanitization okay.
	$result = array();
	$post_users = null;
	$follow_count = 0;
	// Get plugin options.
	if ( '' !== $post_id ) {
		$count = get_user_meta( $post_id, 'wp__post_follow_count', true ); // follow count.
		$count = ( isset( $count ) && is_numeric( $count ) ) ? $count : 0;
		if ( ! king_already_followd( $post_id ) ) { // follow the post.
			if ( is_user_logged_in() ) { // user is logged in.
				$user_id = get_current_user_id();
				$post_users = king_post_user_follows( $user_id, $post_id );

				$user_follow_count = get_user_option( '_user_follow_count', $user_id );
				$user_follow_count = ( isset( $user_follow_count ) && is_numeric( $user_follow_count ) ) ? $user_follow_count : 0;
				update_user_meta( $user_id, 'wp__user_follow_count', ++$user_follow_count );
				if ( $post_users ) {
					update_user_meta( $post_id, 'wp__user_followd', $post_users );
					if ( get_field( 'enable_notification', 'options' ) )  {
						$knotify_comment = array('type' => 'follow', 'who' => $user_id );
						add_user_meta( $post_id, 'king_notify', $knotify_comment );
						$kncount = get_user_meta($post_id, 'king_notify_count', true);
						if (empty($kncount) || $kncount == '') {
							$count = 0;
						}
						$kntotal = (int) $kncount + 1;
						update_user_meta( $post_id, 'king_notify_count', $kntotal );
					}
				}
			}
			$follow_count = ++$count;
			$response['status'] = 'followd';
			$response['icon'] = king_get_unfollow_icon();
		} else { // Unfollow the post
			if ( is_user_logged_in() ) { // user is logged in
				$user_id = get_current_user_id();
				$post_users = king_post_user_follows( $user_id, $post_id );
				// Update User

				$user_follow_count = get_user_option( '_user_follow_count', $user_id );
				$user_follow_count = ( isset( $user_follow_count ) && is_numeric( $user_follow_count ) ) ? $user_follow_count : 0;
				if ( $user_follow_count > 0 ) {
					update_user_option( $user_id, '_user_follow_count', --$user_follow_count );
				}

				// Update Post
				if ( $post_users ) {
					$uid_key = array_search( $user_id, $post_users );
					unset( $post_users[ $uid_key ] );
					update_user_meta( $post_id, 'wp__user_followd', $post_users );

					if ( get_field( 'enable_notification', 'options' ) )  {
						$knotify_comment = array('type' => 'follow', 'who' => $user_id );
						delete_user_meta( $post_id, 'king_notify', $knotify_comment );
						$kncount = get_user_meta($post_id, 'king_notify_count', true);
						if (empty($kncount) || $kncount == '') {
							$kncount = 0;
						} else {
							$kntotal = (int) $kncount - 1;
						}
						update_user_meta( $post_id, 'king_notify_count', $kntotal );
					}

				}
			}
			$follow_count = ( $count > 0 ) ? --$count : 0; // Prevent negative number
			$response['status'] = 'unfollowd';
			$response['icon'] = king_get_followd_icon();
		}

		update_user_option( $post_id, '_post_follow_count', $follow_count );
		update_user_meta( $post_id, '_post_follow_modified', date( 'Y-m-d H:i:s' ) );

		$response['count'] = king_get_follow_count( $follow_count, $post_id );
		if ( true === $disabled ) {
			wp_redirect( get_permalink( $post_id ) );
			exit();

		} else {
			wp_send_json( $response );
		}
	}
}

/**
 * Utility to test if the post is already followd.
 *
 * @since 0.5
 */
function king_already_followd( $post_id ) {
	$post_users = null;
	$user_id = null;
	if ( is_user_logged_in() ) { // user is logged in.
		$user_id = get_current_user_id();
		$post_meta_users = get_user_meta( $post_id, 'wp__user_followd' );
		if ( count( $post_meta_users ) !== 0 ) {
			$post_users = $post_meta_users[0];
		}
	}
	if ( is_array( $post_users ) && in_array( $user_id, $post_users ) ) {
		return true;
	} else {
		return false;
	}
} // king_already_followd.

/**
 * Output the follow button.
 *
 * @since    0.5
 */
function king_get_simple_follows_button( $post_id ) {
	$output        = '';
	$nonce         = wp_create_nonce( 'simple-follows-nonce' );
	$post_id_class = esc_attr( ' follow-button-' . $post_id );
	$follow_count  = get_user_meta( $post_id, 'wp__post_follow_count', true );
	$follow_count  = ( isset( $follow_count ) && is_numeric( $follow_count ) ) ? $follow_count : 0;
	$count         = king_get_follow_count( $follow_count, $post_id );
	$icon_empty    = king_get_followd_icon();
	$icon_full     = king_get_unfollow_icon();
	$loader        = '<span id="follow-loader"></span>';
	if ( king_already_followd( $post_id ) ) {
		$class = esc_attr( ' followd' );
		$title = esc_html__( 'Unfollow', 'king' );
		$icon  = $icon_full;
	} else {
		$class = '';
		$title = esc_html__( 'follow', 'king' );
		$icon  = $icon_empty;
	}
	$output = '<span class="user-follow-button"><a href="' . admin_url( 'admin-ajax.php?action=king_process_simple_follow' . '&post_id=' . $post_id . '&nonce=' . $nonce . '&disabled=true' ) . '" class="follow-button' . $post_id_class . $class . '" data-nonce="' . $nonce . '" data-post-id="' . $post_id . '" title="' . $title . '">' . $icon . $count . '</a>' . $loader . '</span>';
	return $output;
} // king_get_simple_follows_button.


/**
 * Utility retrieves post meta user follows (user id array),
 * then adds new user id to retrieved array
 * @since    0.5
 */
function king_post_user_follows( $user_id, $post_id ) {
	$post_users = '';
	$post_meta_users = get_user_meta( $post_id, 'wp__user_followd' );
	if ( count( $post_meta_users ) !== 0 ) {
		$post_users = $post_meta_users[0];
	}
	if ( ! is_array( $post_users ) ) {
		$post_users = array();
	}
	if ( ! in_array( $user_id, $post_users ) ) {
		$post_users[ 'user-' . $user_id ] = $user_id;
	}
	return $post_users;
} // king_post_user_follows.

/**
 * Utility returns the button icon for "follow" action.
 *
 * @since    0.5
 */
function king_get_followd_icon() {
	/* If already using Font Awesome with your theme, replace svg with: <i class="fa fa-heart"></i> */
	$icon = '<i class="fa fa-user-plus" aria-hidden="true"></i>';
	return $icon;
} // king_get_followd_icon.

function king_get_unfollow_icon() {
	/* If already using Font Awesome with your theme, replace svg with: <i class="fa fa-heart"></i> */
	$icon = '<i class="fa fa-user-times" aria-hidden="true"></i>';
	return $icon;
} // king_get_unfollow_icon.

/**
 * Utility retrieves count plus count options,
 * returns appropriate format based on options
 *
 * @since    0.5
 */
function king_get_follow_count( $follow_count, $post_id ) {
	$follow_text = esc_html__( 'follow', 'king' );
	$unfollow_text = esc_html__( 'unfollow', 'king' );
	if ( king_already_followd( $post_id ) ) {
		$number = $unfollow_text;
	} else {
		$number = $follow_text;
	}
	$count = '<span class="sl-count">' . $number . '</span>';
	return $count;
} // king_get_follow_count.

/**
 * Disable acf css on front-end acf forms.
 */
function king_my_deregister_styles() {
	wp_deregister_style( 'acf' );
	wp_deregister_style( 'acf-field-group' );
	wp_deregister_style( 'wp-admin' );
	wp_deregister_style( 'acf-datepicker' );
}
add_action( 'wp_print_styles', 'king_my_deregister_styles', 100 );


/**
 * Meta tags
 */
function king_meta_tags() {
	$description = get_bloginfo( 'description' );
	if ( get_field( 'enable_meta_tags', 'options' ) ) {
		if ( is_single() ) {
			global $post;
			if ( have_posts() ) :
				while ( have_posts() ) :
					the_post();
					$description = wp_strip_all_tags( substr( get_the_excerpt(), 0, 100 ) );
				endwhile;
				wp_reset_postdata();
			endif;
			if ( get_field( 'facebook_share_description', 'options' ) ) {
				$excerpt = get_field( 'facebook_share_description', 'options' );
			} else {
				$excerpt = $description;
			}
			?>
		<meta name="description" content="<?php echo esc_attr( $description ); ?>">
		<!-- facebook meta tags -->
		<meta property="og:url" content="<?php echo esc_url( get_permalink( $post->ID ) ); ?>"/>
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?php echo get_the_title( $post->ID ); ?>"/>
		<meta property="og:description" content="<?php echo esc_attr( $excerpt ); ?>" />
		<meta property="og:image" content="<?php echo esc_url( wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) ) ); ?>"/>
		<meta property="og:site_name" content="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"/>
			<?php
			if ( get_field( 'twitter_share_description', 'options' ) ) {
				$twiexcerpt = get_field( 'twitter_share_description', 'options' );
			} else {
				$twiexcerpt = $description;
			}
			?>
		<!-- twitter meta tags -->
		<meta name="twitter:card" content="summary_large_image">
		<meta name="twitter:title" content="<?php echo get_the_title( $post->ID ); ?>">
		<meta name="twitter:description" content="<?php echo esc_attr( $twiexcerpt ); ?>">
		<meta name="twitter:image" content="<?php echo esc_url( wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) ) ); ?>">
	<?php	} else { ?>
		<meta name="description" content="<?php echo esc_attr( strip_tags( $description ) ); ?>">
		<?php
	}
}
}
if ( class_exists( 'Acf' ) ) :
	if ( get_field( 'enable_reactions', 'option' ) ) :
		/**
		 * Save Reactions in postmeta.
		 *
		 * @param  [type] $value   [description]
		 * @param  [type] $post_id [description]
		 * @param  [type] $field   [description]
		 * @return [type]          [description]
		 */
		function king_acf_update_value( $value, $post_id, $field ) {
			if ( ! empty( $value ) ) {
				$my_id           = trim( str_replace( 'comment_', '', $post_id ) );
				$comment_id_7    = get_comment( $my_id );
				$comment_post_id = $comment_id_7->comment_post_ID ;
				$total           = get_post_meta( $comment_post_id, 'king_reaction_' . $value . '', true ) ? get_post_meta( $comment_post_id, 'king_reaction_' . $value . '', true ) : 0;
				$total           = (int) $total + 1;
				update_post_meta( $comment_post_id, 'king_reaction_' . $value . '', $total );
			}
		return $value;
		}
		add_filter( 'acf/update_value/key=field_5c9bee01a519a', 'king_acf_update_value', 10, 3 );

		/**
		 * Reaction Options
		 *
		 * @param  field $field field.
		 * @return [type]        [description]
		 */
		function king_comments_reactions( $field ) {
			if ( get_field( 'reactions_title_in_comment_form', 'option' ) ) {
				$field['label'] = get_field( 'reactions_title_in_comment_form', 'option' );
			}
			if ( get_field( '1st_reaction_text', 'option' ) ) {
				$field['choices']['like'] = get_field( '1st_reaction_text', 'option' );
			}
			if ( get_field( '2nd_reaction_text', 'option' ) ) {
				$field['choices']['love'] = get_field( '2nd_reaction_text', 'option' );
			}
			if ( get_field( '3rd_reaction_text', 'option' ) ) {
				$field['choices']['haha'] = get_field( '3rd_reaction_text', 'option' );
			}
			if ( get_field( '4th_reaction_text', 'option' ) ) {
				$field['choices']['wow'] = get_field( '4th_reaction_text', 'option' );
			}
			if ( get_field( '5th_reaction_text', 'option' ) ) {
				$field['choices']['sad'] = get_field( '5th_reaction_text', 'option' );
			}
			if ( get_field( '6th_reaction_text', 'option' ) ) {
				$field['choices']['angry'] = get_field( '6th_reaction_text', 'option' );
			}
			return $field;
		}
		add_filter( 'acf/prepare_field/key=field_5c9bee01a519a', 'king_comments_reactions' );

	else :
		/**
		 * Hide comment reactions.
		 *
		 * @param <type> $field The field.
		 *
		 * @return <type>  ( description_of_the_return_value )
		 */
		function king_hide_field( $field ) {
			return null;
		}
		add_filter( 'acf/load_field/name=comments_reactions', 'king_hide_field' );

	endif;

	if ( get_field( 'enable_reactions', 'option' ) && get_field( 'display_reactions_block', 'option' ) ) :
		/**
		 * Print count vote reactions
		 *
		 * @param int $post_id post id.
		 */
		function king_reactions( $post_id = false ) {
			if ( ! $post_id ) {
				$post_id = get_the_ID();
			}
			$king_reactions = array( 'like', 'love', 'haha', 'wow', 'sad', 'angry' );
			$output         = '';
			foreach ( $king_reactions as $king_reaction ) {
				$count_reaction = get_post_meta( $post_id, 'king_reaction_' . $king_reaction . '', true );

				if ( ! empty( $count_reaction ) ) {
					$output .= '<div class="king-reaction-item" title="' . esc_attr( $king_reaction ) . '"><span class="king-reaction-item-icon king-reaction-' . esc_attr( $king_reaction ) . '"></span><span class="king-reaction-count" >' . esc_attr( $count_reaction ) . '</span></div>';
				}
			}
			return $output;
		}
	endif;
	if ( get_field( 'enable_user_points', 'options' ) ) {
		/**
		 * King User points function.
		 *
		 * @since 0.5
		 */
		function king_user_points( $user_id ) {
			global $wpdb;
			$followers    = get_user_meta( $user_id, 'wp__post_follow_count', true );
			$followers    = is_numeric( $followers ) ? $followers : 0;
			$posts        = esc_attr( count_user_posts( $user_id ) );
			$comments     = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) AS total FROM $wpdb->comments WHERE comment_approved = 1 AND user_id = %s", $user_id ) );
			$followersx   = get_field( 'get_a_follower', 'options' );
			$postsx       = get_field( 'submitting_a_post', 'options' );
			$commentsx    = get_field( 'posting_a_comment', 'options' );
			$bonus_plus   = get_field( 'bonus_points', 'user_' . $user_id );
			$points_total = ( $followers * $followersx ) + ( $posts * $postsx ) + ( $comments * $commentsx ) + $bonus_plus;
			$user_point   = get_user_meta( $user_id, 'king_user_points', true );
			if ( $points_total !== $user_point ) {
				update_user_meta( $user_id, 'king_user_points', $points_total );
				$user_point = get_user_meta( $user_id, 'king_user_points', true );
			}
			return $user_point;
		} // king_user_points.
	}
	endif;

	if ( ! get_option( 'permalink_structure' ) ) :
		/**
		 * King admin notify.
		 */
		function king_admin_notifications() {
			$class   = 'notice notice-info is-dismissible theme-option-property-search-page-notification';
			$message = esc_html__( 'Required: Please go to "Settings > Permalink" and select permalink format instead of the Plain. "This step is to prevent any 404 errors !".', 'king' );
			printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
		}
		add_action( 'admin_notices', 'king_admin_notifications' );
	endif;
if ( king_plugin_active( 'ACF' ) ) :

	/**
	 * Nav menu acf fields.
	 *
	 * @param <type> $items  The items.
	 * @param <type> $args   The arguments.
	 *
	 * @return     <type>  ( description_of_the_return_value )
	 */
	function king_wp_nav_menu_objects( $items, $args ) {
		foreach ( $items as &$item ) {
			$badgemenu = get_field( 'add_badge_to_menu', $item );

			if ( $badgemenu ) {
				$badgetext    = get_field( 'menu_badge_text', $item );
				$badgeback    = get_field( 'menu_badge_background_color', $item );
				$item->title .= '<span class="menu-badge" style="background-color:' . $badgeback . '">' . $badgetext . '</span>';
			}
		}
		return $items;
	}
	add_filter( 'wp_nav_menu_objects', 'king_wp_nav_menu_objects', 10, 2 );

	/**
	 * File upload Limit
	 *
	 * @param  [type] $bytes [description].
	 * @return [type]        [description]
	 */
	function king_increase_upload( $bytes ) {
		if ( get_field( 'max_file_u_size', 'option' ) ) {
			$maxfilesize = get_field( 'max_file_u_size', 'option' );
		} else {
			$maxfilesize = '2';
		}
		return ( $maxfilesize * 1048576 ); // 32 megabytes
	}
	add_filter( 'upload_size_limit', 'king_increase_upload' );

	if ( get_field( 'enable_reactions_without_comments', 'option' ) ) :

		/**
		 * Reaction Box
		 *
		 * @return [type] [description]
		 */
		function king_reactions_box() {
			if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'king_reactions_box_nonce' ) || ! isset( $_REQUEST['nonce'] ) ) {
				exit( 'No naughty business please' );
			}
			$post_id       = $_REQUEST['post'];
			$action        = $_REQUEST['type'];
			$user_id       = get_current_user_id();
			$box_reactions = get_post_meta( $post_id, 'king_reaction_' . $action . '', true );
			$box_reactions = ( empty( $box_reactions ) ) ? 0 : $box_reactions;
			$reactions     = (int) $box_reactions + 1;

			update_post_meta( $post_id, 'king_reaction_'.$action.'', $reactions );
			$new_reactions = reactions_total( $post_id, $reactions );

			$king_user_reactions = get_user_meta( $user_id, 'king_user_reactions' );
			if ( count( $king_user_reactions ) !== 0 ) {
				$king_reactions = $king_user_reactions[0];
			}
			if ( ! is_array( $king_reactions ) ) {
				$king_reactions = array();
			}
			if ( ! array_key_exists( $post_id, $king_reactions ) ) {
				$king_reactions[ $post_id ] = $action;
			}
			update_user_meta( $user_id, 'king_user_reactions', $king_reactions );
			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
				echo json_encode(
					array(
						'reactions'     => $reactions,
						'new_reactions' => $new_reactions,
					)
				);
				die();
			} else {
				wp_redirect( get_permalink( $_REQUEST['post_id'] ) );
				exit();
			}
		}
		add_action( 'wp_ajax_nopriv_king_reactions_box', 'king_reactions_box' );
		add_action( 'wp_ajax_king_reactions_box', 'king_reactions_box' );

		/**
		 * Reaction Buttons
		 *
		 * @return [type] [description]
		 */
		function king_reactions_box_buttons() {
			$reaction_text = '';
			if ( is_single() ) {
				$user_id               = get_current_user_id();
				$post_id               = get_the_ID();
				$king_reaction_buttons = array( 'like', 'love', 'haha', 'wow', 'sad', 'angry' );
				$nonce                 = wp_create_nonce( 'king_reactions_box_nonce' );
				$disabled_class        = '';
				$not_logged            = '';
				$king_reactions_class  = get_user_meta( $user_id, 'king_user_reactions', true );
				if ( ! is_array( $king_reactions_class ) ) {
					$king_reactions_class = array();
				}
				if ( array_key_exists( $post_id, $king_reactions_class ) ) {
					$disabled_class = 'disabled';
				}
				if ( ! is_user_logged_in() ) {
					$not_logged = 'not_logged';
				}
				$king_reactions_voted = $king_reactions_class[ $post_id ];
				$reaction_text        = '<div class="single-boxes king-reactions-post-' . $post_id . ' ' . $disabled_class . '" data-nonce="' . $nonce . '" data-post="' . $post_id . '" data-voted="' . $disabled_class . '" data-logged="' . $not_logged . '"><div class="single-boxes-title"><h4>Reactions</h4></div>';

				foreach ( $king_reaction_buttons as $king_reaction_button ) {
					$box_reactions          = get_post_meta( $post_id, 'king_reaction_' . $king_reaction_button, true );
					$box_reactions          = ( empty( $box_reactions ) ) ? 0 : $box_reactions;
					$box_reactions_percent  = reactions_total( $post_id, $box_reactions );
					$box_reactions_percent2 = reactions_total( $post_id, $box_reactions, 1 );
					$voted_class            = '';
					if ( $king_reactions_voted == $king_reaction_button ) {
						$voted_class = 'voted';
					}

					$reaction_text .= '<div class="king-reaction-buttons ' . $voted_class . '">
					<div class="king-reactions-count king-reactions-count-' . esc_attr( $king_reaction_button ) . '">' . esc_attr( $box_reactions ) . '</div>
					<div class="king-reaction-bar">
					<div class="king-reactions-percent king-reaction-percent-' . esc_attr( $king_reaction_button ) . '" style="height: ' . esc_attr( $box_reactions_percent ) . '%"></div>
					</div>
					<div class="king-reactions-icon king-reaction-' . esc_attr( $king_reaction_button ) . '" data-new="' . $box_reactions_percent2 . '" data-action="' . esc_attr( $king_reaction_button ) . '">
					</div>
					</div>';

				}
				$reaction_text .= '<div id="king-reacted" class="king-reacted hide">' . esc_html__( 'Already reacted for this post.', 'king' ) . '</div>';
				$reaction_text .= '</div>';
			}
			return $reaction_text;
		}

		/**
		 * Reaction Totals.
		 *
		 * @param <type>  $post_id        The post identifier.
		 * @param integer $box_reactions  The box reactions.
		 * @param integer $total          The total.
		 *
		 * @return     integer  ( description_of_the_return_value )
		 */
		function reactions_total( $post_id, $box_reactions, $total=null ) {
			$king_reaction_buttons = array( 'like', 'love', 'haha', 'wow', 'sad', 'angry' );
			$box_reactions_percent = 0;
			foreach ( $king_reaction_buttons as $key => $value ) {
				$box_reactions_t      = get_post_meta( $post_id, 'king_reaction_'.$value, true );
				$box_reactions_t      = ( empty( $box_reactions_t ) ) ? 0 : $box_reactions_t;
				$box_reactions_total += $box_reactions_t;
			}
			if ( $box_reactions_total !== 0 ) {
				$box_reactions_percent = round( ( $box_reactions * 100 ) / ( $box_reactions_total + $total ) );
			}
			return $box_reactions_percent;
		}
	endif;
	if ( get_field( 'enable_leaderboard_badges', 'option' ) ) :
		/**
		 * Save Acf leaderboard Badges.
		 *
		 * @return [type] [description]
		 */
		function king_leaderboard_badge() {
			$count = count( get_field( 'leaderboard_badges', 'option' ) );
			$query = get_users(
				array(
					'orderby'  => 'meta_value_num',
					'meta_key' => 'king_user_points',
					'order'    => 'DESC',
					'number'   => $count,
				)
			);
			foreach ( $query as $user ) {
				$userids[] = $user->ID;
			}

			// check if the repeater field has rows of data.
			if ( have_rows( 'leaderboard_badges', 'option' ) ) :

				// loop through the rows of data.
				while ( have_rows( 'leaderboard_badges', 'option' ) ) : the_row();

					$badgetitle[] = trim( str_replace( ' ', '_', get_sub_field( 'leaderboard_badge_title' ) ) );

				endwhile;
				$q = get_users(
					array(
						'meta_key' => 'king_user_leaderboard',
					)
				);
				foreach ( $q as $qq ) {
					delete_user_meta( $qq->ID, 'king_user_leaderboard' );
				}
			endif;
			foreach ( $userids as $user_id => $value ) {
				update_user_meta( $value, 'king_user_leaderboard', $badgetitle[$user_id] );
			}
		}

	endif;
endif;

/**
 * Register Theme.
 *
 * @return     boolean  ( description_of_the_return_value )
 */
function king_theme_register() {
	$king_register = get_transient( 'king_theme_registered' );

	if ( $king_register ) {
		return true;
	}
	if ( ! king_plugin_active( 'envato_market' ) ) {
		return true;
	}
	$purchased_themes = envato_market()->api()->themes();
	$my_theme         = wp_get_theme();
	foreach ( $purchased_themes as $purchased_theme ) {
		if ( $my_theme->get( 'TextDomain' ) === strtolower( $purchased_theme['name'] ) ) {
			$king_register = true;
		}
	}

	if ( $king_register ) {
		$expired = 60 * 60 * 24 * 100000000;
		set_transient( 'king_theme_registered', true, $expired );
	}

	return $king_register;
}
if ( ! king_theme_register() || ! king_plugin_active( 'envato_market' ) ) :
	/**
	 * King Admin Notices.
	 */
	function king_admin_notifications2() {
		$class   = 'notice notice-info is-dismissible theme-option-property-search-page-notification';
		$message = esc_html__( 'Required: You have to register King Theme !".', 'king' );
		printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
	}
	add_action( 'admin_notices', 'king_admin_notifications2' );
endif;

if ( king_plugin_active( 'ACF' ) ) :
	if ( ( get_field( 'enable_tag_follow', 'options' ) || get_field( 'enable_category_follow', 'options' ) ) && is_user_logged_in() ) :
		/**
		 * Follow tags and Categories.
		 */
		function king_follow_tags() {
			if ( ! is_user_logged_in() ) {
				die( '-1' );
			}
			$postid     = $_POST['to_follow'];
			$this_user  = wp_get_current_user();
			$userid     = $this_user->ID;
			$post_likes = get_user_meta( $userid, 'king_follow_tags' );
			$post_users = $post_likes[0];
			if ( is_array( $post_users ) && in_array( $postid, $post_users ) ) {
				$post_users = followed_tags( $postid, $userid );
				if ( $post_users ) {
					$uid_key = array_search( $postid, $post_users );
					unset( $post_users[ $uid_key ] );
					update_user_meta( $userid, 'king_follow_tags', $post_users );
				}
			} else {
				$post_users = followed_tags( $postid, $userid );
				if ( $post_users ) {
					update_user_meta( $userid, 'king_follow_tags', $post_users );
				}
				echo 'added_like';
			}

			die();
		}
		add_action( 'wp_ajax_nopriv_king_follow_tags', 'king_follow_tags' );
		add_action( 'wp_ajax_king_follow_tags', 'king_follow_tags' );

		/**
		 * Check already followed tags and categories.
		 *
		 * @param <type> $user_id  The user identifier.
		 * @param <type> $post_id  The post identifier.
		 *
		 * @return     array|string  ( description_of_the_return_value )
		 */
		function followed_tags( $user_id, $post_id ) {
			$post_users      = '';
			$post_meta_users = get_user_meta( $post_id, 'king_follow_tags' );
			if ( count( $post_meta_users ) !== 0 ) {
				$post_users = $post_meta_users[0];
			}
			if ( ! is_array( $post_users ) ) {
				$post_users = array();
			}
			if ( ! in_array( $user_id, $post_users ) ) {
				$post_users[] = $user_id;
			}
			return $post_users;
		} // king_post_user_follows.
	endif;

	if ( get_field( 'enable_notification', 'options' ) ) :
		/**
		 * Add comment notification.
		 *
		 * @param <type> $comment_ID        The comment id.
		 * @param <type> $comment_approved  The comment approved.
		 * @param <type> $commentdata       The commentdata.
		 */
		function king_comment_notify( $comment_ID, $comment_approved, $commentdata ) {
			if ( 1 === $comment_approved ) {
				$comment_post_id = $commentdata['comment_post_ID'];
				$user_id         = get_post_field( 'post_author', $comment_post_id );
				$type            = 'comment';
				$commenterid     = $commentdata['user_ID'];

				if ( $commentdata['comment_parent'] != '0' ) {
					$comment         = get_comment( $commentdata['comment_parent'] );
					$user_id         = $comment->user_id;
					$comment_post_id = $commentdata['comment_parent'];
					$type            = 'reply';
				}

				if ( esc_attr( $commenterid ) != esc_attr( $user_id ) ) {
					$knotify_comment = array('type' => $type, 'postid' => $comment_post_id, 'who' => $commenterid );
					add_user_meta( $user_id, 'king_notify', $knotify_comment );
					$count = get_user_meta( $user_id, 'king_notify_count', true );
					if ( empty( $count ) || $count == '' ) {
						$count = 0;
					}
					$total = (int) $count + 1;
					update_user_meta( $user_id, 'king_notify_count', $total );
				}
			}

		}
		add_action( 'comment_post', 'king_comment_notify', 10, 3 );

		/**
		 * Add notificaiton for comment.
		 *
		 * @param <type> $comment_id      The comment identifier.
		 * @param string $comment_status  The comment status.
		 */
		function king_comment_approved_notify( $comment_id, $comment_status ) {
			if ( $comment_status === 'approve' ) {
				$comment         = get_comment( $comment_id );
				$comment_post_id = $comment->comment_post_ID;
				$commenterid     = $comment->user_id;
				$user_id         = get_post_field( 'post_author', $comment_post_id );
				$type            = 'comment';
				if ( $comment->comment_parent != '0' ) {
					$commentp        = get_comment( $comment->comment_parent );
					$user_id         = $commentp->user_id;
					$comment_post_id = $comment->comment_parent;
					$type            = 'reply';
				}

				if ( esc_attr( $commenterid ) != esc_attr( $user_id ) ) {
					$knotify_comment = array( 'type' => $type, 'postid' => $comment_post_id, 'who' => $commenterid );
					add_user_meta( $user_id, 'king_notify', $knotify_comment );
					$count = get_user_meta( $user_id, 'king_notify_count', true );
					if ( empty( $count ) || $count == '' ) {
						$count = 0;
					}
					$total = (int) $count + 1;
					update_user_meta( $user_id, 'king_notify_count', $total );
				}
			}
		}
		add_action( 'wp_set_comment_status', 'king_comment_approved_notify', 10, 2 );

		/**
		 * Show notification
		 */
		function king_show_notify() {
			$user_id     = get_current_user_id();
			$comments    = get_user_meta( $user_id, 'king_notify', false );
			$notify_text = '';

			if ( $comments ) {
				$notify_count = get_user_meta( $user_id, 'king_notify_count', true );
				if ( empty( $notify_count ) || $notify_count === '' ) {
					$notify_count = 0;
				}
				foreach ( array_reverse( $comments ) as $key => $comment ) {
					$user_info   = get_userdata( $comment['who'] );
					$user_url    = site_url() . '/' . $GLOBALS['king_account'] . '/' . $user_info->user_login;
					$user_avatar = '';
					$seen        = '';
					if ( get_field( 'author_image', 'user_' . $comment['who'] ) ) {
						$image       = get_field( 'author_image', 'user_' . $comment['who'] );
						$avatar      = $image['sizes']['thumbnail'];
						$user_avatar = '<img src="' . esc_url( $avatar ) . '" alt="profile" />';
					}
					if ( $key < (int) $notify_count ) {
						$seen = '<span class="notify-seen"></span>';
					}
					if ( 'comment' === $comment['type'] ) {
						$notify_text .= '<li>' . $seen . '<div class="king-notify-avatar"><span class="king-notify-avatar-img">' . $user_avatar . '</span><i class="fas fa-comment-alt fa-xs"></i></div><a href="' . esc_url( $user_url ) . '" >' . esc_attr( $user_info->user_login ) . ' </a>' . esc_html__( 'commented on', 'king' ) . ' <a href="' . get_permalink( $comment['postid'] ) . '" >' . esc_attr( get_the_title( $comment['postid'] ) ) . '</a></li>';
					} elseif ( 'reply' === $comment['type'] ) {
						$notify_text .= '<li>' . $seen . '<div class="king-notify-avatar"><span class="king-notify-avatar-img">' . $user_avatar . '</span><i class="fas fa-reply fa-xs"></i></div><a href="' . esc_url( $user_url ) . '" >' . esc_attr( $user_info->user_login ) . ' </a>' . esc_html__( 'replied your comment', 'king' ) . ' <a href="' . get_comment_link( $comment['postid'] ) . '" >' . esc_html__( 'See it', 'king' ) . '</a></li>';

					} elseif ( 'follow' === $comment['type'] ) {
						$notify_text .= '<li>' . $seen . '<div class="king-notify-avatar"><span class="king-notify-avatar-img">' . $user_avatar . '</span><i class="fas fa-shoe-prints fa-xs"></i></div><a href="' . esc_url( $user_url ) . '" >' . esc_attr( $user_info->user_login ) . ' </a>' . esc_html__( 'started to follow you', 'king' ) . '</li>';
					} elseif ( 'like' === $comment['type'] ) {
						$notify_text .= '<li>' . $seen . '<div class="king-notify-avatar"><span class="king-notify-avatar-img">' . $user_avatar . '</span><i class="fas fa-thumbs-up fa-xs"></i></div><a href="' . esc_url( $user_url ) . '" >' . esc_attr( $user_info->user_login ) . ' </a>' . esc_html__( 'liked your post', 'king' ) . ' <a href="' . get_permalink( $comment['postid'] ) . '" >' . esc_attr( get_the_title( $comment['postid'] ) ) . '</a></li>';
					}
				}
				$notify_text .= '<li class="king-clean-center"><input type="button" class="king-clean" value="&#xf1b8;" title="' . esc_html__( 'Clear All', 'king' ) . '" /></li>';
				delete_user_meta( $user_id, 'king_notify_count' );

			} else {
				$notify_text .= '<li class="king-clean-center"><i class="fas fa-fish"></i><br>' . esc_html__( 'Nothing new right now !', 'king' ) . '</li>';
			}
			echo $notify_text;
			die();
		}
		add_action( 'wp_ajax_nopriv_king_show_notify', 'king_show_notify' );
		add_action( 'wp_ajax_king_show_notify', 'king_show_notify' );

		/**
		 * Clean button for notification.
		 */
		function king_clean_notify() {
			$user_id = get_current_user_id();
			delete_user_meta( $user_id, 'king_notify' );
			$notify_text = '<li class="king-clean-center"><i class="fas fa-fish"></i><br>' . esc_html__( 'Nothing new right now !', 'king' ) . '</li>';
			echo $notify_text;
			die();
		}
		add_action( 'wp_ajax_nopriv_king_clean_notify', 'king_clean_notify' );
		add_action( 'wp_ajax_king_clean_notify', 'king_clean_notify' );
	endif;

	if ( get_field( 'enable_text_translation', 'options' ) ) :

		/**
		 * Translate some texts in theme.
		 *
		 * @param string $translated_text  The translated text.
		 *
		 * @return string ( description_of_the_return_value )
		 */
		function king_change_text( $translated_text ) {
			if ( 'News' === $translated_text && get_field( 'news_text', 'options' ) ) {
				$translated_text = get_field( 'news_text', 'options' );
			}
			if ( 'Submit News' === $translated_text && get_field( 'submit_news_text', 'options' ) ) {
				$translated_text = get_field( 'submit_news_text', 'options' );
			}
			if ( 'Video' === $translated_text && get_field( 'videos_text', 'options' ) ) {
				$translated_text = get_field( 'videos_text', 'options' );
			}
			if ( 'Submit Video' === $translated_text && get_field( 'submit_video_text', 'options' ) ) {
				$translated_text = get_field( 'submit_video_text', 'options' );
			}
			if ( 'Image' === $translated_text && get_field( 'image_text', 'options' ) ) {
				$translated_text = get_field( 'image_text', 'options' );
			}
			if ( 'Submit Image' === $translated_text && get_field( 'submit_image_text', 'options' ) ) {
				$translated_text = get_field( 'submit_image_text', 'options' );
			}
			if ( 'featured' === $translated_text && get_field( 'featured_text', 'options' ) ) {
				$translated_text = get_field( 'featured_text', 'options' );
			}
			if ( 'trending' === $translated_text && get_field( 'trending_text', 'options' ) ) {
				$translated_text = get_field( 'trending_text', 'options' );
			}
			if ( 'My Settings' === $translated_text && get_field( 'my_settings_text', 'options' ) ) {
				$translated_text = get_field( 'my_settings_text', 'options' );
			}
			if ( 'Inbox' === $translated_text && get_field( 'inbox_text', 'options' ) ) {
				$translated_text = get_field( 'inbox_text', 'options' );
			}
			if ( 'Private Messages' === $translated_text && get_field( 'private_messages_text', 'options' ) ) {
				$translated_text = get_field( 'private_messages_text', 'options' );
			}
			if ( 'My Dashboard' === $translated_text && get_field( 'my_dashboard_text', 'options' ) ) {
				$translated_text = get_field( 'my_dashboard_text', 'options' );
			}
			if ( 'Submit Post' === $translated_text && get_field( 'submit_post_text', 'options' ) ) {
				$translated_text = get_field( 'submit_post_text', 'options' );
			}
			if ( 'Save' === $translated_text && get_field( 'save_text', 'options' ) ) {
				$translated_text = get_field( 'save_text', 'options' );
			}
			if ( 'Add New' === $translated_text && get_field( 'add_new_text', 'options' ) ) {
				$translated_text = get_field( 'add_new_text', 'options' );
			}
			if ( 'Select Image' === $translated_text && get_field( 'select_image_text', 'options' ) ) {
				$translated_text = get_field( 'select_image_text', 'options' );
			}
			return $translated_text;
		}
		add_filter( 'gettext', 'king_change_text', 20 );
	endif;

	if ( get_field( 'enable_user_groups', 'options' ) ) :
		/**
		 * King User group get from theme options.
		 *
		 * @param string $field the field.
		 *
		 * @return string ( description_of_the_return_value )
		 */
		function king_load_user_group( $field ) {
			$field['choices'] = array();
			if ( have_rows( 'user_groups', 'option' ) ) {
				while ( have_rows( 'user_groups', 'option' ) ) {
					the_row();
					$value = get_sub_field( 'group_name' );
					$label = '<span class="group-icon" title="' . get_sub_field( 'group_name' ) . '" style="background-color: ' . get_sub_field( 'group_color' ) . ';">' . get_sub_field( 'group_icon' ) . '' . get_sub_field( 'group_name' ) . '</span>';

					$field['choices'][ $value ] = $label;
				}
			}
			return $field;
		}
		add_filter( 'acf/load_field/name=groups_create_posts', 'king_load_user_group' );
		add_filter( 'acf/load_field/name=groups_create_posts_without_approval', 'king_load_user_group' );
		add_filter( 'acf/load_field/name=groups_edit_their_own_posts', 'king_load_user_group' );
		add_filter( 'acf/load_field/name=groups_view_posts', 'king_load_user_group' );
		add_filter( 'acf/load_field/name=groups_write_comments', 'king_load_user_group' );

		/**
		 * User list for group users.
		 *
		 * @param <type> $field  The field.
		 *
		 * @return <type>  ( description_of_the_return_value )
		 */
		function king_users_group( $field ) {
			$field['choices'] = array();
			$users            = get_users();
			foreach ( $users as $user ) {
				$field['choices'][ $user->ID ] = $user->user_login;
			}
			return $field;
		}
		add_filter( 'acf/load_field/name=group_users', 'king_users_group' );

		/**
		 * Group permissions.
		 *
		 * @param string $optn The optn.
		 *
		 * @return boolean ( description_of_the_return_value )
		 */
		function king_groups_permissions( $optn ) {
			$usid   = get_current_user_id();
			$optns  = get_field( '' . $optn . '', 'options' );
			$groups = get_field( 'user_groups', 'options' );
			$rtrn   = array();
			if ( $groups && $optns ) :
				foreach ( $groups as $group ) :
					$usergroups = $group['group_users'];
					if ( $usergroups ) :
						foreach ( $usergroups as $usergroup ) :
							if ( $usergroup == $usid ) :
								$rtrn[] = $group['group_name'];
							endif;
						endforeach;
					endif;
				endforeach;
				if ( array_intersect( $optns, $rtrn ) ) :
					return true;
				else :
					return false;
				endif;
			else :
				return true;
			endif;
		}
	endif;
	if ( get_field( 'enable_live_search', 'options' ) ) :
		/**
		 * King Live Search Results.
		 */
		function king_live_search() {
			$keyword = sanitize_text_field( wp_unslash( $_POST['keyword'] ) );
			if ( get_field( 'enable_live_user_search', 'options' ) ) :
				$args          = array(
					'order'          => 'ASC',
					'search'         => '*' . $keyword . '*',
					'number'         => 3,
					'search_columns' => array(
						'user_login',
						'user_nicename',
						'display_name',
					),
				);
				$wp_user_query = new WP_User_Query( $args );
				$authors       = $wp_user_query->get_results();
				$output        = '<div class="king-search-results">';

				if ( ! empty( $authors ) ) :
					$output .= '<span class="lsearch-title" >' . esc_html__( 'Users', 'king' ) . '</span>';
					foreach ( $authors as $author ) :
						$author_info = get_userdata( $author->ID );

						$output .= '<a href="' . esc_url( site_url() . '/' . $GLOBALS['king_account'] . '/' . $author_info->user_login ) . '">';
						if ( get_field( 'author_image', 'user_' . $author->ID ) ) :
							$image   = get_field( 'author_image', 'user_' . $author->ID );
							$output .= '<img src="' . esc_url( $image['sizes']['thumbnail'] ) . '" alt="profile" />';
						else :
							$output .= '<span class="lsearch-noavatar"></span>';
						endif;
						$output .= $author_info->user_login;
						$output .= '</a>';
					endforeach;
				endif;
			endif;
			$the_query = new WP_Query(
				array(
					'posts_per_page' => 5,
					's'              => $keyword,
					'post_type'      => 'post',
					'post_status'    => 'publish',
				)
			);

			if ( $the_query->have_posts() && $keyword ) :
				$output .= '<span class="lsearch-title" >' . esc_html__( 'Posts', 'king' ) . '</span>';
				while ( $the_query->have_posts() ) :
					$the_query->the_post();
					$output .= '<a href="' . esc_url( get_permalink() ) . '">';
					if ( has_post_format( 'quote' ) ) :
						$output .= '<span class="lsearch-format-news" >' . esc_html__( 'News', 'king' ) . '</span>';
					elseif ( has_post_format( 'video' ) ) :
						$output .= '<span class="lsearch-format-video" >' . esc_html__( 'Video', 'king' ) . '</span>';
					elseif ( has_post_format( 'image' ) ) :
						$output .= '<span class="lsearch-format-image" >' . esc_html__( 'Image', 'king' ) . '</span>';
					endif;
					$output .= '' . get_the_title() . '';
					$output .= '</a>';
				endwhile;
				$output .= '<span class="lsearch-more" ><i class="fas fa-search"></i> <strong>' . esc_attr( $keyword ) . '</strong> - ' . esc_attr( $the_query->found_posts ) . '' . esc_html__( ' total results', 'king' ) . '</span>';
				wp_reset_postdata();
			else :
				$output .= esc_html__( 'No result found.', 'king' );
			endif;
			$output .= '</div>';
			echo $output;
			die();
		}
		add_action( 'wp_ajax_king_live_search', 'king_live_search' );
		add_action( 'wp_ajax_nopriv_king_live_search', 'king_live_search' );
	endif;

	if ( 'king-grid-10' === get_field( 'select_default_display_option', 'options' ) ) :
		if ( function_exists( 'acf_add_local_field_group' ) ) :
			acf_add_local_field_group( array(
				'key'   => 'group_5ea30e5d50d86',
				'title' => 'Post Size',
				'fields' => array(
					array(
						'key' => 'field_5ea30e84c5c54',
						'label' => 'Post Size',
						'name' => 'post_size',
						'type' => 'radio',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'choices' => array(
							'king-size-1x' => '<img src="../wp-content/themes/king/layouts/imgs/templates/post-size/size-1x.svg" height="150" width="150" />',
							'king-size-2x' => '<img src="../wp-content/themes/king/layouts/imgs/templates/post-size/size-2x.svg" height="150" width="150" />',
							'king-size-2y' => '<img src="../wp-content/themes/king/layouts/imgs/templates/post-size/size-2y.svg" height="150" width="150" />',
							'king-size-4x' => '<img src="../wp-content/themes/king/layouts/imgs/templates/post-size/size-4x.svg" height="150" width="150" />',
							'king-size-6x' => '<img src="../wp-content/themes/king/layouts/imgs/templates/post-size/size-6x.svg" height="150" width="150" />',
						),
						'allow_null' => 0,
						'other_choice' => 0,
						'default_value' => '',
						'layout' => 'horizontal',
						'return_format' => 'value',
						'save_other_choice' => 0,
					),
				),
				'location' => array(
					array(
						array(
							'param' => 'post_type',
							'operator' => '==',
							'value' => 'post',
						),
					),
				),
				'menu_order' => 3,
				'position' => 'normal',
				'style' => 'default',
				'label_placement' => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen' => '',
				'active' => true,
				'description' => '',
			));
		endif;
	endif;
endif;
