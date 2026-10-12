<?php
/**
 * Footer newsletter block. Uses a form-plugin shortcode when configured
 * (Gravity Forms etc.), otherwise the built-in nonce-protected form.
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

get_template_part( 'template-parts/components/newsletter-form', null, array( 'context' => 'footer' ) );
