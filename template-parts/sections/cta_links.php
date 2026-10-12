<?php
/**
 * Section: call-to-action link row (pro staff, reviews, about…).
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

$heading = telson_sub( 'heading' );
$text    = telson_sub( 'text' );
$links   = get_sub_field( 'links' );
$theme   = telson_sub( 'theme', 'dark' );
if ( empty( $links ) ) {
	return;
}
?>
<section class="section cta-links<?php echo 'dark' === $theme ? ' section--dark' : ''; ?>">
	<div class="container">
		<?php if ( $heading ) : ?>
			<h2 class="section-title"><?php echo esc_html( $heading ); ?></h2>
		<?php endif; ?>
		<?php if ( $text ) : ?>
			<p class="section-lede"><?php echo esc_html( $text ); ?></p>
		<?php endif; ?>
		<ul class="cta-links__list">
			<?php foreach ( $links as $row ) : ?>
				<li><?php telson_link( $row['link'] ?? null, 'btn btn--outline' ); ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
