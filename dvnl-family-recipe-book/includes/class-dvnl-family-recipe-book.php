<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://devnel.blog
 * @since      1.0.0
 *
 * @package    Dvnl_Family_Recipe_Book
 * @subpackage Dvnl_Family_Recipe_Book/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Dvnl_Family_Recipe_Book
 * @subpackage Dvnl_Family_Recipe_Book/includes
 * @author     Kyle Nel <kyle@devnel.com>
 */
class Dvnl_Family_Recipe_Book {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Dvnl_Family_Recipe_Book_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'DVNL_FAMILY_RECIPE_BOOK_VERSION' ) ) {
			$this->version = DVNL_FAMILY_RECIPE_BOOK_VERSION;
		} else {
			$this->version = '2.0.0';
		}
		$this->plugin_name = 'dvnl-family-recipe-book';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Dvnl_Family_Recipe_Book_Loader. Orchestrates the hooks of the plugin.
	 * - Dvnl_Family_Recipe_Book_i18n. Defines internationalization functionality.
	 * - Dvnl_Family_Recipe_Book_Admin. Defines all hooks for the admin area.
	 * - Dvnl_Family_Recipe_Book_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-dvnl-family-recipe-book-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-dvnl-family-recipe-book-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-dvnl-family-recipe-book-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-dvnl-family-recipe-book-public.php';

		/**
		 * Custom post types
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-dvnl-family-recipe-book-post-types.php';

		/**
		 * Custom post type metaboxes
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-dvnl-family-recipe-book-metaboxes.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-dvnl-family-recipe-book-field-repeater.php';

		$this->loader = new Dvnl_Family_Recipe_Book_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Dvnl_Family_Recipe_Book_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Dvnl_Family_Recipe_Book_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Dvnl_Family_Recipe_Book_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		/**
		* The problem with the initial activation code is that when the activation hook runs, it's after the init hook has run,
		* so hooking into init from the activation hook won't do anything.
		* You don't need to register the CPT within the activation function unless you need rewrite rules to be added
		* via flush_rewrite_rules() on activation. In that case, you'll want to register the CPT normally, via the
		* loader on the init hook, and also re-register it within the activation function and
		* call flush_rewrite_rules() to add the CPT rewrite rules.
		*
		* @link https://github.com/DevinVinson/WordPress-Plugin-Boilerplate/issues/261
		*/
		$plugin_post_types = new Dvnl_Family_Recipe_Book_Post_Types();
		$this->loader->add_action( 'init', $plugin_post_types, 'create_custom_post_type', 999 );

		/**
		 * Register meta field and create metabox
		 *
		 * @link https:// code.tutsplus.com/articles/rock-solid-wordpress-30-themes-using-custom-post-types--net-12093
		 */
		$plugin_metaboxes = new Dvnl_Family_Recipe_Book_Metaboxes();
		$this->loader->add_action( 'add_meta_boxes', $plugin_metaboxes, 'create_recipe_metaboxes' );
		$this->loader->add_action( 'save_post', $plugin_metaboxes, 'save_recipe_metaboxes' );
        // temporary repeater field code
		// $plugin_field_types = new Dvnl_Family_Recipe_Book_Field_Repeater();
		// $this->loader->add_action( 'admin_init', $plugin_field_types, 'hhs_add_meta_boxes' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Dvnl_Family_Recipe_Book_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Dvnl_Family_Recipe_Book_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
