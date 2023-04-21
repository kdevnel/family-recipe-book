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
	<?php wp_nonce_field( 'dvnl_recipe_submit', 'dvnl_recipe_ingredients_nonce' ); ?>
	<p class="meta-options dvnl-ingredients field">
		<label for="dvnl_ingredients">Ingredients</label>
		<input
			id="dvnl_ingredients"
			type="textarea"
			name="dvnl_ingredients"
			value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'dvnl_ingredients', true ) ); ?>">
	</p>
</div>
