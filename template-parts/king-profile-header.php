<?php
/**
 * Profile Header Theme Part.
 *
 * @package King
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php $GLOBALS['hide'] = 'hide'; ?>
<?php get_template_part( 'template-parts/king-header-nav' ); ?>
<?php
$profile_id = get_query_var( 'profile_id' );
if ( $profile_id ) {
	$this_user = get_user_by( 'login', $profile_id );
} else {
	$this_user = wp_get_current_user();
}
$user_id = $this_user->ID;
if ( ! $user_id ) {
	wp_redirect( site_url() );
}
?>

<?php
if ( get_field( 'cover_image', 'user_' . $user_id ) ) {
	$coverimage = get_field( 'cover_image', 'user_' . $user_id );
	$cover      = $coverimage['url'];
} elseif ( get_field( 'default_cover', 'options' ) ) {
	$coverimage = get_field( 'default_cover', 'options' );
	$cover      = $coverimage['url'];
} else {
	$cover = '';
}
?>

<div class="king-profile-top" id="nocover" <?php if ( ! empty( $cover ) ) : ?> style="background-image: url('<?php echo esc_url( $cover ); ?>');" <?php endif; ?> >
	<div class="king-profile-head">
		<div class="king-profile-user">	
			<?php if ( ! $profile_id ) : ?>
				<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_account'] . '/' . $GLOBALS['king_edit'] ); ?>" class="edit-profile"><i class="fa fa-cog fa-2x" aria-hidden="true"></i></a>
			<?php endif; ?>
			<?php if ( $profile_id && ( $user_id !== get_current_user_id() ) && get_field( 'enable_private_messages', 'options' ) && is_user_logged_in() ) : ?>
				<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_prvtmsg'] . '/' . $this_user->user_login ); ?>" class="edit-profile"><i class="fa fa-envelope fa-2x" aria-hidden="true"></i></a>
			<?php endif; ?>			
			<?php
			if ( get_field( 'verified_account', 'user_' . $user_id ) ) {
				$verified = 'verified';
			} else {
				$verified = '';
			}
			?>
			<div class="king-profile-avatar <?php echo esc_attr( $verified ); ?>">
				<?php if ( get_field( 'verified_account', 'user_' . $user_id ) ) : ?>
					<span class="verified_account" title="<?php echo esc_html_e( 'verified account', 'king' ); ?>">
						<i class="fa fa-check-circle fa-2x" aria-hidden="true"></i>
					</span>
				<?php endif; ?>    
				<?php
				if ( get_field( 'author_image', 'user_' . $user_id ) ) :
					$image = get_field( 'author_image', 'user_' . $user_id );
					?>
					<img src="<?php echo esc_url( $image['sizes']['thumbnail'] ); ?>" />
				<?php else : ?>
					<span class="no-avatar"></span>  
				<?php endif; ?>
				<?php if ( get_the_author_meta( 'description', $this_user->ID ) ) : ?>
					<span class="about-link" title="share" data-toggle="modal" data-target="#aboutmodal" role="button"><i class="fas fa-address-card"></i></span>
				<?php endif; ?>
			</div>
		<?php if ( get_field( 'enable_user_points', 'options' ) ) : ?>
			<div class="king-points" title="<?php echo esc_html_e( 'Points','king' ); ?>"><i class="fa fa-star" aria-hidden="true"></i> <?php echo king_user_points( $user_id ); ?></div>
		<?php endif; ?>				
		</div>		
		<div class="king-profile-info">
			<h4><?php echo esc_attr( $this_user->data->display_name ); ?></h4>
			<?php
			if ( get_field( 'enable_user_groups', 'options' ) ) :
				echo king_user_groups( $user_id );
			endif;
			?>
			<?php echo wp_kses_post( get_the_author_meta( 'first_name', $user_id ) ); ?> 
			<?php echo wp_kses_post( get_the_author_meta( 'last_name', $user_id ) ); ?>
		</div>		
		<div class="king-profile-social">
			<ul>
				<?php if ( get_field( 'profile_facebook', 'user_' . $user_id ) ) : ?>
					<li class="fb"><a href="<?php the_field( 'profile_facebook', 'user_' . $user_id ); ?>" target="_blank"><i class="fab fa-facebook"></i></a></li>
				<?php endif; ?>
				<?php if ( get_field( 'profile_twitter', 'user_' . $user_id ) ) : ?>
					<li class="twi"><a href="<?php the_field( 'profile_twitter', 'user_' . $user_id ); ?>" target="_blank"><i class="fab fa-twitter"></i></a></li>
				<?php endif; ?>
				<?php if ( get_field( 'profile_google', 'user_' . $user_id ) ) : ?>
					<li class="g"><a href="<?php the_field( 'profile_google', 'user_' . $user_id ); ?>" target="_blank"><i class="fab fa-google-plus"></i> </a></li>
				<?php endif; ?>
				<?php if ( get_field( 'profile_instagram', 'user_' . $user_id ) ) : ?>
					<li class="g"><a href="<?php the_field( 'profile_instagram', 'user_' . $user_id ); ?>" target="_blank"><i class="fab fa-instagram"></i> </a></li>
				<?php endif; ?>
				<?php if ( get_field( 'profile_pinterest', 'user_' . $user_id ) ) : ?>
					<li class="g"><a href="<?php the_field( 'profile_pinterest', 'user_' . $user_id ); ?>" target="_blank"><i class="fab fa-pinterest-p"></i> </a></li>
				<?php endif; ?>
				<?php if ( get_field( 'profile_linkedin', 'user_' . $user_id ) ) : ?>
					<li class="ln"><a href="<?php the_field( 'profile_linkedin', 'user_' . $user_id ); ?>" target="_blank"><i class="fab fa-linkedin"></i></a></li>
				<?php endif; ?>        
				<?php if ( get_field( 'profile_add_url', 'user_' . $user_id ) ) : ?>
					<li class="ln"><a href="<?php the_field( 'profile_add_url', 'user_' . $user_id ); ?>" target="_blank"><i class="fas fa-link"></i></a></li>
				<?php endif; ?> 				    
			</ul>
		</div>

		<?php if ( ( $profile_id ) && ( is_user_logged_in() ) ) : ?>
			<?php
			$currentuser = wp_get_current_user();
			if ( $user_id !== $currentuser->ID ) {
				echo king_get_simple_follows_button( $user_id );
			}
			?>
		<?php endif; ?>

		<div class="profile-stats">
			<span class="profile-stats-num">
				<?php $posts = count_user_posts( $user_id ); ?>
				<i><?php echo esc_attr( $posts ); ?></i>
				<?php echo esc_html_e( 'Posts', 'king' ); ?>
			</span><!-- posts -->
			<span class="profile-stats-num">
				<i>
					<?php
					$likes = get_user_meta( $user_id, 'wp__user_like_count', true );
					if ( ! empty( $likes ) ) {
						$likes = $likes;
					} else {
						$likes = '0';
					}
					echo esc_attr( $likes );
					?>
				</i>                
				<?php echo esc_html_e( 'Likes', 'king' ); ?>                  
			</span><!-- likes -->
			<span class="profile-stats-num">
				<i>
					<?php
					$comment_count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) AS total FROM $wpdb->comments WHERE comment_approved = 1 AND user_id = %s", $user_id ) );
					echo esc_attr( $comment_count );
					?>
				</i>
				<?php echo esc_html_e( 'Comments', 'king' ); ?>
				</span><!-- comments -->
				<span class="profile-stats-num">

					<i>
						<?php
						$following = get_user_meta( $user_id, 'wp__user_follow_count', true );
						if ( ! empty( $following ) ) {
							echo esc_attr( $following );
						} else {
							echo '0';
						}

						?>
					</i>
					<?php echo esc_html_e( 'Following', 'king' ); ?>
				</span><!-- following -->
				<span class="profile-stats-num">
					<i>
						<?php
						$followers = get_user_meta( $user_id, 'wp__post_follow_count', true );
						if ( ! empty( $followers ) ) {
							echo esc_attr( $followers );
						} else {
							echo '0';
						}

						?>
					</i>
					<?php echo esc_html_e( 'Followers', 'king' ); ?>
				</span><!-- followers -->
			</div>   
<?php if ( get_field( 'enable_user_badges', 'option' ) ) : ?>
	<div class="king-profile-badges">
	<?php
	$lb_badges = get_user_meta( $user_id, 'king_user_leaderboard', true );
	if ( $lb_badges && get_field( 'enable_leaderboard_badges', 'option' ) ) :
		?>
		<div class="king-profile-badge" title="<?php echo esc_attr( str_replace( '_', ' ', $lb_badges ) ); ?>"><span class="lb-<?php echo esc_attr( $lb_badges ); ?>" ></span></div>
<?php endif;
	if ( have_rows( 'king_badges', 'option' ) ) :
		while ( have_rows( 'king_badges', 'option' ) ) :
			the_row();
			$badge_min   = get_sub_field( 'badge_min_point' );
			$badge_max   = get_sub_field( 'badge_max_point' );
			$badge_title = get_sub_field( 'badge_title' );
			$badge_desc  = get_sub_field( 'badge_description' ) ? ' : ' . get_sub_field( 'badge_description' ) : '';

			$user_point = get_user_meta( $user_id, 'king_user_points', true );

			$username[] = trim( str_replace( ' ', '', $badge_title ) );
			if ( get_row_layout() == 'badges_for_points' ) :
				if ( ( $user_point >= $badge_min ) && ( $badge_max >= $user_point ) ) :
					$short[] = trim( str_replace( ' ', '_', $badge_title ) );
					?>
				<div class="king-profile-badge" title="<?php echo esc_attr( $badge_title ); ?><?php echo esc_attr( $badge_desc ); ?> "><span class="<?php echo esc_attr( str_replace( ' ', '_', $badge_title ) ); ?>"></span></div>
					<?php
				endif;
			elseif ( get_row_layout() == 'badges_for_followers' ) :
				if ( ( $followers >= $badge_min ) && ( $badge_max >= $followers ) ) :
					$short[] = trim( str_replace( ' ', '_', $badge_title ) );
					?>
				<div class="king-profile-badge" title="<?php echo esc_attr( $badge_title ); ?><?php echo esc_attr( $badge_desc ); ?> "><span class="<?php echo esc_attr( str_replace( ' ', '_', $badge_title ) ); ?>"></span></div>
					<?php
				endif;
			elseif ( get_row_layout() == 'badges_for_posts' ) :
				if ( ( $posts >= $badge_min ) && ( $badge_max >= $posts ) ) :
					$short[] = trim( str_replace( ' ', '_', $badge_title ) );
					?>
				<div class="king-profile-badge" title="<?php echo esc_attr( $badge_title ); ?><?php echo esc_attr( $badge_desc ); ?> "><span class="<?php echo esc_attr( str_replace( ' ', '_', $badge_title ) ); ?>"></span></div>
					<?php
				endif;
			elseif ( get_row_layout() == 'badges_for_comments' ) :
				if ( ( $comment_count >= $badge_min ) && ( $badge_max >= $comment_count ) ) :
					$short[] = trim( str_replace( ' ', '_', $badge_title ) );
					?>
				<div class="king-profile-badge" title="<?php echo esc_attr( $badge_title ); ?><?php echo esc_attr( $badge_desc ); ?> "><span class="<?php echo esc_attr( str_replace( ' ', '_', $badge_title ) ); ?>"></span></div>
					<?php
				endif;
			elseif ( get_row_layout() == 'badges_for_likes' ) :
				if ( ( $likes >= $badge_min ) && ( $badge_max >= $likes ) ) :
					$short[] = trim( str_replace( ' ', '_', $badge_title ) );
					?>
				<div class="king-profile-badge" title="<?php echo esc_attr( $badge_title ); ?><?php echo esc_attr( $badge_desc ); ?> "><span class="<?php echo esc_attr( str_replace( ' ', '_', $badge_title ) ); ?>"></span></div>
					<?php
				endif;
			endif;
		endwhile;


		update_user_meta( $user_id, 'king_user_badges', $short );

	endif;
	?>
</div>
<?php endif; ?>

			<!--div class="king-profile-links">
				<?php if ( ! $profile_id ) : ?>				
					<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_account'] ); ?>" class="my-posts <?php echo esc_attr( $GLOBALS['profile'] ); ?>"><?php echo esc_html_e( 'My Posts', 'king' ); ?></a>
					<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_likes'] ); ?>" class="my-likes <?php echo esc_attr( $GLOBALS['likes'] ); ?>"><?php echo esc_html_e( 'Liked', 'king' ); ?></a>
					<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_followers'] ); ?>" class="followers <?php echo esc_attr( $GLOBALS['followers'] ); ?>"><?php echo esc_html_e( 'My followers', 'king' ); ?></a>
					<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_following'] ); ?>" class="following <?php echo esc_attr( $GLOBALS['following'] ); ?>"><?php echo esc_html_e( 'Following', 'king' ); ?></a>
				<?php else : ?>
					<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_account'] . '/' . $this_user->data->user_login ); ?>" class="my-posts <?php echo esc_attr( $GLOBALS['profile'] ); ?>"><?php echo esc_attr( $this_user->data->display_name ) . ' ' . esc_html__( 'posts', 'king' ); ?></a>
					<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_likes'] . '/' . $this_user->data->user_login ); ?>" class="my-likes <?php echo esc_attr( $GLOBALS['likes'] ); ?>"><?php echo esc_attr( $this_user->data->display_name ) . ' ' . esc_html__( 'likes', 'king' ); ?></a>      
					<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_followers'] . '/' . $this_user->data->user_login ); ?>" class="followers <?php echo esc_attr( $GLOBALS['followers'] ); ?>"><?php echo esc_attr( $this_user->data->display_name ) . ' ' . esc_html__( 'Followers', 'king' ); ?></a>
					<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_following'] . '/' . $this_user->data->user_login ); ?>" class="following <?php echo esc_attr( $GLOBALS['following'] ); ?>"><?php echo esc_attr( $this_user->data->display_name ) . ' ' . esc_html__( 'Following', 'king' ); ?></a> 
				<?php endif; ?>
			</div>
		</div-->
</div>

