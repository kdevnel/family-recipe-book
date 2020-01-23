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

		/**
		 * dvnl_recipes_single_details hook
		 *
		 * @hooked 		single_recipe_details 		10
		 */
		do_action( 'dvnl_recipes_single_details');

		/**
		 * dvnl_recipes_single_ingredients hook
		 *
		 * @hooked 		single_recipe_ingredients 		10
		 */
		do_action( 'dvnl_recipes_single_ingredients');

		/**
		 * dvnl_recipes_single_instructions hook
		 *
		 * @hooked 		single_recipe_instructions 		10
		 */
		do_action( 'dvnl_recipes_single_instructions');

		/**
		 * dvnl_recipes_single_nutrition hook
		 *
		 * @hooked 		single_recipe_nutrition 		10
		 */
		 do_action( 'dvnl_recipes_single_nutrition'); ?>

		<?php // now to output the rest of the fields ?>


	<?php endwhile;

	/**
	 * dvnl-recipes-single-after-loop hook
	 *
	 * @hooked 		job_single_content_wrap_end 		90
	 */
	do_action( 'dvnl-recipes-single-after-loop' ); ?>

<?php endif; ?>

</div><!-- .entry-content -->

<?
/**
 * Get a custom footer-recipe.php file, if it exists.
 * Otherwise, get default header.
 */
get_footer( 'recipe' );