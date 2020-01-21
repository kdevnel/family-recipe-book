<?php
/**
 * The template for displaying all single recipe posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Family_Recipe_Book
 */

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

/**
 * Get a custom header-recipe.php file, if it exists.
 * Otherwise, get default header.
 */
get_header( 'recipe' ); ?>

<div class="entry-content">

<?php if ( have_posts() ) : ?>

	<div>

	<?php

	/**
	 * dvnl-recipes-single-before-loop hook
	 *
	 * @hooked 		job_single_content_wrap_start 		10
	 */
	do_action( 'dvnl-recipes-single-before-loop' );

	while ( have_posts() ) : the_post(); ?>

		<h2 class="entry-title"><?php the_title(); ?></h2>

		<?php
		//include dvnl_recipes_get_template( 'single-content' );

		// Output the recipe parts
		// check if the repeater field has rows of data
		if( have_rows('recipe_part') ): ?>

			<div class="recipe-ingredients">
			<?php

			// loop through the rows of data
			while ( have_rows('recipe_part') ) : the_row(); ?>
				<div class="recipe-part">
					<h3 class="recipe-part-title"><?php the_sub_field('recipe_part_title'); ?></h3>
					<?php

					//display the recipe ingredients list (sub-repeater)
					if( have_rows( 'ingredient_list' ) ): ?>
						<ul class="ingredient-list">
						<?php
						while ( have_rows( 'ingredient_list' ) ) : the_row(); ?>
							<li class="ingredient-list-item"><?php the_sub_field('ingredient'); ?></li>
						<?php endwhile;
						else :
					endif; ?>
						</ul>

				</div><!-- .recipe-ingredients -->
			<?php endwhile;

			else :

			// no rows found

		endif; ?>
		</div>

		<?php // now to output the rest of the fields


	<?php endwhile;

	/**
	 * dvnl-recipes-single-after-loop hook
	 *
	 * @hooked 		job_single_content_wrap_end 		90
	 */
	do_action( 'dvnl-recipes-single-after-loop' ); ?>

	</div>
<?php endif; ?>

</div><!-- .entry-content -->

<?
/**
 * Get a custom footer-recipe.php file, if it exists.
 * Otherwise, get default header.
 */
get_footer( 'recipe' );