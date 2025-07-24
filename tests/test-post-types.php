<?php
/**
 * Post Types Test Case for wp-env
 *
 * @package DVNL\FamilyRecipeBook
 */

/**
 * Test case for the Recipe Post Type and related taxonomies.
 */
class Test_Post_Types extends WP_UnitTestCase {
	/**
	 * The post type to test.
	 *
	 * @var string
	 */
	protected $post_type = 'dvnl_recipes';

	/**
	 * Test that the recipe post type is registered.
	 */
	public function test_recipe_post_type_is_registered() {
		$post_types = get_post_types();
		$this->assertContains( $this->post_type, array_values( $post_types ) );

		$post_type_object = get_post_type_object( $this->post_type );
		$this->assertNotNull( $post_type_object );
	}

	/**
	 * Test that the recipe taxonomies are registered correctly.
	 */
	public function test_recipe_taxonomies_are_registered() {
		$taxonomies = get_taxonomies();
		$taxonomy_values = array_values( $taxonomies );

		$this->assertContains( 'dvnl_recipe_category', $taxonomy_values );
		$this->assertContains( 'dvnl_recipe_tag', $taxonomy_values );
	}

	/**
	 * Test that the recipe post type supports the correct features.
	 */
	public function test_recipe_post_type_supports() {
		$supports = array(
			'title',
			'editor',
			'thumbnail',
			'author',
			'excerpt',
			'comments',
			'revisions',
			'custom-fields',
		);

		foreach ( $supports as $feature ) {
			$supported = post_type_supports( $this->post_type, $feature );
			$this->assertTrue( $supported, "Post type should support {$feature}" );
		}
	}

	/**
	 * Test that the recipe post type has the correct labels.
	 */
	public function test_recipe_post_type_labels() {
		$post_type_object = get_post_type_object( $this->post_type );
		$this->assertEquals( 'Recipes', $post_type_object->label );
		$this->assertEquals( 'Recipe', $post_type_object->labels->singular_name );
	}

	/**
	 * Test creating a recipe post.
	 */
	public function test_creating_recipe_post() {
		$title = 'Test Recipe';
		$content = 'This is the recipe content.';

		$post_id = wp_insert_post(
			array(
				'post_type'    => $this->post_type,
				'post_title'   => $title,
				'post_content' => $content,
				'post_status'  => 'publish',
			)
		);

		$this->assertIsInt( $post_id );
		$this->assertGreaterThan( 0, $post_id );

		$post = get_post( $post_id );
		$this->assertInstanceOf( 'WP_Post', $post );
		$this->assertEquals( $this->post_type, $post->post_type );
		$this->assertEquals( $title, $post->post_title );
		$this->assertEquals( $content, $post->post_content );
	}
}
