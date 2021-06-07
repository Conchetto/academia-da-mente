<?php
/**
 * Submit video page.
 *
 * @package King
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $king_submit_errors;

if ( isset( $_POST['king_video_post_upload_form_submitted'] ) && wp_verify_nonce( $_POST['king_video_post_upload_form_submitted'], 'king_video_post_upload_form' ) ) {


	$video_url    = '';
	$video_upload = '';
	$video_embed  = '';
	$title        = sanitize_text_field( wp_unslash( $_POST['king_post_title'] ) );
	if ( isset( $_POST['video_url'] ) ) {
		$video_url = wp_unslash( $_POST['video_url'] ); // Input var okey.
	}
	if ( isset( $_POST['acf']['field_58f5335001eed'] ) ) {
		$video_upload = esc_url( wp_unslash( $_POST['acf']['field_58f5335001eed'] ) ); // Input var okey.
	}
	if ( isset( $_POST['acf']['field_59c9812458fe6'] ) ) {
		$video_embed = wp_unslash( $_POST['acf']['field_59c9812458fe6'] ); // Input var okey.
	}
	$tags = sanitize_text_field( sanitize_text_field( wp_unslash( $_POST['king_post_tags'] ) ) );
	$content = wp_unslash( $_POST['king_post_content'] );


	$category = isset( $_POST['king_post_category'] )?$_POST['king_post_category']:''; // input var okay; sanitization okay.

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

	if ( trim( $title ) === '' ) {
		$king_submit_errors['title_empty'] = esc_html__( 'Title is required.', 'king' );
	} elseif ( strlen( $title ) > $title_length ) {
		$king_submit_errors['title_empty'] = esc_html__( 'Title is too long.', 'king' );
	}

	// Content must be set.
	if ( trim( $content ) === '' ) {
		$king_submit_errors['content_empty'] = esc_html__( 'Content is required.', 'king' );
	} elseif ( strlen( $content ) > $content_length ) {
		$king_submit_errors['content_empty'] = esc_html__( 'Content is too long.', 'king' );
	}

	// VideoURL must be set.
	if ( trim( $video_url ) === '' && trim( $video_upload ) === '' && trim( $video_embed ) === '' ) {
		$king_submit_errors['videourl_empty'] = esc_html__( 'Media is required.', 'king' );
	}

	if ( empty( $king_submit_errors ) ) {

		switch ( $_POST['submit_type'] ) {
			case 'send':
			if ( is_super_admin() ) {
				$poststatus = 'publish';
			} elseif ( get_field( 'verified_posts', 'option' ) === true && get_field( 'verified_account', 'user_' . get_current_user_id() ) ) {
				$poststatus = 'publish';
			} elseif ( get_field( 'disable_post_moderation', 'option' ) ) {
				$poststatus = 'publish';
			} elseif ( get_field( 'enable_user_groups', 'options' ) && king_groups_permissions( 'groups_create_posts_without_approval' ) && get_field( 'groups_create_posts_without_approval', 'options' ) ) {
				$poststatus = 'publish';
			} else {
				$poststatus = 'pending';
			}
			break;
			case 'save':
			$poststatus = 'draft';
			break;
		}

		$post_id = wp_insert_post(
			array(
				'post_title'    => wp_strip_all_tags( $title ),
				'post_content'  => $content,
				'tags_input'    => $tags,
				'post_category' => $category,
				'post_status'   => $poststatus,
			)
		);
		set_post_format( $post_id, 'video' );

		update_field( 'video-url', $video_url, $post_id );
		update_post_meta( $post_id, '_video-url', 'field_587be2665e807' );

		set_post_format( $post_id, 'video' );

		if ( isset( $_POST['king_nsfw'] ) ) {
			$king_nsfw = '1';
			update_field( 'nsfw_post', $king_nsfw, $post_id );
			update_post_meta( $post_id, '_nsfw_post', 'field_57d041d6ab8e2' );
		}

		$type = king_source( $video_url );

		if ( 'vimeo.com' === $type || 'dailymotion.com' === $type || 'metacafe.com' === $type || 'vine.co' === $type || 'instagram.com' === $type || 'vid.me' === $type || 'tiktok.com' === $type ) {

			$image_url = king_get_thumb( $video_url );

		} elseif ( 'youtube.com' === $type || 'youtu.be' === $type ) {

			$image_url = king_youtube( $video_url );

		} elseif ( 'soundcloud.com' === $type ) {

			$image_url = king_soundcloud( $video_url );

		} elseif ( 'facebook.com' === $type ) {
			$image_url = king_facebook( $video_url );
		} else {
			$image_url = king_get_thumb( $video_url );
		}

		$attach_id = king_upload_user_file_video( $image_url , $post_id );

		// Set selected image as the featured image.
		set_post_thumbnail( $post_id, $attach_id );

		do_action( 'acf/save_post', $post_id );

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

	$url = 'https://api.soundcloud.com/resolve?url=' . $video_url . '&client_id=KqmJoxaVYyE4XT0XQqFUUQ';
	$track_json = wp_remote_get( $url );
	$track_json2 = wp_remote_retrieve_body( $track_json );
	$track = json_decode( $track_json2 );
	$video_thumbnail_url = str_replace( 'large', 'crop', $track->artwork_url );
	return $video_thumbnail_url;
}

/**
 * Get youtube video thumbnail.
 *
 * @param [type] $video_url video url.
 */
