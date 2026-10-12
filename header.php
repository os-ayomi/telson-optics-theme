<?php
/**
 * Site header.
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'telson-optics' ); ?></a>

<?php get_template_part( 'template-parts/header/announcement-bar' ); ?>

<header class="site-header" id="site-header">
	<div class="site-header__inner container container--wide">
		<?php telson_logo(); ?>

		<nav class="primary-nav" id="primary-nav" aria-label="<?php esc_attr_e( 'Primary', 'telson-optics' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'primary-nav__list',
					'depth'          => 2,
					'fallback_cb'    => false,
				)
			);
			?>
		</nav>

		<div class="site-header__actions">
			<?php telson_currency_switcher(); ?>

			<button class="icon-btn" type="button" data-search-open aria-haspopup="dialog" aria-controls="search-overlay">
				<?php telson_icon( 'search' ); ?>
				<span class="screen-reader-text"><?php esc_html_e( 'Search', 'telson-optics' ); ?></span>
			</button>

			<?php if ( telson_is_woocommerce_active() ) : ?>
				<a class="icon-btn" href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">
					<?php telson_icon( 'user' ); ?>
					<span class="screen-reader-text"><?php esc_html_e( 'My account', 'telson-optics' ); ?></span>
				</a>
				<button class="cart-btn" type="button" data-cart-open aria-haspopup="dialog" aria-controls="cart-drawer">
					<?php telson_icon( 'cart' ); ?>
					<span class="cart-btn__total"><span class="telson-cart-total"><?php echo WC()->cart ? wp_kses_post( WC()->cart->get_cart_subtotal() ) : ''; ?></span></span>
					<span class="cart-btn__count"><span class="telson-cart-count" data-count="<?php echo esc_attr( (string) telson_cart_count() ); ?>"><?php echo esc_html( (string) telson_cart_count() ); ?></span></span>
					<span class="screen-reader-text"><?php esc_html_e( 'Open cart', 'telson-optics' ); ?></span>
				</button>
			<?php endif; ?>

			<button class="icon-btn nav-toggle" type="button" aria-expanded="false" aria-controls="primary-nav">
				<?php telson_icon( 'menu', 'icon icon--open' ); ?>
				<?php telson_icon( 'close', 'icon icon--close' ); ?>
				<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'telson-optics' ); ?></span>
			</button>
		</div>
	</div>
</header>
