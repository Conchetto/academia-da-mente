<?php
/**
 * The header part - headnav2.
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
<?php if ( ! get_field( 'hide_categories', 'options' ) ) :
	$hmenutemplate = get_field_object( 'header_menu_layout', 'options' );
	?>
	<?php if ( $hmenutemplate['value'] == 'hmenu-template-2' ) :
		$columns = get_field_object( 'header_menu_columns', 'options' );
		?>
		<div class="king-cat-list <?php echo esc_attr( $hmenutemplate['value'] ) . ' ' . esc_attr( $columns['value'] ); ?>">
			<?php wp_nav_menu( array( 'theme_location' => 'header-menu' ) ); ?>
		</div>
	<?php endif; ?>
<?php endif; ?>
