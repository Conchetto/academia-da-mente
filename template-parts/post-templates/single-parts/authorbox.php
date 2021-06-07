<?php
/**
 * Post Page Author Box
 *
 * @package King
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<?php if ( ! get_field( 'hide_post_author_box', 'option' ) && get_option( 'permalink_structure' ) ) : ?>
<div class="post-author">
	<?php
	$author    = get_the_author_meta( 'user_nicename' );
	$author_id = $post->post_author;
	if ( get_field( 'author_image', 'user_' . $author_id ) ) :
		$image = get_field( 'author_image', 'user_' . $author_id );
		?>
		<a class="post-author-avatar-a" href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_account'] . '/' . $author ); ?>" >
			<img class="post-author-avatar" src="<?php echo esc_url( $image['sizes']['thumbnail'] ); ?>" />
		</a>
	<?php endif; ?>
	<div class="post-author-top">
		<a class="post-author-name" href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_account'] . '/' . $author ); ?>">
			<?php echo esc_attr( $author ); ?>
			<?php if ( get_field( 'verified_account','user_' . $author_id ) ) : ?>
				<span class="verified_account" title="<?php echo esc_html_e( 'verified account', 'king' ); ?>">
					<i class="fa fa-check-circle fa-2x" aria-hidden="true"></i>
				</span>
			<?php endif; ?>

		</a>
		<?php if ( get_field( 'enable_user_points', 'options' ) ) : ?>
			<div class="king-points" title="<?php echo esc_html_e( 'Points', 'king' ); ?>"><i class="fa fa-star" aria-hidden="true"></i> <?php echo king_user_points( $author_id ); ?></div>
		<?php endif; ?>
	</div>
	<?php
	if ( get_field( 'enable_user_groups', 'options' ) ) :
		echo king_user_groups( $author_id );
	endif;
	?>
	<?php if ( ! get_field( 'hide_author_about_text', 'option' ) ) : ?>
		<?php echo get_the_author_meta( 'description', $author_id ); ?>
	<?php endif; ?>
	<?php
	if ( get_field( 'enable_user_badges', 'option' ) ) :
		$badges    = get_user_meta( $author_id, 'king_user_badges', true );
		$lb_badges = get_user_meta( $author_id, 'king_user_leaderboard', true );
		?>
		<div class="king-profile-box-badges" >
			<?php if ( $lb_badges ) : ?>
				<div class="king-profile-badge" title="<?php echo esc_attr( str_replace( '_', ' ', $lb_badges ) ); ?>"><span class="lb-<?php echo esc_attr( $lb_badges ); ?>" ></span></div>
			<?php endif; ?>
			<?php if ( $badges ) : ?>
				<?php foreach ( $badges as $badge ) : ?>
					<div class="king-profile-badge" title="<?php echo esc_attr( str_replace( '_', ' ', $badge ) ); ?>"><span class="<?php echo esc_attr( $badge ); ?>"></span></div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	<?php endif; ?>	
	<?php if ( ! get_field( 'hide_author_social_links', 'option' ) ) : ?>
		<div class="king-profile-social">
			<ul>
				<?php if ( get_field( 'profile_facebook', 'user_' . $author_id ) ) : ?>
					<li class="fb"><a href="<?php the_field( 'profile_facebook', 'user_' . $author_id ); ?>" target="_blank"><i class="fab fa-facebook"></i></a></li>
				<?php endif; ?>
				<?php if ( get_field( 'profile_instagram', 'user_' . $author_id ) ) : ?>
					<li class="g"><a href="<?php the_field( 'profile_instagram', 'user_' . $author_id ); ?>" target="_blank"><i class="fab fa-instagram"></i> </a></li>
				<?php endif; ?>
				<?php if ( get_field( 'profile_twitter', 'user_' . $author_id ) ) : ?>
					<li class="twi"><a href="<?php the_field( 'profile_twitter', 'user_' . $author_id ); ?>" target="_blank"><i class="fab fa-twitter"></i></a></li>
				<?php endif; ?>
				<?php if ( get_field( 'profile_google', 'user_' . $author_id ) ) : ?>
					<li class="g"><a href="<?php the_field( 'profile_google', 'user_' . $author_id ); ?>" target="_blank"><i class="fab fa-google-plus"></i> </a></li>
				<?php endif; ?>
				<?php if ( get_field( 'profile_linkedin', 'user_' . $author_id ) ) : ?>
					<li class="ln"><a href="<?php the_field( 'profile_linkedin', 'user_' . $author_id ); ?>" target="_blank"><i class="fab fa-linkedin"></i></a></li>
				<?php endif; ?>        
				<?php if ( get_field( 'profile_add_url', 'user_' . $author_id ) ) : ?>
					<li class="ln"><a href="<?php the_field( 'profile_add_url', 'user_' . $author_id ); ?>" target="_blank"><i class="fas fa-link"></i></a></li>
				<?php endif; ?> 				    
			</ul>
		</div>		
	<?php endif; ?>
</div><!-- .post-author -->
<?php endif; ?>