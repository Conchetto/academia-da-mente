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
		<div class="king-submit">
			<span class="king-submit-open"  data-toggle="dropdown" data-target=".king-submit" aria-expanded="false" role="button"><i class="fa fa-plus fa-lg" aria-hidden="true"></i></span>
			<ul class="king-submit-buttons">
				<?php if ( get_field( 'disable_news', 'options' ) !== true ) : ?>
					<li><a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_snews'] ); ?>"><?php echo esc_html_e( 'News', 'king' ); ?><i class="fas fa-newspaper"></i></a></li>
				<?php endif; ?>
				<?php if ( get_field( 'disable_video', 'options' ) !== true ) : ?>
					<li><a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_svideo'] ); ?>"><?php echo esc_html_e( 'Video', 'king' ); ?><i class="fas fa-video"></i></a></li>
				<?php endif; ?>
				<?php if ( get_field( 'disable_image', 'options' ) !== true ) : ?>
					<li><a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_simage'] ); ?>"><?php echo esc_html_e( 'Image', 'king' ); ?><i class="fas fa-image"></i></a></li>
				<?php endif; ?>
			</ul>
		</div><!-- .king-submit -->
	<?php endif; ?>
<?php endif; ?>