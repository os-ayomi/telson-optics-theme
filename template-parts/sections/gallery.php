<?php
/**
 * Section: image gallery.
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

$heading = telson_sub( 'heading' );
$images  = get_sub_field( 'images' );
$columns = max( 2, min( 5, (int) telson_sub( 'columns', 3 ) ) );
if ( empty( $images ) ) {
	return;
}
?>
<section class="section gallery">
	<div class="container container--wide">
		<?php if ( $heading ) : ?>
			<h2 class="section-title"><?php echo esc_html( $heading ); ?></h2>
		<?php endif; ?>
		<ul class="gallery__grid gallery__grid--<?php echo esc_attr( (string) $columns ); ?>">
			<?php foreach ( $images as $image ) : ?>
				<li><?php telson_image( $image, 'telson-tile', array( 'loading' => 'lazy' ) ); ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
