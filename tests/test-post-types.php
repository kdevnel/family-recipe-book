<?php
/**
 * Class PostTypesTest
 *
 * @package Family_Recipe_Book
 */

/**
 * Test case for the Post_Types class.
 */
class PostTypesTest extends WP_UnitTestCase {

	/**
	 * Test that the recipe post type is registered.
	 */
	public function test_recipe_post_type_exists() {
		$post_types = get_post_types();
		$this->assertArrayHasKey( 'dvnl_recipes', $post_types );
	}

	/**
	 * Test that the recipe taxonomies are registered.
	 */
	public function test_recipe_taxonomies_exist() {
		$taxonomies = get_taxonomies();
		$this->assertArrayHasKey( 'recipe_category', $taxonomies );
		$this->assertArrayHasKey( 'recipe_tag', $taxonomies );
	}

	/**
	 * Test that the recipe post type supports the correct features.
	 */
	public function test_recipe_post_type_supports() {
		$this->assertTrue( post_type_supports( 'dvnl_recipes', 'title' ) );
		$this->assertTrue( post_type_supports( 'dvnl_recipes', 'editor' ) );
		$this->assertTrue( post_type_supports( 'dvnl_recipes', 'thumbnail' ) );
		$this->assertTrue( post_type_supports( 'dvnl_recipes', 'author' ) );
		$this->assertTrue( post_type_supports( 'dvnl_recipes', 'excerpt' ) );
	}

	/**
	 * Test that the recipe post type has the correct labels.
	 */
	public function test_recipe_post_type_labels() {
		$post_type_object = get_post_type_object( 'dvnl_recipes' );
		$this->assertEquals( 'Recipes', $post_type_object->label );
		$this->assertEquals( 'Recipe', $post_type_object->labels->singular_name );
	}
}