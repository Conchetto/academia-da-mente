<?php
/**
 * User Profile Page.
 *
 * @package King
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$GLOBALS['profile'] = 'active';
$profile_id         = get_query_var( 'profile_id' );
if ( $profile_id ) {
	$this_user = get_user_by( 'login', $profile_id );
} else {
	$this_user = wp_get_current_user();
}
if ( ! $this_user->ID ) {
	wp_redirect( site_url() );
}
?>
<?php get_header(); ?>
<?php $GLOBALS['hide'] = 'hide'; ?>
<?php get_template_part( 'template-parts/king-profile-header' ); ?>

<div id="primary" class="profile-content-area">
	<?php if ( ! $profile_id ) : ?>
		<!--div class="king-order-nav">
			<ul>
				<li>
					<a class="<?php if ( ! isset( $_GET['orderby'] ) ) { echo 'active'; } ?>" href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_account'] ); ?>" ><?php esc_html_e( 'Published', 'king' ); ?></a>
				</li>
				<li>
					<a class="<?php if ( isset( $_GET['orderby'] ) && $_GET['orderby'] === 'pending' ) {  echo 'active'; } ?>" href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_account'] . '/' . '?orderby=pending' ); ?>" ><?php esc_html_e( 'Pending', 'king' ); ?></a>
				</li>
				<?php if ( get_field( 'enable_save_posts', 'options' ) ) : ?>
					<li>
						<a class="<?php if ( isset( $_GET['orderby'] ) && $_GET['orderby'] === 'draft' ) {  echo 'active'; } ?>" href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_account'] . '/' . '?orderby=draft' ); ?>" ><?php esc_html_e( 'Draft', 'king' ); ?></a>
					</li>
				<?php endif; ?>	
			</ul>
		</div-->	
	<?php endif; ?>	
	<!-- div class="king-profile-content site-main-top">
		<main id="main" class="profile-site-main">
			<ul class="king-posts">
				<li class="grid-sizer"></li>
				<?php
				$paged = isset( $_GET['page'] ) ? $_GET['page'] : 0;
				if ( get_field( 'length_of_posts_in_profile', 'options' ) ) {
					$length_user_posts = get_field( 'length_of_posts_in_profile', 'option' );
				} else {
					$length_user_posts = '8';
				}
					if ( ( isset( $_GET['orderby'] ) &&  $_GET['orderby'] === 'draft' ) && ! $profile_id ) { // input var okay; sanitization.

						$the_query = new WP_Query( array( 'posts_per_page' => $length_user_posts, 'post_type' => 'post', 'author' => $this_user->ID, 'paged' => $paged, 'post_status' => array('draft') ) );

					} elseif ( ( isset( $_GET['orderby'] ) && $_GET['orderby'] === 'pending' ) && ! $profile_id ) { // input var okay; sanitization.

						$the_query = new WP_Query( array( 'posts_per_page' => $length_user_posts, 'post_type' => 'post', 'author' => $this_user->ID, 'paged' => $paged, 'post_status' => array('pending') ) );

					} else {
						$the_query = new WP_Query( array( 'posts_per_page' => $length_user_posts, 'post_type' => 'post', 'author' => $this_user->ID, 'paged' => $paged ) );
					}
					if ( $the_query->have_posts() ) :

						while ( $the_query->have_posts() ) :
							$the_query->the_post();
							get_template_part( 'template-parts/content', get_post_format() );
						endwhile;
						wp_reset_postdata();

						else : ?>
							<div class="no-follower"><i class="fab fa-slack-hash fa-2x"></i><?php esc_html_e( 'Não encontramos nenhum post.', 'king' ); ?> </div>
						<?php endif; ?>							

						<div class="king-pagination">
							<?php
							$format = '?page=%#%';
							if ( $profile_id ) {
								$url = site_url() . '/' . $GLOBALS['king_account'] . '/' . $profile_id . '%_%';
							} else {
								$url = site_url() . '/' . $GLOBALS['king_account'] . '/%_%';
							}
							$big = 999999999;
							echo paginate_links(
								array(
									'base'      => $url,
									'format'    => $format,
									'current'   => max( 1, $paged ),
									'total'     => $the_query->max_num_pages,
									'prev_next' => true,
									'prev_text' => '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
									'next_text' => '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
								)
							);
							?>
						</div>
					</ul>
				</main><!-- #main -->
			</div -->
		</div><!-- #primary -->
		<?php
		if ( get_field( 'enable_leaderboard_badges', 'option' ) ) :
			king_leaderboard_badge( $this_user->ID );
		endif;
		if ( get_the_author_meta( 'description', $this_user->ID ) ) :
			?>
			<div class="king-modal-login modal" id="aboutmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="king-modal-content">
					<button type="button" class="king-modal-close" data-dismiss="modal" aria-label="Close"><i class="icon fa fa-fw fa-times"></i></button>
					<div class="king-modal-header"><h4><?php echo esc_html_e( 'About', 'king' ); ?></h4></div>
					<div class="king-about">
						<?php echo esc_attr( get_the_author_meta( 'description', $this_user->ID ) ); ?>
					</div>
				</div>
			</div>
		<?php endif; ?>
		<?php get_footer(); ?>
