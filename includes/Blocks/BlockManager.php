<?php
/**
 * Block Manager
 *
 * @package DVNL\FamilyRecipeBook
 * @category Blocks
 * @author Your Name
 * @license GPL-2.0-or-later
 * @link https://github.com/yourusername/family-recipe-book
 */

namespace DVNL\FamilyRecipeBook\Blocks;

/**
 * BlockManager class for managing custom blocks.
 *
 * @package DVNL\FamilyRecipeBook
 * @category Blocks
 * @author Your Name
 * @license GPL-2.0-or-later
 * @link https://github.com/yourusername/family-recipe-book
 */
class BlockManager
{
    /**
     * Register custom blocks.
     *
     * @return void
     */
    public function register()
    {
        add_action('init', array($this, 'register_blocks'));
        add_action('enqueue_block_editor_assets', array($this, 'enqueue_editor_assets'));
    }

    /**
     * Register custom blocks.
     *
     * @return void
     */
    public function register_blocks()
    {
        // Check if Gutenberg is active
        if (!function_exists('register_block_type')) {
            return;
        }

        // Register recipe details block
        register_block_type(
            DVNL_FAMILY_RECIPE_BOOK_PLUGIN_DIR . 'build/blocks/recipe-details',
            array(
                'render_callback' => array($this, 'render_recipe_details_block'),
            )
        );
    }

    /**
     * Enqueue editor assets.
     *
     * @return void
     */
    public function enqueue_editor_assets()
    {
        // Enqueue block editor JS
        wp_enqueue_script(
            'dvnl-family-recipe-book-blocks-editor',
            DVNL_FAMILY_RECIPE_BOOK_PLUGIN_URL . 'build/blocks.js',
            array('wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor', 'wp-components'),
            DVNL_FAMILY_RECIPE_BOOK_VERSION,
            true
        );

        // Enqueue block editor styles
        wp_enqueue_style(
            'dvnl-family-recipe-book-blocks-editor-style',
            DVNL_FAMILY_RECIPE_BOOK_PLUGIN_URL . 'build/blocks.css',
            array('wp-edit-blocks'),
            DVNL_FAMILY_RECIPE_BOOK_VERSION
        );

        // Localize script with recipe data
        wp_localize_script(
            'dvnl-family-recipe-book-blocks-editor',
            'dvnlFamilyRecipeBook',
            array(
                'pluginUrl' => DVNL_FAMILY_RECIPE_BOOK_PLUGIN_URL,
            )
        );
    }

    /**
     * Render recipe details block.
     *
     * @param array  $attributes Block attributes.
     * @param string $content    Block content.
     * @return string Rendered block output.
     */
    public function render_recipe_details_block($attributes, $content)
    {
        // Get post meta if we're in a post
        $post_id = get_the_ID();

        if (!$post_id) {
            return '';
        }

        $prep_time = get_post_meta($post_id, '_dvnl_recipe_prep_time', true);
        $cook_time = get_post_meta($post_id, '_dvnl_recipe_cook_time', true);
        $total_time = get_post_meta($post_id, '_dvnl_recipe_total_time', true);
        $servings = get_post_meta($post_id, '_dvnl_recipe_servings', true);
        $calories = get_post_meta($post_id, '_dvnl_recipe_calories', true);
        $difficulty = get_post_meta($post_id, '_dvnl_recipe_difficulty', true);

        // Translate difficulty
        $difficulty_label = '';
        if ($difficulty === 'easy') {
            $difficulty_label = __('Easy', 'family-recipe-book');
        } elseif ($difficulty === 'medium') {
            $difficulty_label = __('Medium', 'family-recipe-book');
        } elseif ($difficulty === 'hard') {
            $difficulty_label = __('Hard', 'family-recipe-book');
        }

        // Start output buffer
        ob_start();
        ?>
        <div class="dvnl-recipe-details">
            <h2><?php esc_html_e('Recipe Details', 'family-recipe-book'); ?></h2>
            <div class="dvnl-recipe-details-grid">
                <?php if ($prep_time) : ?>
                <div class="dvnl-recipe-detail">
                    <span class="dvnl-recipe-detail-label"><?php esc_html_e('Prep Time:', 'family-recipe-book'); ?></span>
                    <span class="dvnl-recipe-detail-value"><?php echo esc_html($prep_time); ?> <?php esc_html_e('minutes', 'family-recipe-book'); ?></span>
                </div>
                <?php endif; ?>

                <?php if ($cook_time) : ?>
                <div class="dvnl-recipe-detail">
                    <span class="dvnl-recipe-detail-label"><?php esc_html_e('Cook Time:', 'family-recipe-book'); ?></span>
                    <span class="dvnl-recipe-detail-value"><?php echo esc_html($cook_time); ?> <?php esc_html_e('minutes', 'family-recipe-book'); ?></span>
                </div>
                <?php endif; ?>

                <?php if ($total_time) : ?>
                <div class="dvnl-recipe-detail">
                    <span class="dvnl-recipe-detail-label"><?php esc_html_e('Total Time:', 'family-recipe-book'); ?></span>
                    <span class="dvnl-recipe-detail-value"><?php echo esc_html($total_time); ?> <?php esc_html_e('minutes', 'family-recipe-book'); ?></span>
                </div>
                <?php endif; ?>

                <?php if ($servings) : ?>
                <div class="dvnl-recipe-detail">
                    <span class="dvnl-recipe-detail-label"><?php esc_html_e('Servings:', 'family-recipe-book'); ?></span>
                    <span class="dvnl-recipe-detail-value"><?php echo esc_html($servings); ?></span>
                </div>
                <?php endif; ?>

                <?php if ($calories) : ?>
                <div class="dvnl-recipe-detail">
                    <span class="dvnl-recipe-detail-label"><?php esc_html_e('Calories:', 'family-recipe-book'); ?></span>
                    <span class="dvnl-recipe-detail-value"><?php echo esc_html($calories); ?> <?php esc_html_e('per serving', 'family-recipe-book'); ?></span>
                </div>
                <?php endif; ?>

                <?php if ($difficulty) : ?>
                <div class="dvnl-recipe-detail">
                    <span class="dvnl-recipe-detail-label"><?php esc_html_e('Difficulty:', 'family-recipe-book'); ?></span>
                    <span class="dvnl-recipe-detail-value"><?php echo esc_html($difficulty_label); ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}