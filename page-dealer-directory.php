<?php
/**
 * Template Name: Dealer Directory
 *
 * Matches the live telsonoptics.com "Find a Dealer" page: a searchable dealer
 * list beside a live interactive Google Map, with no other page content.
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

get_header();

$api_key = telson_option( 'google_maps_api_key' );

$dealers = get_posts(
	array(
		'post_type'      => 'dealer',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => 'title',
		'order'          => 'ASC',
	)
);

$dealers_data = array();
foreach ( $dealers as $dealer ) {
	$address = telson_field( 'address', $dealer->ID );
	$dealers_data[] = array(
		'id'      => $dealer->ID,
		'name'    => $dealer->post_title,
		'address' => $address ? wp_kses_post( $address ) : '',
		'phone'   => telson_field( 'phone', $dealer->ID ),
		'lat'     => telson_field( 'lat', $dealer->ID ),
		'lng'     => telson_field( 'lng', $dealer->ID ),
	);
}
?>
<main id="main" class="site-main site-main--dealer-locator">
	<div id="dealer-locator" class="dealer-locator">
		<div class="dealer-locator__sidebar">
			<div class="dealer-locator__search">
				<label class="screen-reader-text" for="dealer-search"><?php esc_html_e( 'Search dealers', 'telson-optics' ); ?></label>
				<input type="search" id="dealer-search" data-dealer-search placeholder="<?php esc_attr_e( 'Search by name or city…', 'telson-optics' ); ?>">
			</div>
			<p class="dealer-locator__count" data-dealer-count></p>
			<ul class="dealer-locator__list" data-dealer-list></ul>
		</div>
		<div class="dealer-locator__map">
			<?php if ( $api_key ) : ?>
				<div class="dealer-locator__map-canvas" data-dealer-map></div>
			<?php else : ?>
				<div class="dealer-locator__map-fallback">
					<p><?php esc_html_e( 'Map unavailable: no Google Maps API key configured.', 'telson-optics' ); ?></p>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<script type="application/json" id="dealer-locator-data"><?php echo wp_json_encode( $dealers_data ); ?></script>
</main>
<?php
get_footer();