function king_youtube( $video_url ) {
	$querystring = wp_remote_get( $video_url );
	$querystring2 = wp_remote_retrieve_body( $querystring );
	preg_match( '/property="og:image" content="(.*?)"/', $querystring2, $output );
	return ($output[1]) ? $output[1] : false;
}

/**
 * Get facebook video thumbnail.
 *
 * @param [type] $video_url video url.
 * @return mixed
 */
function king_facebook( $video_url ) {
	$facebook_access_token = get_field( 'facebook_user_access_token', 'option' );
	$paths = explode( '/',$video_url );
	$num = count( $paths );
	for ( $i = $num -1; $i > 0; $i-- ) {
		if ( '' !== $paths[ $i ] ) {
			$video_id = $paths[ $i ];
			break;
		}
	}
	$url = 'https://graph.facebook.com/' . $video_id . '/thumbnails?access_token=' . $facebook_access_token;
	$track_json = wp_remote_get( $url );
	$track_json2 = wp_remote_retrieve_body( $track_json );
	$result = json_decode( $track_json2 );

// Fetch the 720px sized picture.
	return $large = $result->data[0]->uri;
}

/**
 * Upload thumbnail.
 *
 * @param [type] $image_url array.
 * @param [type] $post_id post id.
 * @param [type] $filename filename.
 * @param [type] $post_data post data.
 */
function king_upload_user_file_video( $image_url = array(), $post_id = null, $post_data = array() ) {
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';
	if ( ! $image_url || ! $post_id ) {
		return new WP_Error( 'missing', 'Need a valid URL and post ID...' );
	}
	preg_match( '/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG|image)/', $image_url, $matches );
	$url_filename = basename( $matches[0] );
	$url_type     = wp_check_filetype( $url_filename );
	if ( empty( $url_type['ext'] ) ) {
		$url_filename = $image_url . '.jpg';
	}

	$tmp = download_url( $image_url );
	if ( is_wp_error( $tmp ) ) {
		@unlink( $file_array['tmp_name'] );
		$file_array['tmp_name'] = '';
		return $tmp;
	}
	$file_array['tmp_name'] = $tmp;
	$file_array['name']     = $url_filename;
	if ( empty( $post_data['post_title'] ) ) {
		$post_data['post_title'] = basename( $url_filename, '.' . $url_type['ext'] );
	}

	if ( empty( $post_data['post_parent'] ) ) {
		$post_data['post_parent'] = $post_id;
	}
	$att_id = media_handle_sideload( $file_array, $post_id, null, $post_data );
	if ( is_wp_error( $att_id ) ) {
		@unlink( $file_array['tmp_name'] );
		return $att_id;
	}
	return $att_id;
}

?>
<?php
acf_form_head();
get_header();
?>
<?php $GLOBALS['hide'] = 'hide'; ?>

<header class="page-top-header">
	<h1 class="page-title"><?php echo esc_html_e( 'Submit Video', 'king' ); ?></h1>
</header><!-- .page-header -->

<?php get_template_part( 'template-parts/king-header-nav' ); ?>
<?php if ( ! is_user_logged_in() ) : ?>
	<div class="king-alert"><i class="fa fa-bell fa-lg" aria-hidden="true"></i><?php esc_html_e( 'You do not have permission to create a post !', 'king' ); ?>
	<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_login'] ); ?>" class="king-alert-button"><?php esc_html_e( 'Log in ', 'king' ); ?></a>
	<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_register'] ); ?>"><?php esc_html_e( 'Register', 'king' ); ?></a>
