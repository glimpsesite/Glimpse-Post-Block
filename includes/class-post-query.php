<?php
/**
 * Class for handling dynamic post queries for the Glimpse Post Block.
 *
 * This class constructs and executes WP_Query-compatible arguments
 * based on block attributes provided by the user in the Gutenberg editor.
 *
 * @package GlimpsePostBlock
 * @since 1.0.0
 */

// Prevent direct access to this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles the construction and execution of post queries for the Glimpse Post Block.
 *
 * Accepts block attributes and generates a safe, filtered query to retrieve posts
 * based on user-defined criteria such as category, tags, or specific post IDs.
 *
 * @since 1.0.0
 */
class Glimpse_Post_Query {

	/**
	 * Block attributes passed from the editor.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	private $attributes;

	/**
	 * Constructor.
	 *
	 * Stores the block attributes for later use in query construction.
	 *
	 * @since 1.0.0
	 * @param array $attributes Block attributes from the Gutenberg editor.
	 */
	public function __construct( $attributes ) {
		$this->attributes = $attributes;
	}

	/**
	 * Retrieve posts based on the block settings.
	 *
	 * Executes a `get_posts()` query using sanitized and filtered arguments.
	 *
	 * @since 1.0.0
	 * @return array Array of WP_Post objects.
	 */
	public function get_posts() {
		$args = $this->get_query_args();
		return get_posts( $args );
	}

	/**
	 * Build the query arguments based on block attributes.
	 *
	 * Constructs a safe query array with support for:
	 * - Number of posts
	 * - Category filtering
	 * - Tag filtering
	 * - Explicit post selection
	 *
	 * When specific posts are selected, category and tag filters are ignored
	 * to ensure user intent is respected.
	 *
	 * @since 1.0.0
	 * @return array WP_Query-compatible arguments.
	 */
	private function get_query_args() {
		$args = array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => absint( $this->attributes['numberOfPosts'] ?? 5 ),
			'orderby'        => 'date',
			'order'          => 'DESC',
		);

		// Filter by category if specified.
		if ( ! empty( $this->attributes['category'] ) ) {
			$args['cat'] = absint( $this->attributes['category'] );
		}

		// Filter by tags if provided.
		if ( ! empty( $this->attributes['tags'] ) && is_array( $this->attributes['tags'] ) ) {
			$args['tag__in'] = array_map( 'absint', $this->attributes['tags'] );
		}

		// Handle explicitly selected posts.
		if ( ! empty( $this->attributes['specificPosts'] ) && is_array( $this->attributes['specificPosts'] ) ) {
			$args['post__in'] = array_map( 'absint', $this->attributes['specificPosts'] );
			$args['orderby']  = 'post__in'; // Preserve selected order.

			// When specific posts are chosen, ignore other filters to reflect user intent.
			unset( $args['cat'] );
			unset( $args['tag__in'] );
		}

		/**
		 * Filters the query arguments before execution.
		 *
		 * Allows developers to modify or extend the query used by the Glimpse Post Block.
		 *
		 * @since 1.0.0
		 * @param array $args       The query arguments.
		 * @param array $attributes The original block attributes.
		 */
		return apply_filters( 'glimpse_post_block_query_args', $args, $this->attributes );
	}
}