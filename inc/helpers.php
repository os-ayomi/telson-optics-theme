<?php
/**
 * Small, dependency-free helpers used across templates.
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

/**
 * Whether ACF (Pro) field functions are available.
 */
function telson_has_acf(): bool {
	return function_exists( 'get_field' );
}

/**
 * Read a field from the global ACF options page.
 *
 * @param string $name    Field name.
 * @param mixed  $default Fallback when ACF is missing or the field is empty.
 * @return mixed
 */
function telson_option( string $name, $default = '' ) {
	if ( ! telson_has_acf() ) {
		return $default;
	}
	$value = get_field( $name, 'option' );
	return ( null === $value || '' === $value || array() === $value ) ? $default : $value;
}

/**
 * Read a field from a post (defaults to the current post).
 *
 * @param string   $name    Field name.
 * @param int|null $post_id Post ID.
 * @return mixed
 */
function telson_field( string $name, ?int $post_id = null ) {
	return telson_has_acf() ? get_field( $name, $post_id ?: false ) : null;
}

/**
 * Output an attachment image using WordPress responsive image functions.
 *
 * @param int|array|null      $image Attachment ID or ACF image array.
 * @param string              $size  Registered image size.
 * @param array<string,mixed> $attr  Extra attributes.
 */
function telson_image( $image, string $size = 'large', array $attr = array() ): void {
	$id = is_array( $image ) ? ( $image['ID'] ?? 0 ) : absint( $image );
	if ( ! $id ) {
		return;
	}
	echo wp_get_attachment_image( $id, $size, false, $attr ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Render an ACF link array as a styled anchor.
 *
 * @param array|null $link  ACF link array (url, title, target).
 * @param string     $class CSS classes.
 */
function telson_link( $link, string $class = 'btn' ): void {
	if ( empty( $link['url'] ) ) {
		return;
	}
	$target = ! empty( $link['target'] ) ? $link['target'] : '_self';
	printf(
		'<a class="%1$s" href="%2$s" target="%3$s"%4$s>%5$s</a>',
		esc_attr( $class ),
		esc_url( $link['url'] ),
		esc_attr( $target ),
		'_blank' === $target ? ' rel="noopener noreferrer"' : '',
		esc_html( $link['title'] ?? '' )
	);
}

/**
 * Inline SVG icons (24px grid, stroke-based) so no icon font or request is needed.
 *
 * @param string $name  Icon key.
 * @param string $class CSS class on the svg.
 */
function telson_icon( string $name, string $class = 'icon' ): void {
	$paths = array(
		'search'    => '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>',
		'user'      => '<circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 3.6-7 8-7s8 3 8 7"/>',
		'cart'      => '<path d="M3 4h2.5l2.2 11h10.6L20 7H6.4"/><circle cx="9.5" cy="19.5" r="1.4"/><circle cx="17" cy="19.5" r="1.4"/>',
		'menu'      => '<path d="M4 7h16M4 12h16M4 17h16"/>',
		'close'     => '<path d="m6 6 12 12M18 6 6 18"/>',
		'chevron'   => '<path d="m6 9 6 6 6-6"/>',
		'arrow'     => '<path d="M5 12h14m-6-6 6 6-6 6"/>',
		'filter'    => '<path d="M4 6h16M7 12h10M10 18h4"/>',
		'instagram' => '<rect x="4" y="4" width="16" height="16" rx="4"/><circle cx="12" cy="12" r="3.5"/><circle cx="17" cy="7" r=".6"/>',
		'facebook'  => '<path d="M14 8h3V4h-3a4 4 0 0 0-4 4v3H7v4h3v6h4v-6h3l1-4h-4V8z"/>',
		'youtube'   => '<rect x="3" y="6" width="18" height="12" rx="3"/><path d="m10 9.5 5 2.5-5 2.5z"/>',
		'x'         => '<path d="m4 4 16 16M20 4 4 20"/>',
		'tiktok'    => '<path d="M14 4v10.5a3.5 3.5 0 1 1-3.5-3.5M14 4c.3 2.5 2 4 4.5 4.2"/>',
		'linkedin'  => '<rect x="4" y="9" width="3.5" height="11"/><circle cx="5.75" cy="5.5" r="1.75"/><path d="M11 9h3.4v1.6c.6-1 1.8-1.9 3.6-1.9 3 0 3.5 2 3.5 4.5V20H18v-6c0-1.4-.2-2.6-1.7-2.6S14.5 12.6 14.5 14V20H11z"/>',
		'check'     => '<path d="m5 12.5 4.5 4.5L19 7"/>',
		'star'      => '<path d="M12 3.5l2.6 5.4 5.9.7-4.3 4.1 1.1 5.9-5.3-2.9-5.3 2.9 1.1-5.9L3.5 9.6l5.9-.7z" fill="currentColor" stroke="none"/>',
		'star-outline' => '<path d="M12 3.5l2.6 5.4 5.9.7-4.3 4.1 1.1 5.9-5.3-2.9-5.3 2.9 1.1-5.9L3.5 9.6l5.9-.7z"/>',
	);
	if ( ! isset( $paths[ $name ] ) ) {
		return;
	}
	printf(
		'<svg class="%1$s icon--%2$s" aria-hidden="true" focusable="false" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">%3$s</svg>',
		esc_attr( $class ),
		esc_attr( $name ),
		$paths[ $name ] // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static markup defined above.
	);
}

/**
 * Render the configured social links (ACF options repeater).
 *
 * @param string $class Wrapper class.
 */
function telson_social_links( string $class = 'social-links' ): void {
	$links = telson_option( 'social_links', array() );
	if ( empty( $links ) || ! is_array( $links ) ) {
		return;
	}
	echo '<ul class="' . esc_attr( $class ) . '">';
	foreach ( $links as $item ) {
		if ( empty( $item['url'] ) || empty( $item['network'] ) ) {
			continue;
		}
		$label = ucfirst( $item['network'] );
		echo '<li><a href="' . esc_url( $item['url'] ) . '" target="_blank" rel="noopener noreferrer">';
		telson_icon( $item['network'] );
		echo '<span class="screen-reader-text">' . esc_html( $label ) . '</span></a></li>';
	}
	echo '</ul>';
}

/**
 * Render WooCommerce's real add-to-cart button for a given product, so
 * marketing pages (like a model showcase) can link straight into the cart
 * instead of just linking to the product page.
 *
 * @param int    $product_id WooCommerce product ID.
 * @param string $class      CSS classes.
 */
function telson_add_to_cart_button( int $product_id, string $class = 'btn btn--primary' ): void {
	if ( ! $product_id || ! telson_is_woocommerce_active() ) {
		return;
	}
	global $product;
	$product = wc_get_product( $product_id );
	if ( ! $product ) {
		return;
	}
	printf( '<a href="%1$s" data-quantity="1" class="%2$s %3$s" data-product_id="%4$d" data-product_sku="%5$s" aria-label="%6$s" rel="nofollow">%7$s</a>', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- esc_* applied below via sprintf args.
		esc_url( $product->add_to_cart_url() ),
		esc_attr( $class ),
		esc_attr( $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart add_to_cart_button' : '' ),
		esc_attr( $product->get_id() ),
		esc_attr( $product->get_sku() ),
		esc_attr( $product->add_to_cart_description() ),
		esc_html( $product->add_to_cart_text() )
	);
}

/**
 * Site logo: ACF option, then WordPress custom logo, then text wordmark.
 *
 * @param string $option ACF option field holding the logo.
 */
function telson_logo( string $option = 'logo' ): void {
	$logo = telson_option( $option );
	echo '<a class="site-logo" href="' . esc_url( home_url( '/' ) ) . '" rel="home">';
	if ( $logo ) {
		telson_image( $logo, 'medium', array( 'class' => 'site-logo__img', 'alt' => get_bloginfo( 'name' ), 'loading' => 'eager', 'fetchpriority' => 'high' ) );
	} elseif ( has_custom_logo() ) {
		echo wp_get_attachment_image( (int) get_theme_mod( 'custom_logo' ), 'medium', false, array( 'class' => 'site-logo__img', 'loading' => 'eager' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	} else {
		echo '<span class="site-logo__text">' . esc_html( get_bloginfo( 'name' ) ) . '</span>';
	}
	echo '</a>';
}

/**
 * Sanitize a list of slugs from untrusted input. Non-scalar values (e.g. nested
 * arrays in a crafted query string) are dropped instead of raising errors.
 *
 * @param mixed $input Raw value, typically from $_GET.
 * @return string[]
 */
function telson_clean_slugs( $input ): array {
	if ( ! is_array( $input ) ) {
		return array();
	}
	$slugs = array_map( 'sanitize_title', array_map( 'strval', array_filter( $input, 'is_scalar' ) ) );
	return array_values( array_filter( $slugs ) );
}

/**
 * Whether WooCommerce is active.
 */
function telson_is_woocommerce_active(): bool {
	return class_exists( 'WooCommerce' );
}
