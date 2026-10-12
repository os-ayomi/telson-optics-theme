<?php
/**
 * Blog sidebar.
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

if ( ! is_active_sidebar( 'sidebar-blog' ) ) {
	return;
}
?>
<aside class="sidebar" aria-label="<?php esc_attr_e( 'Sidebar', 'telson-optics' ); ?>">
	<?php dynamic_sidebar( 'sidebar-blog' ); ?>
</aside>
