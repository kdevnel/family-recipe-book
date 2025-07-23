<?php
/**
 * Recipe Rating Functionality
 *
 * @package Family_Recipe_Book
 */

namespace dvnl;

/**
 * Class Rating
 *
 * Handles recipe rating functionality including display, submission, and storage.
 */
class Rating {

    /**
     * Constructor
     */
    public function __construct() {
        // Register actions and filters
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'wp_ajax_dvnl_save_rating', array( $this, 'save_rating' ) );
        add_action( 'wp_ajax_nopriv_dvnl_save_rating', array( $this, 'save_rating' ) );
        add_action( 'add_meta_boxes', array( $this, 'add_rating_meta_box' ) );
        add_filter( 'the_content', array( $this, 'display_rating' ) );

        // Add rating data to schema
        add_filter( 'dvnl_recipe_schema_data', array( $this, 'add_rating_to_schema' ) );
    }

    /**
     * Enqueue scripts and styles for rating functionality
     */
    public function enqueue_scripts() {
        if ( is_singular( 'dvnl_recipes' ) ) {
            wp_enqueue_style(
                'dvnl-recipe-rating-style',
                plugin_dir_url( dirname( __FILE__ ) ) . 'assets/css/rating.css',
                array(),
                DVNL_FAMILY_RECIPE_BOOK_VERSION
            );

            wp_enqueue_script(
                'dvnl-recipe-rating-script',
                plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/rating.js',
                array( 'jquery' ),
                DVNL_FAMILY_RECIPE_BOOK_VERSION,
                true
            );

            wp_localize_script(
                'dvnl-recipe-rating-script',
                'dvnlRating',
                array(
                    'ajaxurl' => admin_url( 'admin-ajax.php' ),
                    'nonce'   => wp_create_nonce( 'dvnl_rating_nonce' ),
                    'i18n'    => array(
                        'rateThis'      => __( 'Rate this recipe', 'family-recipe-book' ),
                        'thankYou'      => __( 'Thank you for your rating!', 'family-recipe-book' ),
                        'alreadyRated'  => __( 'You have already rated this recipe.', 'family-recipe-book' ),
                        'error'         => __( 'Error saving rating. Please try again.', 'family-recipe-book' ),
                    ),
                )
            );
        }
    }

    /**
     * Display rating on recipe content
     *
     * @param string $content The post content.
     * @return string Modified content with rating.
     */
    public function display_rating( $content ) {
        if ( is_singular( 'dvnl_recipes' ) && is_main_query() ) {
            $post_id = get_the_ID();
            $rating_html = $this->get_rating_html( $post_id );

            // Add rating after the content
            $content .= $rating_html;
        }

        return $content;
    }

    /**
     * Get HTML for displaying the rating
     *
     * @param int $post_id The post ID.
     * @return string HTML for the rating.
     */
    public function get_rating_html( $post_id ) {
        $rating_data = $this->get_rating_data( $post_id );
        $average = $rating_data['average'];
        $count = $rating_data['count'];

        // Check if user has already rated
        $user_id = get_current_user_id();
        $user_rated = false;
        $user_rating = 0;

        if ( $user_id ) {
            $user_ratings = get_post_meta( $post_id, '_dvnl_recipe_user_ratings', true );
            if ( is_array( $user_ratings ) && isset( $user_ratings[ $user_id ] ) ) {
                $user_rated = true;
                $user_rating = $user_ratings[ $user_id ];
            }
        } else {
            // Check for cookie for non-logged in users
            if ( isset( $_COOKIE['dvnl_recipe_rated_' . $post_id] ) ) {
                $user_rated = true;
                $user_rating = intval( $_COOKIE['dvnl_recipe_rated_' . $post_id] );
            }
        }

        ob_start();
        ?>
        <div class="dvnl-recipe-rating" data-post-id="<?php echo esc_attr( $post_id ); ?>">
            <h3><?php esc_html_e( 'Recipe Rating', 'family-recipe-book' ); ?></h3>

            <?php if ( $count > 0 ) : ?>
                <div class="dvnl-recipe-rating-average">
                    <div class="dvnl-recipe-rating-stars" data-rating="<?php echo esc_attr( $average ); ?>">
                        <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                            <span class="dvnl-recipe-star <?php echo ( $i <= round( $average ) ) ? 'filled' : ''; ?>">★</span>
                        <?php endfor; ?>
                    </div>
                    <div class="dvnl-recipe-rating-text">
                        <?php
                        printf(
                            /* translators: 1: Average rating, 2: Number of ratings */
                            esc_html( _n( '%1$s out of 5 stars (based on %2$s rating)', '%1$s out of 5 stars (based on %2$s ratings)', $count, 'family-recipe-book' ) ),
                            number_format_i18n( $average, 1 ),
                            number_format_i18n( $count )
                        );
                        ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ( ! $user_rated ) : ?>
                <div class="dvnl-recipe-user-rating">
                    <p><?php esc_html_e( 'Rate this recipe:', 'family-recipe-book' ); ?></p>
                    <div class="dvnl-recipe-rating-stars dvnl-recipe-rating-interactive">
                        <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                            <span class="dvnl-recipe-star" data-rating="<?php echo esc_attr( $i ); ?>">★</span>
                        <?php endfor; ?>
                    </div>
                    <div class="dvnl-recipe-rating-message"></div>
                </div>
            <?php else : ?>
                <div class="dvnl-recipe-user-rating">
                    <p><?php esc_html_e( 'Your rating:', 'family-recipe-book' ); ?></p>
                    <div class="dvnl-recipe-rating-stars">
                        <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                            <span class="dvnl-recipe-star <?php echo ( $i <= $user_rating ) ? 'filled' : ''; ?>">★</span>
                        <?php endfor; ?>
                    </div>
                    <div class="dvnl-recipe-rating-message">
                        <?php esc_html_e( 'Thank you for your rating!', 'family-recipe-book' ); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Get rating data for a recipe
     *
     * @param int $post_id The post ID.
     * @return array Rating data with average and count.
     */
    public function get_rating_data( $post_id ) {
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
     * Save a user's rating via AJAX
     */
    public function save_rating() {
        // Verify nonce
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'dvnl_rating_nonce' ) ) {
            wp_send_json_error( array( 'message' => __( 'Security check failed.', 'family-recipe-book' ) ) );
        }

        // Get and validate data
        $post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
        $rating = isset( $_POST['rating'] ) ? intval( $_POST['rating'] ) : 0;

        if ( ! $post_id || $rating < 1 || $rating > 5 ) {
            wp_send_json_error( array( 'message' => __( 'Invalid data.', 'family-recipe-book' ) ) );
        }

        // Check if post exists and is a recipe
        if ( 'dvnl_recipes' !== get_post_type( $post_id ) ) {
            wp_send_json_error( array( 'message' => __( 'Invalid recipe.', 'family-recipe-book' ) ) );
        }

        // Get user ID or IP for non-logged in users
        $user_id = get_current_user_id();
        $user_ratings = get_post_meta( $post_id, '_dvnl_recipe_user_ratings', true );

        if ( ! is_array( $user_ratings ) ) {
            $user_ratings = array();
        }

        // Check if user has already rated
        if ( $user_id && isset( $user_ratings[ $user_id ] ) ) {
            wp_send_json_error( array( 'message' => __( 'You have already rated this recipe.', 'family-recipe-book' ) ) );
        }

        // For non-logged in users, check cookie
        if ( ! $user_id && isset( $_COOKIE['dvnl_recipe_rated_' . $post_id] ) ) {
            wp_send_json_error( array( 'message' => __( 'You have already rated this recipe.', 'family-recipe-book' ) ) );
        }

        // Save the rating
        if ( $user_id ) {
            $user_ratings[ $user_id ] = $rating;
            update_post_meta( $post_id, '_dvnl_recipe_user_ratings', $user_ratings );
        } else {
            // Set cookie for non-logged in users (expires in 1 year)
            setcookie( 'dvnl_recipe_rated_' . $post_id, $rating, time() + YEAR_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );

            // Also store IP to prevent abuse
            $ip = $this->get_user_ip();
            $ip_ratings = get_post_meta( $post_id, '_dvnl_recipe_ip_ratings', true );

            if ( ! is_array( $ip_ratings ) ) {
                $ip_ratings = array();
            }

            $ip_ratings[ $ip ] = $rating;
            update_post_meta( $post_id, '_dvnl_recipe_ip_ratings', $ip_ratings );
        }

        // Update average rating and count
        $this->update_rating_average( $post_id );

        // Get updated rating data
        $rating_data = $this->get_rating_data( $post_id );

        wp_send_json_success( array(
            'message' => __( 'Thank you for your rating!', 'family-recipe-book' ),
            'average' => $rating_data['average'],
            'count'   => $rating_data['count'],
        ) );
    }

    /**
     * Update the average rating for a recipe
     *
     * @param int $post_id The post ID.
     */
    public function update_rating_average( $post_id ) {
        $user_ratings = get_post_meta( $post_id, '_dvnl_recipe_user_ratings', true );
        $ip_ratings = get_post_meta( $post_id, '_dvnl_recipe_ip_ratings', true );

        $all_ratings = array();

        // Combine user ratings
        if ( is_array( $user_ratings ) ) {
            $all_ratings = array_merge( $all_ratings, array_values( $user_ratings ) );
        }

        // Add IP ratings (for non-logged in users)
        if ( is_array( $ip_ratings ) ) {
            $all_ratings = array_merge( $all_ratings, array_values( $ip_ratings ) );
        }

        // Calculate average
        $count = count( $all_ratings );
        $average = 0;

        if ( $count > 0 ) {
            $average = array_sum( $all_ratings ) / $count;
        }

        // Update meta
        update_post_meta( $post_id, '_dvnl_recipe_rating_average', $average );
        update_post_meta( $post_id, '_dvnl_recipe_rating_count', $count );
    }

    /**
     * Add rating meta box to recipe edit screen
     */
    public function add_rating_meta_box() {
        add_meta_box(
            'dvnl_recipe_rating_meta_box',
            __( 'Recipe Ratings', 'family-recipe-book' ),
            array( $this, 'render_rating_meta_box' ),
            'dvnl_recipes',
            'side',
            'default'
        );
    }

    /**
     * Render the rating meta box
     *
     * @param \WP_Post $post The post object.
     */
    public function render_rating_meta_box( $post ) {
        $rating_data = $this->get_rating_data( $post->ID );
        $average = $rating_data['average'];
        $count = $rating_data['count'];

        ?>
        <div class="dvnl-recipe-rating-meta-box">
            <?php if ( $count > 0 ) : ?>
                <p>
                    <strong><?php esc_html_e( 'Average Rating:', 'family-recipe-book' ); ?></strong>
                    <?php echo esc_html( number_format_i18n( $average, 1 ) ); ?> / 5
                </p>
                <p>
                    <strong><?php esc_html_e( 'Number of Ratings:', 'family-recipe-book' ); ?></strong>
                    <?php echo esc_html( number_format_i18n( $count ) ); ?>
                </p>
                <div class="dvnl-recipe-rating-stars">
                    <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                        <span class="dvnl-recipe-star <?php echo ( $i <= round( $average ) ) ? 'filled' : ''; ?>">★</span>
                    <?php endfor; ?>
                </div>
            <?php else : ?>
                <p><?php esc_html_e( 'This recipe has not been rated yet.', 'family-recipe-book' ); ?></p>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Add rating data to Schema.org markup
     *
     * @param array $schema_data The schema data.
     * @return array Modified schema data.
     */
    public function add_rating_to_schema( $schema_data ) {
        if ( isset( $schema_data['@type'] ) && 'Recipe' === $schema_data['@type'] ) {
            $post_id = get_the_ID();
            $rating_data = $this->get_rating_data( $post_id );

            if ( $rating_data['count'] > 0 ) {
                $schema_data['aggregateRating'] = array(
                    '@type'       => 'AggregateRating',
                    'ratingValue' => $rating_data['average'],
                    'ratingCount' => $rating_data['count'],
                    'bestRating'  => '5',
                    'worstRating' => '1',
                );
            }
        }

        return $schema_data;
    }

    /**
     * Get the user's IP address
     *
     * @return string The IP address.
     */
    private function get_user_ip() {
        if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
            $ip = sanitize_text_field( wp_unslash( $_SERVER['HTTP_CLIENT_IP'] ) );
        } elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
            $ip = sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) );
        } else {
            $ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
        }

        return $ip;
    }
}