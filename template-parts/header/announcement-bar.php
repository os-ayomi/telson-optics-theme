<?php
/**
 * Announcement bar (dismissible). Managed in Telson Settings → Header.
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

if ( ! telson_option( 'announcement_enabled', false ) ) {
	return;
}
$text = telson_option( 'announcement_text' );
if ( ! $text ) {
	return;
}
$link = telson_option( 'announcement_link' );
?>
<div class="announcement-bar" data-announcement role="region" aria-label="<?php esc_attr_e( 'Announcement', 'telson-optics' ); ?>">
	<p class="announcement-bar__text">
		<?php echo esc_html( $text ); ?>
		<?php if ( ! empty( $link['url'] ) ) : ?>
			<a href="<?php echo esc_url( $link['url'] ); ?>"><?php echo esc_html( $link['title'] ); ?></a>
		<?php endif; ?>
	</p>
	<button class="announcement-bar__close" type="button" data-announcement-close>
		<?php telson_icon( 'close' ); ?>
		<span class="screen-reader-text"><?php esc_html_e( 'Dismiss announcement', 'telson-optics' ); ?></span>
	</button>
</div>
