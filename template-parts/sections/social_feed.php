<?php
/**
 * Section: social feed (drop in any feed plugin shortcode, e.g. Smash Balloon).
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

$heading   = telson_sub( 'heading' );
$shortcode = telson_sub( 'shortcode' );
$link      = telson_sub( 'link' );
?>
<section class="section social-feed">
	<div class="container container--wide">
		<?php if ( $heading ) : ?>
			<h2 class="section-title"><?php echo esc_html( $heading ); ?></h2>
		<?php endif; ?>
		<?php if ( $shortcode ) : ?>
			<div class="social-feed__embed"><?php echo do_shortcode( wp_kses_post( $shortcode ) ); ?></div>
		<?php endif; ?>
		<?php if ( ! empty( $link['url'] ) ) : ?>
			<p class="section-actions"><?php telson_link( $link, 'btn btn--outline' ); ?></p>
		<?php endif; ?>
	</div>
</section>
