<?php
/**
 * Custom css Option.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package king
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
/* Typography
--------------------------*/
<?php if ( ! get_field( 'disable_custom_css', 'option' ) ) : ?>
/* ------CustomCSS------ */
<?php if ( get_field( 'custom_css', 'option' ) ) : ?>
<?php the_field( 'custom_css', 'option' ); ?>
<?php endif; ?>
/* ------body------ */
<?php if ( get_field( 'body_background', 'option' ) || get_field( 'google_fonts', 'option' ) ) : ?>
body {
<?php if ( get_field( 'body_background', 'option' ) ) : ?>
background-color:<?php the_field( 'body_background', 'option' ); ?>;<?php endif; ?>	
		<?php
		if ( get_field( 'google_fonts', 'option' ) ) :
			$fonts       = get_field_object( 'google_fonts', 'option' );
			$value       = $fonts['value'];
			$font_family = $fonts['choices'][ $value ];
			?>
	font-family: '<?php echo esc_attr( $font_family ); ?>', sans-serif;
<?php endif; ?>	
}
<?php endif; ?>
<?php if ( get_field( 'page_link_hover_color', 'option' ) ) : ?>
a:hover, 
.king-head-nav a:hover, 
.owl-prev:hover, 
.owl-next:hover {
	color:<?php the_field( 'page_link_hover_color', 'option' ); ?>;
}
<?php endif; ?>
<?php if ( get_field( 'post_background', 'option' ) ) : ?>
article.post {
	background-color:<?php the_field( 'post_background', 'option' ); ?>;
}
<?php endif; ?>
<?php if ( get_field( 'page_link_color', 'option' ) ) : ?>
a, 
.king-order-nav ul li .active, 
.king-order-nav ul li a:hover {
	color:<?php the_field( 'page_link_color', 'option' ); ?>;
}
.king-order-nav ul li .active:after, 
.king-order-nav ul li a:hover:after {
	border-color: <?php the_field( 'page_link_color', 'option' ); ?>;
}
.king-categories-head a {
	color: <?php the_field( 'page_link_color', 'option' ); ?>;
}
<?php endif; ?>
<?php if ( get_field( 'posts_meta_color', 'option' ) ) : ?>	
article.post .entry-meta .post-views, article.post .entry-meta .post-comments, article.post .entry-meta .post-likes, article.post .entry-meta .post-time,
article.post .entry-meta .post-views i, article.post .entry-meta .post-comments i, article.post .entry-meta .post-time i, article.post .entry-meta .post-likes i {
	color:<?php the_field( 'posts_meta_color', 'option' ); ?>!important;
}
<?php endif; ?>
<?php if ( get_field( 'posts_meta_background', 'option' ) ) : ?>
article.post .entry-meta {
	background-color:<?php the_field( 'posts_meta_background', 'option' ); ?>;
}
<?php endif; ?>
<?php if ( get_field( 'load_more_background', 'option' ) ) : ?>
.ias-trigger-next {
<?php if ( get_field( 'load_more_background', 'option' ) ) : ?>
	background-color:<?php the_field( 'load_more_background', 'option' ); ?>;<?php endif; ?>
}
<?php endif; ?>
<?php if ( get_field( 'load_more_button_text_color', 'option' ) ) : ?>
.ias-trigger a {
<?php if ( get_field( 'load_more_button_text_color', 'option' ) ) : ?>
	color:<?php the_field( 'load_more_button_text_color', 'option' ); ?>;<?php endif; ?>
}
<?php endif; ?>
<?php if ( get_field( 'button_colors', 'option' ) || get_field( 'button_text_color', 'option' ) ) : ?>
button,
input[type="button"],
input[type="reset"],
input[type="submit"],
.king-alert-button {
<?php if ( get_field( 'button_colors', 'option' ) ) : ?>
	background-color:<?php the_field( 'button_colors', 'option' ); ?>;
	border-color:<?php the_field( 'button_colors', 'option' ); ?>;<?php endif; ?>
<?php if ( get_field( 'button_text_color', 'option' ) ) : ?>
	color:<?php the_field( 'button_text_color', 'option' ); ?>;<?php endif; ?>
}
<?php endif; ?>
<?php if ( get_field( 'follow_button_background', 'option' ) ) : ?>
.user-follow-button a {
	background-color:<?php the_field( 'follow_button_background', 'option' ); ?>;
}
<?php endif; ?>
<?php if ( get_field( 'follow_button_text_color', 'option' ) ) : ?>
.user-follow-button .sl-count, .user-follow-button i {
	color:<?php the_field( 'follow_button_text_color', 'option' ); ?>;
}
<?php endif; ?>
<?php if ( get_field( 'post_entry_content_color', 'option' ) ) : ?>
article .entry-content, .king-profile-sidebar {
	color:<?php the_field( 'post_entry_content_color', 'option' ); ?>;
}
<?php endif; ?>
<?php if ( get_field( 'post_title_color', 'option' ) ) : ?>
article.post .entry-title a {
	color:<?php the_field( 'post_title_color', 'option' ); ?>;
}
<?php endif; ?>
<?php if ( get_field( 'color_for_nsfw', 'option' ) ) : ?>
.nsfw-post, .nsfw, .nsfw-post-page, .nsfw-post-simple {
	background-color:<?php the_field( 'color_for_nsfw', 'option' ); ?>;
}
<?php endif; ?>
/* ------header------ */
<?php if ( get_field( 'header_background', 'option' ) ) : ?>
.king-header, .king-search-top .active {
	background-color:<?php the_field( 'header_background', 'option' ); ?>;
}
<?php endif; ?>
<?php if ( get_field( 'navigation_background', 'option' ) ) : ?>
.main-navigation, .main-navigation ul ul, .main-navigation ul ul ul {
	background-color:<?php the_field( 'navigation_background', 'option' ); ?>;
}
<?php endif; ?>
<?php if ( get_field( 'header_menu_background_color', 'option' ) ) : ?>
.king-cat-list {
	background-color:<?php the_field( 'header_menu_background_color', 'option' ); ?>;
}
<?php endif; ?>
<?php if ( get_field( 'header_menu_link_colors', 'option' ) ) : ?>
.king-cat-list li a {
	color:<?php the_field( 'header_menu_link_colors', 'option' ); ?>;
}
<?php endif; ?>
<?php if ( get_field( 'header_menu_link_hover_colors', 'option' ) ) : ?>
.king-cat-list li a:hover {
	color:<?php the_field( 'header_menu_link_hover_colors', 'option' ); ?>;
}
<?php endif; ?>
<?php if ( get_field( 'header_link_color', 'option' ) ) : ?>
.king-head-nav a, .king-cat-dots, .search-close {
	color:<?php the_field( 'header_link_color', 'option' ); ?>;
}
<?php endif; ?>
<?php if ( get_field( 'header_search_background', 'option' ) ) : ?>
.king-search-top .header-search-form {
	background-color:<?php the_field( 'header_search_background', 'option' ); ?>;
}
<?php endif; ?>
<?php if ( get_field( 'color_for_news', 'option' ) ) : ?>
.news-entry-format,
.term-post-format-quote .page-top-header, 
.king-post-format .entry-format-news:before, 
.king-post-format .entry-format-news:hover {
	background-color:<?php the_field( 'color_for_news', 'option' ); ?>;
}
<?php endif; ?>
<?php if ( get_field( 'color_for_news', 'option' ) ) : ?>
.nav-news {
	border-color:<?php the_field( 'color_for_news', 'option' ); ?>;
}
<?php endif; ?>
<?php if ( get_field( 'color_for_videos', 'option' ) ) : ?>
.video-entry-format,
.term-post-format-video .page-top-header, 
.king-post-format .entry-format-video:before,
.king-post-format .entry-format-video:hover {
	background-color:<?php the_field( 'color_for_videos', 'option' ); ?>;
}
<?php endif; ?>
<?php if ( get_field( 'color_for_videos', 'option' ) ) : ?>
.nav-video {
	border-color:<?php the_field( 'color_for_videos', 'option' ); ?>;
}
<?php endif; ?>
<?php if ( get_field( 'color_for_images', 'option' ) ) : ?>
.image-entry-format,
.term-post-format-image .page-top-header, 
.king-post-format .entry-format-image:before,
.king-post-format .entry-format-image:hover {
	background-color:<?php the_field( 'color_for_images', 'option' ); ?>;
}
<?php endif; ?>
<?php if ( get_field( 'color_for_images', 'option' ) ) : ?>
.nav-image {
	border-color:<?php the_field( 'color_for_images', 'option' ); ?>;
}
<?php endif; ?>
<?php if ( get_field( 'submit_background', 'option' ) ) : ?>
.king-submit-open, .king-submit-buttons {
	background-color:<?php the_field( 'submit_background', 'option' ); ?>;
}
<?php endif; ?>
<?php if ( get_field( 'submit_background', 'option' ) ) : ?>
.king-submit-buttons:before{
	border-right-color:<?php the_field( 'submit_background', 'option' ); ?>;
	border-bottom-color:<?php the_field( 'submit_background', 'option' ); ?>;
}
<?php endif; ?>
<?php if ( get_field( 'submiticon_color', 'option' ) ) : ?>	
.king-submit-open, .king-submit-buttons li a i {
	color:<?php the_field( 'submiticon_color', 'option' ); ?>;
}
<?php endif; ?>
<?php if ( get_field( 'submit_button_link_colors', 'option' ) ) : ?>
.king-submit-buttons li a {
	color:<?php the_field( 'submit_button_link_colors', 'option' ); ?>;
}
<?php endif; ?>
<?php if ( get_field( 'notification_icon_background', 'option' ) || get_field( 'notification_icon_color', 'option' ) ) : ?>
.king-notify-toggle {
<?php if ( get_field( 'notification_icon_background', 'option' ) ) : ?>
	background-color:<?php the_field( 'notification_icon_background', 'option' ); ?>;<?php endif; ?>
<?php if ( get_field( 'notification_icon_color', 'option' ) ) : ?>	
	color:<?php the_field( 'notification_icon_color', 'option' ); ?>;<?php endif; ?>	
}
<?php endif; ?>
<?php if ( get_field( 'notification_box_background', 'option' ) ) : ?>
.king-notify-menu {
	background-color:<?php the_field( 'notification_box_background', 'option' ); ?>;
}
<?php endif; ?>
<?php if ( get_field( 'notification_box_background', 'option' ) ) : ?>
.king-notify-menu:before {
	border-bottom-color:<?php the_field( 'notification_box_background', 'option' ); ?>;
}
<?php endif; ?>
<?php if ( get_field( 'notification_box_inside_background', 'option' ) ) : ?>
#king-notify-inside {
	background-color:<?php the_field( 'notification_box_inside_background', 'option' ); ?>;
}
<?php endif; ?>
<?php if ( get_field( 'notifications_text_color', 'option' ) ) : ?>
#king-notify-inside li a, 
#king-notify-inside li {
	color:<?php the_field( 'notifications_text_color', 'option' ); ?>;
}
<?php endif; ?>
<?php if ( get_field( 'user_points_badge_background', 'option' ) || get_field( 'user_points_badge_color', 'option' ) ) : ?>
.king-points {
<?php if ( get_field( 'user_points_badge_background', 'option' ) ) : ?>
	background-color:<?php the_field( 'user_points_badge_background', 'option' ); ?>;<?php endif; ?>
<?php if ( get_field( 'user_points_badge_color', 'option' ) ) : ?>	
	color:<?php the_field( 'user_points_badge_color', 'option' ); ?>;<?php endif; ?>	
}
<?php endif; ?>
<?php if ( get_field( 'navigation_links', 'option' ) ) : ?>
.header-nav a {
	color:<?php the_field( 'navigation_links', 'option' ); ?>;	
}
.king-switch .btn-default label {
	color:<?php the_field( 'navigation_links', 'option' ); ?>;	
}
<?php endif; ?>
<?php if ( get_field( 'navigation_links_active', 'option' ) ) : ?>
.header-nav .current-menu-item:before {
	background-color: <?php the_field( 'navigation_links_active', 'option' ); ?>;
}
.header-nav .current-menu-item a {
	color: <?php the_field( 'navigation_links_active', 'option' ); ?>;
}
.header-nav a:after {
	background-color: <?php the_field( 'navigation_links_active', 'option' ); ?>;
}
.header-nav a:hover {
	color: <?php the_field( 'navigation_links_active', 'option' ); ?>;
}
<?php endif; ?>
<?php if ( get_field( 'slider_height', 'option' ) ) : ?>
.king-featured, .king-featured .featured-posts {
	height:<?php the_field( 'slider_height', 'option' ); ?>px;
}
<?php endif; ?>
<?php if ( get_field( 'grid_height', 'option' ) ) : $gridh = get_field( 'grid_height', 'option' ); ?>
.king-featured-grid {
	height:<?php echo esc_attr( $gridh ); ?>px;
	grid-auto-rows: <?php echo esc_attr( $gridh / 2 ); ?>px;
}
<?php endif; ?>
<?php if ( get_field( 'display_mini_slider', 'option' ) && get_field( 'mini_slider_post_height', 'option' ) ) : ?>
.editorschoice-post {
	height:<?php the_field( 'mini_slider_post_height', 'option' ); ?>px;
}
<?php endif; ?>
<?php if ( get_field( 'page_header_background', 'option' ) || get_field( 'page_header_text_color', 'option' ) ) : ?>
.page-top-header {
<?php if ( get_field( 'page_header_background', 'option' ) ) : ?>
	background-color:<?php the_field( 'page_header_background', 'option' ); ?>;<?php endif; ?>
<?php if ( get_field( 'page_header_text_color', 'option' ) ) : ?>	
	color:<?php the_field( 'page_header_text_color', 'option' ); ?>;<?php endif; ?>	
}
<?php endif; ?>
<?php if ( get_field( 'login_and_register_buttons_background', 'option' ) ) : ?>
.king-login-buttons a {
	background-color:<?php the_field( 'login_and_register_buttons_background', 'option' ); ?>;
}
<?php endif; ?>
<?php $hero = get_field( 'header_03_options', 'options' );
	if ( $hero['background1'] ) :
