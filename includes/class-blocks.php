<?php
/**
 * Blocks Class
 *
 * @package Family_Recipe_Book
 */

namespace dvnl;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Blocks Class
 *
 * Registers and manages custom blocks for recipes.
 */
class Blocks {

	/**
	 * Initialize the class
	 */
	public function __construct() {
		// add_action( 'init', array( $this, 'register_blocks' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_editor_assets' ) );
		add_action( 'rest_api_init', array( $this, 'register_meta_fields' ) );
	}

	/**
	 * Register custom blocks
	 */
	public function register_blocks() {
		// Check if Gutenberg is active.
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		// Centralized scalable block registration with REST API and server-side rendering.
		$blocks = array(
			'dvnl/recipe-details'      => array(
				'editor_script'   => 'dvnl-family-recipe-book-editor',
				'editor_style'    => 'dvnl-family-recipe-book-editor-style',
				'style'           => 'dvnl-family-recipe-book-style',
				'render_callback' => array( $this, 'render_recipe_details_block' ),
				'attributes'      => array(), // Add block attributes as needed
			),
			'dvnl/recipe-ingredients'  => array(
				'editor_script'   => 'dvnl-family-recipe-book-editor',
				'editor_style'    => 'dvnl-family-recipe-book-editor-style',
				'style'           => 'dvnl-family-recipe-book-style',
				'render_callback' => array( $this, 'render_recipe_ingredients_block' ),
				'attributes'      => array(), // Add block attributes as needed
			),
			'dvnl/recipe-instructions' => array(
				'editor_script'   => 'dvnl-family-recipe-book-editor',
				'editor_style'    => 'dvnl-family-recipe-book-editor-style',
				'style'           => 'dvnl-family-recipe-book-style',
				'render_callback' => array( $this, 'render_recipe_instructions_block' ),
				'attributes'      => array(), // Add block attributes as needed
			),
			// Add more blocks here as needed
		);

		foreach ( $blocks as $name => $args ) {
			register_block_type( $name, $args );
		}

		// REST API support is handled automatically for blocks registered with register_block_type.
	}

	/**
	 * Server-side render callback for recipe details block
	 *
	 * @param array  $attributes Block attributes.
	 * @param string $content    Block content.
	 * @return string Rendered block output.
	 */
	public function render_recipe_details_block( $attributes, $content ) {
		// TODO: Implement server-side rendering logic for recipe details block
		return '<div class="dvnl-recipe-details-block">' . $content . '</div>';
	}

	/**
	 * Server-side render callback for recipe ingredients block
	 *
	 * @param array  $attributes Block attributes.
	 * @param string $content    Block content.
	 * @return string Rendered block output.
	 */
	public function render_recipe_ingredients_block( $attributes, $content ) {
		// TODO: Implement server-side rendering logic for recipe ingredients block
		return '<div class="dvnl-recipe-ingredients-block">' . $content . '</div>';
	}

	/**
	 * Server-side render callback for recipe instructions block
	 *
	 * @param array  $attributes Block attributes.
	 * @param string $content    Block content.
	 * @return string Rendered block output.
	 */
	public function render_recipe_instructions_block( $attributes, $content ) {
		// TODO: Implement server-side rendering logic for recipe instructions block
		return '<div class="dvnl-recipe-instructions-block">' . $content . '</div>';
	}

	/**
	 * Enqueue block editor assets
	 */
	public function enqueue_editor_assets() {
		// Enqueue block editor JS.
		wp_enqueue_script(
			'dvnl-family-recipe-book-editor',
			DVNL_FAMILY_RECIPE_BOOK_PLUGIN_URL . 'build/blocks.js',
			array( 'wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n' ),
			DVNL_FAMILY_RECIPE_BOOK_VERSION,
			true
		);

		// Enqueue block editor CSS.
		wp_enqueue_style(
			'dvnl-family-recipe-book-editor-style',
			DVNL_FAMILY_RECIPE_BOOK_PLUGIN_URL . 'build/blocks.css',
			array( 'wp-edit-blocks' ),
			DVNL_FAMILY_RECIPE_BOOK_VERSION
		);
	}

	/**
	 * Register meta fields for blocks
	 */
	public function register_meta_fields() {
		// Register meta fields for recipe details.
		$meta_fields = array(
			'_dvnl_recipe_prep_time',
			'_dvnl_recipe_cook_time',
			'_dvnl_recipe_total_time',
			'_dvnl_recipe_servings',
			'_dvnl_recipe_calories',
			'_dvnl_recipe_difficulty',
		);

		foreach ( $meta_fields as $meta_key ) {
			register_meta(
				'post',
				$meta_key,
				array(
					'show_in_rest'  => true,
					'single'        => true,
					'type'          => 'string',
					'auth_callback' => function () {
						return current_user_can( 'edit_posts' );
					},
				)
			);
		}
	}
}

// Initialize the class.
new Blocks();
