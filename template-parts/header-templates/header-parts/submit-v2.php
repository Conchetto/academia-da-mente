<?php
/**
 * The header part - user menu.
 *
 * This is the header template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package king
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php if ( get_field( 'disable_users_submit', 'options' ) !== true ) : ?>
	<?php if ( get_option( 'permalink_structure' ) ) : ?>
		<div class="king-modal-login modal" id="submitmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="king-modal-content">
				<button type="button" class="king-modal-close" data-dismiss="modal" aria-label="Close"><i class="icon fa fa-fw fa-times"></i></button>
				<div class="king-submit-v2">
					<?php if ( get_field( 'disable_news', 'options' ) !== true ) : ?>
						<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_snews'] ); ?>"><i class="fas fa-newspaper fa-lg nav-news"></i><?php echo esc_html_e( 'News', 'king' ); ?></a>
					<?php endif; ?>
					<?php if ( get_field( 'disable_video', 'options' ) !== true ) : ?>
						<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_svideo'] ); ?>"><i class="fas fa-video fa-lg nav-video"></i><?php echo esc_html_e( 'Video', 'king' ) ?></a>
					<?php endif; ?>
					<?php if ( get_field( 'disable_image', 'options' ) !== true ) : ?>
						<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_simage'] ); ?>"><i class="fas fa-image fa-lg nav-image"></i><?php echo esc_html_e( 'Image', 'king' ) ?></a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>
<?php endif; ?>
