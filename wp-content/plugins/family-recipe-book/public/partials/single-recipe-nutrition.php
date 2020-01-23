<?php
/**
 * The template for displaying the nutritional information on single recipes
 *
 * @since 1.0.0
 *
 * @package    Family_Recipe_Book
 * @subpackage Family_Recipe_Book/public
 */
?>

<div class="recipe-nutrition-wrap">
        <h3 class="recipe-nutrition-title">Nutritional Information</h3>
        <div>Calories: <?php the_field( 'calories' ); ?></div>
        <div>Carbohydrates: <?php the_field( 'carbohydrates' ); ?></div>
        <div>Fats: <?php the_field( 'fats' ); ?></div>
        <div>Proteins: <?php the_field( 'proteins' ); ?></div>
        <div>Fibre: <?php the_field( 'fibre' ); ?></div>
</div><!-- recipe-nutrition-wrap -->
