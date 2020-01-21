<?php
/**
 * The template for displaying all single posts. Called from single-recipe.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Family_Recipe_Book
 */

$meta = get_post_custom( $post->ID );

/**
 * dvnl-recipes-before-single hook
 */
do_action( 'dvnl-recipes-before-single', $meta );

?><div class="wrap-job"><?php

	/**
	 * dvnl-recipes-before-single-content hook
	 */
	do_action( 'dvnl-recipes-before-single-content', $meta );

		/**
		 * dvnl-recipes-single-content hook
		 */
		do_action( 'dvnl-recipes-single-content', $meta );
		echo "from single-content.php";

	/**
	 * dvnl-recipes-after-single-content hook
	 */
	do_action( 'dvnl-recipes-after-single-content', $meta );

?></div><!-- .wrap-employee --><?php

/**
 * dvnl-recipes-after-single hook
 */
do_action( 'dvnl-recipes-after-single', $meta );