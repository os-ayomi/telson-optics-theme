<?php
/**
 * Gravity Forms integration.
 *
 * The theme's own newsletter and support-box fields accept a shortcode
 * (see template-parts/components/newsletter-form.php and Telson Settings →
 * Product page), and any page — Contact Us, Pro Staff Application — can hold
 * a Gravity Forms shortcode directly in its content. This file only makes
 * the rendered form match the theme instead of Gravity Forms' own styling.
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'GFForms' ) ) {
	return;
}

// The theme's stylesheet owns every form's appearance; stop Gravity Forms
// from also enqueueing its theme CSS (orbital/foundation/framework/reset).
add_filter( 'gform_disable_css', '__return_true' );

add_filter( 'gform_default_field_label_placement', static fn(): string => 'above' );
add_filter( 'gform_default_description_placement', static fn(): string => 'below' );

add_action(
	'wp_enqueue_scripts',
	static function (): void {
		wp_enqueue_style(
			'telson-gravity-forms',
			TELSON_URI . '/assets/css/gravity-forms.css',
			array( 'telson-base' ),
			telson_asset_version( 'assets/css/gravity-forms.css' )
		);
	},
	20
);
