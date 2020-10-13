<?php
/**
 * The template for displaying the ingredients on single recipes
 *
 * @since 1.0.0
 *
 * @package    Family_Recipe_Book
 * @subpackage Family_Recipe_Book/public
 */

// check if the repeater field has rows of data
if (have_rows('recipe_part')): ?>

    <div class="recipe-ingredients">
        <h3 id="ingredients" class="ingredients-title">Ingredients</h3>
        <?php

        // loop through the rows of data
        while (have_rows('recipe_part')) : the_row(); ?>
            <div class="ingredient-part-wrap">
                <h4 class="ingredient-part-title"><?php the_sub_field('recipe_part_title'); ?></h4>
                <?php

                //display the recipe ingredients list (sub-repeater)
                if (have_rows('ingredient_list')): ?>
                    <ul class="ingredient-list">
                    <?php
                    while (have_rows('ingredient_list')) : the_row(); ?>
                        <li class="ingredient-list-item" itemprop="recipeIngredient"><?php the_sub_field('quantity'); ?> <?php the_sub_field('measurement'); ?> <?php the_sub_field('ingredient'); ?></li>
                    <?php endwhile; ?>
                    </ul>
                <?php endif; ?>

            </div><!-- .ingredient-part-wrap -->
        <?php endwhile; ?>
    </div>
<?php else : ?>
    <div>No ingredients listed</div>

<?php endif; ?>
<hr />
