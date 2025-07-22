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
		// Blocks are registered in the relevant JS files. We just need to enqueue them and register meta fields.
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_editor_assets' ) );
		add_action( 'rest_api_init', array( $this, 'register_meta_fields' ) );
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
			'_dvnl_recipe_ingredients_title',
			'_dvnl_recipe_ingredients_list',
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
