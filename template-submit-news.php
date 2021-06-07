<?php
/**
 * Submit news page.
 *
 * @package King
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $king_submit_errors;


if ( isset( $_POST['king_post_upload_form_submitted'] ) and wp_verify_nonce( $_POST['king_post_upload_form_submitted'], 'king_post_upload_form' ) ) { // input var okay; sanitization.

	$title    = ( isset( $_POST['king_post_title'] ) && $_POST['king_post_title'] ) ? htmlspecialchars( $_POST['king_post_title'] ) : '';
	$tags     = sanitize_text_field( $_POST['king_post_tags'] );
	$content  = stripslashes( $_POST['king_post_content'] );
	$category = isset( $_POST['king_post_category'] )?$_POST['king_post_category']:'';

	$king_submit_errors = array();

	$no_images = true;
	foreach ( $_FILES['king_post_files']['name'] as $key => $value ) {
		if ( ! empty( $value ) ) {
			$no_images = false;
		}
	}

	$files = king_re_array_files( $_FILES['king_post_files'] );

	foreach ( $files as $file ) {
		if ( ! $no_images ) {
			$image_data = @getimagesize( $file['tmp_name'] );
			$allowed_file_types = array( 'image/jpg', 'image/jpeg', 'image/png', 'image/gif' );
			if ( ! in_array( $image_data['mime'], $allowed_file_types ) ) {
				$king_submit_errors['image_empty2'] = esc_html__( 'This image is not valid', 'king' );
			}
		}
	}

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

	// image must be set.
	if ( $no_images ) {
		$king_submit_errors['image_empty'] = esc_html__( 'Thumbnail is required.', 'king' );
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

		// Insert post.
		$post_id = wp_insert_post(
			array(
				'post_title'    => wp_strip_all_tags( $title ),
				'post_content'  => $content,
				'tags_input'    => $tags,
				'post_category' => $category,
				'post_status'   => $poststatus,
				'post_author'   => get_current_user_id(),
			)
		);

		$tagg = 'post-format-quote';
		$taxo = 'post_format';
		wp_set_post_terms( $post_id, $tagg, $taxo );

		foreach ( $files as $file ) {
			if ( is_array( $file ) ) {
				$attachment_id = king_upload_user_file( $file , $post_id );
			}
		}

		if ( isset( $_POST['king_nsfw'] ) ) {
			$king_nsfw = '1';
			update_field( 'nsfw_post', $king_nsfw, $post_id );
			update_post_meta( $post_id, '_nsfw_post', 'field_57d041d6ab8e2' );
		}
		do_action( 'acf/save_post', $post_id );

		// Set selected image as the featured image.
		set_post_thumbnail( $post_id, $attachment_id );

		if ( $post_id ) {
			$permalink = get_permalink( $post_id );
			wp_redirect( $permalink );
			exit;
		}
	}
}
/**
 * Upload User File.
 *
 * @param [type] $file video url.
 * @param [type] $post_id Post id.
 */
function king_upload_user_file( $file = array(), $post_id ) {

	require_once ABSPATH . 'wp-admin/includes/admin.php';

	$file_return = wp_handle_upload( $file, array( 'test_form' => false ) );

	if ( isset( $file_return['error'] ) || isset( $file_return['upload_error_handler'] ) ) {
		return false;
	} else {
		$filename      = $file_return['file'];
		$attachment    = array(
			'post_mime_type' => $file_return['type'],
			'post_content'   => '',
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'guid'           => $file_return['url'],
		);
		$attachment_id = wp_insert_attachment( $attachment, $file_return['url'], $post_id );
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		$attachment_data = wp_generate_attachment_metadata( $attachment_id, $filename );
		wp_update_attachment_metadata( $attachment_id, $attachment_data );
		if ( 0 < intval( $attachment_id ) ) {
			return $attachment_id;
		}
	}

	return false;
}

/**
 * Re array files.
 *
 * @param [type] $file_post Post id.
 */
function king_re_array_files( &$file_post ) {
	$file_ary   = array();
	$file_count = count( $file_post['name'] );
	$file_keys  = array_keys( $file_post );
	for ( $i = 0; $i < $file_count; $i++ ) {
		foreach ( $file_keys as $key ) {
			$file_ary[ $i ][ $key ] = $file_post[ $key ][ $i ];
		}
	}
	return $file_ary;
}
?>
<?php
acf_form_head();
get_header();
?>
<?php $GLOBALS['hide'] = 'hide'; ?>

