<?php
/**
 * Section: customer testimonials (scroll-snap carousel).
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

$heading = telson_sub( 'heading' );
$items   = get_sub_field( 'items' );
$theme   = telson_sub( 'theme', 'dark' );
if ( empty( $items ) ) {
	return;
}
?>
<section class="section testimonials<?php echo 'dark' === $theme ? ' section--dark' : ''; ?>">
	<div class="container container--wide">
		<?php if ( $heading ) : ?>
			<h2 class="section-title"><?php echo esc_html( $heading ); ?></h2>
		<?php endif; ?>
		<div class="carousel" data-carousel>
			<ul class="carousel__track testimonial-track" data-carousel-track>
				<?php foreach ( $items as $item ) : ?>
					<li class="carousel__slide testimonial">
						<figure>
							<?php $rating = (int) ( $item['rating'] ?? 5 ); ?>
							<?php if ( $rating > 0 ) : ?>
								<div class="static-stars static-stars--<?php echo esc_attr( (string) min( 5, $rating ) ); ?>" role="img" aria-label="<?php echo esc_attr( sprintf( /* translators: %d: rating out of 5 */ __( 'Rated %d out of 5', 'telson-optics' ), $rating ) ); ?>"></div>
							<?php endif; ?>
							<?php if ( ! empty( $item['title'] ) ) : ?>
								<figcaption class="testimonial__title"><?php echo esc_html( $item['title'] ); ?></figcaption>
							<?php endif; ?>
							<blockquote><p><?php echo esc_html( $item['quote'] ?? '' ); ?></p></blockquote>
							<p class="testimonial__author"><?php echo esc_html( $item['author'] ?? '' ); ?></p>
						</figure>
					</li>
				<?php endforeach; ?>
			</ul>
			<div class="carousel__controls">
				<button class="icon-btn carousel__btn" type="button" data-carousel-prev><?php telson_icon( 'chevron', 'icon icon--prev' ); ?><span class="screen-reader-text"><?php esc_html_e( 'Previous', 'telson-optics' ); ?></span></button>
				<button class="icon-btn carousel__btn" type="button" data-carousel-next><?php telson_icon( 'chevron', 'icon icon--next' ); ?><span class="screen-reader-text"><?php esc_html_e( 'Next', 'telson-optics' ); ?></span></button>
			</div>
		</div>
	</div>
</section>
