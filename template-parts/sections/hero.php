<?php
/**
 * Section: hero.
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

$image     = telson_sub( 'background_image' );
$mobile    = telson_sub( 'background_mobile' );
$kicker    = telson_sub( 'kicker' );
$heading   = telson_sub( 'heading' );
$text      = telson_sub( 'description' );
$cta       = telson_sub( 'cta' );
$cta2      = telson_sub( 'cta_secondary' );
$show_crumbs = ! is_front_page();
?>
<section class="section hero">
	<?php if ( $image ) : ?>
		<picture class="hero__media">
			<?php if ( $mobile ) : ?>
				<source media="(max-width: 767px)" srcset="<?php echo esc_url( wp_get_attachment_image_url( is_array( $mobile ) ? $mobile['ID'] : $mobile, 'large' ) ); ?>">
			<?php endif; ?>
			<?php telson_image( $image, 'telson-hero', array( 'class' => 'hero__img', 'loading' => 'eager', 'fetchpriority' => 'high', 'sizes' => '100vw' ) ); ?>
		</picture>
	<?php endif; ?>
	<div class="hero__inner container">
		<?php if ( $show_crumbs ) : ?>
			<div class="hero__breadcrumbs">
				<?php telson_breadcrumbs(); ?>
			</div>
		<?php endif; ?>
		<?php if ( $kicker ) : ?>
			<p class="hero__kicker"><?php echo esc_html( $kicker ); ?></p>
		<?php endif; ?>
		<?php if ( $heading ) : ?>
			<h1 class="hero__title"><?php echo esc_html( $heading ); ?></h1>
		<?php endif; ?>
		<?php if ( $text ) : ?>
			<p class="hero__text"><?php echo esc_html( $text ); ?></p>
		<?php endif; ?>
		<?php if ( $cta || $cta2 ) : ?>
			<div class="hero__actions">
				<?php telson_link( $cta, 'btn btn--primary' ); ?>
				<?php telson_link( $cta2, 'btn btn--outline' ); ?>
			</div>
		<?php endif; ?>
	</div>
</section>