?>
.king-top-header {
	background-color: <?php echo esc_attr( $hero['background1'] ); ?>;
	background: -moz-linear-gradient(left, <?php echo esc_attr( $hero['background1'] ); ?> 0%, <?php echo esc_attr( $hero['background2'] ); ?> 100%);
	background: -webkit-linear-gradient(left, <?php echo esc_attr( $hero['background1'] ); ?> 0%, <?php echo esc_attr( $hero['background2'] ); ?> 100%);
	background: linear-gradient(to right, <?php echo esc_attr( $hero['background1'] ); ?> 0%, <?php echo esc_attr( $hero['background2'] ); ?> 100%);
}
.king-top-header-menu ul ul {
	background-color: <?php echo esc_attr( $hero['background1'] ); ?>;
}
<?php endif; ?>
<?php $fcolor = get_field( 'header_03_options', 'options' );
	if ( $fcolor['font_color'] ) :
?>
.king-top-header-menu a {
	color: <?php echo esc_attr( $fcolor['font_color'] ); ?>;
}
.king-top-header-menu .menu-item-has-children:after {
	color: <?php echo esc_attr( $fcolor['font_color'] ); ?>;
}
.king-top-header-icons a {
	color: <?php echo esc_attr( $fcolor['font_color'] ); ?>;
	border-color: <?php echo esc_attr( $fcolor['font_color'] ); ?>;
}
<?php endif; ?>

