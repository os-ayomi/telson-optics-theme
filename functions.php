<?php
/**
 * Telson Optics theme bootstrap.
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

define( 'TELSON_VERSION', wp_get_theme()->get( 'Version' ) );
define( 'TELSON_DIR', get_template_directory() );
define( 'TELSON_URI', get_template_directory_uri() );

$telson_includes = array(
	'helpers',
	'setup',
	'enqueue',
	'post-types',
	'acf',
	'template-functions',
	'template-hooks',
	'search',
	'woocommerce',
	'gravity-forms',
	'currency',
);

foreach ( $telson_includes as $telson_include ) {
	require_once TELSON_DIR . '/inc/' . $telson_include . '.php';
}
