<?php
/**
 * Post Types Class
 *
 * @package Family_Recipe_Book
 */

namespace dvnl;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once plugin_dir_path( __FILE__ ) . '/PostTypes/Recipe.php';
use DVNL\FamilyRecipeBook\PostTypes\Recipe;

/**
 * Post Types Class
 *
 * Manages and initializes all custom post types for the plugin.
 */
class Post_Types {

	/**
	 * Post type instances
	 *
	 * @var array
	 */
	private $post_types = [];

	/**
	 * Initialize the class
	 */
	public function __construct() {
		$this->load_post_types();
	}

	/**
	 * Load and initialize all post types
	 */
	private function load_post_types() {
		// Register the Recipe post type
		$this->post_types['dvnl_recipes'] = new Recipe();
		$this->post_types['dvnl_recipes']->register();

		// Add more post types here as needed
		// $this->post_types['other_post_type'] = new Other_Post_Type();
		// $this->post_types['other_post_type']->register();
	}

	/**
	 * Get a specific post type instance
	 *
	 * @param string $type Post type key.
	 * @return object|null Post type instance or null if not found.
	 */
	public function get_post_type($type) {
		return isset($this->post_types[$type]) ? $this->post_types[$type] : null;
	}

	/**
	 * Get all registered post type instances
	 *
	 * @return array Array of post type instances.
	 */
	public function get_post_types() {
		return $this->post_types;
	}
}

// Initialize the class.
new Post_Types();