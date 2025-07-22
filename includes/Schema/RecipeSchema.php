<?php
/**
 * Recipe Schema
 *
 * @package DVNL\FamilyRecipeBook
 * @category Schema
 * @author Your Name
 * @license GPL-2.0-or-later
 * @link https://github.com/yourusername/family-recipe-book
 */

namespace DVNL\FamilyRecipeBook\Schema;

/**
 * RecipeSchema class for adding schema markup to recipes.
 *
 * @package DVNL\FamilyRecipeBook
 * @category Schema
 * @author Your Name
 * @license GPL-2.0-or-later
 * @link https://github.com/yourusername/family-recipe-book
 */
class RecipeSchema
{
    /**
     * Register schema hooks.
     *
     * @return void
     */
    public function register()
    {
        add_action('wp_head', array($this, 'output_schema'), 10);
    }

    /**
     * Output schema markup in the head.
     *
     * @return void
     */
    public function output_schema()
    {
        // Only output schema on single recipe pages
        if (!is_singular('dvnl_recipes')) {
            return;
        }

        $post_id = get_the_ID();
        $schema = $this->generate_recipe_schema($post_id);

        if (!empty($schema)) {
            echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>';
        }
    }

    /**
     * Generate recipe schema for a post.
     *
     * @param int $post_id The post ID.
     * @return array The schema data.
     */
    private function generate_recipe_schema($post_id)
    {
        $post = get_post($post_id);

        if (!$post) {
            return array();
        }

        // Get post data
        $title = get_the_title($post_id);
        $permalink = get_permalink($post_id);
        $date_published = get_the_date('c', $post_id);
        $date_modified = get_the_modified_date('c', $post_id);
        $featured_image = get_the_post_thumbnail_url($post_id, 'full');
        $excerpt = get_the_excerpt($post_id);
        $content = $post->post_content;

        // Get recipe meta
        $prep_time = get_post_meta($post_id, '_dvnl_recipe_prep_time', true);
        $cook_time = get_post_meta($post_id, '_dvnl_recipe_cook_time', true);
        $total_time = get_post_meta($post_id, '_dvnl_recipe_total_time', true);
        $servings = get_post_meta($post_id, '_dvnl_recipe_servings', true);
        $calories = get_post_meta($post_id, '_dvnl_recipe_calories', true);
        $difficulty = get_post_meta($post_id, '_dvnl_recipe_difficulty', true);

        // Get author data
        $author_id = $post->post_author;
        $author_name = get_the_author_meta('display_name', $author_id);
        $author_url = get_author_posts_url($author_id);

        // Parse content to extract ingredients and instructions
        $ingredients = $this->extract_ingredients($content);
        $instructions = $this->extract_instructions($content);

        // Build schema
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Recipe',
            'name' => $title,
            'author' => array(
                '@type' => 'Person',
                'name' => $author_name,
                'url' => $author_url,
            ),
            'datePublished' => $date_published,
            'dateModified' => $date_modified,
            'description' => $excerpt,
            'url' => $permalink,
        );

        // Add image if available
        if ($featured_image) {
            $schema['image'] = array(
                '@type' => 'ImageObject',
                'url' => $featured_image,
                'width' => 1200,
                'height' => 800,
            );
        }

        // Add prep time if available
        if ($prep_time) {
            $schema['prepTime'] = 'PT' . $prep_time . 'M';
        }

        // Add cook time if available
        if ($cook_time) {
            $schema['cookTime'] = 'PT' . $cook_time . 'M';
        }

        // Add total time if available
        if ($total_time) {
            $schema['totalTime'] = 'PT' . $total_time . 'M';
        }

        // Add servings if available
        if ($servings) {
            $schema['recipeYield'] = $servings . ' servings';
        }

        // Add calories if available
        if ($calories) {
            $schema['nutrition'] = array(
                '@type' => 'NutritionInformation',
                'calories' => $calories . ' calories',
            );
        }

        // Add ingredients if available
        if (!empty($ingredients)) {
            $schema['recipeIngredient'] = $ingredients;
        }

        // Add instructions if available
        if (!empty($instructions)) {
            $schema['recipeInstructions'] = array();

            foreach ($instructions as $index => $instruction) {
                $schema['recipeInstructions'][] = array(
                    '@type' => 'HowToStep',
                    'position' => $index + 1,
                    'text' => $instruction,
                );
            }
        }

        // Add recipe category
        $categories = get_the_terms($post_id, 'dvnl_recipe_category');
        if ($categories && !is_wp_error($categories)) {
            $category_names = array();
            foreach ($categories as $category) {
                $category_names[] = $category->name;
            }
            $schema['recipeCategory'] = $category_names;
        }

        // Add recipe tags
        $tags = get_the_terms($post_id, 'dvnl_recipe_tag');
        if ($tags && !is_wp_error($tags)) {
            $tag_names = array();
            foreach ($tags as $tag) {
                $tag_names[] = $tag->name;
            }
            $schema['keywords'] = implode(', ', $tag_names);
        }

        return $schema;
    }

    /**
     * Extract ingredients from content.
     *
     * @param string $content The post content.
     * @return array The ingredients.
     */
    private function extract_ingredients($content)
    {
        $ingredients = array();

        // Look for the ingredients heading and list
        if (preg_match('/<h2[^>]*>Ingredients<\/h2>\s*<ul[^>]*>(.*?)<\/ul>/s', $content, $matches)) {
            if (isset($matches[1])) {
                // Extract list items
                preg_match_all('/<li[^>]*>(.*?)<\/li>/s', $matches[1], $items);
                if (isset($items[1]) && is_array($items[1])) {
                    foreach ($items[1] as $item) {
                        $ingredients[] = strip_tags($item);
                    }
                }
            }
        }

        return $ingredients;
    }

    /**
     * Extract instructions from content.
     *
     * @param string $content The post content.
     * @return array The instructions.
     */
    private function extract_instructions($content)
    {
        $instructions = array();

        // Look for the instructions heading and list
        if (preg_match('/<h2[^>]*>Instructions<\/h2>\s*<ol[^>]*>(.*?)<\/ol>/s', $content, $matches)) {
            if (isset($matches[1])) {
                // Extract list items
                preg_match_all('/<li[^>]*>(.*?)<\/li>/s', $matches[1], $items);
                if (isset($items[1]) && is_array($items[1])) {
                    foreach ($items[1] as $item) {
                        $instructions[] = strip_tags($item);
                    }
                }
            }
        }

        return $instructions;
    }
}