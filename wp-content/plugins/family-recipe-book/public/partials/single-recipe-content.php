<?php
/**
 * The template for displaying the recipe content
 *
 * @since 1.0.0
 *
 * @package    Family_Recipe_Book
 * @subpackage Family_Recipe_Book/public
 */

the_post_thumbnail( 'post_thumbnail', ['itemprop' => 'image'] );

the_content();