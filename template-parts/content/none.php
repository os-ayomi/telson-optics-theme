<?php
/**
 * Empty state.
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="no-results">
	<h2 class="section-title"><?php esc_html_e( 'Nothing found', 'telson-optics' ); ?></h2>
	<p><?php esc_html_e( 'Try a different search term or browse the shop.', 'telson-optics' ); ?></p>
	<?php get_search_form(); ?>
</section>
