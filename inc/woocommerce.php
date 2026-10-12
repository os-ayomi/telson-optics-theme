<?php
/**
 * WooCommerce integration. Customisation is done through hooks wherever possible;
 * the only template overrides are documented in README.md
 * (archive-product.php and content-product.php).
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

if ( ! telson_is_woocommerce_active() ) {
	return;
}

/**
 * Newer WooCommerce installs create Cart and Checkout as Gutenberg blocks.
 * Swap them for the classic shortcodes on activation so the theme (not the
 * block editor) controls those layouts. Only pages still holding the default
 * block markup are touched.
 */
function telson_use_classic_cart_and_checkout(): void {
	$map = array(
		'cart'     => array( 'wp:woocommerce/cart', '[woocommerce_cart]' ),
		'checkout' => array( 'wp:woocommerce/checkout', '[woocommerce_checkout]' ),
	);
	foreach ( $map as $page => $definition ) {
		$page_id = wc_get_page_id( $page );
		$post    = $page_id > 0 ? get_post( $page_id ) : null;
		if ( $post && false !== strpos( $post->post_content, $definition[0] ) ) {
			wp_update_post( array( 'ID' => $page_id, 'post_content' => $definition[1] ) );
		}
	}
}
add_action( 'after_switch_theme', 'telson_use_classic_cart_and_checkout' );

/* ------------------------------------------------------------------------- *
 * Global layout
 * ------------------------------------------------------------------------- */

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

add_action(
	'woocommerce_before_main_content',
	static function (): void {
		echo '<main id="main" class="site-main site-main--shop"><div class="container container--wide">';
		telson_breadcrumbs();
	},
	10
);

add_action(
	'woocommerce_after_main_content',
	static function (): void {
		echo '</div></main>';
	},
	10
);

add_filter( 'loop_shop_columns', static fn(): int => 4 );
add_filter( 'loop_shop_per_page', static fn(): int => 16 );

add_filter(
	'woocommerce_get_image_size_thumbnail',
	static fn(): array => array( 'width' => 600, 'height' => 600, 'crop' => 1 )
);

add_filter(
	'woocommerce_product_loop_start',
	static fn(): string => '<ul class="products product-grid">'
);

add_filter(
	'woocommerce_sale_flash',
	static fn(): string => '<span class="badge badge--sale">' . esc_html__( 'Sale', 'telson-optics' ) . '</span>'
);

add_filter(
	'woocommerce_loop_add_to_cart_args',
	static function ( array $args ): array {
		$args['class'] = trim( ( $args['class'] ?? '' ) . ' btn btn--block' );
		return $args;
	}
);

add_filter( 'woocommerce_pagination_args', static function ( array $args ): array {
	$args['prev_text'] = '<span aria-hidden="true">&larr;</span><span class="screen-reader-text">' . esc_html__( 'Previous page', 'telson-optics' ) . '</span>';
	$args['next_text'] = '<span aria-hidden="true">&rarr;</span><span class="screen-reader-text">' . esc_html__( 'Next page', 'telson-optics' ) . '</span>';
	return $args;
} );

/* ------------------------------------------------------------------------- *
 * Header cart fragments (count + subtotal) and drawer content
 * ------------------------------------------------------------------------- */

add_filter(
	'woocommerce_add_to_cart_fragments',
	static function ( array $fragments ): array {
		$count = telson_cart_count();

		$fragments['span.telson-cart-count'] = sprintf(
			'<span class="telson-cart-count" data-count="%1$d">%1$d</span>',
			$count
		);
		$fragments['span.telson-cart-total'] = '<span class="telson-cart-total">' . wp_kses_post( WC()->cart->get_cart_subtotal() ) . '</span>';

		return $fragments;
	}
);

/* ------------------------------------------------------------------------- *
 * Shop filters (real WooCommerce queries, no client-side faking)
 * ------------------------------------------------------------------------- */

/**
 * Apply category / attribute filters submitted by the shop filter form.
 * Price uses WooCommerce's native min_price / max_price handling.
 *
 * @param WP_Query $query Product query.
 */
