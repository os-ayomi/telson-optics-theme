<?php
/**
 * Search form.
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

$telson_uid = wp_unique_id( 'search-' );
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $telson_uid ); ?>"><?php esc_html_e( 'Search for:', 'telson-optics' ); ?></label>
	<input type="search" id="<?php echo esc_attr( $telson_uid ); ?>" class="search-form__input" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search scopes, mounts, gear…', 'telson-optics' ); ?>" autocomplete="off">
	<button type="submit" class="btn"><?php esc_html_e( 'Search', 'telson-optics' ); ?></button>
</form>
