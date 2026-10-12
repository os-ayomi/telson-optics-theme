<?php
/**
 * Reusable WooCommerce product card. Used by shop, categories, search,
 * related products, cross-sells and the homepage featured products section.
 *
 * Expects the global $product (set by the WooCommerce loop or wc_setup_product_data()).
 *
 * @param array $args { @type string $heading_tag Heading element, default h2. }
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product instanceof WC_Product ) {
	return;
}

$heading_tag = $args['heading_tag'] ?? 'h2';
if ( ! in_array( $heading_tag, array( 'h2', 'h3', 'h4' ), true ) ) {
	$heading_tag = 'h2';
}
$badge_text  = telson_product_badge_text( $product->get_id() );
$permalink   = get_permalink( $product->get_id() );
?>
<article class="product-card">
	<a class="product-card__media" href="<?php echo esc_url( $permalink ); ?>" tabindex="-1" aria-hidden="true">
		<?php echo $product->get_image( 'woocommerce_thumbnail', array( 'class' => 'product-card__img', 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WooCommerce image markup. ?>
	</a>

	<?php if ( $badge_text || $product->is_on_sale() ) : ?>
		<div class="product-card__badges">
			<?php if ( $badge_text ) : ?>
				<span class="badge"><?php echo esc_html( $badge_text ); ?></span>
			<?php endif; ?>
			<?php if ( $product->is_on_sale() ) : ?>
				<span class="badge badge--sale"><?php esc_html_e( 'Sale', 'telson-optics' ); ?></span>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<div class="product-card__body">
		<<?php echo esc_html( $heading_tag ); ?> class="product-card__title">
			<a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $product->get_name() ); ?></a>
		</<?php echo esc_html( $heading_tag ); ?>>

		<?php if ( wc_review_ratings_enabled() && $product->get_average_rating() > 0 ) : ?>
			<div class="product-card__rating"><?php echo wc_get_rating_html( $product->get_average_rating(), $product->get_rating_count() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
		<?php endif; ?>

		<div class="product-card__price price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>
	</div>

	<div class="product-card__action">
		<?php woocommerce_template_loop_add_to_cart(); ?>
	</div>
</article>