add_action(
	'woocommerce_product_query',
	static function ( WP_Query $query ): void {
		// phpcs:disable WordPress.Security.NonceVerification.Recommended -- read-only public filter.
		$tax_query = (array) $query->get( 'tax_query' );

		$cat_slugs = isset( $_GET['telson_cat'] ) ? telson_clean_slugs( wp_unslash( $_GET['telson_cat'] ) ) : array();
		if ( $cat_slugs ) {
			$tax_query[] = array(
				'taxonomy'         => 'product_cat',
				'field'            => 'slug',
				'terms'            => $cat_slugs,
				'operator'         => 'IN',
				'include_children' => true,
			);
		}

		if ( isset( $_GET['telson_attr'] ) && is_array( $_GET['telson_attr'] ) ) {
			foreach ( wp_unslash( $_GET['telson_attr'] ) as $attribute => $terms ) {
				$taxonomy = wc_attribute_taxonomy_name( sanitize_title( (string) $attribute ) );
				$slugs    = taxonomy_exists( $taxonomy ) ? telson_clean_slugs( $terms ) : array();
				if ( $slugs ) {
					$tax_query[] = array(
						'taxonomy' => $taxonomy,
						'field'    => 'slug',
						'terms'    => $slugs,
						'operator' => 'IN',
					);
				}
			}
		}
		// phpcs:enable

		if ( count( $tax_query ) > 1 ) {
			$tax_query['relation'] = 'AND';
		}
		$query->set( 'tax_query', $tax_query );
	}
);

/**
 * Lowest / highest catalogue price, cached.
 *
 * @return array{min:int,max:int}
 */
function telson_get_price_range(): array {
	$cached = get_transient( 'telson_price_range' );
	if ( is_array( $cached ) ) {
		return $cached;
	}
	global $wpdb;
	$row   = $wpdb->get_row( "SELECT MIN(min_price) AS min, MAX(max_price) AS max FROM {$wpdb->wc_product_meta_lookup} WHERE min_price > 0" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
	$range = array(
		'min' => $row ? (int) floor( (float) $row->min ) : 0,
		'max' => $row ? (int) ceil( (float) $row->max ) : 0,
	);
	set_transient( 'telson_price_range', $range, HOUR_IN_SECONDS );
	return $range;
}

add_action(
	'woocommerce_update_product',
	static function (): void {
		delete_transient( 'telson_price_range' );
	}
);

/* ------------------------------------------------------------------------- *
 * Single product
 * ------------------------------------------------------------------------- */

remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

// Rating sits under the title; price follows the short description as on the live site.
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 25 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 45 );

add_action( 'woocommerce_after_single_product_summary', 'telson_pairs_well_with', 5 );
add_action( 'woocommerce_after_single_product_summary', 'telson_product_details_sections', 10 );
add_action( 'woocommerce_after_single_product_summary', 'telson_product_reviews_section', 15 );
add_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

add_filter( 'woocommerce_product_related_products_heading', static fn(): string => __( 'Recommended products', 'telson-optics' ) );
add_filter(
	'woocommerce_output_related_products_args',
	static function ( array $args ): array {
		$args['posts_per_page'] = 4;
		$args['columns']        = 4;
		return $args;
	}
);

/**
 * "Pairs well with": products chosen as WooCommerce upsells, with one-click add.
 */
function telson_pairs_well_with(): void {
	global $product;
	if ( ! $product instanceof WC_Product ) {
		return;
	}
	$upsell_ids = $product->get_upsell_ids();
	if ( empty( $upsell_ids ) ) {
		return;
	}
	$main_product = $product;
	echo '<section class="pairs-well container" aria-labelledby="pairs-well-title"><h2 id="pairs-well-title" class="section-title">' . esc_html__( 'Pairs well with', 'telson-optics' ) . '</h2><ul class="pairs-well__list">';
	foreach ( $upsell_ids as $upsell_id ) {
		$item = wc_get_product( $upsell_id );
		if ( ! $item || ! $item->is_visible() ) {
			continue;
		}
		$product = $item; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- WooCommerce loop functions read this global.
		echo '<li class="pairs-well__item">';
		echo '<a class="pairs-well__thumb" href="' . esc_url( get_permalink( $item->get_id() ) ) . '" tabindex="-1" aria-hidden="true">' . $item->get_image( 'woocommerce_gallery_thumbnail' ) . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<div class="pairs-well__info"><a class="pairs-well__title" href="' . esc_url( get_permalink( $item->get_id() ) ) . '">' . esc_html( $item->get_name() ) . '</a>';
		if ( $item->get_sku() ) {
			echo '<span class="pairs-well__sku">' . esc_html__( 'SKU:', 'telson-optics' ) . ' ' . esc_html( $item->get_sku() ) . '</span>';
		}
		echo '<span class="price">' . wp_kses_post( $item->get_price_html() ) . '</span></div>';
		woocommerce_template_loop_add_to_cart();
		echo '</li>';
	}
	echo '</ul></section>';
	$product = $main_product; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
}

