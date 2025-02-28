<?php
/**
 * Block Template Manager
 *
 * @package DVNL\FamilyRecipeBook
 * @category Blocks
 * @author Your Name
 * @license GPL-2.0-or-later
 * @link https://github.com/yourusername/family-recipe-book
 */

namespace DVNL\FamilyRecipeBook\Blocks;

/**
 * TemplateManager class for managing block templates.
 *
 * @package DVNL\FamilyRecipeBook
 * @category Blocks
 * @author Your Name
 * @license GPL-2.0-or-later
 * @link https://github.com/yourusername/family-recipe-book
 */
class TemplateManager
{
    /**
     * Register block templates.
     *
     * @return void
     */
    public function register()
    {
        add_action('init', array($this, 'register_template_lock'));
    }

    /**
     * Register template lock for recipe post type.
     *
     * @return void
     */
    public function register_template_lock()
    {
        $post_type_object = get_post_type_object('dvnl_recipe');

        if ($post_type_object) {
            $post_type_object->template_lock = 'all';
        }
    }

    /**
     * Get the recipe template.
     *
     * @return array The recipe template.
     */
    public static function get_recipe_template()
    {
        return array(
            array('core/heading', array(
                'level' => 2,
                'content' => __('Description', 'family-recipe-book'),
            )),
            array('core/paragraph', array(
                'placeholder' => __('Write a brief description of your recipe...', 'family-recipe-book'),
            )),
            array('core/heading', array(
                'level' => 2,
                'content' => __('Ingredients', 'family-recipe-book'),
            )),
            array('core/list', array(
                'placeholder' => __('Add ingredients...', 'family-recipe-book'),
            )),
            array('core/heading', array(
                'level' => 2,
                'content' => __('Instructions', 'family-recipe-book'),
            )),
            array('core/list', array(
                'ordered' => true,
                'placeholder' => __('Add instructions...', 'family-recipe-book'),
            )),
            array('dvnl/recipe-details', array()),
        );
    }
}