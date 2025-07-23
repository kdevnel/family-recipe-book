<?php
/**
 * Schema Class
 *
 * @package Family_Recipe_Book
 */

namespace dvnl;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Schema Class
 *
 * Adds Schema.org markup for recipes to improve SEO.
 */
class Schema {

	/**
	 * Initialize the class
	 */
	public function __construct() {
		add_action( 'wp_head', array( $this, 'add_recipe_schema' ) );
	}

	/**
	 * Add recipe schema to the head of recipe pages
	 */
	public function add_recipe_schema() {
		// Only output schema on single recipe pages
		if ( ! is_singular( 'dvnl_recipes' ) ) {
			return;
		}

		$post_id = get_the_ID();
		$schema  = $this->generate_recipe_schema( $post_id );

		if ( ! empty( $schema ) ) {
			echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>';
		}
	}

	/**
	 * Generate recipe schema for a post
	 *
	 * @param int $post_id The post ID.
	 * @return array The schema data.
	 */
	private function generate_recipe_schema( $post_id ) {
		$post = get_post( $post_id );
		if ( ! $post ) {
			return array();
		}

		// Get recipe details
		$prep_time   = get_post_meta( $post_id, '_dvnl_recipe_prep_time', true );
		$cook_time   = get_post_meta( $post_id, '_dvnl_recipe_cook_time', true );
		$total_time  = get_post_meta( $post_id, '_dvnl_recipe_total_time', true );
		$servings    = get_post_meta( $post_id, '_dvnl_recipe_servings', true );
		$calories    = get_post_meta( $post_id, '_dvnl_recipe_calories', true );
		$difficulty  = get_post_meta( $post_id, '_dvnl_recipe_difficulty', true );

		// Get recipe content
		$content = $post->post_content;

		// Get recipe ingredients and instructions from blocks
		$ingredients = array();
		$instructions = array();

		// Parse blocks to extract ingredients and instructions
		if ( function_exists( 'parse_blocks' ) ) {
			$blocks = parse_blocks( $content );

			foreach ( $blocks as $block ) {
				if ( 'dvnl/recipe-ingredients' === $block['blockName'] && ! empty( $block['attrs']['ingredients'] ) ) {
					foreach ( $block['attrs']['ingredients'] as $ingredient ) {
						$ingredients[] = $ingredient;
					}
				} elseif ( 'dvnl/recipe-instructions' === $block['blockName'] && ! empty( $block['attrs']['steps'] ) ) {
					foreach ( $block['attrs']['steps'] as $index => $step ) {
						$instructions[] = array(
							'@type' => 'HowToStep',
							'position' => $index + 1,
							'text' => $step,
						);
					}
				}
			}
		}

		// Build schema
		$schema = array(
			'@context' => 'https://schema.org',
			'@type' => 'Recipe',
			'name' => get_the_title( $post_id ),
			'author' => array(
				'@type' => 'Person',
				'name' => get_the_author_meta( 'display_name', $post->post_author ),
			),
			'datePublished' => get_the_date( 'c', $post_id ),
			'description' => $this->get_recipe_description( $post_id ),
			'image' => get_the_post_thumbnail_url( $post_id, 'full' ),
			'url' => get_permalink( $post_id ),
		);

		// Add recipe details
		if ( ! empty( $prep_time ) ) {
			$schema['prepTime'] = $this->minutes_to_iso8601_duration( $prep_time );
		}

		if ( ! empty( $cook_time ) ) {
			$schema['cookTime'] = $this->minutes_to_iso8601_duration( $cook_time );
		}

		if ( ! empty( $total_time ) ) {
			$schema['totalTime'] = $this->minutes_to_iso8601_duration( $total_time );
		}

		if ( ! empty( $servings ) ) {
			$schema['recipeYield'] = $servings;
		}

		if ( ! empty( $calories ) ) {
			$schema['nutrition'] = array(
				'@type' => 'NutritionInformation',
				'calories' => $calories . ' calories',
			);
		}

		// Add recipe ingredients
		if ( ! empty( $ingredients ) ) {
			$schema['recipeIngredient'] = $ingredients;
		}

		// Add recipe instructions
		if ( ! empty( $instructions ) ) {
			$schema['recipeInstructions'] = $instructions;
		}

		// Add recipe categories
		$categories = get_the_terms( $post_id, 'recipe_category' );
		if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
			$schema['recipeCategory'] = array();
			foreach ( $categories as $category ) {
				$schema['recipeCategory'][] = $category->name;
			}
		}

		// Add recipe tags
		$tags = get_the_terms( $post_id, 'recipe_tag' );
		if ( ! empty( $tags ) && ! is_wp_error( $tags ) ) {
			$schema['keywords'] = array();
			foreach ( $tags as $tag ) {
				$schema['keywords'][] = $tag->name;
			}
			$schema['keywords'] = implode( ', ', $schema['keywords'] );
		}

		return $schema;
	}

	/**
	 * Get the recipe description
	 *
	 * @param int $post_id The post ID.
	 * @return string The recipe description.
	 */
	private function get_recipe_description( $post_id ) {
		// Try to get the excerpt first
		$excerpt = get_the_excerpt( $post_id );
		if ( ! empty( $excerpt ) ) {
			return $excerpt;
		}

		// If no excerpt, try to get the first paragraph from content
		$content = get_post_field( 'post_content', $post_id );
		if ( function_exists( 'parse_blocks' ) ) {
			$blocks = parse_blocks( $content );
			foreach ( $blocks as $block ) {
				if ( 'core/paragraph' === $block['blockName'] && ! empty( $block['innerHTML'] ) ) {
					return wp_strip_all_tags( $block['innerHTML'] );
				}
			}
		}

		// Fallback to post title
		return get_the_title( $post_id );
	}

	/**
	 * Convert minutes to ISO 8601 duration format
	 *
	 * @param int $minutes The number of minutes.
	 * @return string The ISO 8601 duration.
	 */
	private function minutes_to_iso8601_duration( $minutes ) {
		if ( empty( $minutes ) ) {
			return '';
		}

		$hours = floor( $minutes / 60 );
		$mins = $minutes % 60;

		$duration = 'PT';
		if ( $hours > 0 ) {
			$duration .= $hours . 'H';
		}
		if ( $mins > 0 ) {
			$duration .= $mins . 'M';
		}

		return $duration;
	}
}

// Initialize the class.
new Schema();