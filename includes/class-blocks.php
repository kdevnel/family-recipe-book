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
		add_action( 'rest_api_init', array( $this, 'register_basic_meta_fields' ) );
		add_action( 'rest_api_init', array( $this, 'register_array_meta_fields' ) );
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
	 * Register basic meta fields for blocks. Used for strings and integers.
	 * Arrays or more complex types are registered separately.
	 */
	public function register_basic_meta_fields() {
		// Register string meta fields for recipe details.
		$basic_meta_fields = array(
			array(
				'name'          => '_dvnl_recipe_name',
				'type'          => 'string',
			),
			array(
				'name'          => '_dvnl_recipe_cook_time',
				'type'          => 'string',
			),
			array(
				'name'          => '_dvnl_recipe_total_time',
				'type'          => 'string',
			),
			array(
				'name'          => '_dvnl_recipe_servings',
				'type'          => 'integer',
			),
			array(
				'name'          => '_dvnl_recipe_calories',
				'type'          => 'string',
			),
			array(
				'name'          => '_dvnl_recipe_difficulty',
				'type'          => 'string',
			),
			array(
				'name'          => '_dvnl_recipe_ingredients_title',
				'type'          => 'string',
			),
			array(
				'name'          => '_dvnl_recipe_instructions_title',
				'type'          => 'string',
			),
		);

		foreach ( $basic_meta_fields as $field ) {
			// Ensure type is string or integer
			if ( ! in_array( $field['type'], array( 'string', 'integer' ), true ) ) {
				throw new \InvalidArgumentException( 'Invalid type for meta field: ' . $field['name'] . '. Use correct registration method.' );
			}

			register_meta(
				'post',
				$field['name'],
				array(
					'show_in_rest'  => true,
					'single'        => true,
					'type'          => $field['type'],
					'auth_callback' => function () {
						return current_user_can( 'edit_posts' );
					},
				)
			);
		}
	}

	/**
	 * Register array meta fields for blocks
	 */
	public function register_array_meta_fields() {
		$array_meta_fields = array(
			array(
				'name'          => '_dvnl_recipe_ingredients_list',
				'item_type'     => 'string',
			),
			array(
				'name'          => '_dvnl_recipe_instructions_list',
				'item_type'     => 'string',
			),
		);

		foreach ( $array_meta_fields as $field ) {
			register_meta(
				'post',
				$field['name'],
				array(
					'show_in_rest' => array(
						'schema' => array(
							'type'  => 'array',
							'items' => array(
								'type' => $field['item_type'],
							),
						),
					),
					'single'        => true,
					'type'          => 'array',
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
