<?php
/**
 * Section: feature list (heading, text, bullets) with a photo collage.
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

$heading = telson_sub( 'heading' );
$text    = telson_sub( 'text' );
$bullets = telson_sub( 'bullets' );
$images  = telson_sub( 'images' );
?>
<section class="section feature-collage">
	<div class="container container--wide feature-collage__inner">
		<div class="feature-collage__body">
			<?php if ( $heading ) : ?>
				<h2 class="section-title"><?php echo esc_html( $heading ); ?></h2>
			<?php endif; ?>
			<?php if ( $text ) : ?>
				<p><?php echo esc_html( $text ); ?></p>
			<?php endif; ?>
			<?php if ( ! empty( $bullets ) ) : ?>
				<ul class="feature-collage__list">
					<?php foreach ( $bullets as $bullet ) : ?>
						<?php if ( empty( $bullet['text'] ) ) : continue; endif; ?>
						<li><?php telson_icon( 'check' ); ?><span><?php echo esc_html( $bullet['text'] ); ?></span></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
		<?php if ( ! empty( $images ) ) : ?>
			<div class="feature-collage__media">
				<?php foreach ( $images as $image ) : ?>
					<span class="feature-collage__img"><?php telson_image( $image, 'medium', array( 'loading' => 'lazy' ) ); ?></span>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
