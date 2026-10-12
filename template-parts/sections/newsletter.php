<?php
/**
 * Section: newsletter (same form as the footer, different heading).
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="section newsletter-section">
	<div class="container container--narrow">
		<?php
		get_template_part(
			'template-parts/components/newsletter-form',
			null,
			array(
				'context'     => 'section',
				'heading'     => telson_sub( 'heading', telson_option( 'newsletter_heading', __( 'Join the Bold', 'telson-optics' ) ) ),
				'description' => telson_sub( 'description', telson_option( 'newsletter_description' ) ),
			)
		);
		?>
	</div>
</section>
