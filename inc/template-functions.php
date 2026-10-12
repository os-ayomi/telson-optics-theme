<?php
/**
 * Reusable template tags.
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

/**
 * Breadcrumbs. Defers to Rank Math / Yoast when active so schema stays consistent.
 */
function telson_breadcrumbs(): void {
	if ( is_front_page() ) {
		return;
	}

	$plugin_output = '';
	if ( function_exists( 'rank_math_the_breadcrumbs' ) ) {
		ob_start();
		rank_math_the_breadcrumbs();
		$plugin_output = trim( (string) ob_get_clean() );
	} elseif ( function_exists( 'yoast_breadcrumb' ) ) {
		ob_start();
		yoast_breadcrumb( '<p>', '</p>' );
		$plugin_output = trim( (string) ob_get_clean() );
	} elseif ( telson_is_woocommerce_active() && ( is_woocommerce() || is_cart() || is_checkout() ) ) {
		ob_start();
		woocommerce_breadcrumb();
		$plugin_output = trim( (string) ob_get_clean() );
	}

	echo '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'telson-optics' ) . '">';
	if ( $plugin_output ) {
		echo $plugin_output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted plugin breadcrumb markup.
	} else {
		echo '<ol class="breadcrumbs__list"><li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'telson-optics' ) . '</a></li>';
		if ( is_singular() ) {
			echo '<li aria-current="page">' . esc_html( get_the_title() ) . '</li>';
		} elseif ( is_post_type_archive() ) {
			echo '<li aria-current="page">' . esc_html( post_type_archive_title( '', false ) ) . '</li>';
		} elseif ( is_search() ) {
			echo '<li aria-current="page">' . esc_html__( 'Search', 'telson-optics' ) . '</li>';
		} elseif ( is_archive() ) {
			echo '<li aria-current="page">' . esc_html( wp_strip_all_tags( get_the_archive_title() ) ) . '</li>';
		}
		echo '</ol>';
	}
	echo '</nav>';
}

/**
 * Accessible numbered pagination for the main query.
 */
function telson_pagination(): void {
	$links = paginate_links(
		array(
			'type'      => 'list',
			'prev_text' => '<span aria-hidden="true">&larr;</span><span class="screen-reader-text">' . esc_html__( 'Previous page', 'telson-optics' ) . '</span>',
			'next_text' => '<span aria-hidden="true">&rarr;</span><span class="screen-reader-text">' . esc_html__( 'Next page', 'telson-optics' ) . '</span>',
		)
	);
	if ( $links ) {
		echo '<nav class="pagination" aria-label="' . esc_attr__( 'Pagination', 'telson-optics' ) . '">' . wp_kses_post( $links ) . '</nav>';
	}
}

/**
 * Cart item count (0 when WooCommerce is inactive).
 */
function telson_cart_count(): int {
	return ( telson_is_woocommerce_active() && WC()->cart ) ? (int) WC()->cart->get_cart_contents_count() : 0;
}

/**
 * Page title band used by generic templates.
 *
 * @param string $title    Heading text.
 * @param string $subtitle Optional eyebrow text.
 */
function telson_page_header( string $title, string $subtitle = '' ): void {
	get_template_part( 'template-parts/components/page-header', null, array( 'title' => $title, 'subtitle' => $subtitle ) );
}
