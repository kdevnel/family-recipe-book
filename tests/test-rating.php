<?php
/**
 * Class RatingTest
 *
 * @package Family_Recipe_Book
 */

/**
 * Rating test case.
 */
class RatingTest extends WP_UnitTestCase {

    /**
     * Test instance.
     *
     * @var \dvnl\Rating
     */
    protected $rating;

    /**
     * Test post ID.
     *
     * @var int
     */
    protected $post_id;

    /**
     * Set up test environment.
     */
    public function set_up() {
        parent::set_up();

        // Create a test recipe post
        $this->post_id = $this->factory->post->create(array(
            'post_title' => 'Test Recipe',
            'post_type' => 'recipe',
            'post_status' => 'publish',
        ));

        // Initialize the Rating class
        $this->rating = new \dvnl\Rating();
    }

    /**
     * Test that the Rating class can be instantiated.
     */
    public function test_class_exists() {
        $this->assertTrue(class_exists('\dvnl\Rating'));
    }

    /**
     * Test that the constructor sets up the necessary hooks.
     */
    public function test_constructor_hooks() {
        // Check if the hooks are added
        $this->assertEquals(10, has_action('wp_enqueue_scripts', array($this->rating, 'enqueue_scripts')));
        $this->assertEquals(10, has_action('wp_ajax_dvnl_save_rating', array($this->rating, 'save_rating')));
        $this->assertEquals(10, has_action('wp_ajax_nopriv_dvnl_save_rating', array($this->rating, 'save_rating')));
        $this->assertEquals(10, has_action('add_meta_boxes', array($this->rating, 'add_rating_meta_box')));
        $this->assertEquals(10, has_filter('the_content', array($this->rating, 'display_rating')));
        $this->assertEquals(10, has_filter('dvnl_recipe_schema_data', array($this->rating, 'add_rating_to_schema')));
    }

    /**
     * Test the get_rating_data method.
     */
    public function test_get_rating_data() {
        // Test with no ratings
        $rating_data = $this->rating->get_rating_data($this->post_id);
        $this->assertEquals(0, $rating_data['average']);
        $this->assertEquals(0, $rating_data['count']);

        // Add a test rating
        update_post_meta($this->post_id, '_dvnl_recipe_ratings', array(
            'user_1' => 5,
            'ip_127.0.0.1' => 4
        ));
        update_post_meta($this->post_id, '_dvnl_recipe_rating_average', 4.5);
        update_post_meta($this->post_id, '_dvnl_recipe_rating_count', 2);

        // Test with ratings
        $rating_data = $this->rating->get_rating_data($this->post_id);
        $this->assertEquals(4.5, $rating_data['average']);
        $this->assertEquals(2, $rating_data['count']);
    }

    /**
     * Test the update_rating_average method.
     */
    public function test_update_rating_average() {
        // Add test ratings
        update_post_meta($this->post_id, '_dvnl_recipe_user_ratings', array(
            'user_1' => 5,
            'user_2' => 3
        ));

        update_post_meta($this->post_id, '_dvnl_recipe_ip_ratings', array(
            'ip_127.0.0.1' => 4
        ));

        // Update the average
        $this->rating->update_rating_average($this->post_id);

        // Check the updated values
        $average = get_post_meta($this->post_id, '_dvnl_recipe_rating_average', true);
        $count = get_post_meta($this->post_id, '_dvnl_recipe_rating_count', true);

        $this->assertEquals(4, $average);
        $this->assertEquals(3, $count);
    }

    /**
     * Test the add_rating_to_schema method.
     */
    public function test_add_rating_to_schema() {
        // Add test ratings
        update_post_meta($this->post_id, '_dvnl_recipe_rating_average', 4.5);
        update_post_meta($this->post_id, '_dvnl_recipe_rating_count', 10);

        // Create a test schema array
        $schema = array(
            '@type' => 'Recipe',
            'name' => 'Test Recipe'
        );

        // Set up the current post ID
        global $post;
        $original_post = $post;
        $post = get_post($this->post_id);

        // Add rating to schema
        $schema_with_rating = $this->rating->add_rating_to_schema($schema);

        // Restore the original post
        $post = $original_post;

        // Check if rating was added
        $this->assertArrayHasKey('aggregateRating', $schema_with_rating);
        $this->assertEquals('AggregateRating', $schema_with_rating['aggregateRating']['@type']);
        $this->assertEquals(4.5, $schema_with_rating['aggregateRating']['ratingValue']);
        $this->assertEquals(5, $schema_with_rating['aggregateRating']['bestRating']);
        $this->assertEquals(10, $schema_with_rating['aggregateRating']['ratingCount']);
    }

    /**
     * Test the get_rating_html method.
     */
    public function test_get_rating_html() {
        // Add test ratings
        update_post_meta($this->post_id, '_dvnl_recipe_rating_average', 4.5);
        update_post_meta($this->post_id, '_dvnl_recipe_rating_count', 10);

        // Get the rating HTML
        $html = $this->rating->get_rating_html($this->post_id);

        // Check if the HTML contains the expected elements
        $this->assertStringContainsString('dvnl-recipe-rating', $html);
        $this->assertStringContainsString('dvnl-recipe-rating-stars', $html);
        $this->assertStringContainsString('dvnl-recipe-star', $html);
        $this->assertStringContainsString('4.5 out of 5', $html);
    }

    /**
     * Clean up after tests.
     */
    public function tear_down() {
        wp_delete_post($this->post_id, true);
        parent::tear_down();
    }
}