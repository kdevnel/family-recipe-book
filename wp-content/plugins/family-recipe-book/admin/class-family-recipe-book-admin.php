<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       twitter.com/kdevnel
 * @since      1.0.0
 *
 * @package    Family_Recipe_Book
 * @subpackage Family_Recipe_Book/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Family_Recipe_Book
 * @subpackage Family_Recipe_Book/admin
 * @author     Kyle Nel <kyle@devnel.com>
 */
class Family_Recipe_Book_Admin {

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
		 * defined in Family_Recipe_Book_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Family_Recipe_Book_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/family-recipe-book-admin.css', array(), $this->version, 'all' );

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
		 * defined in Family_Recipe_Book_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Family_Recipe_Book_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/family-recipe-book-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Creates a new custom post type
	 *
	 * @since 	1.0.0
	 * @access 	public
	 * @uses 	register_post_type()
	 */
	public static function new_cpt_recipes() {

		$cap_type 	= 'post';
		$plural 	= 'Recipes';
		$single 	= 'Recipe';
		$cpt_name 	= 'family_recipe_book';

		$args['can_export']								= TRUE;
		$args['capability_type']						= $cap_type;
		$args['description']							= 'Everything you need to store your recipes online';
		$args['exclude_from_search']					= FALSE;
		$args['has_archive']							= TRUE;
		$args['hierarchical']							= FALSE;
		$args['map_meta_cap']							= TRUE;
		$args['menu_icon']								= 'dashicons-carrot';
		$args['menu_position']							= 5;
		$args['public']									= TRUE;
		$args['publicly_querable']						= TRUE;
		$args['query_var']								= TRUE;
		$args['register_meta_box_cb']					= '';
		$args['rewrite']								= FALSE;
		$args['show_in_admin_bar']						= TRUE;
		$args['show_in_menu']							= TRUE;
		$args['show_in_nav_menu']						= TRUE;
		$args['show_in_rest']							= FALSE;
		$args['show_ui']								= TRUE;
		$args['supports']								= array(
			'title',
			'thumbnail',
			'comments',
			'editor',
			'author',
			'revisions'
		);
		$args['taxonomies']								= array();

		$args['capabilities']['delete_others_posts']	= "delete_others_{$cap_type}s";
		$args['capabilities']['delete_post']			= "delete_{$cap_type}";
		$args['capabilities']['delete_posts']			= "delete_{$cap_type}s";
		$args['capabilities']['delete_private_posts']	= "delete_private_{$cap_type}s";
		$args['capabilities']['delete_published_posts']	= "delete_published_{$cap_type}s";
		$args['capabilities']['edit_others_posts']		= "edit_others_{$cap_type}s";
		$args['capabilities']['edit_post']				= "edit_{$cap_type}";
		$args['capabilities']['edit_posts']				= "edit_{$cap_type}s";
		$args['capabilities']['edit_private_posts']		= "edit_private_{$cap_type}s";
		$args['capabilities']['edit_published_posts']	= "edit_published_{$cap_type}s";
		$args['capabilities']['publish_posts']			= "publish_{$cap_type}s";
		$args['capabilities']['read_post']				= "read_{$cap_type}";
		$args['capabilities']['read_private_posts']		= "read_private_{$cap_type}s";

		$args['labels']['add_new']						= esc_html__( "Add {$single}", 'dvnl_recipes' );
		$args['labels']['add_new_item']					= esc_html__( "Add New {$single}", 'dvnl_recipes' );
		$args['labels']['all_items']					= esc_html__( "All {$plural}", 'dvnl_recipes' );
		$args['labels']['edit_item']					= esc_html__( "Edit {$single}" , 'dvnl_recipes' );
		$args['labels']['menu_name']					= esc_html__( $plural, 'dvnl_recipes' );
		$args['labels']['name']							= esc_html__( $plural, 'dvnl_recipes' );
		$args['labels']['name_admin_bar']				= esc_html__( $single, 'dvnl_recipes' );
		$args['labels']['new_item']						= esc_html__( "New {$single}", 'dvnl_recipes' );
		$args['labels']['not_found']					= esc_html__( "No {$plural} Found", 'dvnl_recipes' );
		$args['labels']['not_found_in_trash']			= esc_html__( "No {$plural} Found in Trash", 'dvnl_recipes' );
		$args['labels']['parent_item_colon']			= esc_html__( "Parent {$plural}:", 'dvnl_recipes' );
		$args['labels']['search_items']					= esc_html__( "Search {$plural}", 'dvnl_recipes' );
		$args['labels']['singular_name']				= esc_html__( $single, 'dvnl_recipes' );
		$args['labels']['view_item']					= esc_html__( "View {$single}", 'dvnl_recipes' );

		$args['rewrite']['ep_mask']						= EP_PERMALINK;
		$args['rewrite']['feeds']						= TRUE;
		$args['rewrite']['pages']						= TRUE;
		$args['rewrite']['slug']						= esc_html__( strtolower( $plural ), 'dvnl_recipes' );
		$args['rewrite']['with_front']					= FALSE;

		$args = apply_filters( 'recipe-book-cpt-options', $args );

		register_post_type( strtolower( $cpt_name ), $args );
	} // new_cpt_recipes()

