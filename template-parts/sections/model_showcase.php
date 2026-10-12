<?php
/**
 * Section: model showcase. A per-model product deep-dive (hero, precision
 * gallery, turret badges, banner, key features, reticle, optical quality,
 * video + review, warranty, buy bar) for pages like /showcase/tmv-5-32x56/.
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

$product_id = (int) telson_sub( 'product_id' );
$name       = telson_sub( 'model_name' );
?>
<div class="model-showcase">

	<section class="model-showcase__hero">
		<div class="model-showcase__hero-watermark" aria-hidden="true"><?php echo esc_html( $name ); ?></div>
		<div class="container">
			<div class="model-showcase__hero-crumbs"><?php telson_breadcrumbs(); ?></div>
			<?php if ( telson_sub( 'eyebrow' ) ) : ?>
				<p class="eyebrow model-showcase__hero-eyebrow"><?php echo esc_html( telson_sub( 'eyebrow' ) ); ?></p>
			<?php endif; ?>
			<?php if ( $name ) : ?>
				<h1 class="model-showcase__hero-name"><?php echo esc_html( $name ); ?></h1>
			<?php endif; ?>
			<?php if ( telson_sub( 'tagline' ) ) : ?>
				<p class="model-showcase__hero-tagline"><?php echo esc_html( telson_sub( 'tagline' ) ); ?></p>
			<?php endif; ?>
			<?php
			$hero_image = telson_sub( 'hero_image' );
			if ( $hero_image ) :
				?>
				<div class="model-showcase__hero-image">
					<?php telson_image( $hero_image, 'large', array( 'loading' => 'eager', 'fetchpriority' => 'high' ) ); ?>
				</div>
			<?php endif; ?>
			<?php if ( $product_id ) : ?>
				<div class="model-showcase__hero-cta">
					<?php telson_add_to_cart_button( $product_id, 'btn btn--outline-light' ); ?>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<?php
	$turrets_heading = telson_sub( 'turrets_heading' );
	$turrets_text    = telson_sub( 'turrets_text' );
	$gallery         = telson_sub( 'gallery' );
	if ( $turrets_heading || $turrets_text || $gallery ) :
		?>
		<section class="section model-showcase__precision">
			<div class="container container--narrow section-head">
				<?php if ( $turrets_heading ) : ?><h2 class="section-title"><?php echo esc_html( $turrets_heading ); ?></h2><?php endif; ?>
				<?php if ( $turrets_text ) : ?><div class="prose section-lede"><?php echo wp_kses_post( $turrets_text ); ?></div><?php endif; ?>
			</div>
			<?php if ( $gallery ) : ?>
				<div class="container">
					<ul class="model-showcase__gallery">
						<?php foreach ( $gallery as $image ) : ?>
							<li><?php telson_image( $image, 'telson-tile', array( 'loading' => 'lazy' ) ); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
		</section>
	<?php endif; ?>

	<?php
	$badges_heading    = telson_sub( 'badges_heading' );
	$badges_subheading = telson_sub( 'badges_subheading' );
	$badges            = telson_sub( 'badges' );
	if ( $badges_heading || $badges_subheading || $badges ) :
		?>
		<section class="section model-showcase__badges">
			<div class="container container--narrow section-head">
				<?php if ( $badges_heading ) : ?><h2 class="model-showcase__badges-heading"><?php echo esc_html( $badges_heading ); ?></h2><?php endif; ?>
				<?php if ( $badges_subheading ) : ?><p class="section-lede"><?php echo esc_html( $badges_subheading ); ?></p><?php endif; ?>
			</div>
			<?php if ( $badges ) : ?>
				<ul class="model-showcase__badge-list">
					<?php foreach ( $badges as $badge ) : ?>
						<?php if ( ! empty( $badge['text'] ) ) : ?>
							<li><?php echo esc_html( $badge['text'] ); ?></li>
						<?php endif; ?>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</section>
	<?php endif; ?>

	<?php
	$banner_image = telson_sub( 'banner_image' );
	$banner_text  = telson_sub( 'banner_text' );
	if ( $banner_image || $banner_text || $product_id ) :
		?>
		<section class="model-showcase__banner">
			<?php if ( $banner_image ) : ?>
				<div class="model-showcase__banner-media">
					<?php telson_image( $banner_image, 'telson-hero', array( 'loading' => 'lazy' ) ); ?>
				</div>
			<?php endif; ?>
			<div class="model-showcase__banner-inner container">
				<?php if ( $product_id ) : ?>
					<?php telson_add_to_cart_button( $product_id, 'btn btn--primary' ); ?>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>

	<?php
	$features_eyebrow = telson_sub( 'features_eyebrow' );
	$features_heading = telson_sub( 'features_heading' );
	$features         = telson_sub( 'features' );
	if ( $features_eyebrow || $features_heading || $features ) :
		?>
		<section class="section model-showcase__features-section">
			<div class="container">
				<div class="section-head">
					<?php if ( $features_eyebrow ) : ?><p class="eyebrow"><?php echo esc_html( $features_eyebrow ); ?></p><?php endif; ?>
					<?php if ( $features_heading ) : ?><h2 class="section-title"><?php echo esc_html( $features_heading ); ?></h2><?php endif; ?>
				</div>
				<?php if ( $features ) : ?>
					<ul class="model-showcase__features">
						<?php foreach ( $features as $feature ) : ?>
							<?php if ( ! empty( $feature['text'] ) ) : ?>
								<li><?php echo esc_html( $feature['text'] ); ?></li>
							<?php endif; ?>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>

	<?php
	$reticle_heading  = telson_sub( 'reticle_heading' );
	$reticle_text     = telson_sub( 'reticle_text' );
	$reticle_image    = telson_sub( 'reticle_image' );
	$reticle_overlay  = telson_sub( 'reticle_overlay' );
	$reticle_download = telson_sub( 'reticle_download' );
	if ( $reticle_heading || $reticle_text || $reticle_image ) :
		?>
		<section class="section model-showcase__reticle">
			<div class="container container--narrow section-head">
				<?php if ( $reticle_heading ) : ?><h2 class="section-title"><?php echo esc_html( $reticle_heading ); ?></h2><?php endif; ?>
				<?php if ( $reticle_text ) : ?><div class="prose section-lede"><?php echo wp_kses_post( $reticle_text ); ?></div><?php endif; ?>
			</div>
			<?php if ( $reticle_image ) : ?>
				<div class="model-showcase__reticle-image">
					<?php telson_image( $reticle_image, 'medium_large', array( 'class' => 'model-showcase__reticle-base' ) ); ?>
					<?php if ( $reticle_overlay ) : ?>
						<?php telson_image( $reticle_overlay, 'medium_large', array( 'class' => 'model-showcase__reticle-overlay' ) ); ?>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<?php if ( ! empty( $reticle_download['url'] ) ) : ?>
				<div class="section-actions">
					<?php telson_link( $reticle_download, 'btn btn--outline' ); ?>
				</div>
			<?php endif; ?>
		</section>
	<?php endif; ?>

	<?php
	$optical_heading = telson_sub( 'optical_heading' );
	$optical_text    = telson_sub( 'optical_text' );
	$optical_image   = telson_sub( 'optical_image' );
	if ( $optical_heading || $optical_text || $optical_image ) :
		?>
		<section class="section model-showcase__optical">
			<div class="container container--narrow section-head">
				<?php if ( $optical_heading ) : ?><h2 class="section-title"><?php echo esc_html( $optical_heading ); ?></h2><?php endif; ?>
				<?php if ( $optical_text ) : ?><div class="prose section-lede"><?php echo wp_kses_post( $optical_text ); ?></div><?php endif; ?>
			</div>
			<?php if ( $optical_image ) : ?>
				<div class="container container--narrow">
					<?php telson_image( $optical_image, 'large', array( 'class' => 'model-showcase__optical-image', 'loading' => 'lazy' ) ); ?>
				</div>
			<?php endif; ?>
		</section>
	<?php endif; ?>

	<?php if ( $name ) : ?>
		<div class="model-showcase__watermark" aria-hidden="true"><?php echo esc_html( $name ); ?></div>
	<?php endif; ?>

	<?php
	$video_url       = telson_sub( 'video_url' );
	$video_thumbnail = telson_sub( 'video_thumbnail' );
	$rating          = (float) telson_sub( 'review_rating' );
	$quote           = telson_sub( 'review_quote' );
	$author          = telson_sub( 'review_author' );
	if ( $video_url || $quote ) :
		?>
		<section class="section model-showcase__review-section">
			<div class="container model-showcase__review-grid">
				<?php if ( $video_url ) : ?>
					<a class="model-showcase__video" href="<?php echo esc_url( $video_url ); ?>" target="_blank" rel="noopener noreferrer">
						<?php if ( $video_thumbnail ) : ?>
							<?php telson_image( $video_thumbnail, 'large', array( 'loading' => 'lazy' ) ); ?>
						<?php endif; ?>
						<span class="model-showcase__play" aria-hidden="true"></span>
					</a>
				<?php endif; ?>
				<div class="model-showcase__review-content">
					<?php if ( $name ) : ?><p class="eyebrow"><?php echo esc_html( $name ); ?></p><?php endif; ?>
					<h2 class="section-title">Review</h2>
					<?php if ( $name ) : ?><p class="model-showcase__review-model"><?php echo esc_html( $name ); ?></p><?php endif; ?>
					<?php if ( $rating > 0 ) : ?>
						<div class="model-showcase__stars" aria-label="<?php echo esc_attr( $rating . ' out of 5 stars' ); ?>">
							<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
								<?php telson_icon( $i <= round( $rating ) ? 'star' : 'star-outline', 'model-showcase__star' ); ?>
							<?php endfor; ?>
						</div>
					<?php endif; ?>
					<?php if ( $quote ) : ?>
						<blockquote class="model-showcase__quote">
							<p>&ldquo;<?php echo esc_html( $quote ); ?>&rdquo;</p>
							<?php if ( $author ) : ?><cite><?php echo esc_html( $author ); ?></cite><?php endif; ?>
						</blockquote>
					<?php endif; ?>
					<?php if ( $product_id ) : ?>
						<?php telson_add_to_cart_button( $product_id, 'btn btn--primary' ); ?>
					<?php endif; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php
	$warranty_badge   = telson_sub( 'warranty_badge' );
	$warranty_heading = telson_sub( 'warranty_heading' );
	$warranty_text    = telson_sub( 'warranty_text' );
	$warranty_image   = telson_sub( 'warranty_image' );
	if ( $warranty_heading || $warranty_text ) :
		?>
		<section class="section model-showcase__warranty">
			<div class="container model-showcase__warranty-grid">
				<?php if ( $warranty_badge ) : ?>
					<div class="model-showcase__warranty-badge"><?php telson_image( $warranty_badge, 'medium', array( 'loading' => 'lazy' ) ); ?></div>
				<?php endif; ?>
				<div class="model-showcase__warranty-content">
					<?php if ( $warranty_heading ) : ?><h2 class="section-title"><?php echo esc_html( $warranty_heading ); ?></h2><?php endif; ?>
					<?php if ( $warranty_text ) : ?><div class="prose"><?php echo wp_kses_post( wpautop( $warranty_text ) ); ?></div><?php endif; ?>
				</div>
				<?php if ( $warranty_image ) : ?>
					<div class="model-showcase__warranty-image"><?php telson_image( $warranty_image, 'medium', array( 'loading' => 'lazy' ) ); ?></div>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( $product_id && $name ) : ?>
		<section class="model-showcase__buy-bar">
			<div class="container model-showcase__buy-bar-inner">
				<p class="model-showcase__buy-bar-title">Buy <?php echo esc_html( $name ); ?></p>
				<?php telson_add_to_cart_button( $product_id, 'btn btn--primary' ); ?>
			</div>
		</section>
	<?php endif; ?>

</div>
