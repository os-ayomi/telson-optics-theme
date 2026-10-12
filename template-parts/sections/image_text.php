<?php
/**
 * Section: image + text (image on either side, optional background image).
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

$image    = telson_sub( 'image' );
$position = telson_sub( 'image_position', 'left' );
$eyebrow  = telson_sub( 'eyebrow' );
$heading  = telson_sub( 'heading' );
$text     = telson_sub( 'text' );
$stats    = telson_sub( 'stats' );
$cta      = telson_sub( 'cta' );
$bg       = telson_sub( 'background_image' );
$dark     = $bg || 'dark' === telson_sub( 'theme', 'light' );
?>
<section class="section image-text image-text--<?php echo esc_attr( $position ); ?><?php echo $bg ? ' has-bg' : ''; ?><?php echo $dark ? ' section--dark' : ''; ?>">
	<?php if ( $bg ) : ?>
		<div class="image-text__bg"><?php telson_image( $bg, 'telson-banner', array( 'loading' => 'lazy' ) ); ?></div>
	<?php endif; ?>
	<div class="container container--wide image-text__inner">
		<?php if ( $image ) : ?>
			<div class="image-text__media"><?php telson_image( $image, 'large', array( 'loading' => 'lazy' ) ); ?></div>
		<?php endif; ?>
		<div class="image-text__body">
			<?php if ( $eyebrow ) : ?>
				<p class="eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
			<?php endif; ?>
			<?php if ( $heading ) : ?>
				<h2 class="section-title"><?php echo esc_html( $heading ); ?></h2>
			<?php endif; ?>
			<?php if ( $text ) : ?>
				<div class="prose"><?php echo wp_kses_post( $text ); ?></div>
			<?php endif; ?>
			<?php if ( ! empty( $stats ) ) : ?>
				<dl class="image-text__stats">
					<?php foreach ( $stats as $stat ) : ?>
						<?php if ( empty( $stat['value'] ) && empty( $stat['label'] ) ) : continue; endif; ?>
						<div class="image-text__stat">
							<dd><?php echo esc_html( $stat['value'] ?? '' ); ?></dd>
							<dt><?php echo esc_html( $stat['label'] ?? '' ); ?></dt>
						</div>
					<?php endforeach; ?>
				</dl>
			<?php endif; ?>
			<?php telson_link( $cta, 'btn btn--primary' ); ?>
		</div>
	</div>
</section>
