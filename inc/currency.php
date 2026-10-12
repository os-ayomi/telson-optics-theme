<?php
/**
 * Display-only currency switcher, so a store that only needs an approximate
 * "shown in your currency" price doesn't need a dedicated multi-currency
 * plugin. It never touches the amount WooCommerce actually charges: the
 * store's real currency (WooCommerce → Settings → General) is always what
 * the cart totals, checkout and order use.
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

const TELSON_CURRENCY_COOKIE = 'telson_currency';

/**
 * Configured extra currencies, keyed by code.
 *
 * @return array<string,array{label:string,rate:float}>
 */
function telson_currencies(): array {
	if ( ! telson_option( 'currency_switcher_enabled', false ) ) {
		return array();
	}
	$rows = telson_option( 'currencies', array() );
	if ( ! is_array( $rows ) ) {
		return array();
	}
	$currencies = array();
	foreach ( $rows as $row ) {
		$code = isset( $row['code'] ) ? strtoupper( sanitize_text_field( $row['code'] ) ) : '';
		$rate = isset( $row['rate'] ) ? (float) $row['rate'] : 0.0;
		if ( 3 !== strlen( $code ) || $rate <= 0 ) {
			continue;
		}
		$currencies[ $code ] = array(
			'label' => $row['label'] ? sanitize_text_field( $row['label'] ) : $code,
			'rate'  => $rate,
		);
	}
	return $currencies;
}

/**
 * The visitor's chosen currency code, or the store's base currency.
 */
function telson_current_currency(): string {
	$base       = get_woocommerce_currency();
	$currencies = telson_currencies();
	if ( empty( $currencies ) ) {
		return $base;
	}
	$chosen = isset( $_COOKIE[ TELSON_CURRENCY_COOKIE ] ) ? strtoupper( sanitize_text_field( wp_unslash( $_COOKIE[ TELSON_CURRENCY_COOKIE ] ) ) ) : $base;
	return isset( $currencies[ $chosen ] ) ? $chosen : $base;
}

/**
 * Handle the switcher form (?telson_currency=CAD) with a redirect-after-set,
 * so the choice survives page caching without needing a session.
 */
add_action(
	'template_redirect',
	static function (): void {
		if ( ! isset( $_GET['telson_currency'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- idempotent, whitelisted values only.
			return;
		}
		$requested  = strtoupper( sanitize_text_field( wp_unslash( $_GET['telson_currency'] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$currencies = telson_currencies();
		$base       = get_woocommerce_currency();

		if ( $requested === $base || isset( $currencies[ $requested ] ) ) {
			$expire = ( $requested === $base ) ? time() - HOUR_IN_SECONDS : time() + MONTH_IN_SECONDS;
			setcookie( TELSON_CURRENCY_COOKIE, $requested, $expire, COOKIEPATH ?: '/', COOKIE_DOMAIN, is_ssl(), true );
		}

		wp_safe_redirect( remove_query_arg( 'telson_currency' ) );
		exit;
	}
);

/**
 * Convert a store-currency amount into the visitor's chosen currency.
 */
function telson_convert_price( float $amount ): float {
	$currencies = telson_currencies();
	$code       = telson_current_currency();
	if ( ! isset( $currencies[ $code ] ) ) {
		return $amount;
	}
	return $amount * $currencies[ $code ]['rate'];
}

/**
 * Append a converted estimate next to catalogue and cart prices. Checkout
 * keeps the real store-currency price html untouched.
 */
add_filter(
	'woocommerce_get_price_html',
	static function ( string $html, $product ): string {
		if ( is_checkout() || is_admin() ) {
			return $html;
		}
		$code = telson_current_currency();
		if ( $code === get_woocommerce_currency() ) {
			return $html;
		}
		$currencies = telson_currencies();
		$converted  = telson_convert_price( (float) $product->get_price() );
		if ( $converted <= 0 ) {
			return $html;
		}
		// Formatted as a plain number (not wc_price(), which would print the
		// store's own currency symbol) — the label already names the currency.
		$estimate = number_format( $converted, wc_get_price_decimals(), wc_get_price_decimal_separator(), wc_get_price_thousand_separator() );
		return $html . ' <span class="price-estimate">≈ ' . esc_html( $currencies[ $code ]['label'] ) . ' ' . esc_html( $estimate ) . '</span>';
	},
	10,
	2
);

/**
 * Render the switcher only when it is actually configured with a choice.
 */
function telson_currency_switcher(): void {
	$currencies = telson_currencies();
	if ( empty( $currencies ) ) {
		return;
	}
	$base    = get_woocommerce_currency();
	$current = telson_current_currency();
	$all     = array_merge( array( $base => array( 'label' => $base ) ), $currencies );
	?>
	<form class="currency-switcher" method="get" action="<?php echo esc_url( remove_query_arg( 'telson_currency' ) ); ?>">
		<label class="screen-reader-text" for="telson-currency"><?php esc_html_e( 'Currency', 'telson-optics' ); ?></label>
		<select id="telson-currency" name="telson_currency" onchange="this.form.submit()">
			<?php foreach ( $all as $code => $entry ) : ?>
				<option value="<?php echo esc_attr( $code ); ?>" <?php selected( $current, $code ); ?>><?php echo esc_html( $entry['label'] ); ?></option>
			<?php endforeach; ?>
		</select>
		<noscript><button type="submit" class="btn btn--outline"><?php esc_html_e( 'Go', 'telson-optics' ); ?></button></noscript>
	</form>
	<?php
}
