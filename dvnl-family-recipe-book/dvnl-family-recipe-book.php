<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://devnel.blog
 * @since             1.0.0
 * @package           Dvnl_Family_Recipe_Book
 *
 * @wordpress-plugin
 * Plugin Name:       Family Recipe Book
 * Plugin URI:        https://github.com/kdevnel/family-recipe-book
 * Description:       A plugin for allowing you to store family recipes and display them on your WordPress website in a way that all your family can use.
 * Version:           1.0.0
 * Author:            Kyle Nel
 * Author URI:        https://devnel.blog
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       dvnl-family-recipe-book
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
define( 'DVNL_FAMILY_RECIPE_BOOK_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-dvnl-family-recipe-book-activator.php
 */
function activate_dvnl_family_recipe_book() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dvnl-family-recipe-book-activator.php';
	Dvnl_Family_Recipe_Book_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-dvnl-family-recipe-book-deactivator.php
 */
function deactivate_dvnl_family_recipe_book() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dvnl-family-recipe-book-deactivator.php';
	Dvnl_Family_Recipe_Book_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_dvnl_family_recipe_book' );
register_deactivation_hook( __FILE__, 'deactivate_dvnl_family_recipe_book' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-dvnl-family-recipe-book.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_dvnl_family_recipe_book() {

	$plugin = new Dvnl_Family_Recipe_Book();
	$plugin->run();

}
run_dvnl_family_recipe_book();
