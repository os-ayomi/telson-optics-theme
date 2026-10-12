<?php
/**
 * Fallback template and blog index.
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

get_header();

$title = is_home() && ! is_front_page() ? get_the_title( (int) get_option( 'page_for_posts' ) ) : __( 'News', 'telson-optics' );
telson_page_header( $title );
?>
<main id="main" class="site-main">
	<div class="container layout-with-sidebar">
		<div class="layout-with-sidebar__main">
			<?php if ( have_posts() ) : ?>
				<div class="post-grid">
					<?php
					while ( have_posts() ) {
						the_post();
						get_template_part( 'template-parts/content/card', get_post_type() );
					}
					?>
				</div>
				<?php telson_pagination(); ?>
			<?php else : ?>
				<?php get_template_part( 'template-parts/content/none' ); ?>
			<?php endif; ?>
		</div>
		<?php get_sidebar(); ?>
	</div>
</main>
<?php
get_footer();