<?php $leftmenu = get_field( 'header_left_menu_background', 'options' );
	if ( $leftmenu['left_menu_background'] ) :
?>
.king-leftmenu {
	background-color: <?php echo esc_attr( $leftmenu['left_menu_background'] ); ?>;
}
<?php endif; ?>
<?php if ( $leftmenu['left_menu_background'] ) : ?>
.king-leftmenu a {
	color: <?php echo esc_attr( $leftmenu['left_menu_links_color'] ); ?>;
}
<?php endif; ?>
<?php if ( get_field( 'gallery_background', 'option' ) || get_field( 'gallery_text_color', 'option' ) ) : ?>
.king-gallery-container {
<?php if ( get_field( 'gallery_background', 'option' ) ) : ?>
	background-color:<?php the_field( 'gallery_background', 'option' ); ?>;<?php endif; ?>
<?php if ( get_field( 'gallery_text_color', 'option' ) ) : ?>	
	color:<?php the_field( 'gallery_text_color', 'option' ); ?>;<?php endif; ?>	
}
<?php endif; ?>
<?php if ( get_field( 'search_v2_button_background', 'option' ) || get_field( 'search_v2_icon_color', 'option' ) ) : ?>
#searchv2-button {
<?php if ( get_field( 'search_v2_button_background', 'option' ) ) : ?>
	background-color:<?php the_field( 'search_v2_button_background', 'option' ); ?>;<?php endif; ?>
