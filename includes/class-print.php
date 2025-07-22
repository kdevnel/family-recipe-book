<?php
/**
 * Print Class
 *
 * @package Family_Recipe_Book
 */

namespace dvnl;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Print Class
 *
 * Handles the recipe printing functionality.
 */
class Print_Recipe {

	   /**
		* Settings instance
		*
		* @var Settings
		*/
	   private $settings;

	   /**
		* Initialize the class
		*
		* @param Settings $settings Settings instance.
		*/
	   public function __construct( Settings $settings ) {
			   $this->settings = $settings;

			   // Only proceed if print button is enabled
			   if ( 'yes' !== $this->settings->get_option( 'enable_print_button', 'yes' ) ) {
					   return;
			   }

		// Add print button to recipe content
		add_filter( 'the_content', array( $this, 'add_print_button' ) );

		// Add print styles
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_print_styles' ) );

		// Add print script
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_print_script' ) );

		// Register print endpoint
		add_action( 'init', array( $this, 'register_print_endpoint' ) );

		// Handle print template
		add_action( 'template_redirect', array( $this, 'handle_print_template' ) );
	}

	/**
	 * Add print button to recipe content
	 *
	 * @param string $content Post content.
	 * @return string Modified content.
	 */
	public function add_print_button( $content ) {
		// Only add to recipe post type
		if ( ! is_singular( 'dvnl_recipes' ) ) {
			return $content;
		}

		// Create print button
		$print_url = add_query_arg( 'print', 'true', get_permalink() );
		$button = '<div class="dvnl-recipe-print-button">';
		$button .= '<a href="' . esc_url( $print_url ) . '" class="dvnl-recipe-print-link" target="_blank">';
		$button .= '<span class="dashicons dashicons-printer"></span> ';
		$button .= esc_html__( 'Print Recipe', 'family-recipe-book' );
		$button .= '</a>';
		$button .= '</div>';

		// Add button before content
		return $button . $content;
	}

	/**
	 * Enqueue print styles
	 */
	public function enqueue_print_styles() {
		// Only enqueue on recipe pages
		if ( ! is_singular( 'dvnl_recipes' ) ) {
			return;
		}

		wp_enqueue_style(
			'dvnl-family-recipe-book-print',
			DVNL_FAMILY_RECIPE_BOOK_PLUGIN_URL . 'assets/css/print.css',
			array(),
			DVNL_FAMILY_RECIPE_BOOK_VERSION
		);

		// Add inline print button styles
		$print_button_css = '
			.dvnl-recipe-print-button {
				margin-bottom: 20px;
				text-align: right;
			}
			.dvnl-recipe-print-link {
				display: inline-flex;
				align-items: center;
				background-color: #f7f7f7;
				border: 1px solid #ddd;
				border-radius: 4px;
				padding: 8px 16px;
				color: #333;
				text-decoration: none;
				font-size: 14px;
				transition: all 0.2s ease;
			}
			.dvnl-recipe-print-link:hover {
				background-color: #f0f0f0;
				border-color: #ccc;
				color: #000;
			}
			.dvnl-recipe-print-link .dashicons {
				margin-right: 6px;
			}
		';

		wp_add_inline_style( 'dvnl-family-recipe-book-print', $print_button_css );

		// Enqueue dashicons
		wp_enqueue_style( 'dashicons' );
	}

	/**
	 * Enqueue print script
	 */
	public function enqueue_print_script() {
		// Only enqueue on recipe pages
		if ( ! is_singular( 'dvnl_recipes' ) ) {
			return;
		}

		wp_enqueue_script(
			'dvnl-family-recipe-book-print',
			DVNL_FAMILY_RECIPE_BOOK_PLUGIN_URL . 'assets/js/print.js',
			array( 'jquery' ),
			DVNL_FAMILY_RECIPE_BOOK_VERSION,
			true
		);
	}

	/**
	 * Register print endpoint
	 */
	public function register_print_endpoint() {
		add_rewrite_endpoint( 'print', EP_PERMALINK );
	}

