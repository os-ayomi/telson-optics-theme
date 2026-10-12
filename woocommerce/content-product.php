<?php
/**
 * Override of woocommerce/templates/content-product.php.
 * Delegates to the shared product card so markup exists in one place only.
 *
 * @package Telson_Optics
 * @version 9.4.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>
<li <?php wc_product_class( '', $product ); ?>>
	<?php get_template_part( 'template-parts/products/product-card' ); ?>
</li>
