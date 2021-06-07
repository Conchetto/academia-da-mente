<?php
/**
 * The singe post part - share.
 *
 * This is a content template part.
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
<?php if ( get_field( 'social_share_style', 'options' ) == 'share-2' ) : ?>
	<div class="king-share">
		<div class="king-post-nav">
			<span class="share-link" title="share" data-toggle="modal" data-target="#sharemodal" role="button"><i class="fas fa-share"></i></span>
			<?php
			$next_post = get_next_post();
			if ( ! empty( $next_post ) ) :
				?>
				<a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" title="<?php echo esc_attr( $next_post->post_title ); ?>" class="share-link" ><i class="fa fa-angle-left"></i></a>
			<?php endif; ?>
			<?php
			$prev_post = get_previous_post();
			if ( ! empty( $prev_post ) ) :
				?>
				<a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>" title="<?php echo esc_attr( $prev_post->post_title ); ?>" class="share-link" ><i class="fa fa-angle-right"></i></a>
			<?php endif; ?>
		</div>
	</div>
	<div class="king-modal-login modal" id="sharemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="king-modal-content">
			<button type="button" class="king-modal-close" data-dismiss="modal" aria-label="Close"><i class="icon fa fa-fw fa-times"></i></button>
			<div class="king-modal-share">
				<?php echo esc_attr( king_social_share() ); ?>
			</div>
		</div>
	</div>
<?php else : ?>
	<div class="share-top">
		<div class="king-social-share">
			<span class="share-counter">
				<i><?php echo esc_attr( get_post_meta( get_the_ID(), 'share_counter', true ) ); ?> </i>
				<?php echo esc_html_e( 'shares','king' ); ?>
			</span>
			<?php echo esc_attr( king_social_share() ); ?>
			<div class="post-nav">
				<?php
				$next_post = get_next_post();
				if ( ! empty( $next_post ) ) : ?>
					<a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" title="<?php echo esc_attr( $next_post->post_title ); ?>" class="prev-link" ><i class="fa fa-angle-left"></i></a>
				<?php endif; ?>
				<?php
				$prev_post = get_previous_post();
				if ( ! empty( $prev_post ) ) : ?>
					<a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>" title="<?php echo esc_attr( $prev_post->post_title ); ?>" class="prev-link" ><i class="fa fa-angle-right"></i></a>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php endif; ?>
