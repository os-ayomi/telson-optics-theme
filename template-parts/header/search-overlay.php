<?php
/**
 * Search overlay with live results (REST: /wp-json/telson/v1/search).
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="search-overlay" id="search-overlay" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Search', 'telson-optics' ); ?>" hidden>
	<div class="search-overlay__panel">
		<button class="icon-btn search-overlay__close" type="button" data-search-close>
			<?php telson_icon( 'close' ); ?>
			<span class="screen-reader-text"><?php esc_html_e( 'Close search', 'telson-optics' ); ?></span>
		</button>
		<form role="search" method="get" class="search-form search-form--overlay" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<label class="screen-reader-text" for="overlay-search"><?php esc_html_e( 'Search for:', 'telson-optics' ); ?></label>
			<input type="search" id="overlay-search" name="s" class="search-form__input" placeholder="<?php esc_attr_e( 'Search scopes, mounts, gear…', 'telson-optics' ); ?>" autocomplete="off" aria-controls="search-results" data-search-input>
			<button type="submit" class="btn"><?php esc_html_e( 'Search', 'telson-optics' ); ?></button>
		</form>
		<div class="search-overlay__results" id="search-results" aria-live="polite" data-search-results></div>
	</div>
</div>
