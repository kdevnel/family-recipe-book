<?php
/**
 * Display of the recipe details metabox
 *
 * @link       https://devnel.blog
 * @since      2.0.0
 *
 * @package    Dvnl_Family_Recipe_Book
 * @subpackage Dvnl_Family_Recipe_Book/admin/partials
 */

?>
<div class="dvnl-recipes metabox">
    <style scoped>
        .dvnl-recipes.metabox{
            display: grid;
            grid-template-columns: max-content 1fr;
            grid-row-gap: 10px;
            grid-column-gap: 20px;
        }
        .dvnl-recipes.field{
            display: contents;
        }
    </style>
    <?php wp_nonce_field( 'dvnl_recipe_submit', 'dvnl_recipe_details_nonce' ); ?>
    <p class="meta-options dvnl-recipes field">
        <label for="dvnl_original_author">Original Author</label>
        <input
            id="dvnl_original_author"
            type="text"
            name="dvnl_original_author"
            value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'dvnl_original_author', true ) ); ?>">
    </p>
    <p class="meta-options dvnl-recipes field">
        <label for="dvnl_published_date">Published Date</label>
        <input
            id="dvnl_published_date"
            type="date"
            name="dvnl_published_date"
            value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'dvnl_published_date', true ) ); ?>">
    </p>
    <p class="meta-options dvnl-recipes field">
        <label for="dvnl_cost">Cost</label>
        <input
            id="dvnl_cost"
            type="number"
            name="dvnl_cost"
            value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'dvnl_cost', true ) ); ?>">
    </p>
</div>
