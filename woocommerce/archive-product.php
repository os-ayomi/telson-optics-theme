<?php
/**
 * Override of woocommerce/templates/archive-product.php.
 * Adds the filter sidebar, toolbar and promise strip. Loop output still goes
 * through WooCommerce hooks and content-product.php.
 *
 * @package Telson_Optics
 * @version 8.6.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

do_action( 'woocommerce_before_main_content' );
?>
<header class="shop-header">
	<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
		<h1 class="page-title"><?php woocommerce_page_title(); ?></h1>
	<?php endif; ?>
	<?php do_action( 'woocommerce_archive_description' ); ?>
</header>

<div class="shop-layout">
	<?php get_template_part( 'template-parts/products/filters' ); ?>

	<div class="shop-layout__results">
		<?php if ( woocommerce_product_loop() ) : ?>
			<div class="shop-toolbar">
				<button class="btn btn--outline shop-toolbar__filters" type="button" data-filters-open aria-controls="shop-filters">
					<?php telson_icon( 'filter' ); ?>
					<?php esc_html_e( 'Filters', 'telson-optics' ); ?>
				</button>
				<?php
				do_action( 'woocommerce_before_shop_loop' );
				?>
			</div>

			<?php
			woocommerce_product_loop_start();

			if ( wc_get_loop_prop( 'total' ) ) {
				while ( have_posts() ) {
					the_post();
					do_action( 'woocommerce_shop_loop' );
					wc_get_template_part( 'content', 'product' );
				}
			}

			woocommerce_product_loop_end();

			do_action( 'woocommerce_after_shop_loop' );
			?>
		<?php else : ?>
			<?php do_action( 'woocommerce_no_products_found' ); ?>
		<?php endif; ?>
	</div>
</div>

<?php get_template_part( 'template-parts/products/promise-strip' ); ?>

<?php
do_action( 'woocommerce_after_main_content' );

get_footer( 'shop' );
