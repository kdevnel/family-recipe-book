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
        <h3 id="method" class="recipe-method-title">Method</h3>
        <?php

            while ( have_rows('how_to_step') ) : the_row(); ?>
                <div class="instruction-part-wrap">
                    <h4 class="instruction-part-title"><?php the_sub_field('how_to_part'); ?></h4>

                    <?php if ( have_rows( 'instruction_step' ) ): ?>
                        <ol class="instruction-list" itemprop="recipeInstructions">
                            <?php while ( have_rows( 'instruction_step' ) ) : the_row(); ?>
                                <li class="recipe-method-list-item" itemprop="howToStep"><?php the_sub_field('how_to_step'); ?></li>
                            <?php endwhile; ?>
                        </ol>
                   <?php endif; ?>

                </div><!-- .instruction-part-wrap -->

            <?php endwhile; ?>

        </ol>

    </div><!-- recipe-method-wrap -->

<?php else : ?>
    <div>No method listed</div>

<?php endif; ?>
<hr />