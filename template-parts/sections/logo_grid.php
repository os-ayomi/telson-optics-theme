<?php
/**
 * Section: logo grid (retailers, partners, "as seen in").
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

$heading = telson_sub( 'heading' );
$logos   = get_sub_field( 'logos' );
if ( empty( $logos ) ) {
	return;
}
?>
<section class="section logo-grid">
	<div class="container container--wide">
		<?php if ( $heading ) : ?>
			<h2 class="section-title"><?php echo esc_html( $heading ); ?></h2>
		<?php endif; ?>
		<ul class="logo-grid__list">
			<?php foreach ( $logos as $row ) : ?>
				<li>
					<?php if ( ! empty( $row['link']['url'] ) ) : ?>
						<a href="<?php echo esc_url( $row['link']['url'] ); ?>" target="_blank" rel="noopener noreferrer"><?php telson_image( $row['logo'] ?? null, 'medium', array( 'loading' => 'lazy' ) ); ?></a>
					<?php else : ?>
						<?php telson_image( $row['logo'] ?? null, 'medium', array( 'loading' => 'lazy' ) ); ?>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
