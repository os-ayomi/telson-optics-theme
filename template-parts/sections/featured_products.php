<?php
/**
 * Section: featured products (manual pick, category, latest or on sale).
 * Cards come from the shared product-card component.
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

if ( ! telson_is_woocommerce_active() ) {
	return;
}

$heading     = telson_sub( 'heading' );
$description = telson_sub( 'description' );
$source      = telson_sub( 'source', 'manual' );
$count       = max( 1, min( 24, (int) telson_sub( 'count', 8 ) ) );
$layout      = telson_sub( 'layout', 'carousel' );
$view_all    = get_sub_field( 'view_all' );

$args = array(
	'status'  => 'publish',
	'limit'   => $count,
	'return'  => 'objects',
	'orderby' => 'date',
	'order'   => 'DESC',
);

if ( 'manual' === $source ) {
	$ids = wp_list_pluck( (array) get_sub_field( 'products' ), 'ID' );
	if ( empty( $ids ) ) {
		return;
	}
	$args['include'] = array_map( 'absint', $ids );
	$args['orderby'] = 'post__in';
} elseif ( 'category' === $source ) {
	$term = get_sub_field( 'category' );
	$term = is_object( $term ) ? $term : get_term( (int) $term, 'product_cat' );
	if ( ! $term || is_wp_error( $term ) ) {
		return;
	}
	$args['category'] = array( $term->slug );
} elseif ( 'on_sale' === $source ) {
	$args['include'] = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
}

$products = wc_get_products( $args );
if ( empty( $products ) ) {
	return;
}
?>
<section class="section featured-products featured-products--<?php echo esc_attr( $layout ); ?>">
	<div class="container container--wide">
		<div class="section-head">
			<?php if ( $heading ) : ?>
				<h2 class="section-title"><?php echo esc_html( $heading ); ?></h2>
			<?php endif; ?>
			<?php if ( $description ) : ?>
				<p class="section-lede"><?php echo esc_html( $description ); ?></p>
			<?php endif; ?>
		</div>

		<div class="carousel" data-carousel>
			<ul class="products product-grid carousel__track" data-carousel-track>
				<?php
				foreach ( $products as $featured ) {
					$GLOBALS['product'] = $featured; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- read by WooCommerce loop functions.
					echo '<li class="product carousel__slide">';
					get_template_part( 'template-parts/products/product-card', null, array( 'heading_tag' => 'h3' ) );
					echo '</li>';
				}
				unset( $GLOBALS['product'] );
				?>
			</ul>
			<?php if ( 'carousel' === $layout && count( $products ) > 1 ) : ?>
				<div class="carousel__controls">
					<button class="icon-btn carousel__btn" type="button" data-carousel-prev><?php telson_icon( 'chevron', 'icon icon--prev' ); ?><span class="screen-reader-text"><?php esc_html_e( 'Previous', 'telson-optics' ); ?></span></button>
					<button class="icon-btn carousel__btn" type="button" data-carousel-next><?php telson_icon( 'chevron', 'icon icon--next' ); ?><span class="screen-reader-text"><?php esc_html_e( 'Next', 'telson-optics' ); ?></span></button>
				</div>
			<?php endif; ?>
		</div>

		<?php if ( ! empty( $view_all['url'] ) ) : ?>
			<p class="section-actions"><?php telson_link( $view_all, 'btn btn--outline' ); ?></p>
		<?php endif; ?>
	</div>
</section>