<header class="page-top-header">
	<h1 class="page-title"><?php echo esc_html_e( 'Submit News', 'king' ); ?></h1>
</header><!-- .page-header -->

<?php get_template_part( 'template-parts/king-header-nav' ); ?>

<?php if ( ! is_user_logged_in() ) : ?>
	<div class="king-alert"><i class="fa fa-bell fa-lg" aria-hidden="true"></i><?php esc_html_e( 'You do not have permission to create a post !', 'king' ); ?>
	<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_login'] ); ?>" class="king-alert-button"><?php esc_html_e( 'Log in ', 'king' ); ?></a>
	<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_register'] ); ?>"><?php esc_html_e( 'Register', 'king' ); ?></a>
</div>
<?php elseif ( get_field( 'disable_news', 'options' ) !== false || get_field( 'disable_users_submit', 'options' ) !== false ) : ?>
<div class="king-alert"><i class="fa fa-bell fa-lg" aria-hidden="true"></i><?php esc_html_e( 'You do not have permission to view this page!', 'king' ); ?></div>

<?php elseif ( get_field( 'only_verified', 'options' ) === true && ! get_field( 'verified_account', 'user_' . get_current_user_id() ) && ! is_super_admin() ) : ?>  
<div class="king-alert"><i class="fa fa-bell fa-lg" aria-hidden="true"></i><?php esc_html_e( 'You do not have permission to view this page!', 'king' ); ?></div>
<?php elseif ( get_field( 'enable_user_groups', 'options' ) && ! king_groups_permissions( 'groups_create_posts' ) && ! is_super_admin() ) : ?>
	<div class="king-alert"><i class="fa fa-bell fa-lg" aria-hidden="true"></i><?php esc_html_e( 'You do not have permission to view this page!', 'king' ); ?></div>
