<?php
/**
 * ACF Pro integration: options page, JSON sync, flexible content renderer.
 *
 * Field groups are version-controlled in /acf-json.
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

add_filter(
	'acf/settings/save_json',
	static function (): string {
		return TELSON_DIR . '/acf-json';
	}
);

add_filter(
	'acf/settings/load_json',
	static function ( array $paths ): array {
		$paths[] = TELSON_DIR . '/acf-json';
		return $paths;
	}
);

add_action(
	'acf/init',
	static function (): void {
		if ( ! function_exists( 'acf_add_options_page' ) ) {
			return;
		}
		acf_add_options_page(
			array(
				'page_title' => __( 'Telson Optics Settings', 'telson-optics' ),
				'menu_title' => __( 'Telson Settings', 'telson-optics' ),
				'menu_slug'  => 'telson-settings',
				'capability' => 'manage_options',
				'icon_url'   => 'dashicons-visibility',
				'position'   => 59,
				'redirect'   => false,
			)
		);
	}
);

/**
 * Let editors choose which WooCommerce attributes appear in the shop filter.
 */
add_filter(
	'acf/load_field/name=filter_attributes',
	static function ( array $field ): array {
		$field['choices'] = array();
		if ( function_exists( 'wc_get_attribute_taxonomies' ) ) {
			foreach ( wc_get_attribute_taxonomies() as $attribute ) {
				$field['choices'][ $attribute->attribute_name ] = $attribute->attribute_label;
			}
		}
		return $field;
	}
);

/**
 * Render a flexible-content field by delegating each layout to
 * template-parts/sections/{layout}.php.
 *
 * @param string         $field   Flexible content field name.
 * @param int|false|null $post_id Post ID.
 * @return bool True when at least one layout rendered.
 */
function telson_render_sections( string $field = 'sections', $post_id = false ): bool {
	if ( ! telson_has_acf() || ! have_rows( $field, $post_id ) ) {
		return false;
	}
	while ( have_rows( $field, $post_id ) ) {
		the_row();
		$layout = get_row_layout();
		$slug   = sanitize_key( (string) $layout );
		if ( $slug && locate_template( 'template-parts/sections/' . $slug . '.php' ) ) {
			get_template_part( 'template-parts/sections/' . $slug );
		}
	}
	return true;
}

/**
 * Read a sub field of the current flexible-content row.
 *
 * @param string $name    Sub field name.
 * @param mixed  $default Fallback value.
 * @return mixed
 */
function telson_sub( string $name, $default = '' ) {
	$value = get_sub_field( $name );
	return ( null === $value || '' === $value || false === $value ) ? $default : $value;
}

add_action(
	'admin_notices',
	static function (): void {
		if ( telson_has_acf() || ! current_user_can( 'activate_plugins' ) ) {
			return;
		}
		printf(
			'<div class="notice notice-warning"><p>%s</p></div>',
			esc_html__( 'Telson Optics requires ACF Pro for the homepage sections, global settings and product spec fields.', 'telson-optics' )
		);
	}
);
