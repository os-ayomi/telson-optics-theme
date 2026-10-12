<?php
/**
 * Warranty / promise strip under shop listings (Telson Settings → Shop).
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

$items = telson_option( 'shop_promises', array() );
if ( empty( $items ) || ! is_array( $items ) ) {
	return;
}
?>
<section class="promise-strip is-dark" aria-label="<?php esc_attr_e( 'Our promise', 'telson-optics' ); ?>">
	<ul class="promise-strip__list">
		<?php foreach ( $items as $item ) : ?>
			<?php if ( empty( $item['title'] ) ) : ?>
				<?php continue; ?>
			<?php endif; ?>
			<li>
				<?php if ( ! empty( $item['eyebrow'] ) ) : ?>
					<span class="eyebrow"><?php echo esc_html( $item['eyebrow'] ); ?></span>
				<?php endif; ?>
				<strong><?php echo esc_html( $item['title'] ); ?></strong>
				<?php if ( ! empty( $item['text'] ) ) : ?>
					<span><?php echo esc_html( $item['text'] ); ?></span>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ul>
</section>
