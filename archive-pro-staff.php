<?php
/**
 * Pro Staff archive.
 *
 * The "pro-staff" post type itself is registered by JetEngine (real, already
 * populated content) — this template only supplies the front-end layout,
 * read via plain get_post_meta() so it works regardless of which plugin
 * manages the fields.
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<section class="section hero hero--staff">
	<?php
	$hero_image = telson_option( 'pro_staff_hero_image' );
	if ( $hero_image ) :
		?>
		<picture class="hero__media">
			<?php telson_image( $hero_image, 'telson-hero', array( 'class' => 'hero__img', 'loading' => 'eager' ) ); ?>
		</picture>
	<?php endif; ?>
	<div class="hero__inner container">
		<div class="hero__breadcrumbs"><?php telson_breadcrumbs(); ?></div>
		<h1 class="hero__title"><?php post_type_archive_title(); ?></h1>
		<p class="hero__kicker"><?php esc_html_e( 'Team Telson', 'telson-optics' ); ?></p>
	</div>
</section>

<?php
$divider_image = telson_option( 'pro_staff_divider_image' );
if ( $divider_image ) :
	?>
	<div class="staff-divider"><?php telson_image( $divider_image, 'telson-banner', array( 'loading' => 'lazy' ) ); ?></div>
<?php endif; ?>

<main id="main" class="site-main section--dark">
	<div class="container">
		<div class="section-head">
			<h2 class="section-title"><?php esc_html_e( 'Meet Telson Optics Pro Team', 'telson-optics' ); ?></h2>
			<p class="section-lede"><?php esc_html_e( 'Rugged. Precise. Unyielding. The Telson Pro Team embodies the relentless pursuit of excellence. From dominating elite competitions to navigating the toughest hunting terrains, our team pushes limits and trusts only the best.', 'telson-optics' ); ?></p>
		</div>

		<?php if ( have_posts() ) : ?>
			<div class="staff-grid">
				<?php
				while ( have_posts() ) {
					the_post();
					get_template_part( 'template-parts/content/card', 'pro-staff' );
				}
				?>
			</div>
			<?php telson_pagination(); ?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/content/none' ); ?>
		<?php endif; ?>

		<p class="section-actions">
			<a class="btn btn--outline" href="<?php echo esc_url( home_url( '/pro-staff-application/' ) ); ?>"><?php esc_html_e( 'Apply to be a Pro Staff', 'telson-optics' ); ?></a>
		</p>
	</div>
</main>
<?php
get_footer();