/**
 * Key features, specification table, PDF and support call-to-action.
 */
function telson_product_details_sections(): void {
	global $product;
	if ( ! $product instanceof WC_Product ) {
		return;
	}

	$features = telson_field( 'key_features', $product->get_id() );
	$specs    = telson_field( 'specifications', $product->get_id() );
	$pdf      = telson_field( 'spec_pdf', $product->get_id() );
	$content  = $product->get_description();

	echo '<section class="product-details container">';

	if ( $features ) {
		echo '<div class="product-details__block"><h2 class="section-title">' . esc_html__( 'Key features', 'telson-optics' ) . '</h2><div class="prose prose--features">' . wp_kses_post( $features ) . '</div></div>';
	}

	if ( ! empty( $specs ) && is_array( $specs ) ) {
		echo '<div class="product-details__block"><h2 class="section-title">' . esc_html__( 'Specification', 'telson-optics' ) . '</h2><dl class="spec-list">';
		foreach ( $specs as $row ) {
			if ( empty( $row['label'] ) ) {
				continue;
			}
			echo '<div class="spec-list__row"><dt>' . esc_html( $row['label'] ) . '</dt><dd>' . esc_html( $row['value'] ?? '' ) . '</dd></div>';
		}
		echo '</dl>';
		if ( ! empty( $pdf['url'] ) ) {
			echo '<p><a class="btn btn--outline" href="' . esc_url( $pdf['url'] ) . '" target="_blank" rel="noopener">' . esc_html__( 'Download PDF specification', 'telson-optics' ) . '</a></p>';
		}
		echo '</div>';
	} elseif ( $product->has_attributes() ) {
		echo '<div class="product-details__block"><h2 class="section-title">' . esc_html__( 'Specification', 'telson-optics' ) . '</h2>';
		wc_display_product_attributes( $product );
		echo '</div>';
	}

	if ( $content ) {
		echo '<div class="product-details__block product-details__block--wide"><h2 class="section-title">' . esc_html__( 'Description', 'telson-optics' ) . '</h2><div class="prose">' . apply_filters( 'the_content', $content ) . '</div></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the_content filter output.
	}

	$support_heading = telson_option( 'support_heading' );
	$support_link    = telson_option( 'support_link' );
	if ( $support_heading && $support_link ) {
		echo '<aside class="support-cta"><h3>' . esc_html( $support_heading ) . '</h3>';
		$support_text = telson_option( 'support_text' );
		if ( $support_text ) {
			echo '<p>' . esc_html( $support_text ) . '</p>';
		}
		telson_link( $support_link, 'btn' );
		echo '</aside>';
	}

	echo '</section>';
}

/**
 * Reviews (WooCommerce native comments template, so review submission still works).
 */
function telson_product_reviews_section(): void {
	if ( ! comments_open() ) {
		return;
	}
	echo '<section class="product-reviews container" id="reviews-section">';
	comments_template();
	echo '</section>';
}

/* ------------------------------------------------------------------------- *
 * Product badge (ACF override of the "New" ribbon)
 * ------------------------------------------------------------------------- */

/**
 * Custom badge text set on the product edit screen (e.g. "New").
 *
 * @param int $product_id Product ID.
 */
function telson_product_badge_text( int $product_id ): string {
	$text = telson_field( 'badge_text', $product_id );
	return is_string( $text ) ? $text : '';
}
