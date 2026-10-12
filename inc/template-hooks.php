<?php
/**
 * Global template hooks: overlays rendered in the footer, and small filters.
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'wp_footer',
	static function (): void {
		get_template_part( 'template-parts/header/search-overlay' );
		if ( telson_is_woocommerce_active() ) {
			get_template_part( 'template-parts/header/cart-drawer' );
		}
		if ( telson_option( 'age_gate_enabled', false ) ) {
			get_template_part( 'template-parts/components/age-gate' );
		}
	},
	5
);

add_filter(
	'excerpt_length',
	static function (): int {
		return 24;
	}
);

add_filter(
	'excerpt_more',
	static function (): string {
		return '…';
	}
);

/**
 * Newsletter fallback handler (used only when no form shortcode is configured).
 */
add_action( 'admin_post_nopriv_telson_newsletter', 'telson_handle_newsletter' );
add_action( 'admin_post_telson_newsletter', 'telson_handle_newsletter' );

function telson_handle_newsletter(): void {
	$redirect = wp_get_referer() ?: home_url( '/' );

	if ( ! isset( $_POST['telson_newsletter_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['telson_newsletter_nonce'] ) ), 'telson_newsletter' ) ) {
		wp_safe_redirect( add_query_arg( 'newsletter', 'error', $redirect ) );
		exit;
	}

	// Honeypot: real visitors never fill this in.
	if ( ! empty( $_POST['website'] ) ) {
		wp_safe_redirect( add_query_arg( 'newsletter', 'success', $redirect ) );
		exit;
	}

	$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	if ( ! is_email( $email ) ) {
		wp_safe_redirect( add_query_arg( 'newsletter', 'invalid', $redirect ) );
		exit;
	}

	$first      = isset( $_POST['first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['first_name'] ) ) : '';
	$last       = isset( $_POST['last_name'] ) ? sanitize_text_field( wp_unslash( $_POST['last_name'] ) ) : '';
	$experience = isset( $_POST['experience'] ) ? sanitize_text_field( wp_unslash( $_POST['experience'] ) ) : '';

	$to      = telson_option( 'newsletter_recipient', get_option( 'admin_email' ) );
	$subject = sprintf( /* translators: %s: site name */ __( 'New newsletter signup – %s', 'telson-optics' ), get_bloginfo( 'name' ) );
	$body    = sprintf( "%s %s\n%s\n%s", $first, $last, $email, $experience );

	wp_mail( sanitize_email( $to ), $subject, $body, array( 'Reply-To: ' . $email ) );

	wp_safe_redirect( add_query_arg( 'newsletter', 'success', $redirect ) );
	exit;
}
