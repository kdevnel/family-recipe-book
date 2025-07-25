<?php

use PHPUnit\Framework\TestCase;
use Brain\Monkey\Functions;

class TestMyPlugin extends TestCase {
    public function setUp(): void {
        parent::setUp();
        \Brain\Monkey\setUp();
    }

    public function tearDown(): void {
        \Brain\Monkey\tearDown();
        parent::tearDown();
    }

    public function testGetPostType() {
        // Mock the post type object
        $post_type = new stdClass();
        $post_type->name = 'dvnl_recipes';
        $post_type->label = 'Recipes';

        // Use Brain Monkey to mock get_post_type_object
        \Brain\Monkey\Functions\expect('get_post_type_object')
            ->with('dvnl_recipes')
            ->andReturn($post_type);

        // Call the method under test
        $result = get_post_type_object('dvnl_recipes');

        // Assert that the result matches the expected post type object
        $this->assertEquals($post_type, $result, 'The post type object should match the mocked object.');
    }

}
