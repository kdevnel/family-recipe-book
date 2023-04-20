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
	 * @since 1.0.0
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
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
	 * Add metaboxes to the recipe post type.
	 */
	public function register_recipe_metaboxes() {
		add_meta_box(
			'dvnl_family_recipe_book_recipe_details',
			__( 'Recipe Details', 'dvnl-family-recipe-book' ),
			array( $this, 'render_recipe_details_metabox' ),
			'dvnl_recipes',
			'normal',
			'high'
		);
	}

	/**
	 * Render the recipe details metabox.
	 */
	public function render_recipe_details_metabox() {
		include plugin_dir_path( __FILE__ ) . 'partials/dvnl-family-recipe-book-details-metabox.php';
	}

	/**
	 * Save the recipe details metabox.
	 *
	 * @param int $post_id The post ID.
	 */
	public function save_recipe_metaboxes( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		$parent_id = wp_is_post_revision( $post_id );
		if ( $parent_id ) {
			$post_id = $parent_id;
		}

		if ( ! isset( $_POST['dvnl_recipe_details_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['dvnl_recipe_details_nonce'] ), 'dvnl_recipe_submit' ) ) {
			return;
		}

		$fields = array(
			'dvnl_original_author',
			'dvnl_published_date',
			'dvnl_cost',
		);

		foreach ( $fields as $field ) {
			if ( array_key_exists( $field, $_POST ) ) {
				update_post_meta( $post_id, $field, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
			}
		}
	}

}
