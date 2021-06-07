<?php
/**
 * Post Edit template
 *
 * @package king
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $king_submit_errors;
$postid = get_query_var( 'term' );
$format = get_post_format( $postid );
$status = get_post_status( $postid );

if ( isset( $_POST['king_edit_post_upload_form_submitted'] ) and wp_verify_nonce( $_POST['king_edit_post_upload_form_submitted'], 'king_edit_post_upload_form' ) ) { // input var okay; sanitization okay.

	// Get clean input variables
	$edit_title         = sanitize_text_field( $_POST['king_post_title'] );
	$tags               = sanitize_text_field( $_POST['king_post_tags'] );
	$edit_content       = stripslashes( $_POST['king_post_content'] );
	$category           = isset( $_POST['king_post_category'] ) ? $_POST['king_post_category'] : '';
	$king_submit_errors = array();
	if ( get_field( 'maximum_title_length', 'option' ) ) {
		$title_length = get_field( 'maximum_title_length', 'option' );
	} else {
		$title_length = '140';
	}

	if ( get_field( 'maximum_content_length', 'option' ) ) {
		$content_length = get_field( 'maximum_content_length', 'option' );
	} else {
		$content_length = '2000';
	}

	 // title must be set.
	if ( trim( $edit_title ) === '' ) {
		$king_submit_errors['title_empty'] = esc_html__( 'Title is required.', 'king' );
	} elseif ( strlen( $edit_title ) > $title_length ) {
		$king_submit_errors['title_empty'] = esc_html__( 'Title is too long.', 'king' );
	}

	// Content must be set.
	if ( trim( $edit_content ) === '' ) {
		$king_submit_errors['content_empty'] = esc_html__( 'Content is required.', 'king' );
	} elseif ( strlen( $edit_content ) > $content_length ) {
		$king_submit_errors['content_empty'] = esc_html__( 'Content is too long.', 'king' );
	}

	if ( $format === 'video' ) {
		$video_url    = '';
		$video_upload = '';
		$embed_url    = get_field( 'video-url', $postid, false );
		if ( isset( $_POST['acf']['field_587be2665e807'] ) ) {
			$video_url = esc_url( wp_unslash( $_POST['acf']['field_587be2665e807'] ) ); // Input var okey.
		}
		if ( isset( $_POST['acf']['field_58f5335001eed'] ) ) {
			$video_upload = esc_url( wp_unslash( $_POST['acf']['field_58f5335001eed'] ) ); // Input var okey.
		}
		// VideoURL must be set.
		if ( trim( $video_url ) === '' && trim( $video_upload ) === '' ) {
			$king_submit_errors['videourl_empty'] = esc_html__( 'Media is required.', 'king' );
		}
	}
	if ( empty( $king_submit_errors ) ) {

		if ( is_super_admin() ) {
			$poststatus = 'publish';
		} elseif ( get_field( 'moderate_posts_edit', 'option' ) || $status == 'pending' || $status == 'draft' ) {
			$poststatus = 'pending';
		} else {
			$poststatus = 'publish';
		}
		$post_information = array(
			'ID' => $postid,
			'post_title' =>  wp_strip_all_tags( $edit_title ),
			'post_content' => $edit_content,
			'tags_input'  => $tags,
			'post_category' => $category,
			'post_status' => $poststatus,
		);
		$post_id = wp_update_post( $post_information );
		if ( $format === 'video' ) {
			if ( $embed_url !== $video_url ) {
				$type = king_source( $video_url );

				if ( 'vimeo.com' === $type || 'dailymotion.com' === $type || 'metacafe.com' === $type || 'vine.co' === $type || 'instagram.com' === $type || 'vid.me' === $type ) {

					$image_url = king_get_thumb( $video_url );

				} elseif ( 'youtube.com' === $type || 'youtu.be' === $type ) {

					$image_url = king_youtube( $video_url );

				} elseif ( 'soundcloud.com' === $type ) {

					$image_url = king_soundcloud( $video_url );

				} elseif ( 'facebook.com' === $type ) {
					$image_url = king_facebook( $video_url );
				}
			
			$attach_id = king_upload_user_file_video( $image_url , $post_id );
	 		// Set selected image as the featured image.
			set_post_thumbnail( $post_id, $attach_id );
			}
		}
		do_action( 'acf/save_post' , $postid );
		if ( $post_id ) {
			$permalink = get_permalink( $post_id );
			wp_redirect( $permalink );
			exit;
		}
	}
}

/**
 * Get source url of video.
 *
 * @param [type] $video_url video url.
 */