<?php if ( get_field( 'search_v2_icon_color', 'option' ) ) : ?>	
	color:<?php the_field( 'search_v2_icon_color', 'option' ); ?>;<?php endif; ?>	
}
<?php endif; ?>
<?php if ( get_field( 'search_v2_box_background', 'option' ) ) : ?>
.live-king-search-top {
	background-color: <?php the_field( 'search_v2_box_background', 'option' ); ?>;
}
<?php endif; ?>
<?php if ( get_field( 'search_v2_input_background', 'option' ) ) : ?>
.live-king-search {
	background-color: <?php the_field( 'search_v2_input_background', 'option' ); ?>;
}
<?php endif; ?>
/* ------footer------ */
<?php if ( get_field( 'footer_background', 'option' ) || get_field( 'footer_text_color', 'option' ) ) : ?>
.site-footer {
<?php if ( get_field( 'footer_background', 'option' ) ) : ?>
	background-color:<?php the_field( 'footer_background', 'option' ); ?>;<?php endif; ?>
<?php if ( get_field( 'footer_text_color', 'option' ) ) : ?>	
	color:<?php the_field( 'footer_text_color', 'option' ); ?>;<?php endif; ?>	
}
<?php endif; ?>
<?php if ( get_field( 'fatfooter_background', 'option' ) ) : ?>
.fatfooter {
	background-color:<?php the_field( 'fatfooter_background', 'option' ); ?>;
}
<?php endif; ?>
<?php if ( get_field( 'footer_widgets_title_background', 'option' ) || get_field( 'footer_widgets_title_color', 'option' ) ) : ?>
.fatfooter .widget-title {
<?php if ( get_field( 'footer_widgets_title_background', 'option' ) ) : ?>
	background-color:<?php the_field( 'footer_widgets_title_background', 'option' ); ?>;<?php endif; ?>
<?php if ( get_field( 'footer_widgets_title_color', 'option' ) ) : ?>	
	color:<?php the_field( 'footer_widgets_title_color', 'option' ); ?>;
	border-color:<?php the_field( 'footer_widgets_title_color', 'option' ); ?>;<?php endif; ?>		
}
<?php endif; ?>
<?php if ( get_field( 'footer_link_color', 'option' ) ) : ?>
.site-footer a {
	color:<?php the_field( 'footer_link_color', 'option' ); ?>;
}
<?php endif; ?>
<?php if ( get_field( 'alert_background_color', 'option' ) || get_field( 'alert_text_color', 'option' ) ) : ?>
.king-alert-like {
<?php if ( get_field( 'alert_text_color', 'option' ) ) : ?>
	color:<?php the_field( 'alert_text_color', 'option' ); ?>;<?php endif; ?>
<?php if ( get_field( 'alert_background_color', 'option' ) ) : ?>
	background-color:<?php the_field( 'alert_background_color', 'option' ); ?>;<?php endif; ?>
}
<?php endif; ?>
/* ------sidebar------ */
<?php if ( get_field( 'sidebar_text_color', 'option' ) ) : ?>
.first-sidebar ul li {
	color:<?php the_field( 'sidebar_text_color', 'option' ); ?>;
}
<?php endif; ?>
<?php if ( get_field( 'sidebar_links', 'option' ) ) : ?>
.first-sidebar ul li a {
	color:<?php the_field( 'sidebar_links', 'option' ); ?>;
}
<?php endif; ?>
<?php if ( get_field( 'sidebar_widgets_title_background', 'option' ) || get_field( 'sidebar_widgets_title_color', 'option' ) ) : ?>
.widget-title, .king-related .related-title, .widget-title i {
<?php if ( get_field( 'sidebar_widgets_title_background', 'option' ) ) : ?>
	background-color:<?php the_field( 'sidebar_widgets_title_background', 'option' ); ?>;<?php endif; ?>
<?php if ( get_field( 'sidebar_widgets_title_color', 'option' ) ) : ?>	
	color:<?php the_field( 'sidebar_widgets_title_color', 'option' ); ?>;<?php endif; ?>	
}
<?php endif; ?>
<?php if ( get_field( 'sidebar_widgets_post_meta_text_color', 'option' ) ) : ?>	
.entry-meta .post-views, .entry-meta .post-comments, .entry-meta .post-likes, .entry-meta .post-time,
.entry-meta .post-views i, .entry-meta .post-comments i, .entry-meta .post-time i, .entry-meta .post-likes i {
	color:<?php the_field( 'sidebar_widgets_post_meta_text_color', 'option' ); ?>!important;
}
<?php endif; ?>

