<?php
/**
 * Post Types Class
 *
 * @package Family_Recipe_Book
 */

namespace dvnl;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Post Types Class
 *
 * Registers and manages the recipe custom post type.
 */
class Post_Types {

	/**
	 * Initialize the class
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		add_action( 'add_meta_boxes', array( $this, 'register_meta_boxes' ) );
		add_action( 'save_post_recipe', array( $this, 'save_meta_boxes' ), 10, 2 );
		add_filter( 'manage_recipe_posts_columns', array( $this, 'add_recipe_columns' ) );
		add_action( 'manage_recipe_posts_custom_column', array( $this, 'render_recipe_columns' ), 10, 2 );
	}

	/**
	 * Register custom post types
	 */
	public function register_post_types() {
		$labels = array(
			'name'                  => _x( 'Recipes', 'Post type general name', 'family-recipe-book' ),
			'singular_name'         => _x( 'Recipe', 'Post type singular name', 'family-recipe-book' ),
			'menu_name'             => _x( 'Recipes', 'Admin Menu text', 'family-recipe-book' ),
			'name_admin_bar'        => _x( 'Recipe', 'Add New on Toolbar', 'family-recipe-book' ),
			'add_new'               => __( 'Add New', 'family-recipe-book' ),
			'add_new_item'          => __( 'Add New Recipe', 'family-recipe-book' ),
			'new_item'              => __( 'New Recipe', 'family-recipe-book' ),
			'edit_item'             => __( 'Edit Recipe', 'family-recipe-book' ),
			'view_item'             => __( 'View Recipe', 'family-recipe-book' ),
			'all_items'             => __( 'All Recipes', 'family-recipe-book' ),
			'search_items'          => __( 'Search Recipes', 'family-recipe-book' ),
			'parent_item_colon'     => __( 'Parent Recipes:', 'family-recipe-book' ),
			'not_found'             => __( 'No recipes found.', 'family-recipe-book' ),
			'not_found_in_trash'    => __( 'No recipes found in Trash.', 'family-recipe-book' ),
			'featured_image'        => _x( 'Recipe Image', 'Overrides the "Featured Image" phrase', 'family-recipe-book' ),
			'set_featured_image'    => _x( 'Set recipe image', 'Overrides the "Set featured image" phrase', 'family-recipe-book' ),
			'remove_featured_image' => _x( 'Remove recipe image', 'Overrides the "Remove featured image" phrase', 'family-recipe-book' ),
			'use_featured_image'    => _x( 'Use as recipe image', 'Overrides the "Use as featured image" phrase', 'family-recipe-book' ),
			'archives'              => _x( 'Recipe archives', 'The post type archive label used in nav menus', 'family-recipe-book' ),
			'insert_into_item'      => _x( 'Insert into recipe', 'Overrides the "Insert into post" phrase', 'family-recipe-book' ),
			'uploaded_to_this_item' => _x( 'Uploaded to this recipe', 'Overrides the "Uploaded to this post" phrase', 'family-recipe-book' ),
			'filter_items_list'     => _x( 'Filter recipes list', 'Screen reader text for the filter links', 'family-recipe-book' ),
			'items_list_navigation' => _x( 'Recipes list navigation', 'Screen reader text for the pagination', 'family-recipe-book' ),
			'items_list'            => _x( 'Recipes list', 'Screen reader text for the items list', 'family-recipe-book' ),
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'recipe' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 20,
			'menu_icon'          => 'dashicons-food',
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'revisions', 'custom-fields' ),
			'show_in_rest'       => true,
		);