function king_source( $video_url ) {
	$parsed = wp_parse_url( $video_url );
	return str_replace( 'www.','', strtolower( $parsed['host'] ) );
}

/**
 * Get video thumbnail.
 *
 * @param [type] $video_url video url.
 */
function king_get_thumb( $video_url ) {
	$res = wp_remote_get( $video_url );
	$res2 = wp_remote_retrieve_body( $res );
	preg_match( '/property="og:image" content="(.*?)"/', $res2, $output );
	return ($output[1]) ? $output[1] : false;
}

/**
 * Get soundcloud video thumbnail.
 *
 * @param [type] $video_url video url.
 */
function king_soundcloud( $video_url ) {

	$url                 = 'https://api.soundcloud.com/resolve?url=' . $video_url . '&client_id=KqmJoxaVYyE4XT0XQqFUUQ';
	$track_json          = wp_remote_get( $url );
	$track_json2         = wp_remote_retrieve_body( $track_json );
	$track               = json_decode( $track_json2 );
	$video_thumbnail_url = str_replace( 'large', 'crop', $track->artwork_url );
	return $video_thumbnail_url;
}

/**
 * Get youtube video thumbnail.
 *
 * @param [type] $video_url video url.
 */
function king_youtube( $video_url ) {
	$querystring  = wp_remote_get( $video_url );
	$querystring2 = wp_remote_retrieve_body( $querystring );
	preg_match( '/property="og:image" content="(.*?)"/', $querystring2, $output );
	return ( $output[1] ) ? $output[1] : false;
}

/**
 * Get facebook video thumbnail.
 *
 * @param [type] $video_url video url.
 * @return mixed
 */
function king_facebook( $video_url ) {
	$paths = explode( '/',$video_url );
	$num = count( $paths );
	for ( $i = $num -1; $i > 0; $i-- ) {
		if ( '' !== $paths[ $i ] ) {
			$video_id = $paths[ $i ];
			break;
		}
	}
	$url = 'http://graph.facebook.com/' . $video_id;
	$track_json = wp_remote_get( $url );
	$track_json2 = wp_remote_retrieve_body( $track_json );
	$result = json_decode( $track_json2 );

	// Fetch the 720px sized picture.
	return $large = $result->format[1]->picture;
}

/**
 * Upload thumbnail.
 *
 * @param [type] $image_url array.
 * @param [type] $post_id post id.
 * @param [type] $filename filename.
 * @param [type] $post_data post data.
 */
function king_upload_user_file_video( $image_url = array(), $post_id = null, $filename = null, $post_data = array() ) {
	if ( ! $image_url || ! $post_id ) {
		return new WP_Error( 'missing', 'Need a valid URL and post ID...' );
	}
	require_once( ABSPATH . 'wp-admin/includes/file.php' ); // Download file to temp location, returns full server path to temp file.
	$tmp = download_url( $image_url );
	// If error storing temporarily, unlink.
	if ( is_wp_error( $tmp ) ) {
		@unlink( $file_array['tmp_name'] ); // clean up.
		$file_array['tmp_name'] = '';
		return $tmp; // output wp_error.
	}

	preg_match( '/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG)/', $image_url, $matches ); // fix file filename for query strings
	$url_filename = basename( $matches[0] ); // extract filename from url for title
	$url_type = wp_check_filetype( $url_filename ); // determine file type.

	// override filename if given, reconstruct server path.
	if ( ! empty( $filename ) ) {
		$filename = sanitize_file_name( $filename );
		$tmppath = pathinfo( $tmp ); // extract path parts
		$new = $tmppath['dirname'] . '/' . $filename . '.' . $tmppath['extension']; // build new path
		rename( $tmp, $new ); // renames temp file on server
		$tmp = $new; // push new filename (in path) to be used in file array later.
	}

	// assemble file data (should be built like $_FILES since wp_handle_sideload() will be using)
	$file_array['tmp_name'] = $tmp; // full server path to temp file.

	if ( ! empty( $filename ) ) {
		$file_array['name'] = $filename . '.' . $url_type['ext']; // user given filename for title, add original URL extension.
	} else {
		$file_array['name'] = $url_filename; // just use original URL filename.
	}

	// set additional wp_posts columns.
	if ( empty( $post_data['post_title'] ) ) {
		$post_data['post_title'] = basename( $url_filename, '.' . $url_type['ext'] ); // just use the original filename (no extension).
	}

	// make sure gets tied to parent.
	if ( empty( $post_data['post_parent'] ) ) {
		$post_data['post_parent'] = $post_id;
	}

	// required libraries for media_handle_sideload.
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	require_once( ABSPATH . 'wp-admin/includes/media.php' );
	require_once( ABSPATH . 'wp-admin/includes/image.php' );

	// do the validation and storage stuff
	$att_id = media_handle_sideload( $file_array, $post_id, null, $post_data ); // $post_data can override the items saved to wp_posts table, like post_mime_type, guid, post_parent, post_title, post_content, post_status.

	// If error storing permanently, unlink.
	if ( is_wp_error( $att_id ) ) {
		@unlink( $file_array['tmp_name'] );   // clean up
		return $att_id; // output wp_error.
	}
	return $att_id;
}

