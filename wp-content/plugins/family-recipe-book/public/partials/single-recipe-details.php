<?php
/**
 * The template for displaying the recipe details information on single recipes
 *
 * @since 1.0.0
 *
 * @package    Family_Recipe_Book
 * @subpackage Family_Recipe_Book/public
 */
?>

<div class="recipe-details-wrap">
        <h3 class="recipe-nutrition-title">Details</h3>
        <div>Prep Time: <?php the_field( 'prep_time' ); ?></div>
        <div>Cook Time: <?php the_field( 'cook_time' ); ?></div>
        <div>Total Time: <?php the_field( 'total_time' ); ?></div>
        <div>Serves: <?php the_field( 'serves' ); ?></div>
</div><!-- recipe-details-wrap -->
