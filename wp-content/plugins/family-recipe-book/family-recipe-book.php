<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              twitter.com/kdevnel
 * @since             1.0.0
 * @package           Family_Recipe_Book
 *
 * @wordpress-plugin
 * Plugin Name:       Family Recipe Book
 * Plugin URI:        -
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Kyle Nel
 * Author URI:        twitter.com/kdevnel
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       family-recipe-book
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'FAMILY_RECIPE_BOOK_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-family-recipe-book-activator.php
 */
function activate_family_recipe_book() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-family-recipe-book-activator.php';
	Family_Recipe_Book_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-family-recipe-book-deactivator.php
 */
function deactivate_family_recipe_book() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-family-recipe-book-deactivator.php';
	Family_Recipe_Book_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_family_recipe_book' );
register_deactivation_hook( __FILE__, 'deactivate_family_recipe_book' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-family-recipe-book.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_family_recipe_book() {

	$plugin = new Family_Recipe_Book();
	$plugin->run();

}
run_family_recipe_book();
