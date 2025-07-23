<?php
/**
 * Class SearchTest
 *
 * @package Family_Recipe_Book
 */

/**
 * Search test case.
 */
class SearchTest extends WP_UnitTestCase {

    /**
     * Test recipe post ID.
     *
     * @var int
     */
    protected $recipe_id;

    /**
     * Search instance.
     *
     * @var \dvnl\Search
     */
    protected $search;

    /**
     * Set up test environment.
     */
    public function set_up() {
        parent::set_up();

        // Create a test recipe
        $this->recipe_id = $this->factory->post->create(array(
            'post_title'   => 'Test Recipe for Search',
            'post_content' => 'This is a test recipe content for search functionality testing.',
            'post_status'  => 'publish',
            'post_type'    => 'dvnl_recipes',
        ));

        // Add recipe metadata
        update_post_meta($this->recipe_id, '_dvnl_recipe_difficulty', 'medium');
        update_post_meta($this->recipe_id, '_dvnl_recipe_total_time', '45');

        // Create test categories and tags
        $category_id = $this->factory->term->create(array(
            'name'     => 'Test Category',
            'taxonomy' => 'recipe_category',
        ));
        wp_set_post_terms($this->recipe_id, array($category_id), 'recipe_category');

        $tag_id = $this->factory->term->create(array(
            'name'     => 'Test Tag',
            'taxonomy' => 'recipe_tag',
        ));
        wp_set_post_terms($this->recipe_id, array($tag_id), 'recipe_tag');

        // Initialize the Search class
        $this->search = new \dvnl\Search();
    }

    /**
     * Test that the class exists.
     */
    public function test_class_exists() {
        $this->assertTrue(class_exists('\\dvnl\\Search'));
    }

    /**
     * Test that the constructor adds the necessary hooks.
     */
    public function test_constructor_hooks() {
        global $wp_filter;

        $this->assertArrayHasKey('wp_enqueue_scripts', $wp_filter);
        $this->assertArrayHasKey('wp_ajax_dvnl_filter_recipes', $wp_filter);
        $this->assertArrayHasKey('wp_ajax_nopriv_dvnl_filter_recipes', $wp_filter);
        $this->assertArrayHasKey('pre_get_posts', $wp_filter);

        // Test shortcode registration
        $this->assertTrue(shortcode_exists('recipe_search'));
    }

    /**
     * Test the recipe search shortcode output.
     */
    public function test_recipe_search_shortcode() {
        // Get the shortcode output
        $output = $this->search->recipe_search_shortcode(array());

        // Check that the output contains the expected elements
        $this->assertStringContainsString('dvnl-recipe-search', $output);
        $this->assertStringContainsString('dvnl-recipe-search-form', $output);
        $this->assertStringContainsString('dvnl-recipe-search-input', $output);
        $this->assertStringContainsString('dvnl-recipe-filters', $output);
        $this->assertStringContainsString('dvnl-recipe-search-results', $output);
    }

    /**
     * Test the filter_recipes method with various parameters.
     */
    public function test_filter_recipes() {
        // Set up the AJAX request
        $_POST['nonce'] = wp_create_nonce('dvnl_search_nonce');
        $_POST['keyword'] = 'Test Recipe';
        $_POST['categories'] = array();
        $_POST['tags'] = array();
        $_POST['difficulty'] = array();
        $_POST['time'] = 'any';
        $_POST['page'] = 1;
        $_POST['per_page'] = 12;

        // Capture the AJAX output
        ob_start();
        $this->search->filter_recipes();
        $output = ob_get_clean();

        // Decode the JSON response
        $response = json_decode($output, true);

        // Check that the response is successful and contains the expected data
        $this->assertTrue($response['success']);
        $this->assertArrayHasKey('recipes', $response['data']);
        $this->assertArrayHasKey('pagination', $response['data']);

        // Check that our test recipe is in the results
        $found = false;
        foreach ($response['data']['recipes'] as $recipe) {
            if ($recipe['id'] == $this->recipe_id) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found);
    }

    /**
     * Test the customize_recipe_search method.
     */
    public function test_customize_recipe_search() {
        // Create a query with search parameters
        $query = new WP_Query();
        $query->query_vars['post_type'] = 'dvnl_recipes';
        $query->is_search = true;

        // Set up GET parameters
        $_GET['difficulty'] = 'medium';
        $_GET['total_time'] = '30-60';

        // Apply the customization
        $this->search->customize_recipe_search($query);

        // Check that the meta query was added correctly
        $this->assertArrayHasKey('meta_query', $query->query_vars);
        $this->assertCount(2, $query->query_vars['meta_query']);

        // Clean up
        unset($_GET['difficulty']);
        unset($_GET['total_time']);
    }

    /**
     * Test the add_filters_from_get method.
     */
    public function test_add_filters_from_get() {
        // Create a query
        $query = new WP_Query();
        $query->query_vars['meta_query'] = array();

        // Set up GET parameters
        $_GET['difficulty'] = 'easy';

        // Apply the filters
        $this->search->add_filters_from_get($query);

        // Check that the difficulty filter was added
        $this->assertCount(1, $query->query_vars['meta_query']);
        $this->assertEquals('_dvnl_recipe_difficulty', $query->query_vars['meta_query'][0]['key']);
        $this->assertEquals('easy', $query->query_vars['meta_query'][0]['value']);

        // Clean up
        unset($_GET['difficulty']);
    }

    /**
     * Clean up after the test.
     */
    public function tear_down() {
        // Delete the test recipe
        wp_delete_post($this->recipe_id, true);

        parent::tear_down();
    }
}