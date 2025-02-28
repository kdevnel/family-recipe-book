/**
 * Family Recipe Book - Rating Functionality
 */

(function($) {
    'use strict';

    /**
     * Recipe Rating functionality
     */
    const RecipeRating = {
        /**
         * Initialize the rating functionality
         */
        init: function() {
            this.bindEvents();
        },

        /**
         * Bind events for rating stars
         */
        bindEvents: function() {
            // Star hover effect
            $('.dvnl-recipe-rating-interactive .dvnl-recipe-star').on('mouseenter', function() {
                const $this = $(this);
                const rating = $this.data('rating');

                // Add hover class to this star and all previous stars
                $('.dvnl-recipe-rating-interactive .dvnl-recipe-star').each(function() {
                    const $star = $(this);
                    if ($star.data('rating') <= rating) {
                        $star.addClass('hover');
                    } else {
                        $star.removeClass('hover');
                    }
                });
            });

            // Remove hover effect when mouse leaves the rating container
            $('.dvnl-recipe-rating-interactive').on('mouseleave', function() {
                $('.dvnl-recipe-rating-interactive .dvnl-recipe-star').removeClass('hover');
            });

            // Handle star click for rating submission
            $('.dvnl-recipe-rating-interactive .dvnl-recipe-star').on('click', function() {
                const $this = $(this);
                const rating = $this.data('rating');
                const postId = $this.closest('.dvnl-recipe-rating').data('post-id');

                RecipeRating.submitRating(postId, rating);
            });
        },

        /**
         * Submit a rating via AJAX
         *
         * @param {number} postId The post ID
         * @param {number} rating The rating value (1-5)
         */
        submitRating: function(postId, rating) {
            const $ratingContainer = $(`.dvnl-recipe-rating[data-post-id="${postId}"]`);
            const $message = $ratingContainer.find('.dvnl-recipe-rating-message');

            // Clear previous messages
            $message.removeClass('success error').hide();

            // Show loading state
            $ratingContainer.addClass('loading');

            $.ajax({
                url: dvnl_rating.ajax_url,
                type: 'POST',
                data: {
                    action: 'dvnl_save_rating',
                    post_id: postId,
                    rating: rating,
                    nonce: dvnl_rating.nonce
                },
                success: function(response) {
                    $ratingContainer.removeClass('loading');

                    if (response.success) {
                        // Update the average rating display
                        const $avgRating = $ratingContainer.find('.dvnl-recipe-avg-rating');
                        const $avgStars = $avgRating.find('.dvnl-recipe-rating-stars');
                        const $avgText = $avgRating.find('.dvnl-recipe-rating-text');

                        // Update stars
                        $avgStars.find('.dvnl-recipe-star').each(function() {
                            const $star = $(this);
                            if ($star.data('rating') <= response.data.average) {
                                $star.addClass('filled');
                            } else if ($star.data('rating') - 0.5 <= response.data.average) {
                                $star.addClass('half-filled');
                            } else {
                                $star.removeClass('filled half-filled');
                            }
                        });

                        // Update text
                        $avgText.text(`${response.data.average.toFixed(1)} out of 5 (${response.data.count} ${response.data.count === 1 ? 'rating' : 'ratings'})`);

                        // Replace the interactive rating with the user's rating
                        const $userRating = $ratingContainer.find('.dvnl-recipe-user-rating');
                        $userRating.html(`
                            <p>Your rating:</p>
                            <div class="dvnl-recipe-rating-stars">
                                ${RecipeRating.generateStars(rating, false)}
                            </div>
                        `);

                        // Show success message
                        $message.addClass('success').text(response.data.message).show();
                    } else {
                        // Show error message
                        $message.addClass('error').text(response.data.message).show();
                    }
                },
                error: function() {
                    $ratingContainer.removeClass('loading');
                    $message.addClass('error').text('An error occurred. Please try again.').show();
                }
            });
        },

        /**
         * Generate HTML for star rating
         *
         * @param {number} rating The current rating
         * @param {boolean} interactive Whether the stars should be interactive
         * @returns {string} HTML for stars
         */
        generateStars: function(rating, interactive) {
            let html = '';
            for (let i = 1; i <= 5; i++) {
                const filled = i <= rating ? 'filled' : '';
                html += `<span class="dvnl-recipe-star ${filled}" data-rating="${i}">★</span>`;
            }
            return html;
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        RecipeRating.init();
    });

})(jQuery);