/**
 * Family Recipe Book - Admin Scripts
 */

(function($) {
    'use strict';

    /**
     * Recipe Admin Functionality
     */
    const RecipeAdmin = {
        /**
         * Initialize the admin functionality
         */
        init: function() {
            // Initialize meta box functionality
            this.initMetaBox();

            // Initialize settings page functionality
            this.initSettingsPage();
        },

        /**
         * Initialize meta box functionality
         */
        initMetaBox: function() {
            // Auto-calculate total time when prep time or cook time changes
            $('#_dvnl_recipe_prep_time, #_dvnl_recipe_cook_time').on('change', function() {
                const prepTime = parseInt($('#_dvnl_recipe_prep_time').val()) || 0;
                const cookTime = parseInt($('#_dvnl_recipe_cook_time').val()) || 0;
                const totalTime = prepTime + cookTime;

                if (totalTime > 0) {
                    $('#_dvnl_recipe_total_time').val(totalTime);
                }
            });

            // Set default difficulty if empty
            if (!$('#_dvnl_recipe_difficulty').val()) {
                $('#_dvnl_recipe_difficulty').val('medium');
            }
        },

        /**
         * Initialize settings page functionality
         */
        initSettingsPage: function() {
            // Only run on settings page
            if (!$('.dvnl-recipe-settings-page').length) {
                return;
            }

            // Toggle dependent settings based on checkbox state
            $('input[type="checkbox"]').on('change', function() {
                const $this = $(this);
                const dependentSelector = $this.data('controls');

                if (dependentSelector) {
                    const $dependent = $(dependentSelector);
                    $dependent.toggle($this.is(':checked'));
                }
            }).trigger('change');

            // Initialize CodeMirror for custom CSS if available
            if (typeof wp !== 'undefined' && wp.codeEditor && $('#custom_css').length) {
                wp.codeEditor.initialize($('#custom_css'), {
                    codemirror: {
                        mode: 'css',
                        lineNumbers: true,
                        lineWrapping: true,
                        indentUnit: 4,
                        indentWithTabs: true,
                        theme: 'default'
                    }
                });
            }
        }
    };

    // Initialize when the document is ready
    $(document).ready(function() {
        RecipeAdmin.init();
    });

})(jQuery);