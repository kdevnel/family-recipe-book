<?php
/**
 * PHPUnit bootstrap file for wp-env compatibility
 *
 * @package DVNL\FamilyRecipeBook
 */

// Find the WordPress tests directory.
$_tests_dir = getenv( 'WP_TESTS_DIR' );

// Forward custom PHPUnit Polyfills configuration to PHPUnit bootstrap file.
$_phpunit_polyfills_path = getenv( 'WP_TESTS_PHPUNIT_POLYFILLS_PATH' );
if ( false !== $_phpunit_polyfills_path ) {
	define( 'WP_TESTS_PHPUNIT_POLYFILLS_PATH', $_phpunit_polyfills_path );
}

// Load the PHPUnit Polyfills for compatibility with multiple PHPUnit versions.
if ( file_exists( dirname( __DIR__ ) . '/vendor/yoast/phpunit-polyfills/phpunitpolyfills-autoload.php' ) ) {
	require_once dirname( __DIR__ ) . '/vendor/yoast/phpunit-polyfills/phpunitpolyfills-autoload.php';
}

// Support direct use of WP_PHPUNIT__TESTS_CONFIG for wp-env.
$_env_config = getenv( 'WP_PHPUNIT__TESTS_CONFIG' );
if ( $_env_config && file_exists( $_env_config ) ) {
	// Using wp-env setup.
	if ( ! $_tests_dir ) {
		$_tests_dir = '/wp-phpunit';
	}
}

// Try multiple possible locations.
if ( ! $_tests_dir ) {
	$_locations = array(
		'/wp-phpunit',
		'/wp-phpunit/includes',
		dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . '/wordpress-develop/tests/phpunit',
		rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib',
	);

	foreach ( $_locations as $location ) {
		if ( file_exists( $location . '/includes/functions.php' ) ) {
			$_tests_dir = $location;
			break;
		}
	}
}

// Fallback - use local copy.
if ( ! $_tests_dir && file_exists( dirname( __DIR__ ) . '/tests/lib/functions.php' ) ) {
	$_tests_dir = dirname( __DIR__ ) . '/tests/lib';
}

// Exit if tests directory not found.
if ( ! $_tests_dir || ! file_exists( $_tests_dir . '/includes/functions.php' ) ) {
	echo "Could not find WordPress tests directory. Please set WP_TESTS_DIR environment variable or use wp-env.\n";
	exit( 1 );
}

// Give access to tests_add_filter() function.
require_once $_tests_dir . '/includes/functions.php';

/**
 * Manually load the plugin being tested.
 */
function _dvnl_manually_load_plugin() {
	require dirname( __DIR__ ) . '/family-recipe-book.php';
}

tests_add_filter( 'muplugins_loaded', '_dvnl_manually_load_plugin' );

// Start up the WP testing environment.
require $_tests_dir . '/includes/bootstrap.php';