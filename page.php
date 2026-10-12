<?php
/**
 * Generic page: optional flexible sections, then standard content.
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$has_sections = telson_has_acf() && have_rows( 'sections' );
	$hide_title   = (bool) telson_field( 'hide_page_title' );
	$is_commerce  = telson_is_woocommerce_active() && ( is_cart() || is_checkout() || is_account_page() );
	?>
	<?php if ( ! $hide_title && ! $has_sections ) : ?>
		<?php telson_page_header( get_the_title() ); ?>
	<?php endif; ?>

	<main id="main" class="site-main">
		<?php if ( $has_sections ) : ?>
			<?php telson_render_sections( 'sections' ); ?>
		<?php endif; ?>

		<?php if ( get_the_content() ) : ?>
			<div class="container <?php echo $is_commerce ? 'container--wide' : 'container--narrow'; ?> page-content">
				<article <?php post_class( $is_commerce ? '' : 'prose' ); ?>>
					<?php the_content(); ?>
				</article>
			</div>
		<?php endif; ?>
	</main>
<?php endwhile; ?>
<?php
get_footer();
