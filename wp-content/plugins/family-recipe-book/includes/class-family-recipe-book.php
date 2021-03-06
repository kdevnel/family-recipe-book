<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       twitter.com/kdevnel
 * @since      1.0.0
 *
 * @package    Family_Recipe_Book
 * @subpackage Family_Recipe_Book/includes
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
 * @package    Family_Recipe_Book
 * @subpackage Family_Recipe_Book/includes
 * @author     Kyle Nel <kyle@devnel.com>
 */
class Family_Recipe_Book {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Family_Recipe_Book_Loader    $loader    Maintains and registers all hooks for the plugin.
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
		if ( defined( 'FAMILY_RECIPE_BOOK_VERSION' ) ) {
			$this->version = FAMILY_RECIPE_BOOK_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'family-recipe-book';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_template_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Family_Recipe_Book_Loader. Orchestrates the hooks of the plugin.
	 * - Family_Recipe_Book_i18n. Defines internationalization functionality.
	 * - Family_Recipe_Book_Admin. Defines all hooks for the admin area.
	 * - Family_Recipe_Book_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-family-recipe-book-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-family-recipe-book-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-family-recipe-book-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-family-recipe-book-public.php';

		/**
		 * The class responsible for defining all actions creating the templates.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-family-recipe-book-template-functions.php';

		/**
		 * The class responsible for all global functions.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/family-recipe-book-global-functions.php';

		$this->loader = new Family_Recipe_Book_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Family_Recipe_Book_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Family_Recipe_Book_i18n();

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

		$plugin_admin = new Family_Recipe_Book_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_admin, 'new_cpt_recipes' );
		$this->loader->add_action( 'init', $plugin_admin, 'new_taxonomy_creator' );
		$this->loader->add_action( 'init', $plugin_admin, 'new_taxonomy_meal_type' );
		$this->loader->add_action( 'init', $plugin_admin, 'new_taxonomy_cuisine_type' );

		$this->loader->add_filter( 'pre_get_posts', $plugin_admin, 'dvnl_recipe_author_search' );

		// ACF local JSON filter
		$this->loader->add_filter( 'acf/settings/save_json', $plugin_admin, 'set_acf_json_save_folder' );
		$this->loader->add_filter( 'acf/settings/load_json', $plugin_admin, 'add_acf_json_load_folder' );


	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Family_Recipe_Book_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_filter( 'single_template', $plugin_public, 'single_recipe_template' );

	}

	/**
	 * Register all of the hooks related to the templates.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_template_hooks() {

		$plugin_templates = new Family_Recipe_Book_Template_Functions( $this->get_plugin_name(), $this->get_version() );

		// Single
		$this->loader->add_action( 'dvnl-recipes-single-content', $plugin_templates, 'single_post_title', 10 );
		$this->loader->add_action( 'dvnl-recipes-single-content', $plugin_templates, 'single_post_content', 15 );
		$this->loader->add_action( 'dvnl-recipes-single-content', $plugin_templates, 'single_post_responsibilities', 20 );
		$this->loader->add_action( 'dvnl-recipes-single-content', $plugin_templates, 'single_post_location', 25 );
		$this->loader->add_action( 'dvnl-recipes-single-content', $plugin_templates, 'single_post_education', 30 );
		$this->loader->add_action( 'dvnl-recipes-single-content', $plugin_templates, 'single_post_skills', 35 );
		$this->loader->add_action( 'dvnl-recipes-single-content', $plugin_templates, 'single_post_experience', 40 );
		$this->loader->add_action( 'dvnl-recipes-single-content', $plugin_templates, 'single_post_info', 45 );
		$this->loader->add_action( 'dvnl-recipes-single-content', $plugin_templates, 'single_post_file', 50 );
		$this->loader->add_action( 'dvnl-recipes-after-single', $plugin_templates, 'single_post_how_to_apply', 10 );

		$this->loader->add_action( 'dvnl_recipes_single_header', $plugin_templates, 'single_recipe_header', 10);
		$this->loader->add_action( 'dvnl_recipes_single_details', $plugin_templates, 'single_recipe_details', 10);
		$this->loader->add_action( 'dvnl_recipes_single_content', $plugin_templates, 'single_recipe_content', 10);
		$this->loader->add_action( 'dvnl_recipes_single_ingredients', $plugin_templates, 'single_recipe_ingredients', 10);
		$this->loader->add_action( 'dvnl_recipes_single_instructions', $plugin_templates, 'single_recipe_instructions', 10);
		$this->loader->add_action( 'dvnl_recipes_single_nutrition', $plugin_templates, 'single_recipe_nutrition', 10);
		$this->loader->add_action( 'dvnl_recipes_single_meta', $plugin_templates, 'single_recipe_meta', 10);

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
	 * @return    Family_Recipe_Book_Loader    Orchestrates the hooks of the plugin.
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