acf_form_head();
get_header(); ?>
<?php
$title          = get_the_title( $postid );
$content        = apply_filters( 'the_content', get_post_field( 'post_content', $postid ) );
$tags           = strip_tags( get_the_term_list( $postid, 'post_tag', '', ', ', '' ) );
$post_thumb     = get_post_thumbnail_id( $postid );
$post_thumb_url = get_the_post_thumbnail_url( $postid, 'medium' );
$post_author    = get_post_field( 'post_author', $postid );
$current_user   = wp_get_current_user();
?>
<?php get_template_part( 'template-parts/king-header-nav' ); ?>
<?php if ( ! is_user_logged_in() || empty( $postid ) || ! get_field( 'enable_post_edit', 'options' ) || empty( $title ) ) : ?>
	<div class="king-alert"><i class="fa fa-bell fa-lg" aria-hidden="true"></i><?php esc_html_e( 'You do not have permission to edit this post !', 'king' ); ?></div>
<?php elseif ( esc_attr( $post_author ) !== esc_attr( $current_user->ID ) && ! is_super_admin() ) : ?>
	<div class="king-alert"><i class="fa fa-bell fa-lg" aria-hidden="true"></i><?php esc_html_e( 'You do not have permission to edit this post !', 'king' ); ?></div>
<?php elseif ( ( get_field( 'verified_edit_posts', 'options' ) && ! get_field( 'verified_account', 'user_' . $current_user->ID ) ) && ( get_field( 'enable_user_groups', 'options' ) && ! king_groups_permissions( 'groups_edit_their_own_posts' ) ) && ! is_super_admin() ) : ?>
	<div class="king-alert"><i class="fa fa-bell fa-lg" aria-hidden="true"></i><?php esc_html_e( 'You do not have permission to edit this post !', 'king' ); ?></div>
