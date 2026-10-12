<?php
/**
 * Pro staff card.
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

$cover = telson_field( 'cover_image' );
?>
<article <?php post_class( 'staff-card' ); ?>>
	<a class="staff-card__link" href="<?php the_permalink(); ?>">
		<span class="staff-card__media">
			<?php
			if ( $cover ) {
				telson_image( $cover, 'telson-portrait', array( 'loading' => 'lazy' ) );
			} elseif ( has_post_thumbnail() ) {
				the_post_thumbnail( 'telson-portrait' );
			}
			?>
		</span>
		<h2 class="staff-card__name"><?php the_title(); ?></h2>
	</a>
</article>
