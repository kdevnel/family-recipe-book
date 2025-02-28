/**
 * Family Recipe Book - Sharing Functionality
 */

(function($) {
    'use strict';

    /**
     * Social Sharing Functionality
     */
    const RecipeSharing = {
        /**
         * Initialize the sharing functionality
         */
        init: function() {
            // Handle social sharing button clicks
            $('.dvnl-recipe-sharing-button').on('click', function(e) {
                // Skip for email links
                if ($(this).hasClass('dvnl-recipe-sharing-email')) {
                    return true;
                }

                e.preventDefault();

                const url = $(this).attr('href');
                const width = 550;
                const height = 420;
                const left = (screen.width / 2) - (width / 2);
                const top = (screen.height / 2) - (height / 2);

                // Open share dialog in popup
                window.open(
                    url,
                    'share_recipe',
                    'width=' + width + ',height=' + height + ',top=' + top + ',left=' + left + ',toolbar=0,menubar=0,location=0,status=0'
                );
            });

            // Add WhatsApp button only on mobile devices
            RecipeSharing.toggleWhatsAppButton();
            $(window).on('resize', RecipeSharing.toggleWhatsAppButton);
        },

        /**
         * Toggle WhatsApp button visibility based on screen size
         */
        toggleWhatsAppButton: function() {
            const isMobile = window.matchMedia('(max-width: 768px)').matches;
            $('.dvnl-recipe-sharing-whatsapp').toggle(isMobile);
        }
    };

    // Initialize when the document is ready
    $(document).ready(function() {
        RecipeSharing.init();
    });

})(jQuery);