<?php else : ?>
<div id="primary" class="page-content-area">
	<main id="main" class="page-news-main king-post-edit">
		<form id="king_posts_form" action="" method="POST" enctype="multipart/form-data">
			<div class="submit-news-left">
				<div class="king-form-group">
					<label for="king_post_title"><?php esc_html_e( 'Title', 'king' ); ?></label>
					<input class="form-control bpinput" name="king_post_title" id="king_post_title" type="text" value="<?php echo esc_attr( $title ); ?>" maxlength="<?php the_field( 'maximum_title_length', 'option' ); ?>" required />
				</div>
				<?php if ( isset( $king_submit_errors['title_empty'] ) ) : ?>
					<div class="king-error"><?php echo esc_attr( $king_submit_errors['title_empty'] ); ?></div>
				<?php endif; ?>				
				<?php
				$include = array();
				$categories = get_terms('category', array(
					'include' => $include,
					'hide_empty' => false,
				));
				$categories_count = count( $categories );

				// get post categories.
				$post_cats     = get_the_category( $postid );
				$post_cats_arr = array();

				foreach ( $post_cats as $post_cat ) {
					$post_cats_arr[] = $post_cat->term_id;
				}
				if ( $categories_count > 1 ) :
					?>
				<div class="king-form-group form-categories">
					<span class="form-label"><?php esc_html_e( 'Select Category', 'king' ); ?></span>
					<ul>
						<?php
						foreach ( $categories as $cat ) {
							$checked = '';
							if ( in_array( $cat->term_id, $post_cats_arr ) ) {
								$checked = 'checked';
							}
							echo '<li class="form-categories-item"><input type="checkbox" id="king_post_cat-' . esc_attr( $cat->term_id ) . '" name="king_post_category[]" value="' . esc_attr( $cat->term_id ) . '" ' . esc_attr( $checked ) . ' /><label for="king_post_cat-' . esc_attr( $cat->term_id ) . '">' . esc_attr( $cat->name ) . '</label></li>';
						}
						?>
					</ul>
				</div>
			<?php endif; ?>			
			<div class="king-form-group">
				<label for="king_post_content"><?php esc_html_e( 'Content', 'king' ); ?></label>
				<div class="tinymce" id="king_post_content"><?php echo( wp_kses_post( html_entity_decode( $content ) ) );; ?></div>
			</div>
			<?php if ( isset( $king_submit_errors['content_empty'] ) ) : ?>
				<div class="king-error"><?php echo esc_attr( $king_submit_errors['content_empty'] ); ?></div>
			<?php endif; ?>			
			<?php if ( $format === 'quote' ) {
				acf_form(array(
					'post_id' => $postid,
					'form' => false,
					'return' => '',
					'uploader' => false,
					'field_groups' => array( 'group_58bddb03a9046' ),
				));
			} elseif ( $format === 'image' ) {
				acf_form(array(
					'post_id' => $postid,
					'form' => false,
					'return' => '',
					'uploader' => false,
					'field_groups' => array( 'group_58bf2f49e4513' ),
				));
			} elseif ( $format === 'video' ) {
				acf_form(array(
					'post_id' => $postid,
					'form' => false,
					'return' => '',
					'uploader' => false,
					'field_groups' => array( 'group_58bc8b2c4a3e8' ),
				));
			}
			?>	

			<?php if ( $format === 'video' && isset( $king_submit_errors['videourl_empty'] ) ) : ?>
				<div class="king-error"><?php echo esc_attr( $king_submit_errors['videourl_empty'] ); ?></div>
			<?php endif; ?> 			
				<div class="king-form-group">
					<label for="king_post_tags"><?php esc_html_e( 'Tags', 'king' ); ?></label>
					<input class="form-control bpinput" name="king_post_tags" id="king_post_tags" type="text" value="<?php echo esc_attr( $tags ); ?>" />
				</div>
				<span class="help-block"><?php esc_html_e( 'Separate each tag by comma. (tag1, tag2, tag3)', 'king' ) ?></span>
			</div>
			<div class="submit-news-right">
			<div class="submit-news-right-fixed">
			<?php if ( $format !== 'video' ) : ?>
				<div class="acf-field acf-field-image acf-field-58f5594a975cb" style="width: 100%; min-height: 210px;" data-name="_thumbnail_id" data-type="image" data-key="field_58f5594a975cb" data-width="50">
					<div class="acf-input">
						<div class="acf-image-uploader acf-cf has-value" data-preview_size="medium" data-library="uploadedTo" data-mime_types="jpg, png, gif, jpeg" data-uploader="wp">
							<input name="acf[field_58f5594a975cb]" value="<?php echo esc_attr( $post_thumb ); ?>" type="hidden">	<div class="view show-if-value acf-soh" style="width: 100%;">
							<img data-name="image" src="<?php echo esc_url( $post_thumb_url ); ?>">
							<ul class="acf-hl acf-soh-target">
								<li><a class="acf-icon -pencil dark" data-name="edit" href="#" title="Edit"></a></li>
								<li><a class="acf-icon -cancel dark" data-name="remove" href="#" title="Remove"></a></li>
							</ul>
						</div>
						<div class="view hide-if-value inputprev-span">
							<p style="margin:0;"><i class="fa fa-cloud-upload fa-2x"></i><label class="featured-image-upload"><a data-name="add" class="acf-button button" href="#">Add Image</a></label></p>
						</div>
						</div>
					</div>
				</div>
			<?php endif; ?>			
				<input type="submit" id="king-submitbutton" class="king-submit-button" name="king-editpost" value="<?php esc_html_e( 'Update Post', 'king' ); ?>">
				<?php if ( current_user_can( 'edit_post', $postid ) && get_field( 'allow_users_to_delete_their_posts', 'option' ) ) : ?>
					<a onclick="return confirm('Are you SURE you want to delete this post?')" href="<?php echo wp_nonce_url('' . home_url() . '/wp-admin/post.php?post=' . $postid . '&action=delete', 'delete-post_' . $postid ); ?>" class="king-submit-button king-delete-post"><?php esc_html_e( 'Delete Post', 'king' ); ?></a>
				<?php endif; ?>
			</div>	
			</div>
			<?php echo wp_nonce_field( 'king_edit_post_upload_form', 'king_edit_post_upload_form_submitted' ); ?>
		</form>	

	</main><!-- #main -->
</div><!-- #primary -->
<?php wp_enqueue_media(); ?>
<?php endif; ?>
<?php get_footer(); ?>