/* ------PostPage------ */
<?php if ( get_field( 'post_content_background_color', 'option' ) || get_field( 'post_content_text_color', 'option' ) ) : ?>
.post-page .post {
<?php if ( get_field( 'post_content_background_color', 'option' ) ) : ?>
	background-color:<?php the_field( 'post_content_background_color', 'option' ); ?>;<?php endif; ?>
	<?php if ( get_field( 'post_content_text_color', 'option' ) ) : ?>	
	color:<?php the_field( 'post_content_text_color', 'option' ); ?>;<?php endif; ?>
}
<?php endif; ?>
<?php if ( get_field( 'post_content_text_color', 'option' ) ) : ?>
.tags-links a {
	color:<?php the_field( 'post_content_text_color', 'option' ); ?>;
	border-color: <?php the_field( 'post_content_text_color', 'option' ); ?>;
}
<?php endif; ?>
<?php if ( get_field( 'share_bar_background', 'option' ) ) : ?>
.king-social-share {
	background-color:<?php the_field( 'share_bar_background', 'option' ); ?>;
}
<?php endif; ?>
<?php if ( get_field( 'share_bar_buttons_background', 'option' ) ) : ?>
.king-social-share .share-buttons a,
.post-nav a {
	background-color:<?php the_field( 'share_bar_buttons_background', 'option' ); ?>;
}
<?php endif; ?>
<?php if ( get_field( 'post_author_box_background', 'option' ) || get_field( 'post_author_box_text_color', 'option' ) ) : ?>
.post-author {
<?php if ( get_field( 'post_author_box_background', 'option' ) ) : ?>
	background-color:<?php the_field( 'post_author_box_background', 'option' ); ?>;<?php endif; ?>
	<?php if ( get_field( 'post_author_box_text_color', 'option' ) ) : ?>	
	color:<?php the_field( 'post_author_box_text_color', 'option' ); ?>;<?php endif; ?>
}
<?php endif; ?>
<?php if ( get_field( 'post_author_box_text_color', 'option' ) ) : ?>
.post-author-name {
	color:<?php the_field( 'post_author_box_text_color', 'option' ); ?>;
}
<?php endif; ?>
<?php if ( get_field( 'post_meta_background', 'option' ) ) : ?>
.single-post .post-meta {
	background-color:<?php the_field( 'post_meta_background', 'option' ); ?>;
}
<?php endif; ?>
<?php if ( get_field( 'post_meta_text_color', 'option' ) ) : ?>	
.single-post .post-views, .single-post .post-comments, .single-post .post-time, .single-post .post-likes,
.single-post .post-views i, .single-post .post-comments i, .single-post .post-time i, .single-post .post-likes i {
	color:<?php the_field( 'post_meta_text_color', 'option' ); ?>;
}
<?php endif; ?>
<?php if ( get_field( 'editors_choice_badge_background', 'option' ) || get_field( 'editors_choice_badge_text', 'option' )  ) : ?>
.editors-badge {
<?php if ( get_field( 'editors_choice_badge_background', 'option' ) ) : ?>
	background-color:<?php the_field( 'editors_choice_badge_background', 'option' ); ?>;<?php endif; ?>
<?php if ( get_field( 'editors_choice_badge_text', 'option' ) ) : ?>
	color:<?php the_field( 'editors_choice_badge_text', 'option' ); ?>;<?php endif; ?>
}
<?php endif; ?>
<?php if ( get_field( 'post_like_button_background', 'option' ) || get_field( 'post_like_button_icon_color', 'option' ) ) : ?>
.king-like a {
<?php if ( get_field( 'post_like_button_background', 'option' ) ) : ?>
	background-color:<?php the_field( 'post_like_button_background', 'option' ); ?>;<?php endif; ?>
<?php if ( get_field( 'post_like_button_icon_color', 'option' ) ) : ?>
	color:<?php the_field( 'post_like_button_icon_color', 'option' ); ?>;<?php endif; ?>
}
<?php endif; ?>
<?php if ( get_field( 'comments_area_background_color', 'option' ) ) : ?>
.comments-area {
	background-color:<?php the_field( 'comments_area_background_color', 'option' ); ?>;
}
<?php endif; ?>
<?php if ( get_field( 'comment_box_background', 'option' ) || get_field( 'comment_box_text_color', 'option' ) ) : ?>
#comments .comment-box, #comments .comments-title {
<?php if ( get_field( 'comment_box_background', 'option' ) ) : ?>
	background-color:<?php the_field( 'comment_box_background', 'option' ); ?>;<?php endif; ?>
