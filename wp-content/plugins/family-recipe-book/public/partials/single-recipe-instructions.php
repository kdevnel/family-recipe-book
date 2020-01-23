<?php
/**
 * The template for displaying the method on single recipes
 *
 * @since 1.0.0
 *
 * @package    Family_Recipe_Book
 * @subpackage Family_Recipe_Book/public
 */

// check if the repeater field has rows of data
if( have_rows('how_to_step') ): ?>

    <div class="recipe-method-wrap">
        <h3 class="recipe-method-title">Method</h3>
        <ol class="recipe-method-list">
        <?php

            while ( have_rows('how_to_step') ) : the_row(); ?>
                <li class="recipe-method-list-item"><?php the_sub_field('step'); ?></li>
            <?php endwhile; ?>

        </ol>


    </div><!-- recipe-method-wrap -->

<?php else : ?>
    <div>No method listed</div>

<?php endif; ?>