<?php
/**
 * Age verification modal (Telson Settings → Age gate). Remembered via a first-party cookie.
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

$heading = telson_option( 'age_gate_heading', __( 'Age verification', 'telson-optics' ) );
$message = telson_option( 'age_gate_message', __( 'Our products are intended for adults. By entering this website, you confirm that you are at least 18 years of age.', 'telson-optics' ) );
$yes     = telson_option( 'age_gate_confirm_label', __( 'Yes, I am 18+', 'telson-optics' ) );
$no      = telson_option( 'age_gate_deny_label', __( 'No, I am under 18', 'telson-optics' ) );
?>
<div class="age-gate" id="age-gate" role="dialog" aria-modal="true" aria-labelledby="age-gate-title" hidden>
	<div class="age-gate__panel">
		<?php telson_logo( 'footer_logo' ); ?>
		<h2 class="age-gate__title" id="age-gate-title"><?php echo esc_html( $heading ); ?></h2>
		<p><?php echo esc_html( $message ); ?></p>
		<div class="age-gate__actions">
			<button class="btn btn--primary" type="button" data-age-confirm><?php echo esc_html( $yes ); ?></button>
			<button class="btn btn--outline-light" type="button" data-age-deny><?php echo esc_html( $no ); ?></button>
		</div>
	</div>
</div>
