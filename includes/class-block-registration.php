<?php
/**
 * Block registration class for the Glimpse Post Block plugin.
 *
 * This class handles the registration of the dynamic Gutenberg block,
 * defines its attributes schema, and renders its frontend output.
 *
 * @package GlimpsePostBlock
 * @since 1.0.0
 */

// Prevent direct access to this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers and renders the Glimpse Post Block.
 *
 * This class hooks into the 'init' action to register a dynamic block
 * with WordPress. It defines block attributes and provides a render callback
 * that queries and displays posts based on user-configured settings.
 *
 * @since 1.0.0
 */
class Glimpse_Post_Block_Registration {

	/**
	 * Constructor.
	 *
	 * Binds the block registration to the 'init' hook.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_block' ) );
	}

	/**
	 * Register the Glimpse Post Block with WordPress.
	 *
	 * Defines the block name, attributes schema, and render callback.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function register_block() {
		register_block_type(
			'glimpse-post-block/posts',
			array(
				'render_callback' => array( $this, 'render_block' ),
				'attributes'      => $this->get_block_attributes(),
			)
		);
	}

	/**
	 * Define the block's attributes schema.
	 *
	 * Specifies the data structure expected from the block editor,
	 * including types, defaults, and validation hints for JavaScript.
	 *
	 * @since 1.0.0
	 * @return array Block attributes schema.
	 */
	private function get_block_attributes() {
		return array(
			'category'      => array(
				'type'    => 'string',
				'default' => '',
			),
			'tags'          => array(
				'type'    => 'array',
				'default' => array(),
				'items'   => array(
					'type' => 'string',
				),
			),
			'numberOfPosts' => array(
				'type'    => 'number',
				'default' => 5,
			),
			'specificPosts' => array(
				'type'    => 'array',
				'default' => array(),
				'items'   => array(
					'type' => 'string',
				),
			),
			'showTitle'     => array(
				'type'    => 'boolean',
				'default' => true,
			),
			'showImage'     => array(
				'type'    => 'boolean',
				'default' => true,
			),
			'showExcerpt'   => array(
				'type'    => 'boolean',
				'default' => true,
			),
			'titleLink'     => array(
				'type'    => 'boolean',
				'default' => true,
			),
			'buttonLink'    => array(
				'type'    => 'boolean',
				'default' => false,
			),
			'buttonText'    => array(
				'type'    => 'string',
				'default' => esc_html__( 'Read More', 'glimpse-post-block' ),
			),
			'className'     => array(
				'type'    => 'string',
				'default' => '',
			),
		);
	}

	/**
	 * Render the block frontend output.
	 *
	 * Queries posts based on block attributes and returns safe, escaped HTML.
	 *
	 * @since 1.0.0
	 * @param array $attributes Block attributes.
	 * @param string $content   Block inner content (unused in dynamic blocks).
	 * @return string Rendered block HTML.
	 */
	public function render_block( $attributes, $content ) {
		$query = new Glimpse_Post_Query( $attributes );
		$posts = $query->get_posts();

		if ( empty( $posts ) ) {
			return '<p class="glimpse-no-posts">' . esc_html__( 'No posts found.', 'glimpse-post-block' ) . '</p>';
		}

		ob_start();
		?>
<div class="glimpse-post-block <?php echo esc_attr( $attributes['className'] ); ?>">
    <?php foreach ( $posts as $post ) : setup_postdata( $post ); ?>
    <article class="glimpse-post-item">
        <?php if ( $attributes['showImage'] && has_post_thumbnail( $post->ID ) ) : ?>
        <div class="glimpse-post-image">
            <?php if ( $attributes['titleLink'] ) : ?>
            <a href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>"
                aria-label="<?php echo esc_attr( $post->post_title ); ?>">
                <?php echo get_the_post_thumbnail( $post->ID, 'medium' ); ?>
            </a>
            <?php else : ?>
            <?php echo get_the_post_thumbnail( $post->ID, 'medium' ); ?>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <div class="glimpse-post-content">
            <?php if ( $attributes['showTitle'] ) : ?>
            <h3 class="glimpse-post-title">
                <?php if ( $attributes['titleLink'] ) : ?>
                <a href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>">
                    <?php echo esc_html( $post->post_title ); ?>
                </a>
                <?php else : ?>
                <?php echo esc_html( $post->post_title ); ?>
                <?php endif; ?>
            </h3>
            <?php endif; ?>

            <?php if ( $attributes['showExcerpt'] ) : ?>
            <div class="glimpse-post-excerpt">
                <?php
								$excerpt = ! empty( $post->post_excerpt ) ? $post->post_excerpt : $post->post_content;
								echo wp_kses_post( wp_trim_words( $excerpt, 20 ) );
								?>
            </div>
            <?php endif; ?>

            <?php if ( $attributes['buttonLink'] && ! $attributes['titleLink'] ) : ?>
            <div class="glimpse-post-button">
                <a href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>" class="glimpse-read-more">
                    <?php echo esc_html( $attributes['buttonText'] ); ?>
                </a>
            </div>
            <?php endif; ?>
        </div>
    </article>
    <?php endforeach; ?>
    <?php wp_reset_postdata(); ?>
</div>
<?php
		return ob_get_clean();
	}
}