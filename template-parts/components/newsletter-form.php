<?php
/**
 * Newsletter form component.
 *
 * @param array $args {
 *     @type string $context     'footer' or 'section'.
 *     @type string $heading     Override heading.
 *     @type string $description Override description.
 * }
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

$context     = $args['context'] ?? 'footer';
$heading     = $args['heading'] ?? telson_option( 'newsletter_heading', __( 'Join the Bold', 'telson-optics' ) );
$description = $args['description'] ?? telson_option( 'newsletter_description' );
$shortcode   = telson_option( 'newsletter_shortcode' );
$options     = telson_option( 'newsletter_experience_levels', array() );
// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- display-only flag.
$status      = isset( $_GET['newsletter'] ) ? sanitize_key( wp_unslash( $_GET['newsletter'] ) ) : '';
$uid         = wp_unique_id( 'nl-' );
?>
<section class="newsletter newsletter--<?php echo esc_attr( $context ); ?>" aria-labelledby="<?php echo esc_attr( $uid ); ?>-title">
	<h2 class="site-footer__title" id="<?php echo esc_attr( $uid ); ?>-title"><?php echo esc_html( $heading ); ?></h2>
	<?php if ( $description ) : ?>
		<p class="newsletter__desc"><?php echo esc_html( $description ); ?></p>
	<?php endif; ?>

	<?php if ( $shortcode ) : ?>
		<?php echo do_shortcode( wp_kses_post( $shortcode ) ); ?>
	<?php else : ?>
		<?php if ( 'success' === $status ) : ?>
			<p class="notice notice--success" role="status"><?php esc_html_e( 'Thanks for signing up.', 'telson-optics' ); ?></p>
		<?php elseif ( in_array( $status, array( 'error', 'invalid' ), true ) ) : ?>
			<p class="notice notice--error" role="alert"><?php esc_html_e( 'Please check your details and try again.', 'telson-optics' ); ?></p>
		<?php endif; ?>
		<form class="newsletter__form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="telson_newsletter">
			<?php wp_nonce_field( 'telson_newsletter', 'telson_newsletter_nonce' ); ?>
			<div class="field-row">
				<div class="field">
					<label for="<?php echo esc_attr( $uid ); ?>-first"><?php esc_html_e( 'First name', 'telson-optics' ); ?> <span aria-hidden="true">*</span></label>
					<input id="<?php echo esc_attr( $uid ); ?>-first" type="text" name="first_name" autocomplete="given-name" required>
				</div>
				<div class="field">
					<label for="<?php echo esc_attr( $uid ); ?>-last"><?php esc_html_e( 'Last name', 'telson-optics' ); ?></label>
					<input id="<?php echo esc_attr( $uid ); ?>-last" type="text" name="last_name" autocomplete="family-name">
				</div>
			</div>
			<div class="field">
				<label for="<?php echo esc_attr( $uid ); ?>-email"><?php esc_html_e( 'Email', 'telson-optics' ); ?> <span aria-hidden="true">*</span></label>
				<input id="<?php echo esc_attr( $uid ); ?>-email" type="email" name="email" autocomplete="email" required>
			</div>
			<?php if ( ! empty( $options ) && is_array( $options ) ) : ?>
				<div class="field">
					<label for="<?php echo esc_attr( $uid ); ?>-exp"><?php esc_html_e( 'Level of experience', 'telson-optics' ); ?></label>
					<select id="<?php echo esc_attr( $uid ); ?>-exp" name="experience">
						<?php foreach ( $options as $option ) : ?>
							<?php if ( ! empty( $option['label'] ) ) : ?>
								<option value="<?php echo esc_attr( $option['label'] ); ?>"><?php echo esc_html( $option['label'] ); ?></option>
							<?php endif; ?>
						<?php endforeach; ?>
					</select>
				</div>
			<?php endif; ?>
			<div class="hp-field" aria-hidden="true">
				<label for="<?php echo esc_attr( $uid ); ?>-web">Website</label>
				<input id="<?php echo esc_attr( $uid ); ?>-web" type="text" name="website" tabindex="-1" autocomplete="off">
			</div>
			<button class="btn btn--primary" type="submit"><?php esc_html_e( 'Sign me up', 'telson-optics' ); ?></button>
		</form>
	<?php endif; ?>
</section>
