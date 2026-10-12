<?php
/**
 * Generic archive (categories, tags, dates).
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

get_header();
telson_page_header( wp_strip_all_tags( get_the_archive_title() ) );
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