		register_post_type( 'recipe', $args );
	}

	/**
	 * Register taxonomies for the recipe post type
	 */
	public function register_taxonomies() {
		// Register Recipe Category taxonomy
		$category_labels = array(
			'name'                       => _x( 'Recipe Categories', 'Taxonomy general name', 'family-recipe-book' ),
			'singular_name'              => _x( 'Recipe Category', 'Taxonomy singular name', 'family-recipe-book' ),
			'search_items'               => __( 'Search Recipe Categories', 'family-recipe-book' ),
			'popular_items'              => __( 'Popular Recipe Categories', 'family-recipe-book' ),
			'all_items'                  => __( 'All Recipe Categories', 'family-recipe-book' ),
			'parent_item'                => __( 'Parent Recipe Category', 'family-recipe-book' ),
			'parent_item_colon'          => __( 'Parent Recipe Category:', 'family-recipe-book' ),
			'edit_item'                  => __( 'Edit Recipe Category', 'family-recipe-book' ),
			'update_item'                => __( 'Update Recipe Category', 'family-recipe-book' ),
			'add_new_item'               => __( 'Add New Recipe Category', 'family-recipe-book' ),
			'new_item_name'              => __( 'New Recipe Category Name', 'family-recipe-book' ),
			'separate_items_with_commas' => __( 'Separate recipe categories with commas', 'family-recipe-book' ),
			'add_or_remove_items'        => __( 'Add or remove recipe categories', 'family-recipe-book' ),
			'choose_from_most_used'      => __( 'Choose from the most used recipe categories', 'family-recipe-book' ),
			'not_found'                  => __( 'No recipe categories found.', 'family-recipe-book' ),
			'menu_name'                  => __( 'Categories', 'family-recipe-book' ),
		);

		$category_args = array(
			'labels'            => $category_labels,
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'recipe-category' ),
		);

		register_taxonomy( 'recipe_category', 'recipe', $category_args );

		// Register Recipe Tag taxonomy
		$tag_labels = array(
			'name'                       => _x( 'Recipe Tags', 'Taxonomy general name', 'family-recipe-book' ),
			'singular_name'              => _x( 'Recipe Tag', 'Taxonomy singular name', 'family-recipe-book' ),
			'search_items'               => __( 'Search Recipe Tags', 'family-recipe-book' ),
			'popular_items'              => __( 'Popular Recipe Tags', 'family-recipe-book' ),
			'all_items'                  => __( 'All Recipe Tags', 'family-recipe-book' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __( 'Edit Recipe Tag', 'family-recipe-book' ),
			'update_item'                => __( 'Update Recipe Tag', 'family-recipe-book' ),
			'add_new_item'               => __( 'Add New Recipe Tag', 'family-recipe-book' ),
			'new_item_name'              => __( 'New Recipe Tag Name', 'family-recipe-book' ),
			'separate_items_with_commas' => __( 'Separate recipe tags with commas', 'family-recipe-book' ),
			'add_or_remove_items'        => __( 'Add or remove recipe tags', 'family-recipe-book' ),
			'choose_from_most_used'      => __( 'Choose from the most used recipe tags', 'family-recipe-book' ),
			'not_found'                  => __( 'No recipe tags found.', 'family-recipe-book' ),
			'menu_name'                  => __( 'Tags', 'family-recipe-book' ),
		);

		$tag_args = array(
			'labels'            => $tag_labels,
			'hierarchical'      => false,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'recipe-tag' ),
		);

		register_taxonomy( 'recipe_tag', 'recipe', $tag_args );
	}

	/**
	 * Register meta boxes for the recipe post type
	 */
	public function register_meta_boxes() {
		add_meta_box(
			'recipe_details',
			__( 'Recipe Details', 'family-recipe-book' ),
			array( $this, 'render_meta_box' ),
			'recipe',
			'normal',
			'high'
		);
	}

	/**
	 * Render the recipe details meta box
	 *
	 * @param \WP_Post $post The post object.
	 */
	public function render_meta_box( $post ) {
		// Add nonce for security
		wp_nonce_field( 'recipe_details_nonce', 'recipe_details_nonce' );

		// Get saved values
		$prep_time = get_post_meta( $post->ID, '_dvnl_recipe_prep_time', true );
		$cook_time = get_post_meta( $post->ID, '_dvnl_recipe_cook_time', true );
		$total_time = get_post_meta( $post->ID, '_dvnl_recipe_total_time', true );
		$servings = get_post_meta( $post->ID, '_dvnl_recipe_servings', true );
		$calories = get_post_meta( $post->ID, '_dvnl_recipe_calories', true );
		$difficulty = get_post_meta( $post->ID, '_dvnl_recipe_difficulty', true );
		?>
		<div class="dvnl-recipe-meta-box">
			<p>
				<label for="dvnl_recipe_prep_time"><?php esc_html_e( 'Preparation Time (minutes):', 'family-recipe-book' ); ?></label>
				<input type="number" id="dvnl_recipe_prep_time" name="dvnl_recipe_prep_time" value="<?php echo esc_attr( $prep_time ); ?>" min="0" />
			</p>
			<p>
				<label for="dvnl_recipe_cook_time"><?php esc_html_e( 'Cooking Time (minutes):', 'family-recipe-book' ); ?></label>
				<input type="number" id="dvnl_recipe_cook_time" name="dvnl_recipe_cook_time" value="<?php echo esc_attr( $cook_time ); ?>" min="0" />
			</p>
			<p>
				<label for="dvnl_recipe_total_time"><?php esc_html_e( 'Total Time (minutes):', 'family-recipe-book' ); ?></label>
				<input type="number" id="dvnl_recipe_total_time" name="dvnl_recipe_total_time" value="<?php echo esc_attr( $total_time ); ?>" min="0" />
			</p>
			<p>
				<label for="dvnl_recipe_servings"><?php esc_html_e( 'Servings:', 'family-recipe-book' ); ?></label>
				<input type="number" id="dvnl_recipe_servings" name="dvnl_recipe_servings" value="<?php echo esc_attr( $servings ); ?>" min="1" />
			</p>
			<p>
				<label for="dvnl_recipe_calories"><?php esc_html_e( 'Calories (per serving):', 'family-recipe-book' ); ?></label>
				<input type="number" id="dvnl_recipe_calories" name="dvnl_recipe_calories" value="<?php echo esc_attr( $calories ); ?>" min="0" />
			</p>
			<p>
				<label for="dvnl_recipe_difficulty"><?php esc_html_e( 'Difficulty:', 'family-recipe-book' ); ?></label>
				<select id="dvnl_recipe_difficulty" name="dvnl_recipe_difficulty">
					<option value="easy" <?php selected( $difficulty, 'easy' ); ?>><?php esc_html_e( 'Easy', 'family-recipe-book' ); ?></option>
					<option value="medium" <?php selected( $difficulty, 'medium' ); ?>><?php esc_html_e( 'Medium', 'family-recipe-book' ); ?></option>
					<option value="hard" <?php selected( $difficulty, 'hard' ); ?>><?php esc_html_e( 'Hard', 'family-recipe-book' ); ?></option>
				</select>
			</p>
		</div>
		<?php
	}

	/**
	 * Save the meta box data
	 *
	 * @param int      $post_id The post ID.
	 * @param \WP_Post $post    The post object.
	 */
	public function save_meta_boxes( $post_id, $post ) {
		// Check if nonce is set
		if ( ! isset( $_POST['recipe_details_nonce'] ) ) {
			return;
		}

		// Verify nonce
		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['recipe_details_nonce'] ) ), 'recipe_details_nonce' ) ) {
			return;
		}

		// Check if autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check permissions
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Define fields to save
		$fields = array(
			'_dvnl_recipe_prep_time'  => 'dvnl_recipe_prep_time',
			'_dvnl_recipe_cook_time'  => 'dvnl_recipe_cook_time',
			'_dvnl_recipe_total_time' => 'dvnl_recipe_total_time',
			'_dvnl_recipe_servings'   => 'dvnl_recipe_servings',
			'_dvnl_recipe_calories'   => 'dvnl_recipe_calories',
			'_dvnl_recipe_difficulty' => 'dvnl_recipe_difficulty',
		);

		foreach ( $fields as $meta_key => $post_key ) {
			if ( isset( $_POST[ $post_key ] ) ) {
				$value = sanitize_text_field( wp_unslash( $_POST[ $post_key ] ) );
				update_post_meta( $post_id, $meta_key, $value );
			}
		}
	}

	/**
	 * Add custom columns to recipe post type
	 *
	 * @param array $columns The existing columns.
	 * @return array Modified columns.
	 */
	public function add_recipe_columns( $columns ) {
		$new_columns = array();

		foreach ( $columns as $key => $value ) {
			if ( 'title' === $key ) {
				$new_columns[ $key ]           = $value;
				$new_columns['recipe_details'] = __( 'Recipe Details', 'family-recipe-book' );
			} else {
				$new_columns[ $key ] = $value;
			}
		}

		return $new_columns;
	}

	/**
	 * Render custom column content
	 *
	 * @param string $column  The column name.
	 * @param int    $post_id The post ID.
	 */
	public function render_recipe_columns( $column, $post_id ) {
		if ( 'recipe_details' === $column ) {
			$prep_time  = get_post_meta( $post_id, '_dvnl_recipe_prep_time', true );
			$cook_time  = get_post_meta( $post_id, '_dvnl_recipe_cook_time', true );
			$servings   = get_post_meta( $post_id, '_dvnl_recipe_servings', true );
			$difficulty = get_post_meta( $post_id, '_dvnl_recipe_difficulty', true );

			if ( $prep_time ) {
				echo '<div class="recipe-detail"><span class="recipe-detail-label">' . esc_html__( 'Prep:', 'family-recipe-book' ) . '</span> ' . esc_html( $prep_time ) . ' ' . esc_html__( 'min', 'family-recipe-book' ) . '</div>';
			}

			if ( $cook_time ) {
				echo '<div class="recipe-detail"><span class="recipe-detail-label">' . esc_html__( 'Cook:', 'family-recipe-book' ) . '</span> ' . esc_html( $cook_time ) . ' ' . esc_html__( 'min', 'family-recipe-book' ) . '</div>';
			}

			if ( $servings ) {
				echo '<div class="recipe-detail"><span class="recipe-detail-label">' . esc_html__( 'Servings:', 'family-recipe-book' ) . '</span> ' . esc_html( $servings ) . '</div>';
			}

			if ( $difficulty ) {
				$difficulty_label = '';
				switch ( $difficulty ) {
					case 'easy':
						$difficulty_label = __( 'Easy', 'family-recipe-book' );
						break;
					case 'medium':
						$difficulty_label = __( 'Medium', 'family-recipe-book' );
						break;
					case 'hard':
						$difficulty_label = __( 'Hard', 'family-recipe-book' );
						break;
				}
				echo '<div class="recipe-detail"><span class="recipe-detail-label">' . esc_html__( 'Difficulty:', 'family-recipe-book' ) . '</span> ' . esc_html( $difficulty_label ) . '</div>';
			}
		}
	}
}

// Initialize the class.
new Post_Types();