<?php
/**
 * The template for displaying the recipe meta on single recipes
 *
 * @since 1.0.0
 *
 * @package    Family_Recipe_Book
 * @subpackage Family_Recipe_Book/public
 */


$cuisine = get_the_term_list( $post->ID, 'cuisine_type', '', ', ', '' );
$meal = get_the_term_list( $post->ID, 'meal_type', '', ', ', '' );
?>

<div class="recipe-meta-wrap">
    <div class="recipe-cuisine">Cuisine: <?php echo $cuisine; ?></div>
    <div class="recipe-meal-type">Meal: <?php echo $meal; ?></div>
</div>