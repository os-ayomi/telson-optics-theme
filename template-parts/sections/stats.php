<?php
/**
 * Section: headline statistics (About page).
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

$heading = telson_sub( 'heading' );
$items   = get_sub_field( 'items' );
$theme   = telson_sub( 'theme', 'light' );
if ( empty( $items ) ) {
	return;
}
?>
<section class="section stats<?php echo 'dark' === $theme ? ' section--dark' : ''; ?>">
	<div class="container">
		<?php if ( $heading ) : ?>
			<h2 class="section-title"><?php echo esc_html( $heading ); ?></h2>
		<?php endif; ?>
		<dl class="stats__list">
			<?php foreach ( $items as $item ) : ?>
				<div class="stats__item">
					<dt><?php echo esc_html( $item['label'] ?? '' ); ?></dt>
					<dd><?php echo esc_html( $item['value'] ?? '' ); ?></dd>
				</div>
			<?php endforeach; ?>
		</dl>
	</div>
</section>
