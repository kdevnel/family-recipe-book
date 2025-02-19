<?php
/**
 * The file that defines the block registration functionality
 *
 * @link       https://devnel.blog
 * @since      1.0.0
 *
 * @package    Dvnl_Family_Recipe_Book
 * @subpackage Dvnl_Family_Recipe_Book/includes
 */

/**
 * The block registration class.
 *
 * Handles registration and initialization of Gutenberg blocks for the plugin.
 *
 * @since      1.0.0
 * @package    Dvnl_Family_Recipe_Book
 * @subpackage Dvnl_Family_Recipe_Book/includes
 * @author     Kyle Nel <kyle@devnel.com>
 */
class Dvnl_Family_Recipe_Book_Blocks {

	/**
	 * Initialize the blocks.
	 *
	 * @since    1.0.0
	 */
	public function init_blocks() {
		// Only load if Gutenberg is available.
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		// Register the block using block.json from the build directory
		register_block_type( plugin_dir_path( __DIR__ ) . 'build/blocks/recipe-card' );
	}
}