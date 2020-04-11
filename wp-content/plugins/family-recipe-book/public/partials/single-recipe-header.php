<?php
/**
 * The template for displaying the recipe header
 *
 * @since 1.0.0
 *
 * @package    Family_Recipe_Book
 * @subpackage Family_Recipe_Book/public
 */
?>

<h2 class="entry-title" itemprop="name"><?php the_title(); ?></h2>

<nav class="recipe-post-nav">
    <ul>
        <li>Jump to: </li>
        <li><a href="#details">Details</a></li>
        <li><a href="#ingredients">Ingredients</a></li>
        <li><a href="#method">Method</a></li>
        <li><a href="#nutrition">Nutrition</a></li>
    </ul>
</nav>