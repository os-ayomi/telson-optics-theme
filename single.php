<?php
/**
 * Single blog post.
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	telson_page_header( get_the_title(), get_the_date() );
	?>
	<main id="main" class="site-main">
		<div class="container container--narrow">
			<article <?php post_class( 'prose' ); ?>>
				<?php if ( has_post_thumbnail() ) : ?>
					<figure class="post-hero"><?php the_post_thumbnail( 'large' ); ?></figure>
				<?php endif; ?>
				<?php the_content(); ?>
			</article>
			<?php
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
			the_post_navigation();
			?>
		</div>
	</main>
<?php endwhile; ?>
<?php
get_footer();
