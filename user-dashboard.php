<?php
/**
 * User following users posts page.
 *
 * @package King
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$profile_id = get_query_var( 'profile_id' );
if ( $profile_id ) {
	$this_user = get_user_by( 'login', $profile_id );
} else {
	$this_user = wp_get_current_user();
}
if ( ! $this_user->ID ) {
	wp_redirect( site_url() );
}

if ( empty( get_query_var( 'orderby' ) ) ) {
	$user_query = new WP_User_Query(
		array(
			'meta_query' => array(
				array(
					'key'     => 'wp__user_followd',
					'value'   => '"user-' . $this_user->ID . '";i:' . $this_user->ID . ';',
					'compare' => 'LIKE',
				),
			),
		)
	);
	if ( ! empty( $user_query->results ) ) {
		foreach ( $user_query->results as $user ) {
			$followtags[] = $user->ID;
		}
	} else {
		$followtags = array('0');
	}
} else {
	$followtags = get_user_meta( $this_user->ID, 'king_follow_tags', true );
	if ( empty( $followtags ) ) {
		$followtags = array( '0' );
	}
}
?>
<?php get_header(); ?> 

<div class="king-dashboard-user">
	<div class="king-dashboard-avatar">
		<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_account'] ); ?>">
			<?php if ( get_field( 'author_image','user_' . $this_user->ID ) ) : $image = get_field( 'author_image','user_' . $this_user->ID ); ?>
				<img src="<?php echo esc_url( $image['sizes']['thumbnail'] ); ?>" />
			<?php endif; ?>
		</a>
	</div>
	<div class="king-dashboard-username">
		<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_account'] ); ?>">
			<h4><?php echo esc_attr( $this_user->data->display_name ); ?></h4>
		</a>
		<?php if ( get_query_var( 'orderby' ) === 'cats' ) : ?>
			<?php echo esc_html_e( ' / Following Categories', 'king' ); ?>
			<?php elseif ( get_query_var( 'orderby' ) === 'tags' ) : ?>
				<?php echo esc_html_e( ' / Following Tags', 'king' ); ?>
				<?php else : ?>
					<?php echo esc_html_e( ' / Following Users Posts', 'king' ); ?>
				<?php endif; ?>
			</div>
			<div class="king-dashboard-head">
				<?php if ( get_field( 'enable_category_follow', 'options' ) || get_field( 'enable_tag_follow', 'options' ) ) : ?>
				<ul>
					<li>
						<a class="<?php if ( empty( get_query_var( 'orderby' ) ) ) { echo 'active'; } ?>" href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_dashboard'] ); ?>" ><?php esc_html_e( 'Following Users', 'king' ); ?></a>
					</li>
					<?php if ( get_field( 'enable_category_follow', 'options' ) ) : ?>
						<li>
							<a class="<?php if ( get_query_var( 'orderby' ) === 'cats' ) {  echo 'active'; } ?>" href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_dashboard'] . '/' . '?orderby=cats' ); ?>" ><?php esc_html_e( 'Categories', 'king' ); ?></a>
						</li>
					<?php endif; ?>
					<?php if ( get_field( 'enable_tag_follow', 'options' ) ) : ?>
						<li>
							<a class="<?php if ( get_query_var( 'orderby' ) === 'tags' ) {  echo 'active'; } ?>" href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_dashboard'] . '/' . '?orderby=tags' ); ?>" ><?php esc_html_e( 'Tags', 'king' ); ?></a>
						</li>
					<?php endif; ?>	
				</ul>
			<?php endif; ?>
			<div class="king-dashboard-nav">
				<?php foreach ( $followtags as $key => $value ) : ?>
					<?php if ( ( get_query_var( 'orderby' ) === 'cats' ) && get_field( 'enable_category_follow', 'options' ) ) : ?>
					<?php if ( get_cat_name( $value ) ) : ?>
						<a href="<?php echo get_category_link( $value ); ?>"><i class="fas fa-star-of-life fa-sm"></i> <?php echo get_cat_name( $value ); ?></a>
					<?php endif; ?>
				<?php elseif ( ( get_query_var( 'orderby' ) === 'tags' ) && get_field( 'enable_tag_follow', 'options' ) ) :
				$tag = get_tag( $value ); ?>
				<?php if ( $tag->name ) : ?>
					<a href="<?php echo get_tag_link( $value ); ?>">#<?php echo esc_attr( $tag->name ); ?></a>
				<?php endif; ?>
				<?php elseif ( empty( get_query_var( 'orderby' ) ) ) : ?>
					<?php $user_info = get_userdata( $value ); ?>
					<a href="<?php echo esc_url( site_url() . '/' . $GLOBALS['king_account'] . '/' . $user_info->user_login ); ?>">@<?php echo esc_attr( $user_info->user_login ); ?></a>
				<?php endif; ?>	
			<?php endforeach; ?>
		</div>
	</div>
</div>

<div id="primary" class="profile-content-area site-main-top">
	<main id="main" class="profile-site-main">	
		<ul class="king-posts">
		<li class="grid-sizer"></li>                
			<?php
			$paged = isset( $_GET['page'] ) ? $_GET['page'] : 0 ;
			if ( get_field( 'length_of_users_dashboard', 'options' ) ) {
				$length_dashboard = get_field( 'length_of_users_dashboard', 'option' );
			} else {
				$length_dashboard = '10';
			}

			if ( ( get_query_var( 'orderby' ) === 'cats' ) && get_field( 'enable_category_follow', 'options' ) ) {
				$followarray = 'category__in';
			} elseif ( ( get_query_var( 'orderby' ) === 'tags' ) && get_field( 'enable_tag_follow', 'options' ) ) {
				$followarray = 'tag__in';
			} else {
				$followarray = 'author__in';
			}
			$the_query = new WP_Query(
				array(
					'posts_per_page' => $length_dashboard,
					'post_type'      => 'post',
					$followarray     => $followtags,
					'paged'          => $paged,
					'post__not_in'   => get_option( 'sticky_posts' ),
				)
			);
			if ( ! empty( $the_query->have_posts() ) ) :
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					get_template_part( 'template-parts/content', 'profile-post' );
				}
				wp_reset_postdata();
				;else : ?>
				<div class="no-follower"><i class="fab fa-slack-hash fa-2x"></i><?php esc_html_e( 'you\'re not following yet', 'king' ); ?> </div>
			<?php endif; ?>	


			<?php if ( ! empty( $the_query->have_posts() ) ) : ?>
				<div class="king-pagination">
					<?php
					$format = '?page=%#%';
					if ( $profile_id ) {
						$url = site_url() . '/' . $GLOBALS['king_dashboard'] . '/' . $profile_id . '%_%';
					} else {
						$url = site_url() . '/' . $GLOBALS['king_dashboard'] . '%_%';
					}
							$big = 999999999; // need an unlikely integer.
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
					<?php endif; ?>	
				</ul>
			</main><!-- #main -->
		</div><!-- #primary -->

		<?php get_footer(); ?>