	/**
	 * Handle print template
	 */
	public function handle_print_template() {
		global $wp_query;

		// Check if this is a print request
		if ( ! isset( $wp_query->query_vars['print'] ) && ! isset( $_GET['print'] ) ) {
			return;
		}

		// Only handle recipe post type
		if ( ! is_singular( 'dvnl_recipes' ) ) {
			return;
		}

		// Get the recipe post
		$post = get_post();
		if ( ! $post ) {
			return;
		}

		// Get recipe details
		$prep_time   = get_post_meta( $post->ID, '_dvnl_recipe_prep_time', true );
		$cook_time   = get_post_meta( $post->ID, '_dvnl_recipe_cook_time', true );
		$total_time  = get_post_meta( $post->ID, '_dvnl_recipe_total_time', true );
		$servings    = get_post_meta( $post->ID, '_dvnl_recipe_servings', true );
		$calories    = get_post_meta( $post->ID, '_dvnl_recipe_calories', true );
		$difficulty  = get_post_meta( $post->ID, '_dvnl_recipe_difficulty', true );

		// Start output buffering
		ob_start();
		?>
		<!DOCTYPE html>
		<html <?php language_attributes(); ?>>
		<head>
			<meta charset="<?php bloginfo( 'charset' ); ?>">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<title><?php echo esc_html( get_the_title( $post ) . ' - ' . __( 'Print Recipe', 'family-recipe-book' ) ); ?></title>
			<style>
				body {
					font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
					line-height: 1.6;
					color: #333;
					max-width: 800px;
					margin: 0 auto;
					padding: 20px;
				}
				h1 {
					font-size: 24px;
					margin-bottom: 20px;
					border-bottom: 2px solid #ddd;
					padding-bottom: 10px;
				}
				h2 {
					font-size: 20px;
					margin-top: 30px;
					margin-bottom: 15px;
					border-bottom: 1px solid #eee;
					padding-bottom: 5px;
				}
				img {
					max-width: 100%;
					height: auto;
					display: block;
					margin: 20px 0;
				}
				.recipe-meta {
					display: grid;
					grid-template-columns: repeat(3, 1fr);
					gap: 15px;
					margin-bottom: 30px;
					background-color: #f9f9f9;
					padding: 15px;
					border-radius: 4px;
				}
				.recipe-meta-item {
					padding: 10px;
					background-color: #fff;
					border-radius: 4px;
					box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
				}
				.recipe-meta-label {
					font-weight: bold;
					color: #555;
					font-size: 12px;
					display: block;
					margin-bottom: 5px;
				}
				.recipe-meta-value {
					font-size: 16px;
				}
				.recipe-ingredients {
					margin-bottom: 30px;
				}
				.recipe-ingredients ul {
					list-style-type: none;
					padding: 0;
				}
				.recipe-ingredients li {
					padding: 8px 0;
					border-bottom: 1px solid #eee;
				}
				.recipe-instructions {
					margin-bottom: 30px;
				}
				.recipe-instructions ol {
					padding-left: 20px;
				}
				.recipe-instructions li {
					margin-bottom: 15px;
				}
				.recipe-footer {
					margin-top: 40px;
					font-size: 12px;
					color: #777;
					text-align: center;
					border-top: 1px solid #eee;
					padding-top: 20px;
				}
				@media print {
					body {
						padding: 0;
					}
					.recipe-footer {
						display: none;
					}
				}
			</style>
		</head>
		<body onload="window.print()">
			<div class="recipe-container">
				<h1><?php echo esc_html( get_the_title( $post ) ); ?></h1>

				<?php if ( has_post_thumbnail( $post ) ) : ?>
					<div class="recipe-image">
						<?php echo get_the_post_thumbnail( $post, 'medium' ); ?>
					</div>
				<?php endif; ?>

				<div class="recipe-meta">
					<?php if ( ! empty( $prep_time ) ) : ?>
						<div class="recipe-meta-item">
							<span class="recipe-meta-label"><?php esc_html_e( 'Prep Time', 'family-recipe-book' ); ?></span>
							<span class="recipe-meta-value"><?php echo esc_html( $prep_time ); ?> <?php esc_html_e( 'mins', 'family-recipe-book' ); ?></span>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $cook_time ) ) : ?>
						<div class="recipe-meta-item">
							<span class="recipe-meta-label"><?php esc_html_e( 'Cook Time', 'family-recipe-book' ); ?></span>
							<span class="recipe-meta-value"><?php echo esc_html( $cook_time ); ?> <?php esc_html_e( 'mins', 'family-recipe-book' ); ?></span>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $total_time ) ) : ?>
						<div class="recipe-meta-item">
							<span class="recipe-meta-label"><?php esc_html_e( 'Total Time', 'family-recipe-book' ); ?></span>
							<span class="recipe-meta-value"><?php echo esc_html( $total_time ); ?> <?php esc_html_e( 'mins', 'family-recipe-book' ); ?></span>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $servings ) ) : ?>
						<div class="recipe-meta-item">
							<span class="recipe-meta-label"><?php esc_html_e( 'Servings', 'family-recipe-book' ); ?></span>
							<span class="recipe-meta-value"><?php echo esc_html( $servings ); ?></span>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $calories ) ) : ?>
						<div class="recipe-meta-item">
							<span class="recipe-meta-label"><?php esc_html_e( 'Calories', 'family-recipe-book' ); ?></span>
							<span class="recipe-meta-value"><?php echo esc_html( $calories ); ?></span>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $difficulty ) ) : ?>
						<div class="recipe-meta-item">
							<span class="recipe-meta-label"><?php esc_html_e( 'Difficulty', 'family-recipe-book' ); ?></span>
							<span class="recipe-meta-value"><?php echo esc_html( ucfirst( $difficulty ) ); ?></span>
						</div>
					<?php endif; ?>
				</div>

				<?php
				// Parse blocks to extract ingredients and instructions
				$content = $post->post_content;
				$ingredients_title = __( 'Ingredients', 'family-recipe-book' );
				$ingredients = array();
				$instructions_title = __( 'Instructions', 'family-recipe-book' );
				$instructions = array();

				if ( function_exists( 'parse_blocks' ) ) {
					$blocks = parse_blocks( $content );

					foreach ( $blocks as $block ) {
						if ( 'dvnl/recipe-ingredients' === $block['blockName'] ) {
							if ( ! empty( $block['attrs']['title'] ) ) {
								$ingredients_title = $block['attrs']['title'];
							}
							if ( ! empty( $block['attrs']['ingredients'] ) ) {
								$ingredients = $block['attrs']['ingredients'];
							}
						} elseif ( 'dvnl/recipe-instructions' === $block['blockName'] ) {
							if ( ! empty( $block['attrs']['title'] ) ) {
								$instructions_title = $block['attrs']['title'];
							}
							if ( ! empty( $block['attrs']['steps'] ) ) {
								$instructions = $block['attrs']['steps'];
							}
						}
					}
				}
				?>

				<?php if ( ! empty( $ingredients ) ) : ?>
					<div class="recipe-ingredients">
						<h2><?php echo esc_html( $ingredients_title ); ?></h2>
						<ul>
							<?php foreach ( $ingredients as $ingredient ) : ?>
								<li><?php echo esc_html( $ingredient ); ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $instructions ) ) : ?>
					<div class="recipe-instructions">
						<h2><?php echo esc_html( $instructions_title ); ?></h2>
						<ol>
							<?php foreach ( $instructions as $step ) : ?>
								<li><?php echo esc_html( $step ); ?></li>
							<?php endforeach; ?>
						</ol>
					</div>
				<?php endif; ?>

				<div class="recipe-footer">
					<p><?php echo esc_html( get_bloginfo( 'name' ) ); ?> - <?php echo esc_html( date( 'Y' ) ); ?></p>
					<p><?php esc_html_e( 'Recipe URL:', 'family-recipe-book' ); ?> <?php echo esc_url( get_permalink( $post ) ); ?></p>
				</div>
			</div>
		</body>
		</html>
		<?php
		$output = ob_get_clean();
		echo $output;
		exit;
	}
}
