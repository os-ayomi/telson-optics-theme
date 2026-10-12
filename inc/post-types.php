<?php
/**
 * Custom post types.
 *
 * Pro Staff ("pro-staff") was originally registered by JetEngine. The theme
 * now registers it too, with the exact same settings JetEngine was using
 * (public, has_archive, rewrite slug, supports, menu icon — pulled from the
 * live `get_post_type_object()` output so nothing changes for visitors).
 * Since the theme's `init` callback is added after plugins have already run
 * theirs, this registration is the one WordPress keeps; once JetEngine's own
 * "Pro Staff" entry is removed from JetEngine → Post Types, this is the only
 * registration left and nothing else needs to change. The 18 existing
 * members and their fields are untouched — see acf-json/group_telson_pro_staff.json,
 * which targets the exact same meta keys JetEngine already wrote.
 *
 * Dealer is a theme-only type: the live site has a dealer network of
 * comparable size that standard posts/pages can't model cleanly.
 *
 * @package Telson_Optics
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'init',
	static function (): void {
		register_post_type(
			'pro-staff',
			array(
				'labels'              => array(
					'name'          => __( 'Pro Staff', 'telson-optics' ),
					'singular_name' => __( 'Pro Staff', 'telson-optics' ),
					'add_new_item'  => __( 'Add Pro Staff', 'telson-optics' ),
					'edit_item'     => __( 'Edit Pro Staff', 'telson-optics' ),
					'all_items'     => __( 'Pro Staff', 'telson-optics' ),
				),
				'public'              => true,
				'hierarchical'        => false,
				'has_archive'         => true,
				'rewrite'             => array( 'slug' => 'pro-staff', 'with_front' => false ),
				'supports'            => array( 'title', 'editor', 'thumbnail' ),
				'menu_icon'           => 'dashicons-id-alt',
				'show_in_rest'        => true,
				'publicly_queryable'  => true,
				'exclude_from_search' => false,
				'capability_type'     => 'post',
			)
		);

		register_post_type(
			'dealer',
			array(
				'labels'        => array(
					'name'          => __( 'Dealers', 'telson-optics' ),
					'singular_name' => __( 'Dealer', 'telson-optics' ),
					'add_new_item'  => __( 'Add dealer', 'telson-optics' ),
					'edit_item'     => __( 'Edit dealer', 'telson-optics' ),
					'all_items'     => __( 'All dealers', 'telson-optics' ),
				),
				// No public archive or single template — dealers are only ever
				// listed together on the "Dealer Directory" page template.
				'public'        => false,
				'show_ui'       => true,
				'has_archive'   => false,
				'publicly_queryable' => false,
				'exclude_from_search' => true,
				'menu_icon'     => 'dashicons-store',
				'menu_position' => 22,
				'show_in_rest'  => true,
				'supports'      => array( 'title', 'page-attributes' ),
			)
		);
	}
);

add_action(
	'after_switch_theme',
	static function (): void {
		flush_rewrite_rules();
	}
);
