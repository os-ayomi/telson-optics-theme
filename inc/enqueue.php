<?php
/**
 * Asset registration and conditional loading.
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

/**
 * Versioned asset URL helper (cache-busts by file modification time).
 *
 * @param string $path Path relative to the theme root.
 * @return string
 */
function telson_asset_version( string $path ): string {
	$file = TELSON_DIR . '/' . ltrim( $path, '/' );
	return file_exists( $file ) ? (string) filemtime( $file ) : TELSON_VERSION;
}

add_action(
	'wp_enqueue_scripts',
	static function (): void {
		wp_enqueue_style(
			'telson-fonts',
			'https://fonts.googleapis.com/css2?family=Goldman:wght@400;700&family=Oswald:wght@300;400;500;600&display=swap',
			array(),
			null // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
		);

		$styles = array(
			'telson-base'       => 'assets/css/base.css',
			'telson-components' => 'assets/css/components.css',
		);
		$previous = array( 'telson-fonts' );
		foreach ( $styles as $handle => $path ) {
			wp_enqueue_style( $handle, TELSON_URI . '/' . $path, $previous, telson_asset_version( $path ) );
			$previous = array( $handle );
		}

		if ( is_page() || is_front_page() || is_post_type_archive( 'pro-staff' ) || is_singular( 'pro-staff' ) ) {
			wp_enqueue_style( 'telson-sections', TELSON_URI . '/assets/css/sections.css', array( 'telson-components' ), telson_asset_version( 'assets/css/sections.css' ) );
		}

		if ( telson_is_woocommerce_active() ) {
			wp_enqueue_style( 'telson-woocommerce', TELSON_URI . '/assets/css/woocommerce.css', array( 'telson-components' ), telson_asset_version( 'assets/css/woocommerce.css' ) );
			// Cart fragments keep the header count and cart drawer in sync everywhere.
			wp_enqueue_script( 'wc-cart-fragments' );
		}

		if ( is_page_template( 'page-dealer-directory.php' ) ) {
			wp_enqueue_script( 'telson-dealer-map', TELSON_URI . '/assets/js/dealer-map.js', array(), telson_asset_version( 'assets/js/dealer-map.js' ), array( 'strategy' => 'defer', 'in_footer' => true ) );

			$maps_api_key = telson_option( 'google_maps_api_key' );
			if ( $maps_api_key ) {
				wp_enqueue_script(
					'telson-google-maps',
					add_query_arg(
						array(
							'key'      => $maps_api_key,
							'callback' => 'telsonInitDealerMap',
							'loading'  => 'async',
						),
						'https://maps.googleapis.com/maps/api/js'
					),
					array( 'telson-dealer-map' ),
					null, // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
					array( 'strategy' => 'defer', 'in_footer' => true )
				);
			}
		}

		$scripts = array( 'core', 'search' );
		if ( telson_is_woocommerce_active() ) {
			$scripts[] = 'cart';
			$scripts[] = 'shop';
		}
		if ( is_front_page() || is_page() ) {
			$scripts[] = 'sections';
		}

		foreach ( $scripts as $name ) {
			$path   = 'assets/js/' . $name . '.js';
			$handle = 'telson-' . $name;
			wp_enqueue_script(
				$handle,
				TELSON_URI . '/' . $path,
				'cart' === $name ? array( 'jquery' ) : array(),
				telson_asset_version( $path ),
				array( 'strategy' => 'defer', 'in_footer' => true )
			);
		}

		wp_localize_script(
			'telson-core',
			'telsonData',
			array(
				'restUrl'   => esc_url_raw( rest_url( 'telson/v1/' ) ),
				'ageGate'   => (bool) telson_option( 'age_gate_enabled', false ),
				'ageExit'   => esc_url( telson_option( 'age_gate_exit_url', 'https://www.google.com/' ) ),
				'ageDays'   => 30,
				'i18n'      => array(
					'noResults'   => __( 'No results found.', 'telson-optics' ),
					'searching'   => __( 'Searching…', 'telson-optics' ),
					'products'    => __( 'Products', 'telson-optics' ),
					'pages'       => __( 'Pages & articles', 'telson-optics' ),
					'addedToCart' => __( 'Added to cart', 'telson-optics' ),
					'decrease'    => __( 'Decrease quantity', 'telson-optics' ),
					'increase'    => __( 'Increase quantity', 'telson-optics' ),
				),
			)
		);
	}
);

add_filter(
	'wp_resource_hints',
	static function ( array $urls, string $relation ): array {
		if ( 'preconnect' === $relation ) {
			$urls[] = 'https://fonts.googleapis.com';
			$urls[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' => 'anonymous' );
		}
		return $urls;
	},
	10,
	2
);

// The theme ships its own WooCommerce styling; drop the default layout sheets.
add_filter(
	'woocommerce_enqueue_styles',
	static function ( array $styles ): array {
		unset( $styles['woocommerce-layout'], $styles['woocommerce-smallscreen'], $styles['woocommerce-general'] );
		return $styles;
	}
);