	/**
	 * Creates a new taxonomy for a custom post type
	 *
	 * @since 	1.0.0
	 * @access 	public
	 * @uses 	register_taxonomy()
	 */
	public static function new_taxonomy_creator() {

		$plural 	= 'Creators';
		$single 	= 'Creator';
		$tax_name 	= 'recipe_creator';

		$opts['hierarchical']							= TRUE;
		//$opts['meta_box_cb'] 							= '';
		$opts['public']									= TRUE;
		$opts['query_var']								= $tax_name;
		$opts['show_admin_column'] 						= TRUE;
		$opts['show_in_nav_menus']						= TRUE;
		$opts['show_tag_cloud'] 						= TRUE;
		$opts['show_ui']								= TRUE;
		$opts['sort'] 									= '';
		//$opts['update_count_callback'] 					= '';

		$opts['capabilities']['assign_terms'] 			= 'edit_posts';
		$opts['capabilities']['delete_terms'] 			= 'manage_categories';
		$opts['capabilities']['edit_terms'] 			= 'manage_categories';
		$opts['capabilities']['manage_terms'] 			= 'manage_categories';

		$opts['labels']['add_new_item'] 				= esc_html__( "Add New {$single}", 'dvnl_recipes' );
		$opts['labels']['add_or_remove_items'] 			= esc_html__( "Add or remove {$plural}", 'dvnl_recipes' );
		$opts['labels']['all_items'] 					= esc_html__( $plural, 'dvnl_recipes' );
		$opts['labels']['choose_from_most_used'] 		= esc_html__( "Choose from most used {$plural}", 'dvnl_recipes' );
		$opts['labels']['edit_item'] 					= esc_html__( "Edit {$single}" , 'dvnl_recipes');
		$opts['labels']['menu_name'] 					= esc_html__( $plural, 'dvnl_recipes' );
		$opts['labels']['name'] 						= esc_html__( $plural, 'dvnl_recipes' );
		$opts['labels']['new_item_name'] 				= esc_html__( "New {$single} Name", 'dvnl_recipes' );
		$opts['labels']['not_found'] 					= esc_html__( "No {$plural} Found", 'dvnl_recipes' );
		$opts['labels']['parent_item'] 					= esc_html__( "Parent {$single}", 'dvnl_recipes' );
		$opts['labels']['parent_item_colon'] 			= esc_html__( "Parent {$single}:", 'dvnl_recipes' );
		$opts['labels']['popular_items'] 				= esc_html__( "Popular {$plural}", 'dvnl_recipes' );
		$opts['labels']['search_items'] 				= esc_html__( "Search {$plural}", 'dvnl_recipes' );
		$opts['labels']['separate_items_with_commas'] 	= esc_html__( "Separate {$plural} with commas", 'dvnl_recipes' );
		$opts['labels']['singular_name'] 				= esc_html__( $single, 'dvnl_recipes' );
		$opts['labels']['update_item'] 					= esc_html__( "Update {$single}", 'dvnl_recipes' );
		$opts['labels']['view_item'] 					= esc_html__( "View {$single}", 'dvnl_recipes' );

		$opts['rewrite']['ep_mask']						= EP_NONE;
		$opts['rewrite']['hierarchical']				= FALSE;
		$opts['rewrite']['slug']						= esc_html__( strtolower( $single ), 'dvnl_recipes' );
		$opts['rewrite']['with_front']					= FALSE;

		$opts = apply_filters( 'dvnl-recipes-taxonomy-options', $opts );

		register_taxonomy( $tax_name, 'family_recipe_book', $opts );

	} // new_taxonomy_meal_type()

