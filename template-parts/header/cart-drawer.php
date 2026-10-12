<?php
/**
 * Cart drawer. WooCommerce owns the content via cart fragments.
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="cart-drawer" id="cart-drawer" role="dialog" aria-modal="true" aria-labelledby="cart-drawer-title" hidden>
	<div class="cart-drawer__backdrop" data-cart-close></div>
	<div class="cart-drawer__panel">
		<div class="cart-drawer__head">
			<h2 class="cart-drawer__title" id="cart-drawer-title"><?php esc_html_e( 'Your cart', 'telson-optics' ); ?></h2>
			<button class="icon-btn" type="button" data-cart-close>
				<?php telson_icon( 'close' ); ?>
				<span class="screen-reader-text"><?php esc_html_e( 'Close cart', 'telson-optics' ); ?></span>
			</button>
		</div>
		<div class="widget_shopping_cart_content">
			<?php woocommerce_mini_cart(); ?>
		</div>
	</div>
</div>
