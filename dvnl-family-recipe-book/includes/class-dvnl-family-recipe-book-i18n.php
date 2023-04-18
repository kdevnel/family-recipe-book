<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://devnel.blog
 * @since      1.0.0
 *
 * @package    Dvnl_Family_Recipe_Book
 * @subpackage Dvnl_Family_Recipe_Book/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Dvnl_Family_Recipe_Book
 * @subpackage Dvnl_Family_Recipe_Book/includes
 * @author     Kyle Nel <kyle@devnel.com>
 */
class Dvnl_Family_Recipe_Book_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'dvnl-family-recipe-book',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
