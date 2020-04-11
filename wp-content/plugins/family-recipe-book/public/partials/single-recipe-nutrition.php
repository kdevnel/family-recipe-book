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

<?php if ( get_field( 'add_nutrition' ) == TRUE ) { ?>
<div class="recipe-nutrition-wrap">
    <h3 id="nutrition" class="recipe-nutrition-title">Nutritional Information</h3>
    <ul>
        <li>Calories: <?php the_field( 'calories' ); ?></li>
        <li>Carbohydrates: <?php the_field( 'carbohydrates' ); ?></li>
        <li>Fats: <?php the_field( 'fats' ); ?></li>
        <li>Proteins: <?php the_field( 'proteins' ); ?></li>
        <li>Fibre: <?php the_field( 'fibre' ); ?></li>
    </ul>
</div><!-- recipe-nutrition-wrap -->
<?php };
