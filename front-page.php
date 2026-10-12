<?php
/**
 * Front page. Layout is the ACF "sections" flexible content field.
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main" class="site-main site-main--home">
	<?php
	if ( ! telson_render_sections( 'sections' ) ) {
		while ( have_posts() ) {
			the_post();
			echo '<div class="container prose">';
			the_content();
			echo '</div>';
		}
	}
	?>
</main>
<?php
get_footer();
