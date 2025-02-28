/**
 * Family Recipe Book - Print Functionality
 */

(function($) {
    'use strict';

    /**
     * Print Recipe Functionality
     */
    const PrintRecipe = {
        /**
         * Initialize the print functionality
         */
        init: function() {
            // Direct print button click
            $('.dvnl-recipe-print-link').on('click', function(e) {
                // If the user is holding the Ctrl key, let the browser handle it
                // This allows opening in a new tab
                if (e.ctrlKey || e.metaKey) {
                    return true;
                }

                e.preventDefault();
                PrintRecipe.printRecipe($(this).attr('href'));
            });
        },

        /**
         * Print the recipe
         *
         * @param {string} printUrl The URL to the print page
         */
        printRecipe: function(printUrl) {
            // Open a new window for printing
            const printWindow = window.open(printUrl, 'print_recipe', 'height=600,width=800');

            // Focus the print window
            if (printWindow) {
                printWindow.focus();
            }
        }
    };

    // Initialize when the document is ready
    $(document).ready(function() {
        PrintRecipe.init();
    });

})(jQuery);