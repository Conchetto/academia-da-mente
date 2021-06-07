<?php
/**
 * Submit Image Page.
 *
 * @package King
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $king_submit_errors;


if ( isset( $_POST['king_post_upload_form_submitted'] ) and wp_verify_nonce( $_POST['king_post_upload_form_submitted'], 'king_post_upload_form' ) ) { // input var okay; sanitization.

	$current_user = wp_get_current_user();

		// Get clean input variables
	$title    = sanitize_text_field( $_POST['king_post_title'] ); // input var okay; sanitization.
	$tags     = sanitize_text_field( $_POST['king_post_tags'] ); // input var okay; sanitization.
	$content  = stripslashes( $_POST['king_post_content'] ); // input var okay; sanitization.
	$thumb    = sanitize_text_field( $_POST['featured_image'] ); // input var okay; sanitization.
	$category = isset( $_POST['king_post_category'] ) ? $_POST['king_post_category'] : ''; // input var okay; sanitization.

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

	if ( trim( $thumb ) === '' ) {
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
		$post_id = wp_insert_post(array(
			'post_title'  => wp_strip_all_tags( $title ),
			'post_content'  => $content,
			'tags_input'  => $tags,
			'post_category' => $category,
			'post_status' => $poststatus,
		));

		$tag = 'post-format-image';
		$taxonomy = 'post_format';

		wp_set_post_terms( $post_id, $tag, $taxonomy );

		if ( isset( $_POST['king_nsfw'] ) ) {
			$king_nsfw = '1';
			update_field( 'nsfw_post', $king_nsfw, $post_id );
			update_post_meta( $post_id, '_nsfw_post', 'field_57d041d6ab8e2' );
		}

		do_action( 'acf/save_post' , $post_id );

		// Set selected image as the featured image.
		set_post_thumbnail( $post_id, $thumb );

		if ( $post_id ) {
			$permalink = get_permalink( $post_id );
			wp_redirect( $permalink );
			exit;
		}
	}
}
?>

<?php acf_form_head(); get_header(); ?>
<?php $GLOBALS['hide'] = 'hide'; ?>
<header class="page-top-header">
	<h1 class="page-title"><?php echo esc_html_e( 'Submit Image', 'king' ); ?></h1>
</header><!-- .page-header -->

<?php get_template_part( 'template-parts/king-header-nav' ); ?>

<?php if ( ! is_user_logged_in() ) : ?>
	<div class="king-alert"><i class="fa fa-bell fa-lg" aria-hidden="true"></i><?php esc_html_e( 'You do not have permission to create a post !', 'king' ) ?>
		<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_login'] ); ?>" class="king-alert-button"><?php esc_html_e( 'Log in ', 'king' ) ?></a>
		<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_register'] ); ?>"><?php esc_html_e( 'Register', 'king' ) ?></a>
	</div>

<?php elseif ( get_field( 'disable_image', 'options' ) !== false || get_field( 'disable_users_submit', 'options' ) !== false ) : ?>
	<div class="king-alert"><i class="fa fa-bell fa-lg" aria-hidden="true"></i>
		<?php esc_html_e( 'You do not have permission to view this page!', 'king' ) ?></div>

<?php elseif ( get_field( 'only_verified', 'options' ) === true && ! get_field( 'verified_account', 'user_' . get_current_user_id() ) && ! is_super_admin() ) : ?>  
		<div class="king-alert"><i class="fa fa-bell fa-lg" aria-hidden="true"></i><?php esc_html_e( 'You do not have permission to view this page!', 'king' ); ?></div>
<?php elseif ( get_field( 'enable_user_groups', 'options' ) && ! king_groups_permissions( 'groups_create_posts' ) && ! is_super_admin() ) : ?>
	<div class="king-alert"><i class="fa fa-bell fa-lg" aria-hidden="true"></i><?php esc_html_e( 'You do not have permission to view this page!', 'king' ); ?></div>
<?php else : ?>

			<!-- #primary BEGIN -->
			<div id="primary" class="page-content-area">
				<main id="main" class="page-site-main king-submit-image">
					<?php if ( get_field( 'custom_message_image', 'options' ) ) : ?>
						<div class="king-custom-message">
							<?php the_field( 'custom_message_image', 'options' ); ?>
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
						$include = array();
						$categories = get_terms('category', array(
							'include' => $include,
							'hide_empty' => false,
						));

						$categories_count = count( $categories );
						if ( $categories_count > 1 ) :
						?>
						<div class="king-form-group form-categories">
							<span class="form-label"><?php esc_html_e( 'Select Category', 'king' ); ?></span>
							<ul>
								<?php
								foreach ( $categories as $cat ) {
									if ( $cat->parent == 0 ) {
										if ( ! get_field( 'category_for_video', $cat ) && ! get_field( 'category_for_news', $cat ) || get_field( 'category_for_image', $cat ) ) :
											echo '<li class="form-categories-item"><input type="checkbox" id="king_post_cat-' . esc_attr( $cat->term_id ) . '" name="king_post_category[]" value="' . esc_attr( $cat->term_id ) . '" /><label for="king_post_cat-' . esc_attr( $cat->term_id ) . '">' . esc_attr( $cat->name ) . '</label></li>';
										endif;
										foreach ( $categories as $subcategory ) {
											if ( $subcategory->parent == $cat->term_id ) {
												if ( ! get_field( 'category_for_video', $subcategory ) && ! get_field( 'category_for_news', $subcategory ) || get_field( 'category_for_image', $subcategory ) ) :
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
				<div class="acf-field acf-field-image king-thumbnail" data-type="image">
						<div class="acf-input">
							<div class="acf-image-uploader acf-cf" data-preview_size="large" data-library="uploadedTo" data-mime_types="jpg, jpeg, png, gif," data-uploader="wp">
								<input name="featured_image" value="" type="hidden">	<div class="show-if-value image-wrap" style="max-width: 1024px">
								<img data-name="image" src="">
								<div class="acf-actions -hover">
									<a class="acf-icon -pencil dark" data-name="edit" href="#" title="<?php echo esc_html_e( 'Edit', 'king' ); ?>"></a>
									<a class="acf-icon -cancel dark" data-name="remove" href="#" title="<?php echo esc_html_e( 'Remove', 'king' ); ?>"></a>
								</div>
							</div>
							<div class="view hide-if-value">
								<a data-name="add" class="acf-button button" href="#"><?php esc_html_e( 'Select Image', 'king' ); ?></a>
							</div>
						</div>
					</div>
				</div>				
					<?php if ( isset( $king_submit_errors['image_empty'] ) ) : ?>
						<div class="king-error"><?php echo esc_attr( $king_submit_errors['image_empty'] ); ?></div>
					<?php endif; ?>                           
					<div class="acf-field acf-field-repeater acf-field-58bf2f79ed6d3 king-repeater" data-name="images_lists" data-type="repeater" data-key="field_58bf2f79ed6d3">
						<div class="acf-input">
							<input type="hidden" name="acf[field_58bf2f79ed6d3]"><div class="acf-repeater -empty -block" data-min="0" data-max="0">
							<table class="acf-table">
								<tbody>
									<tr class="acf-row acf-clone" data-id="acfcloneindex">
										<td class="acf-row-handle order" title="<?php echo esc_html_e( 'Drag to reorder', 'king' ); ?>">
											<span>1</span>
											<div class="acf-row-handle remove">
												<a class="acf-icon -plus small" href="#" data-event="add-row" title="<?php echo esc_html_e( 'Add row', 'king' ); ?>"></a>
												<a class="acf-icon -minus small" href="#" data-event="remove-row" title="<?php echo esc_html_e( 'Remove row', 'king' ); ?>"></a>
											</div>
										</td>
										<td class="acf-fields">				
											<div class="acf-field acf-field-image acf-field-58bf2f96ed6d4" data-name="images_list" data-type="image" data-key="field_58bf2f96ed6d4">
												<div class="acf-input">
													<div class="acf-image-uploader acf-cf" data-preview_size="large" data-library="uploadedTo" data-mime_types="jpg, jpeg, png, gif," data-uploader="wp">
														<input name="acf[field_58bf2f79ed6d3][acfcloneindex][field_58bf2f96ed6d4]" value="" type="hidden" disabled="">	<div class="show-if-value image-wrap" style="max-width: 1024px">
														<img data-name="image" src="">
														<div class="acf-actions -hover">
															<a class="acf-icon -pencil dark" data-name="edit" href="#" title="<?php echo esc_html_e( 'Edit', 'king' ); ?>"></a>
															<a class="acf-icon -cancel dark" data-name="remove" href="#" title="<?php echo esc_html_e( 'Remove', 'king' ); ?>"></a>
														</div>
													</div>
													<div class="view hide-if-value">
														<p><a data-name="add" class="acf-button button" href="#"><?php esc_html_e( 'Select Image', 'king' ); ?></a></p>
													</div>
												</div>
											</div>
										</div>
									</td>								
								</tr>
							</tbody>
						</table>

						<ul class="acf-actions acf-hl">
							<li>
								<a class="acf-button button button-primary" href="#" data-event="add-row"><i class="fa fa-plus" aria-hidden="true"></i><?php esc_html_e( 'Add New', 'king' ); ?></a>
							</li>
						</ul>

					</div>
				</div>
			</div>					
					<div class="king-form-group">
						<label for="king_post_content"><?php esc_html_e( 'Content', 'king' ); ?></label>
						<div class="tinymce" id="king_post_content"><?php echo esc_attr( isset( $_POST['king_post_content'] ) ? $_POST['king_post_content'] : '' ); ?></div>
					</div>
					<?php if ( isset( $king_submit_errors['content_empty'] ) ) : ?>
						<div class="king-error"><?php echo esc_attr( $king_submit_errors['content_empty'] ); ?></div>
					<?php endif; ?> 

					<div class="king-form-group">
						<label for="king_post_tags"><?php esc_html_e( 'Tags', 'king' ); ?></label>
						<input class="form-control bpinput" name="king_post_tags" id="king_post_tags" type="text" value="<?php echo isset( $_POST['king_post_tags'] ) ? $_POST['king_post_tags'] : '' ?>" />
					</div>
					<span class="help-block"><?php esc_html_e( 'Separate each tag by comma. (tag1, tag2, tag3)', 'king' ) ?></span>

					<?php if ( get_field( 'enable_nsfw_for_images', 'options' ) ) : ?>
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

				</form>
			</main><!-- #main -->
		</div><!-- .main-column -->

	<?php endif; ?>
<?php wp_enqueue_media(); ?>
<?php get_footer(); ?>
