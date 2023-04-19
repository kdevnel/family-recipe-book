<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://devnel.blog
 * @since      1.0.0
 *
 * @package    Dvnl_Family_Recipe_Book
 * @subpackage Dvnl_Family_Recipe_Book/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Dvnl_Family_Recipe_Book
 * @subpackage Dvnl_Family_Recipe_Book/admin
 * @author     Kyle Nel <kyle@devnel.com>
 */
class Dvnl_Family_Recipe_Book_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dvnl_Family_Recipe_Book_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dvnl_Family_Recipe_Book_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/dvnl-family-recipe-book-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dvnl_Family_Recipe_Book_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dvnl_Family_Recipe_Book_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/dvnl-family-recipe-book-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Save custom post meta fields.
	 */
	public function update_or_save_post_meta() {
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		global $post;
		update_post_meta( $post->ID, 'dvnl_recipe_ingredients', $_POST[ 'ingredients' ] );
		update_post_meta( $post->ID, 'dvnl_recipe_instructions', $_POST[ 'instructions' ] );
		// Author - will need to be a dropdown of users.
		// Original Author - search for a non-user author like a category.
		// URL
		// Servings
		// Cost
		// Course
		// Cuisine
		// Diet
		// Keywords
		// Difficulty
		// Timings
			// Prep time (days / hours / minutes)
			// Cook time (days / hours / minutes)
			// Rest time (days / hours / minutes)
			// Total time (days / hours / minutes)
		// Ingredients
			// Single Ingredient
				// Amount
				// Unit
				// Name
				// Notes
				// Group
		// Instructions
			// Single step
				// Instruction
				// Media
				// Group
		// Video
		// Nutrition
			// Protein
			// Carbohydrates
			// Fat
			// Cholesterol
			// Sodium
			// Fibre
		// Notes.
	}

	/**
	 * Register metaboxes for custom meta fields.
	 */
	public function add_meta_boxes() {
		add_meta_box( 'recipe-meta', 'Recipe Details', array( $this, 'display_meta_boxes' ), 'dvnl_recipes', 'normal', 'low' );
	}

	/**
	 * Display the metaboxes and custom meta fields.
	 */
	public function display_meta_boxes() {
		global $post;

		$meta_fields = get_post_custom( $post->ID );

		$ingredients = $meta_fields['dvnl_recipe_ingredients'][0];
		?>
		<label><?php _e( 'Ingredients:', $this->plugin_name ); ?></label><input name="ingredients" value="<?php echo $ingredients; ?>" /><br>
		<?php

		$instructions = $meta_fields['dvnl_recipe_instructions'][0];
		?>
		<label><?php _e( 'Instructions:', $this->plugin_name ); ?></label><input name="instructions" value="<?php echo $instructions; ?>" /><br>
		<?php
	}
}
