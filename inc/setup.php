<?php
/**
 * Theme setup: supports, menus, image sizes, widget areas.
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'after_setup_theme',
	static function (): void {
		load_theme_textdomain( 'telson-optics', TELSON_DIR . '/languages' );

		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'custom-logo', array( 'height' => 80, 'width' => 400, 'flex-width' => true, 'flex-height' => true ) );
		add_theme_support(
			'html5',
			array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
		);

		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );

		register_nav_menus(
			array(
				'primary'         => __( 'Primary menu', 'telson-optics' ),
				'footer_products' => __( 'Footer: Products', 'telson-optics' ),
				'footer_learn'    => __( 'Footer: Learn', 'telson-optics' ),
				'footer_info'     => __( 'Footer: Info', 'telson-optics' ),
				'footer_legal'    => __( 'Footer: legal bar', 'telson-optics' ),
			)
		);

		add_image_size( 'telson-hero', 1920, 900, true );
		add_image_size( 'telson-banner', 1600, 700, true );
		add_image_size( 'telson-tile', 720, 540, true );
		add_image_size( 'telson-portrait', 640, 800, true );
	}
);

add_action(
	'widgets_init',
	static function (): void {
		register_sidebar(
			array(
				'name'          => __( 'Blog sidebar', 'telson-optics' ),
				'id'            => 'sidebar-blog',
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="widget__title">',
				'after_title'   => '</h2>',
			)
		);
	}
);

add_filter(
	'body_class',
	static function ( array $classes ): array {
		$classes[] = 'has-announcement-bar-' . ( telson_option( 'announcement_enabled', false ) ? 'on' : 'off' );
		return $classes;
	}
);

/**
 * Expose the current-menu-item state and dropdown toggle on parent items.
 */
add_filter(
	'walker_nav_menu_start_el',
	static function ( string $output, $item, int $depth, $args ): string {
		if ( empty( $args->theme_location ) || 'primary' !== $args->theme_location ) {
			return $output;
		}
		if ( in_array( 'menu-item-has-children', (array) $item->classes, true ) ) {
			$output .= sprintf(
				'<button class="submenu-toggle" type="button" aria-expanded="false" aria-label="%s">',
				/* translators: %s: menu item title */
				esc_attr( sprintf( __( 'Toggle %s submenu', 'telson-optics' ), wp_strip_all_tags( $item->title ) ) )
			);
			ob_start();
			telson_icon( 'chevron' );
			$output .= ob_get_clean() . '</button>';
		}
		return $output;
	},
	10,
	4
);

add_filter(
	'nav_menu_submenu_css_class',
	static function ( array $classes ): array {
		$classes[] = 'sub-menu--panel';
		return $classes;
	}
);
