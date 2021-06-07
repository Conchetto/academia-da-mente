<?php
/**
 * Post Page image galleries
 *
 * @package King
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$galleryc = '';
if ( ! get_field( 'enable_lightbox_gallery', 'option' ) ) {
	$galleryc = ' gallery-disabled';
}
$gallery = get_field( 'gallery_layout', 'option' );
if ( $gallery ) {
	$gclass = $gallery;
} else {
	$gclass = 'king-gallery-01';
}
if ( 'king-gallery-03' === $gclass || 'king-gallery-02' === $gclass ) :
	?>
	<div class="king-images <?php echo esc_attr( $gclass ); ?><?php echo esc_attr( $galleryc ); ?>">
		<?php if ( have_rows( 'images_lists' ) ) : ?>
			<a href="#first" class="images-item">
				<?php
				if ( has_post_thumbnail() ) :
					$fthumb = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'medium_large' );
					if ( 'king-gallery-03' === $gclass ) :
						?>
						<span class="images-item-span" data-toggle="modal" data-target="#gallery" style="background-image: url('<?php echo esc_url( $fthumb['0'] ); ?>');"></span>
					<?php else : ?>
						<img src="<?php echo esc_url( $fthumb['0'] ); ?>" data-toggle="modal" data-target="#gallery" />
					<?php endif; ?>
				<?php endif; ?>
			</a>
			<?php
			while ( have_rows( 'images_lists' ) ) :
				the_row();
				$image = get_sub_field( 'images_list' );
				$thumb = $image['sizes']['medium_large'];
				?>
				<a href="#<?php echo esc_attr( $image['ID'] ); ?>" class="images-item">				
					<?php
					if ( $image ) :
						if ( 'king-gallery-03' === $gclass ) :
							?>
							<span class="images-item-span" data-toggle="modal" data-target="#gallery" style="background-image: url('<?php echo esc_url( $thumb ); ?>');" ></span>
						<?php else : ?>
							<img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" data-toggle="modal" data-target="#gallery" />
						<?php endif; ?>
					<?php endif; ?>
				</a>
			<?php endwhile; ?>
		<?php else : ?>
			<div class="images-item image-alone">
				<?php
				if ( has_post_thumbnail() ) :
					echo get_the_post_thumbnail( get_the_ID(), 'full' );
				endif;
				?>
			</div>
		<?php endif; ?>	
	</div>
<?php else : ?>
	<div class="king-images">
	<?php if ( empty( $galleryc ) ) : ?>
		<span class="gallery-toggle" data-toggle="modal" data-target="#gallery" role="button"><i class="fas fa-camera"></i><?php esc_html_e( 'View Gallery', 'king' ); ?></span>
	<?php endif; ?>
		<div class="owl-carousel <?php echo esc_attr( $gclass ); ?><?php echo esc_attr( $galleryc ); ?>">
			<div class="images-item">
				<?php
				if ( has_post_thumbnail() ) :
					echo get_the_post_thumbnail( get_the_ID(), 'medium_large' );
				endif;
				?>
			</div>
			<?php if ( have_rows( 'images_lists' ) ) : ?>
				<?php
				while ( have_rows( 'images_lists' ) ) :
					the_row();
					$image = get_sub_field( 'images_list' );
					$thumb = $image['sizes']['medium_large'];
					?>
					<div class="images-item">				
						<?php if ( $image ) : ?>
							<img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" />
						<?php endif; ?>
					</div>
				<?php endwhile; ?>
			<?php endif; ?>	
		</div>
	</div>
<?php endif; ?>
