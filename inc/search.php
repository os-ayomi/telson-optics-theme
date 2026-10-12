<?php
/**
 * Live search REST endpoint (products + content). Read-only, published content only.
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'rest_api_init',
	static function (): void {
		register_rest_route(
			'telson/v1',
			'/search',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => 'telson_rest_search',
				'permission_callback' => '__return_true',
				'args'                => array(
					'q' => array(
						'required'          => true,
						'type'              => 'string',
						'sanitize_callback' => 'sanitize_text_field',
						'validate_callback' => static function ( $value ): bool {
							return is_string( $value ) && mb_strlen( trim( $value ) ) >= 2 && mb_strlen( $value ) <= 80;
						},
					),
				),
			)
		);
	}
);

/**
 * @param WP_REST_Request $request Request.
 * @return WP_REST_Response
 */
function telson_rest_search( WP_REST_Request $request ): WP_REST_Response {
	$term     = $request->get_param( 'q' );
	$products = array();
	$content  = array();

	if ( telson_is_woocommerce_active() ) {
		$found = wc_get_products(
			array(
				's'          => $term,
				'limit'      => 6,
				'status'     => 'publish',
				'visibility' => 'search',
				'orderby'    => 'relevance',
			)
		);
		foreach ( $found as $product ) {
			$products[] = array(
				'title' => wp_strip_all_tags( $product->get_name() ),
				'url'   => get_permalink( $product->get_id() ),
				'price' => '' === $product->get_price() ? '' : html_entity_decode( wp_strip_all_tags( wc_price( (float) $product->get_price() ) ), ENT_QUOTES ),
				'image' => get_the_post_thumbnail_url( $product->get_id(), 'thumbnail' ) ?: '',
			);
		}
	}

	$posts = get_posts(
		array(
			's'                   => $term,
			'post_type'           => array( 'page', 'post', 'pro-staff' ),
			'post_status'         => 'publish',
			'posts_per_page'      => 4,
			'ignore_sticky_posts' => true,
			'has_password'        => false,
		)
	);
	foreach ( $posts as $post ) {
		$content[] = array(
			'title' => wp_strip_all_tags( get_the_title( $post ) ),
			'url'   => get_permalink( $post ),
		);
	}

	return new WP_REST_Response(
		array(
			'products' => $products,
			'content'  => $content,
			'all'      => add_query_arg( 's', rawurlencode( $term ), home_url( '/' ) ),
		)
	);
}
