<?php
/**
 * Template Locking Class
 *
 * @package Family_Recipe_Book
 */

namespace dvnl;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Template Locking Class
 *
 * Manages template locking for recipe post types to ensure they use our custom blocks.
 */
class Template_Locking {

	/**
	 * Initialize the class
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_recipe_template' ) );
	}

	/**
	 * Register the template for the recipe post type
	 */
	public function register_recipe_template() {
		$post_type_object = get_post_type_object( 'recipe' );

		if ( ! $post_type_object ) {
			return;
		}

		// Define the template structure
		$post_type_object->template = array(
			array(
				'core/paragraph',
				array(
					'placeholder' => __( 'Add a brief description of your recipe...', 'family-recipe-book' ),
				),
			),
			array( 'dvnl/recipe-details', array() ),
			array( 'dvnl/recipe-ingredients', array() ),
			// array( 'dvnl/recipe-instructions', array() ),
		);

		// Set template lock to 'insert' - allows adding blocks but not removing required ones
		$post_type_object->template_lock = 'insert';
	}
}

// Initialize the class.
new Template_Locking();
