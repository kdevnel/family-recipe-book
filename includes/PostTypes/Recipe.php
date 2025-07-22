<?php
/**
 * Recipe Custom Post Type
 *
 * @package DVNL\FamilyRecipeBook
 */

namespace DVNL\FamilyRecipeBook\PostTypes;

/**
 * Recipe class for registering the recipe custom post type.
 */
class Recipe {

    /**
     * Post type name.
     *
     * @var string
     */
    private $post_type = 'dvnl_recipe';

    /**
     * Register the custom post type.
     *
     * @return void
     */
    public function register() {
        add_action( 'init', array( $this, 'register_post_type' ) );
        add_action( 'init', array( $this, 'register_taxonomies' ) );
    }

    /**
     * Register the recipe custom post type.
     *
     * @return void
     */
    public function register_post_type() {
        $labels = array(
            'name'                  => _x( 'Recipes', 'Post type general name', 'family-recipe-book' ),
            'singular_name'         => _x( 'Recipe', 'Post type singular name', 'family-recipe-book' ),
            'menu_name'             => _x( 'Recipes', 'Admin Menu text', 'family-recipe-book' ),
            'name_admin_bar'        => _x( 'Recipe', 'Add New on Toolbar', 'family-recipe-book' ),
            'add_new'               => __( 'Add New', 'family-recipe-book' ),
            'add_new_item'          => __( 'Add New Recipe', 'family-recipe-book' ),
            'new_item'              => __( 'New Recipe', 'family-recipe-book' ),
            'edit_item'             => __( 'Edit Recipe', 'family-recipe-book' ),
            'view_item'             => __( 'View Recipe', 'family-recipe-book' ),
            'all_items'             => __( 'All Recipes', 'family-recipe-book' ),
            'search_items'          => __( 'Search Recipes', 'family-recipe-book' ),
            'parent_item_colon'     => __( 'Parent Recipes:', 'family-recipe-book' ),
            'not_found'             => __( 'No recipes found.', 'family-recipe-book' ),
            'not_found_in_trash'    => __( 'No recipes found in Trash.', 'family-recipe-book' ),
            'featured_image'        => _x( 'Recipe Image', 'Overrides the "Featured Image" phrase', 'family-recipe-book' ),
            'set_featured_image'    => _x( 'Set recipe image', 'Overrides the "Set featured image" phrase', 'family-recipe-book' ),
            'remove_featured_image' => _x( 'Remove recipe image', 'Overrides the "Remove featured image" phrase', 'family-recipe-book' ),
            'use_featured_image'    => _x( 'Use as recipe image', 'Overrides the "Use as featured image" phrase', 'family-recipe-book' ),
            'archives'              => _x( 'Recipe archives', 'The post type archive label used in nav menus', 'family-recipe-book' ),
            'insert_into_item'      => _x( 'Insert into recipe', 'Overrides the "Insert into post" phrase', 'family-recipe-book' ),
            'uploaded_to_this_item' => _x( 'Uploaded to this recipe', 'Overrides the "Uploaded to this post" phrase', 'family-recipe-book' ),
            'filter_items_list'     => _x( 'Filter recipes list', 'Screen reader text for the filter links', 'family-recipe-book' ),
            'items_list_navigation' => _x( 'Recipes list navigation', 'Screen reader text for the pagination', 'family-recipe-book' ),
            'items_list'            => _x( 'Recipes list', 'Screen reader text for the items list', 'family-recipe-book' ),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'recipes' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 20,
            'menu_icon'          => 'dashicons-food',
            'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'custom-fields', 'revisions' ),
            'show_in_rest'       => true,
            'template'           => array(
                array( 'core/heading', array(
                    'level' => 2,
                    'content' => __( 'Description', 'family-recipe-book' ),
                ) ),
                array( 'core/paragraph', array(
                    'placeholder' => __( 'Write a brief description of your recipe...', 'family-recipe-book' ),
                ) ),
                array( 'core/heading', array(
                    'level' => 2,
                    'content' => __( 'Ingredients', 'family-recipe-book' ),
                ) ),
                array( 'core/list', array(
                    'placeholder' => __( 'Add ingredients...', 'family-recipe-book' ),
                ) ),
                array( 'core/heading', array(
                    'level' => 2,
                    'content' => __( 'Instructions', 'family-recipe-book' ),
                ) ),
                array( 'core/list', array(
                    'ordered' => true,
                    'placeholder' => __( 'Add instructions...', 'family-recipe-book' ),
                ) ),
                array( 'dvnl/recipe-details', array() ),
            ),
            'template_lock'      => 'all',
        );

        register_post_type( $this->post_type, $args );
    }