</div>
<?php elseif ( get_field( 'disable_video', 'options' ) !== false || get_field( 'disable_users_submit', 'options' ) !== false ) : ?>
<div class="king-alert"><i class="fa fa-bell fa-lg" aria-hidden="true"></i>
	<?php esc_html_e( 'You do not have permission to view this page!', 'king' ); ?></div>
	<?php elseif ( get_field( 'only_verified', 'options' ) === true && ! get_field( 'verified_account', 'user_' . get_current_user_id() ) && ! is_super_admin() ) : ?>  
	<div class="king-alert"><i class="fa fa-bell fa-lg" aria-hidden="true"></i><?php esc_html_e( 'You do not have permission to view this page!', 'king' ); ?></div>
	<?php elseif ( get_field( 'enable_user_groups', 'options' ) && ! king_groups_permissions( 'groups_create_posts' ) && ! is_super_admin() ) : ?>
	<div class="king-alert"><i class="fa fa-bell fa-lg" aria-hidden="true"></i><?php esc_html_e( 'You do not have permission to view this page!', 'king' ); ?></div>
	<?php else : ?>
		<!-- #primary BEGIN -->
		<div id="primary" class="page-content-area">
			<main id="main" class="page-site-main king-submit-video">
				<?php if ( get_field( 'custom_message_video', 'options' ) ) : ?>
					<div class="king-custom-message">
						<?php the_field( 'custom_message_video', 'options' ); ?>
					</div>
				<?php endif; ?>

				<form id="king_posts_form" action="" method="POST" enctype="multipart/form-data">
					<div class="king-form-group">
						<label for="king_post_title"><?php esc_html_e( 'Title', 'king' ); ?></label>
						<input class="form-control bpinput" name="king_post_title" id="king_post_title" type="text" value="<?php echo esc_attr( isset( $_POST['king_post_title'] ) ? $_POST['king_post_title'] : '' ); ?>" maxlength="<?php the_field( 'maximum_title_length', 'option' ); ?>" required />
					</div>
					<?php if ( isset( $king_submit_errors['title_empty'] ) ) : ?>
						<div class="king-error"><?php echo esc_attr( $king_submit_errors['title_empty'] ); ?></div>
					<?php endif; ?>
					<?php
					$include    = array();
					$categories = get_terms(
						'category',
						array(
							'include'    => $include,
							'hide_empty' => false,
						)
					);

					$categories_count = count( $categories );
					if ( $categories_count > 1 ) :
						?>
					<div class="king-form-group form-categories">
						<span class="form-label"><?php esc_html_e( 'Select Category', 'king' ); ?></span>
						<ul>
						<?php
						foreach ( $categories as $cat ) {
							if ( $cat->parent == 0 ) {
								if ( ! get_field( 'category_for_image', $cat ) && ! get_field( 'category_for_news', $cat ) || get_field( 'category_for_video', $cat ) ) :
									echo '<li class="form-categories-item"><input type="checkbox" id="king_post_cat-' . esc_attr( $cat->term_id ) . '" name="king_post_category[]" value="' . esc_attr( $cat->term_id ) . '" /><label for="king_post_cat-' . esc_attr( $cat->term_id ) . '">' . esc_attr( $cat->name ) . '</label></li>';
								endif;
								foreach ( $categories as $subcategory ) {
									if ( $subcategory->parent == $cat->term_id ) {
										if ( ! get_field( 'category_for_image', $subcategory ) && ! get_field( 'category_for_news', $subcategory ) || get_field( 'category_for_video', $subcategory ) ) :
												echo '<li class="form-categories-item"><input type="checkbox" id="king_post_cat-' . esc_attr( $subcategory->term_id ) . '" name="king_post_category[]" value="' . esc_attr( $subcategory->term_id ) . '" /><label class="king-post-subcat" for="king_post_cat-' . esc_attr( $subcategory->term_id ) . '">' . esc_attr( $subcategory->name ) . '</label></li>';
										endif;
									}
								}
							}
						}
						?>
						</ul>
					</div>
			<?php endif; ?>
			<div class="king-form-group">
				<label for="video-url"><?php esc_html_e( 'Media', 'king' ); ?></label>
				<div class="inside acf-fields">
					<?php $kinghide = '';
					if ( get_field( 'disable_video_and_mp3_upload', 'options' ) ) {
						$kinghide = ' hide';
					}
					?>
					<div class="inside acf-fields -top">
						<div class="acf-field acf-field-true-false acf-field-58f533f201eee<?php echo esc_attr( $kinghide ); ?>" data-name="video_tab" data-type="true_false" data-key="field_58f533f201eee">	
							<div class="acf-input">
								<div class="acf-true-false">
									<input name="acf[field_58f533f201eee]" value="0" type="hidden">
									<label>
										<input type="checkbox" id="acf-field_58f533f201eee" name="acf[field_58f533f201eee]" value="1" class="acf-switch-input" autocomplete="off">
										<div class="acf-switch"><span class="acf-switch-on" style="min-width: 40px;"><?php esc_html_e( 'UPLOAD', 'king' ); ?></span><span class="acf-switch-off" style="min-width: 40px;"><?php esc_html_e( 'URL', 'king' ); ?></span><div class="acf-switch-slider"></div></div>			</label>
									</div>
								</div>
							</div>

							<div class="acf-field acf-field-oembed acf-field-587be2665e807" data-name="video-url" data-type="oembed" data-key="field_587be2665e807" data-conditions="[[{&quot;field&quot;:&quot;field_58f533f201eee&quot;,&quot;operator&quot;:&quot;!=&quot;,&quot;value&quot;:&quot;1&quot;}]]">

								<div class="acf-input">
									<div class="acf-oembed">
										<input class="input-value" name="video_url" value="<?php echo esc_attr( isset( $_POST['video_url'] ) ? $_POST['video_url'] : '' ); ?>" type="hidden">
										<div class="title">
											<input class="input-search" value="<?php echo esc_attr( isset( $_POST['video_url'] ) ? $_POST['video_url'] : '' ); ?>" placeholder="Enter URL" autocomplete="off" type="text">		
											<div class="acf-actions -hover">
												<a data-name="clear-button" href="#" class="acf-icon -cancel grey"></a>
											</div>
										</div>
										<div class="canvas">
											<div class="canvas-media">
											</div>
											<i class="acf-icon -picture hide-if-value"></i>
										</div>

									</div>
								</div>
							</div>

						</div>
						<?php if ( ! get_field( 'disable_video_and_mp3_upload', 'options' ) ) : ?>
							<div class="acf-field acf-field-file acf-field-58f5335001eed -c0 acf-hidden" data-name="video_upload" data-type="file" data-key="field_58f5335001eed" data-conditions="[[{&quot;field&quot;:&quot;field_58f533f201eee&quot;,&quot;operator&quot;:&quot;==&quot;,&quot;value&quot;:&quot;1&quot;}]]" hidden="">
								<div class="acf-input">
									<div class="acf-file-uploader" data-library="uploadedTo" data-mime_types="mp4, flv, mp3" data-uploader="wp">
										<input name="acf[field_58f5335001eed]" value="<?php echo esc_attr( isset( $_POST['acf']['field_58f5335001eed'] ) ? $_POST['acf']['field_58f5335001eed'] : '' ); ?>" data-name="id" type="hidden" disabled="">
										<div class="show-if-value file-wrap">
											<div class="file-icon">
												<img data-name="icon" src="">
											</div>
											<div class="file-info">
												<a data-name="filename" href="" target="_blank"></a>
												<strong><?php esc_html_e( 'size:', 'king' ); ?></strong><span data-name="filesize"></span>
											</div>
											<div class="acf-actions -hover">
												<a class="acf-icon -pencil dark" data-name="edit" href="#" title="<?php echo esc_html_e( 'Edit', 'king' ); ?>"></a>
												<a class="acf-icon -cancel dark" data-name="remove" href="#" title="<?php echo esc_html_e( 'Remove', 'king' ); ?>"></a>
											</div>
										</div>
										<div class="hide-if-value">
											<a data-name="add" class="acf-button button" href="#"><?php esc_html_e( 'Upload Media', 'king' ); ?></a>		
										</div>
									</div>
								</div>
							</div>
							<div class="acf-field acf-field-image acf-field-58f5594a975cb acf-hidden" data-name="_thumbnail_id" data-type="image" data-key="field_58f5594a975cb" data-width="50" data-conditions="[[{&quot;field&quot;:&quot;field_58f533f201eee&quot;,&quot;operator&quot;:&quot;==&quot;,&quot;value&quot;:&quot;1&quot;}]]" hidden="">
								<div class="acf-input">
									<div class="acf-image-uploader" data-preview_size="thumbnail" data-library="uploadedTo" data-mime_types="jpg, png, gif, jpeg" data-uploader="wp">
										<input name="acf[field_58f5594a975cb]" value="" type="hidden" disabled="">
										<div class="show-if-value image-wrap">
											<img data-name="image" src="">
											<div class="acf-actions -hover">
												<a class="acf-icon -pencil dark" data-name="edit" href="#" title="<?php echo esc_html_e( 'Edit', 'king' ); ?>"></a>
												<a class="acf-icon -cancel dark" data-name="remove" href="#" title="<?php echo esc_html_e( 'Remove', 'king' ); ?>"></a>
											</div>
										</div>
										<i class="acf-icon -picture hide-if-value"></i>
										<div class="hide-if-value">
											<a data-name="add" class="acf-button button" href="#"><?php esc_html_e( 'Upload Thumbnail', 'king' ); ?></a>
										</div>
									</div>
								</div>
							</div>
							<?php if ( get_field( 'enable_embed_code_adding', 'options' ) ) : ?>
								<div class="acf-field acf-field-textarea acf-field-59c9812458fe6 acf-hidden" data-name="media_embed_code" data-type="textarea" data-key="field_59c9812458fe6" data-conditions="[[{&quot;field&quot;:&quot;field_58f533f201eee&quot;,&quot;operator&quot;:&quot;==&quot;,&quot;value&quot;:&quot;1&quot;}]]" hidden="">
									<label for="acf-field_59c9812458fe6"><?php echo esc_html_e( 'Embed Code', 'king' ); ?></label>
									<div class="acf-input">
										<textarea id="acf-field_59c9812458fe6" name="acf[field_59c9812458fe6]" rows="4" disabled=""></textarea>			</div>
									</div>
								<?php endif; ?>
							<?php endif; ?>
						</div>
					</div>
					<?php if ( isset( $king_submit_errors['videourl_empty'] ) ) : ?>
						<div class="king-error"><?php echo esc_attr( $king_submit_errors['videourl_empty'] ); ?></div>
					<?php endif; ?>

					<div class="king-form-group">
						<label for="king_post_content"><?php esc_html_e( 'Content', 'king' ); ?></label>
						<div class="tinymce" id="king_post_content"><?php echo esc_attr( isset( $_POST['king_post_content'] ) ? $_POST['king_post_content'] : '' ); ?></div>
					</div>
					<?php if ( isset( $king_submit_errors['content_empty'] ) ) : ?>
						<div class="king-error"><?php echo esc_attr( $king_submit_errors['content_empty'] ); ?></div>
					<?php endif; ?>

					<div class="king-form-group">
						<label for="king_post_tags"><?php esc_html_e( 'Tags', 'king' ); ?></label>
						<input class="form-control bpinput" name="king_post_tags" id="king_post_tags" type="text" value="<?php echo esc_attr( isset( $_POST['king_post_tags'] ) ? $_POST['king_post_tags'] : '' ); ?>" />
					</div>
					<span class="help-block"><?php esc_html_e( 'Separate each tag by comma. (tag1, tag2, tag3)', 'king' ) ?></span>

					<?php if ( get_field( 'enable_nsfw_for_videos', 'options' ) ) : ?>
						<div class="king-nsfw">
							<input id="king_nsfw" type="checkbox" name="king_nsfw" value="0">
							<label for="king_nsfw"><?php esc_html_e( 'This post is Not Safe for Work', 'king' ); ?></label>
						</div>
					<?php endif; ?>

					<button class="king-submit-button" data-loading-text="<?php esc_html_e( 'Loading...', 'king' ) ?>" type="submit" id="submit-loading" value="send" name="submit_type"><?php esc_html_e( 'Submit Post', 'king' ); ?></button>
					<?php if ( get_field( 'enable_save_posts', 'options' ) ) : ?>
						<button class="king-submit-button" data-loading-text="<?php esc_html_e( 'Loading...', 'king' ) ?>" name="submit_type" type="submit" id="submit-loading2" value="save"><?php esc_html_e( 'Save', 'king' ); ?></button>
					<?php endif; ?>
					<?php echo wp_nonce_field( 'king_video_post_upload_form', 'king_video_post_upload_form_submitted' ); ?>

				</form>
			</main><!-- #main -->
		</div><!-- .main-column -->

	<?php endif; ?>
	<?php wp_enqueue_media(); ?>
	<?php get_footer(); ?>
