<?php
/**
 * Plugin Name: Glimpse Post Block
 * Plugin URI:  https://wordpress.org/plugins/glimpse-post-block/
 * Description: A custom Gutenberg block to display posts with advanced filtering options.
 * Version:     1.0.0
 * Author:      Mohamed Taman
 * Author URI:  https://profiles.wordpress.org/mohamedtaman/
 * License:     GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: glimpse-post-block
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 7.4
 *
 * @package GlimpsePostBlock
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */

// Prevent direct access to this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants.
define( 'GLIMPSE_POST_BLOCK_VERSION', '1.0.0' );
define( 'GLIMPSE_POST_BLOCK_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'GLIMPSE_POST_BLOCK_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Main plugin class.
 *
 * This class implements the singleton pattern to ensure only one instance
 * of the plugin is loaded. It handles initialization, file inclusion,
 * hook registration, and asset management.
 *
 * @since 1.0.0
 */
final class Glimpse_Post_Block {

	/**
	 * The single instance of the class.
	 *
	 * @var Glimpse_Post_Block|null
	 * @since 1.0.0
	 */
	private static $instance = null;

	/**
	 * Get the single instance of the class.
	 *
	 * @since 1.0.0
	 * @return Glimpse_Post_Block
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Class constructor.
	 *
	 * Initializes the plugin by defining constants, including required files,
	 * and setting up WordPress hooks.
	 *
	 * @since 1.0.0
	 */
	private function __construct() {
		$this->includes();
		$this->init_hooks();
	}

	/**
	 * Include required files.
	 *
	 * Loads helper functions and core classes needed for the plugin to operate.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function includes() {
		require_once GLIMPSE_POST_BLOCK_PLUGIN_PATH . 'includes/helpers.php';
		require_once GLIMPSE_POST_BLOCK_PLUGIN_PATH . 'includes/class-block-registration.php';
		require_once GLIMPSE_POST_BLOCK_PLUGIN_PATH . 'includes/class-post-query.php';
	}

	/**
	 * Initialize WordPress hooks.
	 *
	 * Registers actions for localization, initialization, and asset enqueueing.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function init_hooks() {
		add_action( 'init', array( $this, 'load_textdomain' ) );
		add_action( 'init', array( $this, 'init_plugin' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ) );
 	}

	/**
	 * Load plugin textdomain for translations.
	 *
	 * Looks for translation files in the `/languages` directory.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function load_textdomain() {
		load_plugin_textdomain(
			'glimpse-post-block',
			false,
			dirname( plugin_basename( __FILE__ ) ) . '/languages'
		);
	}

	/**
	 * Initialize the plugin core components.
	 *
	 * Instantiates the block registration class to register the Gutenberg block.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function init_plugin() {
		new Glimpse_Post_Block_Registration();
	}

	/**
	 * Enqueue frontend styles.
	 *
	 * Loads the public-facing CSS file for the block’s frontend rendering.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function enqueue_frontend_assets() {
		wp_enqueue_style(
			'glimpse-post-block-style',
			GLIMPSE_POST_BLOCK_PLUGIN_URL . 'assets/css/style.css',
			array(),
			GLIMPSE_POST_BLOCK_VERSION
		);
	}

	 

	/**
	 * Prepare localized data for use in the editor script.
	 *
	 * Returns an associative array containing categories, tags, posts,
	 * a security nonce, and translatable strings.
	 *
	 * @since 1.0.0
	 * @return array Localized data.
	 */
	private function get_editor_data() {
		return array(
			'categories' => glimpse_get_categories(),
			'tags'       => glimpse_get_tags(),
			'posts'      => glimpse_get_posts_list(),
			'nonce'      => wp_create_nonce( 'glimpse_post_block_nonce' ),
			'i18n'       => array(
				'selectCategory' => __( 'Select Category', 'glimpse-post-block' ),
				'selectTags'     => __( 'Select Tags', 'glimpse-post-block' ),
				'selectPosts'    => __( 'Select Specific Posts', 'glimpse-post-block' ),
				'numberOfPosts'  => __( 'Number of Posts', 'glimpse-post-block' ),
				'showTitle'      => __( 'Show Title', 'glimpse-post-block' ),
				'showImage'      => __( 'Show Featured Image', 'glimpse-post-block' ),
				'showExcerpt'    => __( 'Show Excerpt', 'glimpse-post-block' ),
				'titleLink'      => __( 'Link on Title', 'glimpse-post-block' ),
				'buttonLink'     => __( 'Show Read More Button', 'glimpse-post-block' ),
				'buttonText'     => __( 'Button Text', 'glimpse-post-block' ),
			),
		);
	}
}

/**
 * Initialize the Glimpse Post Block plugin.
 *
 * @since 1.0.0
 * @return Glimpse_Post_Block
 */
function glimpse_post_block() {
	return Glimpse_Post_Block::instance();
}

// Bootstrap the plugin.
glimpse_post_block();