    /**
     * Register taxonomies for the recipe post type.
     *
     * @return void
     */
    public function register_taxonomies() {
        // Register Recipe Category taxonomy
        $category_labels = array(
            'name'                       => _x( 'Recipe Categories', 'Taxonomy general name', 'family-recipe-book' ),
            'singular_name'              => _x( 'Recipe Category', 'Taxonomy singular name', 'family-recipe-book' ),
            'search_items'               => __( 'Search Recipe Categories', 'family-recipe-book' ),
            'popular_items'              => __( 'Popular Recipe Categories', 'family-recipe-book' ),
            'all_items'                  => __( 'All Recipe Categories', 'family-recipe-book' ),
            'parent_item'                => __( 'Parent Recipe Category', 'family-recipe-book' ),
            'parent_item_colon'          => __( 'Parent Recipe Category:', 'family-recipe-book' ),
            'edit_item'                  => __( 'Edit Recipe Category', 'family-recipe-book' ),
            'update_item'                => __( 'Update Recipe Category', 'family-recipe-book' ),
            'add_new_item'               => __( 'Add New Recipe Category', 'family-recipe-book' ),
            'new_item_name'              => __( 'New Recipe Category Name', 'family-recipe-book' ),
            'separate_items_with_commas' => __( 'Separate recipe categories with commas', 'family-recipe-book' ),
            'add_or_remove_items'        => __( 'Add or remove recipe categories', 'family-recipe-book' ),
            'choose_from_most_used'      => __( 'Choose from the most used recipe categories', 'family-recipe-book' ),
            'not_found'                  => __( 'No recipe categories found.', 'family-recipe-book' ),
            'menu_name'                  => __( 'Categories', 'family-recipe-book' ),
        );

        $category_args = array(
            'labels'            => $category_labels,
            'hierarchical'      => true,
            'public'            => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud'     => true,
            'show_in_rest'      => true,
            'rewrite'           => array( 'slug' => 'recipe-category' ),
        );

        register_taxonomy( 'dvnl_recipe_category', $this->post_type, $category_args );

        // Register Recipe Tag taxonomy
        $tag_labels = array(
            'name'                       => _x( 'Recipe Tags', 'Taxonomy general name', 'family-recipe-book' ),
            'singular_name'              => _x( 'Recipe Tag', 'Taxonomy singular name', 'family-recipe-book' ),
            'search_items'               => __( 'Search Recipe Tags', 'family-recipe-book' ),
            'popular_items'              => __( 'Popular Recipe Tags', 'family-recipe-book' ),
            'all_items'                  => __( 'All Recipe Tags', 'family-recipe-book' ),
            'parent_item'                => null,
            'parent_item_colon'          => null,
            'edit_item'                  => __( 'Edit Recipe Tag', 'family-recipe-book' ),
            'update_item'                => __( 'Update Recipe Tag', 'family-recipe-book' ),
            'add_new_item'               => __( 'Add New Recipe Tag', 'family-recipe-book' ),
            'new_item_name'              => __( 'New Recipe Tag Name', 'family-recipe-book' ),
            'separate_items_with_commas' => __( 'Separate recipe tags with commas', 'family-recipe-book' ),
            'add_or_remove_items'        => __( 'Add or remove recipe tags', 'family-recipe-book' ),
            'choose_from_most_used'      => __( 'Choose from the most used recipe tags', 'family-recipe-book' ),
            'not_found'                  => __( 'No recipe tags found.', 'family-recipe-book' ),
            'menu_name'                  => __( 'Tags', 'family-recipe-book' ),
        );

        $tag_args = array(
            'labels'            => $tag_labels,
            'hierarchical'      => false,
            'public'            => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud'     => true,
            'show_in_rest'      => true,
            'rewrite'           => array( 'slug' => 'recipe-tag' ),
        );

        register_taxonomy( 'dvnl_recipe_tag', $this->post_type, $tag_args );
    }
}