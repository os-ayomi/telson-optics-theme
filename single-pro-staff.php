<?php
/**
 * Single Pro Staff profile.
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

get_header();
telson_page_header( get_the_title(), __( 'Team Telson', 'telson-optics' ) );
?>
<main id="main" class="site-main">
	<div class="container">
		<?php telson_breadcrumbs(); ?>
		<?php
		while ( have_posts() ) :
			the_post();
			$cover       = telson_field( 'cover_image' );
			$second      = telson_field( 'second_featured_image' );
			$role        = telson_field( 'heading' );
			$description = telson_field( 'short_description' );
			$gallery     = telson_field( 'gallery' );
			$instagram   = telson_field( 'instagram_link' );
			$website     = telson_field( 'web_link' );
			$facebook    = telson_field( 'facebook_link' );
			?>
			<article class="staff-single">
				<?php if ( $cover ) : ?>
					<div class="staff-single__photo">
						<?php telson_image( $cover, 'telson-portrait', array( 'loading' => 'eager' ) ); ?>
					</div>
				<?php endif; ?>
				<div class="staff-single__body">
					<h1><?php the_title(); ?></h1>
					<?php if ( $role ) : ?>
						<p class="staff-card__role"><?php echo esc_html( $role ); ?></p>
					<?php endif; ?>
					<?php if ( $description ) : ?>
						<div class="prose"><?php echo wp_kses_post( wpautop( $description ) ); ?></div>
					<?php elseif ( get_the_content() ) : ?>
						<div class="prose"><?php the_content(); ?></div>
					<?php endif; ?>
					<?php if ( $instagram || $website || $facebook ) : ?>
						<div class="staff-links">
							<?php if ( $website ) : ?>
								<a class="btn btn--outline" href="<?php echo esc_url( $website ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Website', 'telson-optics' ); ?></a>
							<?php endif; ?>
							<?php if ( $instagram ) : ?>
								<a class="btn btn--outline" href="<?php echo esc_url( $instagram ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Instagram', 'telson-optics' ); ?></a>
							<?php endif; ?>
							<?php if ( $facebook ) : ?>
								<a class="btn btn--outline" href="<?php echo esc_url( $facebook ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Facebook', 'telson-optics' ); ?></a>
							<?php endif; ?>
						</div>
					<?php endif; ?>
					<?php if ( $second ) : ?>
						<div class="staff-single__second-photo">
							<?php telson_image( $second, 'large', array( 'loading' => 'lazy' ) ); ?>
						</div>
					<?php endif; ?>
					<?php if ( ! empty( $gallery ) ) : ?>
						<ul class="gallery__grid gallery__grid--3">
							<?php foreach ( $gallery as $image ) : ?>
								<li><?php telson_image( $image, 'telson-tile', array( 'loading' => 'lazy' ) ); ?></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>
			</article>
		<?php endwhile; ?>
	</div>
</main>
<?php
get_footer();
