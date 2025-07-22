<?php
/**
 * Recipe Search and Filtering Functionality
 *
 * @package Family_Recipe_Book
 */

namespace dvnl;

/**
 * Class Search
 *
 * Handles recipe search and filtering functionality.
 */
class Search {

    /**
     * Constructor
     */
    public function __construct() {
        // Register actions and filters
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'wp_ajax_dvnl_filter_recipes', array( $this, 'filter_recipes' ) );
        add_action( 'wp_ajax_nopriv_dvnl_filter_recipes', array( $this, 'filter_recipes' ) );

        // Add shortcode for recipe search and filter
        add_shortcode( 'recipe_search', array( $this, 'recipe_search_shortcode' ) );

        // Add filter to pre_get_posts for search customization
        add_filter( 'pre_get_posts', array( $this, 'customize_recipe_search' ) );
    }

    /**
     * Enqueue scripts and styles for search functionality
     */
    public function enqueue_scripts() {
        // Only load on archive pages or when shortcode is used
        if ( is_post_type_archive( 'dvnl_recipes' ) || is_tax( array( 'recipe_category', 'recipe_tag' ) ) || has_shortcode( get_the_content(), 'recipe_search' ) ) {
            wp_enqueue_style(
                'dvnl-recipe-search-style',
                plugin_dir_url( dirname( __FILE__ ) ) . 'assets/css/search.css',
                array(),
                DVNL_FAMILY_RECIPE_BOOK_VERSION
            );

            wp_enqueue_script(
                'dvnl-recipe-search-script',
                plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/search.js',
                array( 'jquery' ),
                DVNL_FAMILY_RECIPE_BOOK_VERSION,
                true
            );

            wp_localize_script(
                'dvnl-recipe-search-script',
                'dvnl_search',
                array(
                    'ajax_url' => admin_url( 'admin-ajax.php' ),
                    'nonce'    => wp_create_nonce( 'dvnl_search_nonce' ),
                    'i18n'     => array(
                        'loading'       => __( 'Loading recipes...', 'family-recipe-book' ),
                        'no_results'    => __( 'No recipes found matching your criteria.', 'family-recipe-book' ),
                        'error'         => __( 'Error loading recipes. Please try again.', 'family-recipe-book' ),
                    ),
                )
            );
        }
    }

    /**
     * Recipe search shortcode callback
     *
     * @param array $atts Shortcode attributes.
     * @return string HTML output.
     */
    public function recipe_search_shortcode( $atts ) {
        $atts = shortcode_atts(
            array(
                'title'             => __( 'Find Recipes', 'family-recipe-book' ),
                'show_categories'   => 'true',
                'show_tags'         => 'true',
                'show_difficulty'   => 'true',
                'show_time'         => 'true',
                'results_per_page'  => 12,
            ),
            $atts,
            'recipe_search'
        );

        // Convert string booleans to actual booleans
        foreach ( array( 'show_categories', 'show_tags', 'show_difficulty', 'show_time' ) as $key ) {
            $atts[ $key ] = filter_var( $atts[ $key ], FILTER_VALIDATE_BOOLEAN );
        }

        // Convert results_per_page to integer
        $atts['results_per_page'] = intval( $atts['results_per_page'] );

        // Get categories and tags for filter options
        $categories = get_terms( array(
            'taxonomy'   => 'recipe_category',
            'hide_empty' => true,
        ) );

        $tags = get_terms( array(
            'taxonomy'   => 'recipe_tag',
            'hide_empty' => true,
        ) );

        // Get difficulty options
        $difficulty_options = array(
            'easy'      => __( 'Easy', 'family-recipe-book' ),
            'medium'    => __( 'Medium', 'family-recipe-book' ),
            'hard'      => __( 'Hard', 'family-recipe-book' ),
        );

        // Start output buffering
        ob_start();
        ?>
        <div class="dvnl-recipe-search" data-results-per-page="<?php echo esc_attr( $atts['results_per_page'] ); ?>">
            <h2 class="dvnl-recipe-search-title"><?php echo esc_html( $atts['title'] ); ?></h2>

            <div class="dvnl-recipe-search-form">
                <div class="dvnl-recipe-search-input">
                    <input type="text" id="dvnl-recipe-search-keyword" placeholder="<?php esc_attr_e( 'Search recipes...', 'family-recipe-book' ); ?>">
                    <button type="button" id="dvnl-recipe-search-submit">
                        <?php esc_html_e( 'Search', 'family-recipe-book' ); ?>
                    </button>
                </div>

                <div class="dvnl-recipe-filters">
                    <?php if ( $atts['show_categories'] && ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
                        <div class="dvnl-recipe-filter dvnl-recipe-filter-categories">
                            <h3><?php esc_html_e( 'Categories', 'family-recipe-book' ); ?></h3>
                            <div class="dvnl-recipe-filter-options">
                                <?php foreach ( $categories as $category ) : ?>
                                    <label>
                                        <input type="checkbox" name="category[]" value="<?php echo esc_attr( $category->slug ); ?>">
                                        <?php echo esc_html( $category->name ); ?>
                                        <span class="dvnl-recipe-count">(<?php echo esc_html( $category->count ); ?>)</span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ( $atts['show_tags'] && ! empty( $tags ) && ! is_wp_error( $tags ) ) : ?>
                        <div class="dvnl-recipe-filter dvnl-recipe-filter-tags">
                            <h3><?php esc_html_e( 'Tags', 'family-recipe-book' ); ?></h3>
                            <div class="dvnl-recipe-filter-options">
                                <?php foreach ( $tags as $tag ) : ?>
                                    <label>
                                        <input type="checkbox" name="tag[]" value="<?php echo esc_attr( $tag->slug ); ?>">
                                        <?php echo esc_html( $tag->name ); ?>
                                        <span class="dvnl-recipe-count">(<?php echo esc_html( $tag->count ); ?>)</span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ( $atts['show_difficulty'] ) : ?>
                        <div class="dvnl-recipe-filter dvnl-recipe-filter-difficulty">
                            <h3><?php esc_html_e( 'Difficulty', 'family-recipe-book' ); ?></h3>
                            <div class="dvnl-recipe-filter-options">
                                <?php foreach ( $difficulty_options as $value => $label ) : ?>
                                    <label>
                                        <input type="checkbox" name="difficulty[]" value="<?php echo esc_attr( $value ); ?>">
                                        <?php echo esc_html( $label ); ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ( $atts['show_time'] ) : ?>
                        <div class="dvnl-recipe-filter dvnl-recipe-filter-time">
                            <h3><?php esc_html_e( 'Total Time', 'family-recipe-book' ); ?></h3>
                            <div class="dvnl-recipe-filter-options">
                                <label>
                                    <input type="radio" name="time" value="15">
                                    <?php esc_html_e( 'Under 15 minutes', 'family-recipe-book' ); ?>
                                </label>
                                <label>
                                    <input type="radio" name="time" value="30">
                                    <?php esc_html_e( 'Under 30 minutes', 'family-recipe-book' ); ?>
                                </label>
                                <label>
                                    <input type="radio" name="time" value="60">
                                    <?php esc_html_e( 'Under 1 hour', 'family-recipe-book' ); ?>
                                </label>
                                <label>
                                    <input type="radio" name="time" value="any" checked>
                                    <?php esc_html_e( 'Any time', 'family-recipe-book' ); ?>
                                </label>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="dvnl-recipe-filter-actions">
                        <button type="button" id="dvnl-recipe-filter-apply">
                            <?php esc_html_e( 'Apply Filters', 'family-recipe-book' ); ?>
                        </button>
                        <button type="button" id="dvnl-recipe-filter-reset">
                            <?php esc_html_e( 'Reset', 'family-recipe-book' ); ?>
                        </button>
                    </div>
                </div>
            </div>

            <div class="dvnl-recipe-search-results">
                <div class="dvnl-recipe-search-results-count"></div>
                <div class="dvnl-recipe-search-results-grid"></div>
                <div class="dvnl-recipe-search-loading"><?php esc_html_e( 'Loading recipes...', 'family-recipe-book' ); ?></div>
                <div class="dvnl-recipe-search-no-results"><?php esc_html_e( 'No recipes found matching your criteria.', 'family-recipe-book' ); ?></div>
                <div class="dvnl-recipe-search-pagination"></div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * AJAX handler for filtering recipes
     */
    public function filter_recipes() {
        // Check nonce
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'dvnl_search_nonce' ) ) {
            wp_send_json_error( array( 'message' => __( 'Security check failed.', 'family-recipe-book' ) ) );
        }

        // Get and sanitize parameters
        $keyword = isset( $_POST['keyword'] ) ? sanitize_text_field( wp_unslash( $_POST['keyword'] ) ) : '';
        $categories = isset( $_POST['categories'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['categories'] ) ) : array();
        $tags = isset( $_POST['tags'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['tags'] ) ) : array();
        $difficulty = isset( $_POST['difficulty'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['difficulty'] ) ) : array();
        $time = isset( $_POST['time'] ) ? sanitize_text_field( wp_unslash( $_POST['time'] ) ) : 'any';
        $page = isset( $_POST['page'] ) ? intval( $_POST['page'] ) : 1;
        $per_page = isset( $_POST['per_page'] ) ? intval( $_POST['per_page'] ) : 12;

        // Build query args
        $args = array(
            'post_type'      => 'dvnl_recipes',
            'post_status'    => 'publish',
            'posts_per_page' => $per_page,
            'paged'          => $page,
        );

        // Add search keyword
        if ( ! empty( $keyword ) ) {
            $args['s'] = $keyword;
        }

        // Add taxonomy filters
        $tax_query = array();

        if ( ! empty( $categories ) ) {
            $tax_query[] = array(
                'taxonomy' => 'recipe_category',
                'field'    => 'slug',
                'terms'    => $categories,
            );
        }

        if ( ! empty( $tags ) ) {
            $tax_query[] = array(
                'taxonomy' => 'recipe_tag',
                'field'    => 'slug',
                'terms'    => $tags,
            );
        }

        if ( ! empty( $tax_query ) ) {
            $args['tax_query'] = $tax_query;
        }

        // Add meta query for difficulty and time
        $meta_query = array();

        if ( ! empty( $difficulty ) ) {
            $meta_query[] = array(
                'key'     => '_dvnl_recipe_difficulty',
                'value'   => $difficulty,
                'compare' => 'IN',
            );
        }

        if ( ! empty( $time ) && 'any' !== $time ) {
            $meta_query[] = array(
                'key'     => '_dvnl_recipe_total_time',
                'value'   => intval( $time ),
                'compare' => '<=',
                'type'    => 'NUMERIC',
            );
        }

        if ( ! empty( $meta_query ) ) {
            $args['meta_query'] = $meta_query;
        }

        // Run the query
        $query = new \WP_Query( $args );

        // Prepare the response
        $recipes = array();
        $pagination = array(
            'total_pages' => $query->max_num_pages,
            'current_page' => $page,
            'total_recipes' => $query->found_posts,
        );

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();

                // Get recipe data
                $recipe_id = get_the_ID();
                $thumbnail = get_the_post_thumbnail_url( $recipe_id, 'medium' );
                $difficulty = get_post_meta( $recipe_id, '_dvnl_recipe_difficulty', true );
                $prep_time = get_post_meta( $recipe_id, '_dvnl_recipe_prep_time', true );
                $cook_time = get_post_meta( $recipe_id, '_dvnl_recipe_cook_time', true );
                $total_time = get_post_meta( $recipe_id, '_dvnl_recipe_total_time', true );
                $rating_data = $this->get_recipe_rating( $recipe_id );

                $recipes[] = array(
                    'id'          => $recipe_id,
                    'title'       => get_the_title(),
                    'permalink'   => get_permalink(),
                    'thumbnail'   => $thumbnail ? $thumbnail : '',
                    'excerpt'     => get_the_excerpt(),
                    'difficulty'  => $difficulty,
                    'prep_time'   => $prep_time,
                    'cook_time'   => $cook_time,
                    'total_time'  => $total_time,
                    'rating'      => $rating_data['average'],
                    'rating_count' => $rating_data['count'],
                );
            }

            wp_reset_postdata();
        }

        wp_send_json_success( array(
            'recipes'    => $recipes,
            'pagination' => $pagination,
        ) );
    }

    /**
     * Get recipe rating data
     *
     * @param int $post_id The post ID.
     * @return array Rating data with average and count.
     */
    private function get_recipe_rating( $post_id ) {
        $average = get_post_meta( $post_id, '_dvnl_recipe_rating_average', true );
        $count = get_post_meta( $post_id, '_dvnl_recipe_rating_count', true );

        if ( empty( $average ) ) {
            $average = 0;
        }

        if ( empty( $count ) ) {
            $count = 0;
        }

        return array(
            'average' => (float) $average,
            'count'   => (int) $count,
        );
    }

    /**
     * Customize recipe search query
     *
     * @param \WP_Query $query The WP_Query instance.
     * @return \WP_Query Modified query.
     */
    public function customize_recipe_search( $query ) {
        // Only modify main query on frontend
        if ( ! is_admin() && $query->is_main_query() ) {
            // If it's a recipe search or archive
            if ( $query->is_search() && isset( $_GET['post_type'] ) && 'dvnl_recipes' === $_GET['post_type'] ) {
                $query->set( 'post_type', 'dvnl_recipes' );

                // Add filters from GET parameters
                $this->add_filters_from_get( $query );
            } elseif ( $query->is_post_type_archive( 'dvnl_recipes' ) || $query->is_tax( array( 'recipe_category', 'recipe_tag' ) ) ) {
                // Add filters from GET parameters
                $this->add_filters_from_get( $query );
            }
        }

        return $query;
    }

    /**
     * Add filters from GET parameters to the query
     *
     * @param \WP_Query $query The WP_Query instance.
     */
    private function add_filters_from_get( $query ) {
        // Get and sanitize parameters
        $difficulty = isset( $_GET['difficulty'] ) ? sanitize_text_field( wp_unslash( $_GET['difficulty'] ) ) : '';
        $time = isset( $_GET['time'] ) ? sanitize_text_field( wp_unslash( $_GET['time'] ) ) : '';

        // Add meta query for difficulty and time
        $meta_query = array();

        if ( ! empty( $difficulty ) ) {
            $meta_query[] = array(
                'key'     => '_dvnl_recipe_difficulty',
                'value'   => $difficulty,
                'compare' => '=',
            );
        }

        if ( ! empty( $time ) && 'any' !== $time ) {
            $meta_query[] = array(
                'key'     => '_dvnl_recipe_total_time',
                'value'   => intval( $time ),
                'compare' => '<=',
                'type'    => 'NUMERIC',
            );
        }

        if ( ! empty( $meta_query ) ) {
            $query->set( 'meta_query', $meta_query );
        }
    }
}