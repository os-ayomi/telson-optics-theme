<?php
/**
 * Section: free-form content block.
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

$heading = telson_sub( 'heading' );
$content = telson_sub( 'content' );
$width   = telson_sub( 'width', 'narrow' );
$theme   = telson_sub( 'theme', 'light' );
?>
<section class="section content-section<?php echo 'dark' === $theme ? ' section--dark' : ''; ?>">
	<div class="container container--<?php echo esc_attr( $width ); ?>">
		<?php if ( $heading ) : ?>
			<h2 class="section-title"><?php echo esc_html( $heading ); ?></h2>
		<?php endif; ?>
		<?php if ( $content ) : ?>
			<div class="prose"><?php echo wp_kses_post( $content ); ?></div>
		<?php endif; ?>
	</div>
</section>
