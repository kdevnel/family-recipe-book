<?php
/**
 * Globally-accessible functions
 *
 * @link 		twitter.com/kdevnel
 * @since 		1.0.0
 *
 * @package		Family_Recipe_Book
 * @subpackage 	Family_Recipe_Book/includes
 */

/**
 * Returns the result of the get_template global function
 */
function dvnl_recipes_get_template( $name ) {

	return Family_Recipe_Book_Globals::get_template( $name );

}


class Family_Recipe_Book_Globals {

    /**
     * Returns the path to a template file
     *
     * Looks for the file in these directories, in this order:
     * 		Current theme
     * 		Parent theme
     * 		Current theme templates folder
     * 		Parent theme templates folder
     * 		This plugin
     *
     * To use a custom list template in a theme, copy the
     * file from public/templates into a templates folder in your
     * theme. Customize as needed, but keep the file name as-is. The
     * plugin will automatically use your custom template file instead
     * of the ones included in the plugin.
     *
     * @param 	string 		$name 			The name of a template file
     * @return 	string 						The path to the template
     */
    public static function get_template( $name ) {

        $template = '';

        $locations[] = "{$name}.php";
        $locations[] = "/partials/{$name}.php";

        /**
         * Filter the locations to search for a template file
         *
         * @param 	array 		$locations 			File names and/or paths to check
         */
        apply_filters( 'dvnl-recipes-template-paths', $locations );

        $template = locate_template( $locations, TRUE );

        if ( empty( $template ) ) {

            $template = plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/' . $name . '.php';

        }

        return $template;

    } // get_template()

} // class