	/**
	 * Creates a new taxonomy for a custom post type
	 *
	 * @since 	1.0.0
	 * @access 	public
	 * @uses 	register_taxonomy()
	 */
	public static function new_taxonomy_meal_type() {

		$plural 	= 'Meal Types';
		$single 	= 'Meal Type';
		$tax_name 	= 'meal_type';
		$slug		= 'meal';

		$opts['hierarchical']							= TRUE;
		//$opts['meta_box_cb'] 							= '';
		$opts['public']									= TRUE;
		$opts['query_var']								= $tax_name;
		$opts['show_admin_column'] 						= FALSE;
		$opts['show_in_nav_menus']						= TRUE;
		$opts['show_tag_cloud'] 						= TRUE;
		$opts['show_ui']								= TRUE;
		$opts['sort'] 									= '';
		//$opts['update_count_callback'] 					= '';

		$opts['capabilities']['assign_terms'] 			= 'edit_posts';
		$opts['capabilities']['delete_terms'] 			= 'manage_categories';
		$opts['capabilities']['edit_terms'] 			= 'manage_categories';
		$opts['capabilities']['manage_terms'] 			= 'manage_categories';

		$opts['labels']['add_new_item'] 				= esc_html__( "Add New {$single}", 'dvnl_recipes' );
		$opts['labels']['add_or_remove_items'] 			= esc_html__( "Add or remove {$plural}", 'dvnl_recipes' );
		$opts['labels']['all_items'] 					= esc_html__( $plural, 'dvnl_recipes' );
		$opts['labels']['choose_from_most_used'] 		= esc_html__( "Choose from most used {$plural}", 'dvnl_recipes' );
		$opts['labels']['edit_item'] 					= esc_html__( "Edit {$single}" , 'dvnl_recipes');
		$opts['labels']['menu_name'] 					= esc_html__( $plural, 'dvnl_recipes' );
		$opts['labels']['name'] 						= esc_html__( $plural, 'dvnl_recipes' );
		$opts['labels']['new_item_name'] 				= esc_html__( "New {$single} Name", 'dvnl_recipes' );
		$opts['labels']['not_found'] 					= esc_html__( "No {$plural} Found", 'dvnl_recipes' );
		$opts['labels']['parent_item'] 					= esc_html__( "Parent {$single}", 'dvnl_recipes' );
		$opts['labels']['parent_item_colon'] 			= esc_html__( "Parent {$single}:", 'dvnl_recipes' );
		$opts['labels']['popular_items'] 				= esc_html__( "Popular {$plural}", 'dvnl_recipes' );
		$opts['labels']['search_items'] 				= esc_html__( "Search {$plural}", 'dvnl_recipes' );
		$opts['labels']['separate_items_with_commas'] 	= esc_html__( "Separate {$plural} with commas", 'dvnl_recipes' );
		$opts['labels']['singular_name'] 				= esc_html__( $single, 'dvnl_recipes' );
		$opts['labels']['update_item'] 					= esc_html__( "Update {$single}", 'dvnl_recipes' );
		$opts['labels']['view_item'] 					= esc_html__( "View {$single}", 'dvnl_recipes' );

		$opts['rewrite']['ep_mask']						= EP_NONE;
		$opts['rewrite']['hierarchical']				= FALSE;
		$opts['rewrite']['slug']						= esc_html__( strtolower( $slug ), 'dvnl_recipes' );
		$opts['rewrite']['with_front']					= FALSE;

		$opts = apply_filters( 'dvnl-recipes-taxonomy-options', $opts );

		register_taxonomy( $tax_name, 'family_recipe_book', $opts );

	} // new_taxonomy_meal_type()

