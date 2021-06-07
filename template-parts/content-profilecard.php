<?php
/**
 * Users Profile box theme part.
 *
 * @package King
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php $user = get_userdata( $user_id ); ?>
<?php
if ( get_field( 'cover_image','user_' . $user_id ) ) {
	$coverimage = get_field( 'cover_image','user_' . $user_id );
	$cover = $coverimage['sizes']['medium'];
} elseif ( get_field( 'default_cover', 'options' ) ) {
	$coverimage = get_field( 'default_cover', 'options' );
	$cover = $coverimage['sizes'][ 'medium' ];
} else {
	$cover = '';
}
?>
<div id="user-<?php echo esc_attr( $user_id ); ?>" class="profileCard" >
	<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_account'] . '/' . $user->user_login ); ?>">
		<span class="profileCard-cover" id="card-nocover" <?php if ( ! empty( $cover ) ) : ?> style="background-image: url('<?php echo esc_url( $cover ); ?>');" <?php endif; ?> > </span>
	</a>
	<div class="ProfileCard-content">
		<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_account'] . '/' . $user->user_login ); ?>">
			<?php if ( get_field( 'author_image','user_' . $user_id ) ) : $image = get_field( 'author_image','user_' . $user_id ); ?>
				<img src="<?php  echo esc_url( $image['sizes']['thumbnail'] ); ?>" />
			<?php else : ?>
				<span class="card-noavatar"></span>  
			<?php endif; ?>
		</a>
		<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_account'] . '/' . $user->user_login ); ?>">
			<p><span class="card-name"><?php echo esc_attr( $user->display_name ); ?></span></p> 
		</a>
		<p>
			<b>
				<?php
				$following = get_user_meta( $user_id, 'wp__user_follow_count', true );
				if ( ! empty( $following ) ) {
					echo esc_attr( $following );
				} else {
					echo '0';
				}
				?>

				</b>
					<?php echo esc_html_e( 'Following','king' ); ?>
				<b>	
					<?php
					$followers = get_user_meta( $user_id, 'wp__post_follow_count', true );
					if ( ! empty( $followers ) ) {
						echo esc_attr( $followers );
					} else {
						echo '0';
					}

					?>
				</b> 
				<?php echo esc_html_e( 'Followers','king' ); ?>
			</p>  
		</div>
	</div><!-- #post-## -->
