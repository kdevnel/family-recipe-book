<?php
/**
 * The template for displaying the recipe details information on single recipes
 *
 * @since 1.0.0
 *
 * @package    Family_Recipe_Book
 * @subpackage Family_Recipe_Book/public
 */

$creator = get_the_term_list( $post->ID, 'recipe_creator', '', ', ', '' );

function recipe_datetime($fieldScale, $fieldTime){
    switch ( get_field( $fieldScale ) ) {
        case 'days':
            $recipe_datetime = 'P' . get_field( $fieldTime ) . 'DT0H0M0S';
        break;
        case 'mins':
            $recipe_datetime = 'P0DT0H' . get_field( $fieldTime ) . 'M0S';
        break;
        case 'hours':
            $recipe_datetime = 'P0DT' . get_field( $fieldTime ) . 'H0M0S';
        break;
    }
    return $recipe_datetime;
}

$prepTime = get_field( 'prep_time' ) . ' ' . get_field( 'prep_timescale' );
$cookTime = get_field( 'cook_time' ) . ' ' . get_field( 'cook_timescale' );
$totalTime = get_field( 'total_time' ) . ' ' . get_field( 'total_timescale' );
?>

<div class="recipe-details-wrap">
    <?php if ($creator) { ?>
    <div class="recipe-creator">Original creator: <?php echo $creator; ?></div>
    <?php } ?>

    <ul class="recipe-details-list">
        <li class="recipe-prep-time" itemprop="prepTime" datetime="<?php echo recipe_datetime('prep_timescale','prep_time'); ?>">Prep Time: <?php echo $prepTime; ?></li>
        <li class="recipe-cook-time" itemprop="cookTime" datetime="<?php echo recipe_datetime('cook_timescale','cook_time'); ?>">Cook Time: <?php echo $cookTime; ?></li>
        <li class="recipe-total-time" itemprop="totalTime" datetime="<?php echo recipe_datetime('total_timescale','total_time'); ?>">Total Time: <?php echo $totalTime; ?></li>
        <li class="recipe-yield" itemprop="recipeYield">Serves: <?php the_field( 'serves' ); ?></li>
        <li>Difficulty: <?php the_field( 'difficulty' ); ?></li>
    </ul>
</div><!-- recipe-details-wrap -->