	/**
	 * Creates a new taxonomy for a custom post type
	 *
	 * @since 	1.0.0
	 * @access 	public
	 * @uses 	register_taxonomy()
	 */
	public static function new_taxonomy_cuisine_type() {

		$plural 	= 'Cuisine';
		$single 	= 'Cuisine';
		$tax_name 	= 'cuisine_type';

		$opts['hierarchical']							= TRUE;
		//$opts['meta_box_cb'] 							= '';
		$opts['public']									= TRUE;
		$opts['query_var']								= $tax_name;
		$opts['show_admin_column'] 						= FALSE;
		$opts['show_in_nav_menus']						= TRUE;
		$opts['show_tag_cloud'] 						= TRUE;
		$opts['show_ui']								= TRUE;
		$opts['sort'] 									= '';
		//$opts['update_count_callback'] 					= '';

		$opts['capabilities']['assign_terms'] 			= 'edit_posts';
		$opts['capabilities']['delete_terms'] 			= 'manage_categories';
		$opts['capabilities']['edit_terms'] 			= 'manage_categories';
		$opts['capabilities']['manage_terms'] 			= 'manage_categories';

		$opts['labels']['add_new_item'] 				= esc_html__( "Add New {$single}", 'dvnl_recipes' );
		$opts['labels']['add_or_remove_items'] 			= esc_html__( "Add or remove {$plural}", 'dvnl_recipes' );
		$opts['labels']['all_items'] 					= esc_html__( $plural, 'dvnl_recipes' );
		$opts['labels']['choose_from_most_used'] 		= esc_html__( "Choose from most used {$plural}", 'dvnl_recipes' );
		$opts['labels']['edit_item'] 					= esc_html__( "Edit {$single}" , 'dvnl_recipes');
		$opts['labels']['menu_name'] 					= esc_html__( $plural, 'dvnl_recipes' );
		$opts['labels']['name'] 						= esc_html__( $plural, 'dvnl_recipes' );
		$opts['labels']['new_item_name'] 				= esc_html__( "New {$single} Name", 'dvnl_recipes' );
		$opts['labels']['not_found'] 					= esc_html__( "No {$plural} Found", 'dvnl_recipes' );
		$opts['labels']['parent_item'] 					= esc_html__( "Parent {$single}", 'dvnl_recipes' );
		$opts['labels']['parent_item_colon'] 			= esc_html__( "Parent {$single}:", 'dvnl_recipes' );
		$opts['labels']['popular_items'] 				= esc_html__( "Popular {$plural}", 'dvnl_recipes' );
		$opts['labels']['search_items'] 				= esc_html__( "Search {$plural}", 'dvnl_recipes' );
		$opts['labels']['separate_items_with_commas'] 	= esc_html__( "Separate {$plural} with commas", 'dvnl_recipes' );
		$opts['labels']['singular_name'] 				= esc_html__( $single, 'dvnl_recipes' );
		$opts['labels']['update_item'] 					= esc_html__( "Update {$single}", 'dvnl_recipes' );
		$opts['labels']['view_item'] 					= esc_html__( "View {$single}", 'dvnl_recipes' );

		$opts['rewrite']['ep_mask']						= EP_NONE;
		$opts['rewrite']['hierarchical']				= FALSE;
		$opts['rewrite']['slug']						= esc_html__( strtolower( $single ), 'dvnl_recipes' );
		$opts['rewrite']['with_front']					= FALSE;

		$opts = apply_filters( 'dvnl-recipes-taxonomy-options', $opts );

		register_taxonomy( $tax_name, 'family_recipe_book', $opts );

	} // new_taxonomy_cuisine_type()

	/**
	 * Creates a new folder for ACF Local JSON to saving
	 *
	 * @since 	1.0.0
	 * @access 	public
	 */
	public static function set_acf_json_save_folder( $path ) {
		$path = plugin_dir_path( __DIR__ ) . 'includes/acf-json';
		return $path;
	}

	/**
	 * Creates a new folder for ACF Local JSON to load
	 *
	 * @since 	1.0.0
	 * @access 	public
	 */
	public static function add_acf_json_load_folder( $paths ) {
		unset($paths[0]);
		$paths[] = plugin_dir_path( __DIR__ ) . 'includes/acf-json';
		return $paths;
	}

}
