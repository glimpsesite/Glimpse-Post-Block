<?php
/**
 * Helper functions for the Glimpse Post Block plugin.
 *
 * This file contains utility functions used for fetching categories,
 * tags, posts, nonce verification, and block registration.
 *
 * @package GlimpsePostBlock
 * @since 1.0.0
 */

// Prevent direct access to this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Retrieve a list of post categories for use in the block editor.
 *
 * Returns an array of category options, including an "All Categories" placeholder.
 * Each option is formatted as an associative array with 'label' and 'value'.
 *
 * @since 1.0.0
 * @return array List of category options.
 */
function glimpse_get_categories() {
	$categories = get_categories(
		array(
			'hide_empty' => false,
		)
	);

	$options = array(
		array(
			'name' => __( 'Select Category', 'glimpse-post-block' ),
			'id' => '',
		),
	);

	foreach ( $categories as $category ) {
		$options[] = array(
			'name' => $category->name,
			'id' => (string) $category->term_id,
		);
	}

	return $options;
}

/**
 * Retrieve a list of post tags for use in the block editor.
 *
 * Returns an array of tag options formatted as 'label' and 'value' pairs.
 *
 * @since 1.0.0
 * @return array List of tag options.
 */
function glimpse_get_tags() {
	$tags = get_tags(
		array(
			'hide_empty' => false,
		)
	);

	$options = array(
		array(
			'name' => __( 'Select Tags', 'glimpse-post-block' ),
			'id' => '',
		),
	);

	foreach ( $tags as $tag ) {
		$options[] = array(
			'name' => $tag->name,
			'id' => (string) $tag->term_id,
		);
	}

	return $options;
}

/**
 * Retrieve a list of published posts for selection in the editor.
 *
 * Limits results to 50 posts to prevent performance issues in the editor UI.
 *
 * @since 1.0.0
 * @return array List of post options with title and ID.
 */
function glimpse_get_posts_list() {
	$posts = get_posts(
		array(
			'numberposts' => 50,
			'post_status' => 'publish',
			'post_type'   => 'post',
		)
	);

	$options = array(
		array(
			'title' => __( 'Select Specific Posts', 'glimpse-post-block' ),
			'id' => '',
		),
	);

	foreach ( $posts as $post ) {
		$options[] = array(
			'title' => $post->post_title,
			'id' => (string) $post->ID,
		);
	}

	return $options;
}

/**
 * Verify the security nonce used in AJAX or dynamic block requests.
 *
 * Ensures the request originates from a trusted source within the plugin context.
 *
 * @since 1.0.0
 * @param string $nonce Nonce value to verify.
 * @return bool True if the nonce is valid, false otherwise.
 */
function glimpse_verify_nonce( $nonce ) {
	return wp_verify_nonce( $nonce, 'glimpse_post_block_nonce' );
}

/**
 * Register the Glimpse Post Block with WordPress.
 *
 * Uses `register_block_type()` to register the dynamic block with its
 * editor and frontend assets, along with a render callback.
 *
 * @since 1.0.0
 * @return void
 */
function glimpse_register_post_block() {
	register_block_type(
		'glimpse-post-block/posts',
		array(
			'editor_script'   => 'glimpse-post-block-editor',
			'editor_style'    => 'glimpse-post-block-editor-style',
			'style'           => 'glimpse-post-block-style',
			'render_callback' => 'glimpse_render_posts_block',
		)
	);
}