<?php if ( get_field( 'comment_box_text_color', 'option' ) ) : ?>
	color:<?php the_field( 'comment_box_text_color', 'option' ); ?>;<?php endif; ?>
}
<?php endif; ?>
<?php if ( get_field( 'comment_box_text_color', 'option' ) ) : ?>
#comments .user-header-settings cite, .comment-meta time {
	color:<?php the_field( 'comment_box_text_color', 'option' ); ?>;
}
<?php endif; ?>
<?php if ( get_field( 'comments_reply_button_background', 'option' ) || get_field( 'comments_reply_button_value_color', 'option' ) ) : ?>
.comment-reply-link {
<?php if ( get_field( 'comments_reply_button_background', 'option' ) ) : ?>
	background-color:<?php the_field( 'comments_reply_button_background', 'option' ); ?>;<?php endif; ?>
<?php if ( get_field( 'comments_reply_button_value_color', 'option' ) ) : ?>
	color:<?php the_field( 'comments_reply_button_value_color', 'option' ); ?>;<?php endif; ?>
}
<?php endif; ?>
<?php if ( get_field( 'comment_form_background', 'option' ) || get_field( 'comment_form_text_color', 'option' ) ) : ?>
.comment-respond {
<?php if ( get_field( 'comment_form_background', 'option' ) ) : ?>
	background-color:<?php the_field( 'comment_form_background', 'option' ); ?>;<?php endif; ?>
<?php if ( get_field( 'comment_form_text_color', 'option' ) ) : ?>
	color:<?php the_field( 'comment_form_text_color', 'option' ); ?>;<?php endif; ?>
}
<?php endif; ?>
<?php if ( get_field( 'post_page_boxes_background', 'option' ) ) : ?>
.single-boxes {
	background-color: <?php the_field( 'post_page_boxes_background', 'option' ); ?>;
}	
<?php endif; ?>
<?php if ( get_field( 'post_page_boxes_title_background', 'option' ) ) : ?>
.single-boxes-title {
	background-color: <?php the_field( 'post_page_boxes_title_background', 'option' ); ?>;
}
<?php endif; ?>
<?php if ( get_field( 'post_page_boxes_title_color', 'option' ) ) : ?>
.single-boxes-title h4 {
	color: <?php the_field( 'post_page_boxes_title_color', 'option' ); ?>;
}
<?php endif; ?>
<?php else : ?> 
<?php if ( get_field( 'google_fonts', 'options' ) ) : ?>
body {
<?php if ( get_field( 'google_fonts','options' ) ) :
	$fonts = get_field_object( 'google_fonts', 'options' );
	$value = $fonts['value'];
	$font_family = $fonts['choices'][ $value ];
?>
	font-family: '<?php echo esc_attr( $font_family ); ?>', sans-serif;<?php endif; ?>	
}
<?php endif; ?>
<?php if ( get_field( 'slider_height', 'option' ) ) : ?>
.king-featured, .king-featured .featured-posts {
	height:<?php the_field( 'slider_height', 'option' ); ?>px;
}
<?php endif; ?>
<?php endif; ?>
<?php if ( get_field( '1st_reaction', 'option' ) ) : ?>
.king-reactions ul li:first-child label:before,
.king-reaction-like {
	background-image: url(<?php the_field( '1st_reaction', 'option' ); ?>);
}
<?php endif; ?>
<?php if ( get_field( '2nd_reaction', 'option' ) ) : ?>
.king-reactions ul li:nth-child(2) label:before,
.king-reaction-love {
	background-image: url(<?php the_field( '2nd_reaction', 'option' ); ?>);
}
<?php endif; ?>
<?php if ( get_field( '3rd_reaction', 'option' ) ) : ?>
.king-reactions ul li:nth-child(3) label:before,
.king-reaction-haha {
	background-image: url(<?php the_field( '3rd_reaction', 'option' ); ?>);
}
<?php endif; ?>
<?php if ( get_field( '4th_reaction', 'option' ) ) : ?>
.king-reactions ul li:nth-child(4) label:before,
.king-reaction-wow {
	background-image: url(<?php the_field( '4th_reaction', 'option' ); ?>);
}
<?php endif; ?>
<?php if ( get_field( '5th_reaction', 'option' ) ) : ?>
.king-reactions ul li:nth-child(5) label:before,
.king-reaction-sad {
	background-image: url(<?php the_field( '5th_reaction', 'option' ); ?>);
}
<?php endif; ?>
<?php if ( get_field( '6th_reaction', 'option' ) ) : ?>
.king-reactions ul li:nth-child(6) label:before,
.king-reaction-angry {
	background-image: url(<?php the_field( '6th_reaction', 'option' ); ?>);
}
<?php endif; ?>
<?php if ( get_field( 'default_avatar', 'options' ) ) : $avatar = get_field( 'default_avatar', 'options' ); ?>
.user-header-noavatar, .king-notify-avatar-img, .no-avatar, .users-avatar .users-noavatar, .king-dashboard-avatar, .king-inbox-avatar, .card-noavatar {
	background-image: url(<?php echo esc_url( $avatar['sizes'][ 'thumbnail' ] ); ?>);
	background-size: cover;
}
<?php endif; ?>
<?php
if ( get_field( 'enable_user_badges', 'option' ) ) :
	if ( have_rows( 'king_badges', 'option' ) ) :
		while ( have_rows( 'king_badges', 'option' ) ) :
			the_row();
			$badge_img = get_sub_field( 'badge_icon' );
			$badge_ttle = get_sub_field( 'badge_title' );
			$badge_ttle = trim( str_replace( ' ', '_', $badge_ttle ) );
			if ( get_row_layout() == 'badges_for_points' ) :
