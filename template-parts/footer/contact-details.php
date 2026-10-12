<?php
/**
 * Footer contact details (Telson Settings → Contact).
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

$phone   = telson_option( 'phone' );
$email   = telson_option( 'email' );
$address = telson_option( 'address' );

if ( ! $phone && ! $email && ! $address ) {
	return;
}
?>
<address class="contact-details">
	<?php if ( $phone ) : ?>
		<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
	<?php endif; ?>
	<?php if ( $email ) : ?>
		<a href="mailto:<?php echo esc_attr( antispambot( $email ) ); ?>"><?php echo esc_html( antispambot( $email ) ); ?></a>
	<?php endif; ?>
	<?php if ( $address ) : ?>
		<span><?php echo nl2br( esc_html( $address ) ); ?></span>
	<?php endif; ?>
</address>
