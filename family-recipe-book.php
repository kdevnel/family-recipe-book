<?php
/**
 * Family Recipe Book
 *
 * @package     DVNL\FamilyRecipeBook
 * @author      Your Name
 * @copyright   2023 Your Name or Company Name
 * @license     GPL-2.0-or-later
 * @link        https://github.com/yourusername/family-recipe-book
 *
 * @wordpress-plugin
 * Plugin Name: Family Recipe Book
 * Plugin URI: https://example.com/plugins/family-recipe-book/
 * Description: A WordPress plugin for managing family recipes with modern best practices.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://example.com/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: family-recipe-book
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * Category: recipes, food
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Define plugin constants
define('DVNL_FAMILY_RECIPE_BOOK_VERSION', '1.0.0');
define('DVNL_FAMILY_RECIPE_BOOK_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('DVNL_FAMILY_RECIPE_BOOK_PLUGIN_URL', plugin_dir_url(__FILE__));
define('DVNL_FAMILY_RECIPE_BOOK_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Include required files
require_once DVNL_FAMILY_RECIPE_BOOK_PLUGIN_DIR . 'includes/class-post-types.php';
require_once DVNL_FAMILY_RECIPE_BOOK_PLUGIN_DIR . 'includes/class-blocks.php';
require_once DVNL_FAMILY_RECIPE_BOOK_PLUGIN_DIR . 'includes/class-schema.php';
require_once DVNL_FAMILY_RECIPE_BOOK_PLUGIN_DIR . 'includes/class-template-locking.php';
require_once DVNL_FAMILY_RECIPE_BOOK_PLUGIN_DIR . 'includes/class-settings.php';
require_once DVNL_FAMILY_RECIPE_BOOK_PLUGIN_DIR . 'includes/class-print.php';
require_once DVNL_FAMILY_RECIPE_BOOK_PLUGIN_DIR . 'includes/class-sharing.php';
require_once DVNL_FAMILY_RECIPE_BOOK_PLUGIN_DIR . 'includes/class-rating.php';
require_once DVNL_FAMILY_RECIPE_BOOK_PLUGIN_DIR . 'includes/class-search.php';

// Initialize the plugin
function dvnl_family_recipe_book_init() {
    // Load text domain for translations
    load_plugin_textdomain('family-recipe-book', false, dirname(plugin_basename(__FILE__)) . '/languages');

    // Register the custom post type
    $post_type = new \dvnl\Post_Types();

    // Register custom blocks
    $blocks = new \dvnl\Blocks();

    // Register schema markup
    $schema = new \dvnl\Schema();

    // Register template locking
    $template_locking = new \dvnl\Template_Locking();

    // Register settings
    $settings = new \dvnl\Settings();

    // Register print functionality
    $print = new \dvnl\Print_Recipe();

    // Register sharing functionality
    $sharing = new \dvnl\Sharing();

    // Register rating functionality
    $rating = new \dvnl\Rating();

    // Register search functionality
    $search = new \dvnl\Search();
}
add_action('plugins_loaded', 'dvnl_family_recipe_book_init');

// Register activation hook
register_activation_hook(__FILE__, function() {
    // Flush rewrite rules on activation
    $post_type = new \dvnl\Post_Types();
    flush_rewrite_rules();
});

// Register deactivation hook
register_deactivation_hook(__FILE__, function() {
    // Flush rewrite rules on deactivation
    flush_rewrite_rules();
});

// Enqueue admin scripts and styles
function dvnl_family_recipe_book_admin_enqueue_scripts() {
    $screen = get_current_screen();

    // Only enqueue on recipe post type
    if ('recipe' === $screen->post_type) {
        wp_enqueue_style(
            'dvnl-family-recipe-book-admin',
            DVNL_FAMILY_RECIPE_BOOK_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            DVNL_FAMILY_RECIPE_BOOK_VERSION
        );

        wp_enqueue_script(
            'dvnl-family-recipe-book-admin',
            DVNL_FAMILY_RECIPE_BOOK_PLUGIN_URL . 'assets/js/admin.js',
            array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components'),
            DVNL_FAMILY_RECIPE_BOOK_VERSION,
            true
        );
    }
}
add_action('admin_enqueue_scripts', 'dvnl_family_recipe_book_admin_enqueue_scripts');

// Enqueue frontend scripts and styles
function dvnl_family_recipe_book_enqueue_scripts() {
    if (is_singular('recipe')) {
        wp_enqueue_style(
            'dvnl-family-recipe-book',
            DVNL_FAMILY_RECIPE_BOOK_PLUGIN_URL . 'assets/css/public.css',
            array(),
            DVNL_FAMILY_RECIPE_BOOK_VERSION
        );

        wp_enqueue_style(
            'dvnl-family-recipe-book-sharing',
            DVNL_FAMILY_RECIPE_BOOK_PLUGIN_URL . 'assets/css/sharing.css',
            array(),
            DVNL_FAMILY_RECIPE_BOOK_VERSION
        );

        wp_enqueue_style(
            'dvnl-family-recipe-book-rating',
            DVNL_FAMILY_RECIPE_BOOK_PLUGIN_URL . 'assets/css/rating.css',
            array(),
            DVNL_FAMILY_RECIPE_BOOK_VERSION
        );

        wp_enqueue_script(
            'dvnl-family-recipe-book',
            DVNL_FAMILY_RECIPE_BOOK_PLUGIN_URL . 'assets/js/public.js',
            array('jquery'),
            DVNL_FAMILY_RECIPE_BOOK_VERSION,
            true
        );
    }

    // Enqueue search styles and scripts on archive pages
    if (is_post_type_archive('recipe') || is_tax(array('recipe_category', 'recipe_tag'))) {
        wp_enqueue_style(
            'dvnl-family-recipe-book-search',
            DVNL_FAMILY_RECIPE_BOOK_PLUGIN_URL . 'assets/css/search.css',
            array(),
            DVNL_FAMILY_RECIPE_BOOK_VERSION
        );

        wp_enqueue_script(
            'dvnl-family-recipe-book-search',
            DVNL_FAMILY_RECIPE_BOOK_PLUGIN_URL . 'assets/js/search.js',
            array('jquery'),
            DVNL_FAMILY_RECIPE_BOOK_VERSION,
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'dvnl_family_recipe_book_enqueue_scripts');