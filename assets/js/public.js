/**
 * Family Recipe Book - Public JavaScript
 *
 * This file contains scripts for the public-facing side of the plugin.
 */

(function($) {
    'use strict';

    /**
     * Recipe functionality.
     */
    const DVNLRecipe = {
        /**
         * Initialize the recipe functionality.
         */
        init: function() {
            this.setupPrintButton();
            this.setupServingsAdjustment();
        },

        /**
         * Setup print button functionality.
         */
        setupPrintButton: function() {
            const $printButton = $('.dvnl-recipe-print-button');

            if ($printButton.length) {
                $printButton.on('click', function(e) {
                    e.preventDefault();
                    window.print();
                });
            }
        },

        /**
         * Setup servings adjustment functionality.
         */
        setupServingsAdjustment: function() {
            const $servingsInput = $('.dvnl-recipe-servings-input');
            const $servingsAdjust = $('.dvnl-recipe-servings-adjust');

            if ($servingsInput.length && $servingsAdjust.length) {
                // Get original servings
                const originalServings = parseInt($servingsInput.data('original-servings'), 10);

                // Get all ingredient amounts
                const $ingredientAmounts = $('.dvnl-recipe-ingredient-amount');

                // Store original amounts
                $ingredientAmounts.each(function() {
                    $(this).data('original-amount', $(this).text());
                });

                // Handle servings adjustment
                $servingsAdjust.on('click', function(e) {
                    e.preventDefault();

                    const newServings = parseInt($servingsInput.val(), 10);

                    if (newServings > 0 && newServings !== originalServings) {
                        const ratio = newServings / originalServings;

                        // Update ingredient amounts
                        $ingredientAmounts.each(function() {
                            const originalAmount = $(this).data('original-amount');
                            const originalValue = parseFloat(originalAmount);

                            if (!isNaN(originalValue)) {
                                const newValue = (originalValue * ratio).toFixed(2).replace(/\.00$/, '');
                                $(this).text(newValue);
                            }
                        });
                    }
                });
            }
        }
    };

    // Initialize when the DOM is ready
    $(document).ready(function() {
        DVNLRecipe.init();
    });

})(jQuery);