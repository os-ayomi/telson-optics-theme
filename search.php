<?php
/**
 * Search results. Products render with the shared product card.
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

get_header();

/* translators: %s: search query */
telson_page_header( sprintf( __( 'Search results for “%s”', 'telson-optics' ), get_search_query() ) );
?>
<main id="main" class="site-main">
	<div class="container">
		<?php telson_breadcrumbs(); ?>
		<?php get_search_form(); ?>

		<?php if ( have_posts() ) : ?>
			<?php
			$products = array();
			$others   = array();
			foreach ( $GLOBALS['wp_query']->posts as $found ) {
				if ( 'product' === $found->post_type ) {
					$products[] = $found;
				} else {
					$others[] = $found;
				}
			}
			?>

			<?php if ( $products ) : ?>
				<h2 class="section-title"><?php esc_html_e( 'Products', 'telson-optics' ); ?></h2>
				<ul class="products product-grid">
					<?php
					foreach ( $products as $found ) {
						$GLOBALS['post'] = $found; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
						setup_postdata( $found );
						wc_setup_product_data( $found );
						echo '<li ';
						wc_product_class( '', $found );
						echo '>';
						get_template_part( 'template-parts/products/product-card' );
						echo '</li>';
					}
					wp_reset_postdata();
					?>
				</ul>
			<?php endif; ?>

			<?php if ( $others ) : ?>
				<h2 class="section-title"><?php esc_html_e( 'Pages & articles', 'telson-optics' ); ?></h2>
				<div class="post-grid">
					<?php
					foreach ( $others as $found ) {
						$GLOBALS['post'] = $found; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
						setup_postdata( $found );
						get_template_part( 'template-parts/content/card', get_post_type() );
					}
					wp_reset_postdata();
					?>
				</div>
			<?php endif; ?>

			<?php telson_pagination(); ?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/content/none' ); ?>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
