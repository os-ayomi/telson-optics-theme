<?php
/**
 * 404 template.
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main" class="site-main">
	<div class="container container--narrow not-found">
		<p class="eyebrow">404</p>
		<h1 class="page-title"><?php esc_html_e( 'Off target', 'telson-optics' ); ?></h1>
		<p><?php esc_html_e( 'The page you are looking for has moved or no longer exists. Try a search or head back to the shop.', 'telson-optics' ); ?></p>
		<?php get_search_form(); ?>
		<p>
			<a class="btn" href="<?php echo esc_url( telson_is_woocommerce_active() ? wc_get_page_permalink( 'shop' ) : home_url( '/' ) ); ?>"><?php esc_html_e( 'Shop now', 'telson-optics' ); ?></a>
		</p>
	</div>
</main>
<?php
get_footer();
