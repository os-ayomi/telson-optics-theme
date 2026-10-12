<?php
/**
 * Section: full-width promotional banner.
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

$image   = telson_sub( 'background_image' );
$eyebrow = telson_sub( 'eyebrow' );
$heading = telson_sub( 'heading' );
$text    = telson_sub( 'text' );
$cta     = telson_sub( 'cta' );
$cta2    = telson_sub( 'cta_secondary' );
$align   = telson_sub( 'align', 'left' );
?>
<section class="section banner banner--<?php echo esc_attr( $align ); ?>">
	<?php if ( $image ) : ?>
		<div class="banner__media"><?php telson_image( $image, 'telson-banner', array( 'loading' => 'lazy' ) ); ?></div>
	<?php endif; ?>
	<div class="banner__inner container">
		<?php if ( $eyebrow ) : ?>
			<p class="eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
		<?php endif; ?>
		<?php if ( $heading ) : ?>
			<h2 class="section-title"><?php echo esc_html( $heading ); ?></h2>
		<?php endif; ?>
		<?php if ( $text ) : ?>
			<p><?php echo esc_html( $text ); ?></p>
		<?php endif; ?>
		<?php if ( $cta || $cta2 ) : ?>
			<div class="banner__actions">
				<?php telson_link( $cta, 'btn btn--primary' ); ?>
				<?php telson_link( $cta2, 'btn btn--outline' ); ?>
			</div>
		<?php endif; ?>
	</div>
</section>