?>
.king-profile-badge .<?php echo esc_attr( $badge_ttle ); ?> {
	background-image: url(<?php echo esc_url( $badge_img ); ?>);
}
<?php elseif ( get_row_layout() == 'badges_for_followers' ) : ?>
.king-profile-badge .<?php echo esc_attr( $badge_ttle ); ?> {
	background-image: url(<?php echo esc_url( $badge_img ); ?>);
}
<?php elseif ( get_row_layout() == 'badges_for_posts' ) : ?>
.king-profile-badge .<?php echo esc_attr( $badge_ttle ); ?> {
	background-image: url(<?php echo esc_url( $badge_img ); ?>);
}
<?php elseif ( get_row_layout() == 'badges_for_comments' ) : ?>
.king-profile-badge .<?php echo esc_attr( $badge_ttle ); ?> {
	background-image: url(<?php echo esc_url( $badge_img ); ?>);
}
<?php elseif ( get_row_layout() == 'badges_for_likes' ) : ?>
.king-profile-badge .<?php echo esc_attr( $badge_ttle ); ?> {
	background-image: url(<?php echo esc_url( $badge_img ); ?>);
}

<?php

			endif;
		endwhile;
	endif;
endif;
?>
<?php
// check if the repeater field has rows of data.
if ( have_rows( 'leaderboard_badges', 'option' ) ) :
	// loop through the rows of data.
	while ( have_rows( 'leaderboard_badges', 'option' ) ) : the_row();
		$lb_badge_img = get_sub_field( 'leaderboard_badge_icon' );
		$lb_badge_ttle = get_sub_field( 'leaderboard_badge_title' );
		$lb_badge_ttle = trim( str_replace( ' ', '_', $lb_badge_ttle ) );
?>
.lb-<?php echo esc_attr( $lb_badge_ttle ); ?> {
	background-image: url(<?php echo esc_url( $lb_badge_img ); ?>);
}
<?php 
	endwhile;
endif;
?>
<?php if ( get_field( 'enable_rtl', 'option' ) ) : ?>
.site-content {
	direction: rtl;
	unicode-bidi: embed;
}
.king-cat-list ul {
	direction: rtl;
	unicode-bidi: embed;
	text-align:right;
}
.king-social-share, 
.single-format-image .share-top .is_stuck,
.share-top, 
.post-like,
.king-featured,
.content-right-top {
	direction: ltr;
}

article.post {
	text-align:right!important;
}
<?php endif; ?>