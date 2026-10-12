<?php
/**
 * Section: stacked region banners (global presence / distributor list).
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

$eyebrow = telson_sub( 'eyebrow' );
$heading = telson_sub( 'heading' );
$text    = telson_sub( 'text' );
$regions = telson_sub( 'regions' );
if ( empty( $regions ) ) {
	return;
}
?>
<section class="section region-list section--dark">
	<div class="container container--wide">
		<div class="section-head">
			<?php if ( $eyebrow ) : ?>
				<p class="eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
			<?php endif; ?>
			<?php if ( $heading ) : ?>
				<h2 class="section-title"><?php echo esc_html( $heading ); ?></h2>
			<?php endif; ?>
			<?php if ( $text ) : ?>
				<p class="section-lede"><?php echo esc_html( $text ); ?></p>
			<?php endif; ?>
		</div>
	</div>
	<ul class="region-list__items">
		<?php foreach ( $regions as $region ) : ?>
			<?php
			$name  = $region['name'] ?? '';
			$rtext = $region['text'] ?? '';
			$link  = $region['link'] ?? null;
			$image = $region['background_image'] ?? null;
			if ( ! $name ) {
				continue;
			}
			?>
			<li class="region-list__item">
				<?php if ( $image ) : ?>
					<div class="region-list__media"><?php telson_image( $image, 'telson-banner', array( 'loading' => 'lazy' ) ); ?></div>
				<?php endif; ?>
				<div class="region-list__body container container--wide">
					<h3 class="region-list__name"><?php echo esc_html( $name ); ?></h3>
					<?php if ( $rtext ) : ?>
						<p><?php echo esc_html( $rtext ); ?></p>
					<?php endif; ?>
					<?php if ( ! empty( $link['url'] ) ) : ?>
						<p class="region-list__cta"><?php telson_link( $link, 'btn btn--outline' ); ?></p>
					<?php endif; ?>
				</div>
			</li>
		<?php endforeach; ?>
	</ul>
</section>