<?php else : ?>
	<!-- #primary BEGIN -->
	<div id="primary" class="page-content-area">
		<main id="main" class="page-news-main">
			<form id="king_posts_form" action="" method="POST" enctype="multipart/form-data">
				<div class="submit-news-left">
					<?php if ( get_field( 'custom_message_news', 'options' ) ) : ?>
						<div class="king-message-submit">
							<?php the_field( 'custom_message_news', 'options' ); ?>
						</div>
					<?php endif; ?>
					<div class="king-form-group">
						<label for="king_post_title"><?php esc_html_e( 'Title', 'king' ); ?></label>
						<input class="form-control bpinput" name="king_post_title" id="king_post_title" type="text" value="<?php echo esc_attr( isset( $_POST['king_post_title'] ) ? $_POST['king_post_title'] : '' ); ?>" maxlength="<?php the_field( 'maximum_title_length', 'option' ); ?>" required/>
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
										if ( ! get_field( 'category_for_video', $cat ) && ! get_field( 'category_for_image', $cat ) || get_field( 'category_for_news', $cat ) ) :
											echo '<li class="form-categories-item"><input type="checkbox" id="king_post_cat-' . esc_attr( $cat->term_id ) . '" name="king_post_category[]" value="' . esc_attr( $cat->term_id ) . '" /><label for="king_post_cat-' . esc_attr( $cat->term_id ) . '">' . esc_attr( $cat->name ) . '</label></li>';
										endif;
										foreach ( $categories as $subcategory ) {
											if ( $subcategory->parent == $cat->term_id ) {
												if ( ! get_field( 'category_for_video', $subcategory ) && ! get_field( 'category_for_image', $subcategory ) || get_field( 'category_for_news', $subcategory ) ) :
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
						<label for="king_post_content"><?php esc_html_e( 'Content', 'king' ); ?></label>
						<div class="tinymce" id="king_post_content"><?php echo esc_attr( isset( $_POST['king_post_content'] ) ? $_POST['king_post_content'] : '' ); ?></div>
					</div>
					<?php if ( isset( $king_submit_errors['content_empty'] ) ) : ?>
						<div class="king-error"><?php echo esc_attr( $king_submit_errors['content_empty'] ); ?></div>
					<?php endif; ?>

					<div class="acf-field acf-field-repeater acf-field-58bddb0df74fe king-repeater" data-name="news_list_items" data-type="repeater" data-key="field_58bddb0df74fe">

						<div class="acf-input">
							<div class="acf-repeater -block -empty" data-min="0" data-max="80">
								<input name="acf[field_58bddb0df74fe]" value="" type="hidden">
								<table class="acf-table">
									<tbody class="ui-sortable" style="">
										<tr class="acf-row acf-clone" data-id="acfcloneindex" style="">

											<td class="acf-row-handle order ui-sortable-handle" title="<?php echo esc_html_e( 'Drag to reorder', 'king' ); ?>">
												<span>1</span>
												<div class="acf-row-handle remove">
													<a class="acf-icon -plus small acf-js-tooltip" href="#" data-event="add-row" title="<?php echo esc_html_e( 'Add row', 'king' ); ?>"></a>
													<a class="acf-icon -minus small acf-js-tooltip" href="#" data-event="remove-row" title="<?php echo esc_html_e( 'Remove row', 'king' ); ?>"></a>
												</div>			
											</td>

											<td class="acf-fields">				
												<div class="acf-field acf-field-text acf-field-58bddb31f74ff" data-name="news_list_title" data-type="text" data-key="field_58bddb31f74ff">
													<div class="acf-input">
														<div class="acf-input-wrap"><input type="text" id="acf-field_58bddb0df74fe-acfcloneindex-field_58bddb31f74ff" name="acf[field_58bddb0df74fe][acfcloneindex][field_58bddb31f74ff]" placeholder="<?php esc_html_e( 'list item title', 'king' ); ?>" disabled=""></div>			</div>
													</div>

													<div class="acf-field acf-field-true-false acf-field-58bddb5ef7500" data-name="news_image_or_video" data-type="true_false" data-key="field_58bddb5ef7500">
														<div class="acf-input">
															<div class="acf-true-false">
																<input name="acf[field_58bddb0df74fe][acfcloneindex][field_58bddb5ef7500]" value="0" type="hidden" disabled="">
																<label>
																	<input type="checkbox" id="acf-field_58bddb0df74fe-acfcloneindex-field_58bddb5ef7500" name="acf[field_58bddb0df74fe][acfcloneindex][field_58bddb5ef7500]" value="1" class="acf-switch-input" autocomplete="off" disabled="">
																	<div class="acf-switch"><span class="acf-switch-on"><?php esc_html_e( 'Video', 'king' ); ?></span><span class="acf-switch-off"><?php esc_html_e( 'Image', 'king' ); ?></span><div class="acf-switch-slider"></div></div>			</label>
																</div>
															</div>
														</div>

														<div class="acf-field acf-field-image acf-field-58bddb82f7501" data-name="news_list_image" data-type="image" data-key="field_58bddb82f7501" data-conditions="[[{&quot;field&quot;:&quot;field_58bddb5ef7500&quot;,&quot;operator&quot;:&quot;!=&quot;,&quot;value&quot;:&quot;1&quot;}]]">

															<div class="acf-input">
																<div class="acf-image-uploader" data-preview_size="large" data-library="uploadedTo" data-mime_types="jpg, jpeg, png, gif" data-uploader="wp">
																	<input name="acf[field_58bddb0df74fe][acfcloneindex][field_58bddb82f7501]" value="" type="hidden" disabled="">
																	<div class="show-if-value image-wrap" style="max-width: 1024px">
																		<img data-name="image" src="">
																		<div class="acf-actions -hover">
																			<a class="acf-icon -pencil dark" data-name="edit" href="#" title="<?php echo esc_html_e( 'Edit', 'king' ); ?>"></a><a class="acf-icon -cancel dark" data-name="remove" href="#" title="<?php echo esc_html_e( 'Remove', 'king' ); ?>"></a>
																		</div>
																	</div>
																	<div class="hide-if-value">
																		<p><a data-name="add" class="acf-button button" href="#"><?php esc_html_e( 'Add Image', 'king' ); ?></a></p>
																	</div>
																</div>
															</div>
														</div>

														<div class="acf-field acf-field-oembed acf-field-58bddbb9f7502" data-name="news_list_media" data-type="oembed" data-key="field_58bddbb9f7502" data-conditions="[[{&quot;field&quot;:&quot;field_58bddb5ef7500&quot;,&quot;operator&quot;:&quot;==&quot;,&quot;value&quot;:&quot;1&quot;}]]">

															<div class="acf-input">
																<div class="acf-oembed">
																	<input class="input-value" name="acf[field_58bddb0df74fe][acfcloneindex][field_58bddbb9f7502]" value="" type="hidden" placeholder="<?php esc_html_e( 'Media URL', 'king' ); ?>" disabled="">
																	<div class="title">
																		<input class="input-search" value="" placeholder="Enter URL" autocomplete="off" type="text">		<div class="acf-actions -hover">
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
														<div class="acf-field acf-field-textarea acf-field-58bddbd2f7503" data-name="news_list_content" data-type="textarea" data-key="field_58bddbd2f7503">
															<div class="acf-input">
																<textarea id="acf-field_58bddb0df74fe-acfcloneindex-field_58bddbd2f7503" name="acf[field_58bddb0df74fe][acfcloneindex][field_58bddbd2f7503]" rows="4" placeholder="<?php esc_html_e( 'List item content', 'king' ); ?>"  disabled=""></textarea>			</div>
															</div>
														</td>
													</tr>
												</tbody>
											</table>
											<div class="acf-actions">
												<li><a class="acf-button button button-primary" href="#" data-event="add-row"><i class="fa fa-plus" aria-hidden="true"></i><?php esc_html_e( 'New item', 'king' ) ?></a></li>
											</div>

										</div>
									</div>
								</div>
								<div class="king-form-group">
									<label for="king_post_tags"><?php esc_html_e( 'Tags', 'king' ); ?></label>
									<input class="form-control bpinput" name="king_post_tags" id="king_post_tags" type="text" value="<?php echo esc_attr( isset( $_POST['king_post_tags'] ) ? $_POST['king_post_tags'] : '' ); ?>"  autocomplete="off" />
								</div>
								<span class="help-block"><?php esc_html_e( 'Separate each tag by comma. (tag1, tag2, tag3)', 'king' ) ?></span>

							</div>
							<div class="submit-news-right">
								<div class="submit-news-right-fixed">
									<div class="king-form-group">
										<span class="inputprev-span">
											<img class="inputprev" /><i class="fa fa-cloud-upload fa-2x"></i>
											<label for="featured-image" class="featured-image-upload">
												<?php esc_html_e( 'Select Thumbnail', 'king' ) ?>
											</label>

											<input type="file" name="king_post_files[]" id="featured-image" required >
										</span>
									</div>
									<?php if ( isset( $king_submit_errors['image_empty'] ) ) : ?>
										<div class="king-error"><?php echo esc_attr( $king_submit_errors['image_empty'] ); ?></div>
									<?php endif; ?>
									<?php if ( isset( $king_submit_errors['image_empty2'] ) ) : ?>
										<div class="king-error"><?php echo esc_attr( $king_submit_errors['image_empty2'] ); ?></div>
									<?php endif; ?>

									<?php if ( get_field( 'enable_nsfw_for_news', 'options' ) ) : ?>
										<div class="king-nsfw">
											<input id="king_nsfw" type="checkbox" name="king_nsfw" value="0">
											<label for="king_nsfw"><?php esc_html_e( 'This post is Not Safe for Work', 'king' ) ?></label>
										</div>
									<?php endif; ?>

									<button class="king-submit-button" data-loading-text="<?php esc_html_e( 'Loading...', 'king' ) ?>" type="submit" value="send" name="submit_type" id="submit-loading"><?php esc_html_e( 'Submit Post', 'king' ); ?></button>
									<?php if ( get_field( 'enable_save_posts', 'options' ) ) : ?>
										<button class="king-submit-button" data-loading-text="<?php esc_html_e( 'Loading...', 'king' ) ?>" name="submit_type" type="submit" id="submit-loading2" value="save"><?php esc_html_e( 'Save', 'king' ); ?></button>
									<?php endif; ?>							
									<?php echo wp_nonce_field( 'king_post_upload_form', 'king_post_upload_form_submitted' ); ?>
								</div>
							</div>
						</form>
					</main><!-- #main -->
				</div><!-- #primary -->

			<?php endif; ?>
			<?php wp_enqueue_media(); ?>
			<?php get_footer(); ?>
