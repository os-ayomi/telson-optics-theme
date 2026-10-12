<?php
/**
 * Section: category tiles.
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

$heading = telson_sub( 'heading' );
$style   = telson_sub( 'style', 'overlay' );
$tiles   = get_sub_field( 'tiles' );
if ( empty( $tiles ) ) {
	return;
}
$is_plain = 'plain' === $style;
?>
<section class="section category-tiles category-tiles--<?php echo esc_attr( $style ); ?>">
	<div class="container container--wide">
		<?php if ( $heading ) : ?>
			<h2 class="section-title"><?php echo esc_html( $heading ); ?></h2>
		<?php endif; ?>
		<div class="<?php echo $is_plain ? 'carousel' : ''; ?>" <?php echo $is_plain ? 'data-carousel' : ''; ?>>
			<ul class="tile-grid<?php echo $is_plain ? ' tile-grid--plain carousel__track' : ''; ?>" <?php echo $is_plain ? 'data-carousel-track' : ''; ?>>
				<?php foreach ( $tiles as $tile ) : ?>
					<?php
					$link = $tile['link'] ?? null;
					if ( empty( $link['url'] ) ) {
						continue;
					}
					?>
					<li class="tile<?php echo $is_plain ? ' carousel__slide' : ''; ?>">
						<a class="tile__link" href="<?php echo esc_url( $link['url'] ); ?>" <?php echo ( ! empty( $link['target'] ) && '_blank' === $link['target'] ) ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
							<?php if ( ! empty( $tile['image'] ) ) : ?>
								<span class="tile__media"><?php telson_image( $tile['image'], 'telson-tile', array( 'loading' => 'lazy' ) ); ?></span>
							<?php endif; ?>
							<span class="tile__label"><?php echo esc_html( $link['title'] ); ?></span>
							<?php if ( ! $is_plain ) : ?>
								<span class="tile__cta"><?php esc_html_e( 'View', 'telson-optics' ); ?> <?php telson_icon( 'arrow' ); ?></span>
							<?php endif; ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
			<?php if ( $is_plain && count( $tiles ) > 3 ) : ?>
				<div class="carousel__controls">
					<button class="icon-btn carousel__btn" type="button" data-carousel-prev><?php telson_icon( 'chevron', 'icon icon--prev' ); ?><span class="screen-reader-text"><?php esc_html_e( 'Previous', 'telson-optics' ); ?></span></button>
					<button class="icon-btn carousel__btn" type="button" data-carousel-next><?php telson_icon( 'chevron', 'icon icon--next' ); ?><span class="screen-reader-text"><?php esc_html_e( 'Next', 'telson-optics' ); ?></span></button>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
