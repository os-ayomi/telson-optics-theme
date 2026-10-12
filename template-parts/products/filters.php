<?php
/**
 * Shop filters: category, attributes, price. Submits a plain GET request that
 * WooCommerce (and inc/woocommerce.php) turns into a real product query.
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

// phpcs:disable WordPress.Security.NonceVerification.Recommended -- read-only filter state.
$selected_cats  = isset( $_GET['telson_cat'] ) ? telson_clean_slugs( wp_unslash( $_GET['telson_cat'] ) ) : array();
$selected_attrs = array();
if ( isset( $_GET['telson_attr'] ) && is_array( $_GET['telson_attr'] ) ) {
	foreach ( wp_unslash( $_GET['telson_attr'] ) as $attribute_key => $attribute_terms ) {
		$selected_attrs[ sanitize_title( (string) $attribute_key ) ] = telson_clean_slugs( $attribute_terms );
	}
}
$orderby        = isset( $_GET['orderby'] ) ? sanitize_key( wp_unslash( $_GET['orderby'] ) ) : '';
$range          = telson_get_price_range();
$min_price      = isset( $_GET['min_price'] ) ? absint( $_GET['min_price'] ) : $range['min'];
$max_price      = isset( $_GET['max_price'] ) ? absint( $_GET['max_price'] ) : $range['max'];
// phpcs:enable

$base_url = is_product_taxonomy() && get_queried_object() instanceof WP_Term
	? get_term_link( get_queried_object() )
	: wc_get_page_permalink( 'shop' );

$categories = get_terms(
	array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => true,
		'exclude'    => array( (int) get_option( 'default_product_cat' ) ),
		'orderby'    => 'name',
	)
);

$allowed     = telson_option( 'filter_attributes', array() );
$attributes  = array();
foreach ( wc_get_attribute_taxonomies() as $attribute ) {
	if ( ! empty( $allowed ) && ! in_array( $attribute->attribute_name, (array) $allowed, true ) ) {
		continue;
	}
	$terms = get_terms( array( 'taxonomy' => wc_attribute_taxonomy_name( $attribute->attribute_name ), 'hide_empty' => true ) );
	if ( ! is_wp_error( $terms ) && $terms ) {
		$attributes[ $attribute->attribute_name ] = array( 'label' => $attribute->attribute_label, 'terms' => $terms );
	}
}

$active_count = count( $selected_cats ) + array_sum( array_map( 'count', $selected_attrs ) );
?>
<aside class="shop-filters" id="shop-filters" aria-label="<?php esc_attr_e( 'Product filters', 'telson-optics' ); ?>" data-shop-filters>
	<form class="shop-filters__form" method="get" action="<?php echo esc_url( $base_url ); ?>">
		<div class="shop-filters__head">
			<h2 class="shop-filters__title"><?php esc_html_e( 'Product filter', 'telson-optics' ); ?></h2>
			<button class="icon-btn shop-filters__close" type="button" data-filters-close>
				<?php telson_icon( 'close' ); ?>
				<span class="screen-reader-text"><?php esc_html_e( 'Close filters', 'telson-optics' ); ?></span>
			</button>
		</div>

		<?php if ( $orderby ) : ?>
			<input type="hidden" name="orderby" value="<?php echo esc_attr( $orderby ); ?>">
		<?php endif; ?>

		<?php if ( $range['max'] > $range['min'] ) : ?>
			<details class="filter-group" open>
				<summary><?php esc_html_e( 'Price', 'telson-optics' ); ?></summary>
				<div class="price-range" data-price-range data-min="<?php echo esc_attr( (string) $range['min'] ); ?>" data-max="<?php echo esc_attr( (string) $range['max'] ); ?>">
					<div class="price-range__values">
						<span><?php echo esc_html( get_woocommerce_currency_symbol() ); ?><output data-price-min-out><?php echo esc_html( (string) $min_price ); ?></output></span>
						<span aria-hidden="true">—</span>
						<span><?php echo esc_html( get_woocommerce_currency_symbol() ); ?><output data-price-max-out><?php echo esc_html( (string) $max_price ); ?></output></span>
					</div>
					<div class="price-range__sliders">
						<label class="screen-reader-text" for="filter-min-price"><?php esc_html_e( 'Minimum price', 'telson-optics' ); ?></label>
						<input id="filter-min-price" type="range" name="min_price" min="<?php echo esc_attr( (string) $range['min'] ); ?>" max="<?php echo esc_attr( (string) $range['max'] ); ?>" value="<?php echo esc_attr( (string) $min_price ); ?>" data-price-min>
						<label class="screen-reader-text" for="filter-max-price"><?php esc_html_e( 'Maximum price', 'telson-optics' ); ?></label>
						<input id="filter-max-price" type="range" name="max_price" min="<?php echo esc_attr( (string) $range['min'] ); ?>" max="<?php echo esc_attr( (string) $range['max'] ); ?>" value="<?php echo esc_attr( (string) $max_price ); ?>" data-price-max>
					</div>
				</div>
			</details>
		<?php endif; ?>

		<?php if ( ! is_wp_error( $categories ) && $categories ) : ?>
			<details class="filter-group" open>
				<summary><?php esc_html_e( 'Category', 'telson-optics' ); ?></summary>
				<ul class="filter-list">
					<?php foreach ( $categories as $category ) : ?>
						<li class="<?php echo $category->parent ? 'filter-list__child' : ''; ?>">
							<label>
								<input type="checkbox" name="telson_cat[]" value="<?php echo esc_attr( $category->slug ); ?>" <?php checked( in_array( $category->slug, $selected_cats, true ) ); ?>>
								<span><?php echo esc_html( $category->name ); ?></span>
							</label>
						</li>
					<?php endforeach; ?>
				</ul>
			</details>
		<?php endif; ?>

		<?php foreach ( $attributes as $slug => $group ) : ?>
			<?php $chosen = $selected_attrs[ $slug ] ?? array(); ?>
			<details class="filter-group" <?php echo $chosen ? 'open' : ''; ?>>
				<summary><?php echo esc_html( $group['label'] ); ?></summary>
				<ul class="filter-list">
					<?php foreach ( $group['terms'] as $term ) : ?>
						<li>
							<label>
								<input type="checkbox" name="telson_attr[<?php echo esc_attr( $slug ); ?>][]" value="<?php echo esc_attr( $term->slug ); ?>" <?php checked( in_array( $term->slug, $chosen, true ) ); ?>>
								<span><?php echo esc_html( $term->name ); ?></span>
							</label>
						</li>
					<?php endforeach; ?>
				</ul>
			</details>
		<?php endforeach; ?>

		<div class="shop-filters__actions">
			<button class="btn btn--primary btn--block" type="submit"><?php esc_html_e( 'Filter', 'telson-optics' ); ?></button>
			<?php if ( $active_count || isset( $_GET['min_price'] ) || isset( $_GET['max_price'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
				<a class="shop-filters__clear" href="<?php echo esc_url( $base_url ); ?>"><?php esc_html_e( 'Clear all', 'telson-optics' ); ?></a>
			<?php endif; ?>
		</div>
	</form>
</aside>
<div class="shop-filters__backdrop" data-filters-close hidden></div>
