<?php
/**
 * Site footer.
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

$footer_menus = array(
	'footer_products' => __( 'Products', 'telson-optics' ),
	'footer_learn'    => __( 'Learn', 'telson-optics' ),
	'footer_info'     => __( 'Info', 'telson-optics' ),
);
?>
<footer class="site-footer">
	<div class="container container--wide site-footer__grid">
		<div class="site-footer__brand">
			<?php telson_logo( 'footer_logo' ); ?>
			<?php $description = telson_option( 'footer_description' ); ?>
			<?php if ( $description ) : ?>
				<p class="site-footer__desc"><?php echo esc_html( $description ); ?></p>
			<?php endif; ?>
			<?php get_template_part( 'template-parts/footer/contact-details' ); ?>
			<?php telson_social_links(); ?>
		</div>

		<?php foreach ( $footer_menus as $location => $fallback_title ) : ?>
			<?php if ( has_nav_menu( $location ) ) : ?>
				<nav class="site-footer__col" aria-labelledby="footer-<?php echo esc_attr( $location ); ?>">
					<h2 class="site-footer__title" id="footer-<?php echo esc_attr( $location ); ?>">
						<?php echo esc_html( $fallback_title ); ?>
					</h2>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => $location,
							'container'      => false,
							'menu_class'     => 'footer-menu',
							'depth'          => 1,
						)
					);
					?>
				</nav>
			<?php endif; ?>
		<?php endforeach; ?>

		<?php get_template_part( 'template-parts/footer/newsletter' ); ?>
	</div>

	<div class="site-footer__bar">
		<div class="container container--wide site-footer__bar-inner">
			<p class="site-footer__copy">
				<?php
				$copyright = telson_option( 'footer_copyright', '© {year} ' . get_bloginfo( 'name' ) . '.' );
				echo esc_html( str_replace( '{year}', wp_date( 'Y' ), $copyright ) );
				?>
			</p>
			<?php
			if ( has_nav_menu( 'footer_legal' ) ) {
				wp_nav_menu(
					array(
						'theme_location'  => 'footer_legal',
						'container'       => 'nav',
						'container_class' => 'site-footer__legal',
						'container_aria_label' => __( 'Legal', 'telson-optics' ),
						'menu_class'      => 'footer-legal-menu',
						'depth'           => 1,
					)
				);
			}
			$credit = telson_option( 'footer_credit' );
			if ( $credit ) {
				echo '<p class="site-footer__credit">' . wp_kses_post( $credit ) . '</p>';
			}
			?>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
