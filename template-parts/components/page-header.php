<?php
/**
 * Page title band.
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

$title    = $args['title'] ?? '';
$subtitle = $args['subtitle'] ?? '';
?>
<header class="page-header">
	<div class="container">
		<?php if ( $subtitle ) : ?>
			<p class="eyebrow"><?php echo esc_html( $subtitle ); ?></p>
		<?php endif; ?>
		<h1 class="page-title"><?php echo esc_html( $title ); ?></h1>
	</div>
</header>
