<?php
/**
 * Section: series showcase ("The Scope Family").
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

$eyebrow = telson_sub( 'eyebrow' );
$series  = get_sub_field( 'series' );
if ( empty( $series ) ) {
	return;
}
?>
<section class="section series-showcase">
	<div class="container container--wide">
		<?php if ( $eyebrow ) : ?>
			<p class="section-eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
		<?php endif; ?>
		<div class="series-grid">
			<?php foreach ( $series as $item ) : ?>
				<article class="series-card">
					<?php if ( ! empty( $item['image'] ) ) : ?>
						<div class="series-card__media"><?php telson_image( $item['image'], 'large', array( 'loading' => 'lazy' ) ); ?></div>
					<?php endif; ?>
					<div class="series-card__body">
						<h3 class="series-card__title"><?php echo esc_html( $item['title'] ?? '' ); ?></h3>
						<?php if ( ! empty( $item['description'] ) ) : ?>
							<p><?php echo esc_html( $item['description'] ); ?></p>
						<?php endif; ?>
						<?php if ( ! empty( $item['links'] ) ) : ?>
							<ul class="series-card__links">
								<?php foreach ( $item['links'] as $row ) : ?>
									<li><?php telson_link( $row['link'] ?? null, 'btn btn--outline' ); ?></li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
