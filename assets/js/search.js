/**
 * Family Recipe Book - Search and Filtering JavaScript
 */
(function($) {
    'use strict';

    // Main search object
    const RecipeSearch = {
        // Initialize the search functionality
        init: function() {
            this.searchForm = $('.dvnl-recipe-search-form');
            this.searchInput = $('.dvnl-recipe-search-input input');
            this.searchButton = $('.dvnl-recipe-search-input button');
            this.filterApplyButton = $('#dvnl-recipe-filter-apply');
            this.filterResetButton = $('#dvnl-recipe-filter-reset');
            this.resultsContainer = $('.dvnl-recipe-search-results');
            this.resultsGrid = $('.dvnl-recipe-search-results-grid');
            this.resultsCount = $('.dvnl-recipe-search-results-count');
            this.paginationContainer = $('.dvnl-recipe-search-pagination');
            this.searchContainer = $('.dvnl-recipe-search');

            this.currentPage = 1;
            this.totalPages = 1;

            this.bindEvents();

            // If URL has search parameters, apply them
            if (window.location.search) {
                this.applyUrlFilters();
            }
        },

        // Bind event listeners
        bindEvents: function() {
            const self = this;

            // Search form submission
            this.searchForm.on('submit', function(e) {
                e.preventDefault();
                self.performSearch();
            });

            // Search button click
            this.searchButton.on('click', function(e) {
                e.preventDefault();
                self.performSearch();
            });

            // Apply filters button
            this.filterApplyButton.on('click', function(e) {
                e.preventDefault();
                self.performSearch();
            });

            // Reset filters button
            this.filterResetButton.on('click', function(e) {
                e.preventDefault();
                self.resetFilters();
            });

            // Pagination clicks
            this.paginationContainer.on('click', '.dvnl-recipe-search-pagination-item:not(.disabled)', function() {
                const page = $(this).data('page');
                if (page) {
                    self.currentPage = page;
                    self.performSearch(false);
                }
            });

            // Handle browser back/forward buttons
            $(window).on('popstate', function() {
                self.applyUrlFilters();
            });
        },

        // Apply filters from URL parameters
        applyUrlFilters: function() {
            const urlParams = new URLSearchParams(window.location.search);

            // Reset all filters first
            this.resetFiltersWithoutSearch();

            // Apply search keyword
            if (urlParams.has('s')) {
                this.searchInput.val(urlParams.get('s'));
            }

            // Apply categories
            if (urlParams.has('recipe_category')) {
                const categories = urlParams.getAll('recipe_category');
                categories.forEach(function(cat) {
                    $('input[name="recipe_category[]"][value="' + cat + '"]').prop('checked', true);
                });
            }

            // Apply tags
            if (urlParams.has('recipe_tag')) {
                const tags = urlParams.getAll('recipe_tag');
                tags.forEach(function(tag) {
                    $('input[name="recipe_tag[]"][value="' + tag + '"]').prop('checked', true);
                });
            }

            // Apply difficulty
            if (urlParams.has('difficulty')) {
                $('input[name="difficulty"][value="' + urlParams.get('difficulty') + '"]').prop('checked', true);
            }

            // Apply time
            if (urlParams.has('total_time')) {
                $('input[name="total_time"][value="' + urlParams.get('total_time') + '"]').prop('checked', true);
            }

            // Apply page
            if (urlParams.has('page')) {
                this.currentPage = parseInt(urlParams.get('page'), 10);
            } else {
                this.currentPage = 1;
            }

            // Perform search with the applied filters
            this.performSearch(false);
        },

        // Perform search with current filters
        performSearch: function(updateUrl = true) {
            const self = this;
            const formData = this.getFormData();

            // Add current page to form data
            formData.page = this.currentPage;

            // Show loading state
            this.searchContainer.addClass('is-loading');
            this.resultsGrid.hide();
            this.resultsCount.hide();
            this.paginationContainer.hide();

            // Update URL if requested
            if (updateUrl) {
                this.updateUrl(formData);
            }

            // Perform AJAX request
            $.ajax({
                url: dvnl_recipe_search.ajax_url,
                type: 'POST',
                data: {
                    action: 'dvnl_filter_recipes',
                    nonce: dvnl_recipe_search.nonce,
                    search_data: formData
                },
                success: function(response) {
                    if (response.success) {
                        self.updateResults(response.data);
                    } else {
                        self.showError(response.data.message || 'An error occurred while searching.');
                    }
                },
                error: function() {
                    self.showError('A server error occurred. Please try again later.');
                },
                complete: function() {
                    self.searchContainer.removeClass('is-loading');
                }
            });
        },

        // Update the results display
        updateResults: function(data) {
            // Update results count
            this.resultsCount.html(data.count_text).show();

            // Update results grid
            if (data.recipes.length > 0) {
                this.resultsGrid.html(data.html).show();
                this.searchContainer.removeClass('no-results');
            } else {
                this.resultsGrid.hide();
                this.searchContainer.addClass('no-results');
            }

            // Update pagination
            if (data.total_pages > 1) {
                this.totalPages = data.total_pages;
                this.updatePagination();
                this.paginationContainer.show();
            } else {
                this.paginationContainer.hide();
            }

            // Scroll to results if not already visible
            if (!this.isElementInViewport(this.resultsContainer[0])) {
                $('html, body').animate({
                    scrollTop: this.resultsContainer.offset().top - 50
                }, 500);
            }
        },

        // Update pagination links
        updatePagination: function() {
            let paginationHtml = '';
            const self = this;

            // Previous button
            paginationHtml += '<div class="dvnl-recipe-search-pagination-item' + (this.currentPage === 1 ? ' disabled' : '') + '" data-page="' + (this.currentPage - 1) + '">←</div>';

            // Page numbers
            let startPage = Math.max(1, this.currentPage - 2);
            let endPage = Math.min(this.totalPages, startPage + 4);

            // Adjust start page if we're near the end
            if (endPage - startPage < 4) {
                startPage = Math.max(1, endPage - 4);
            }

            for (let i = startPage; i <= endPage; i++) {
                paginationHtml += '<div class="dvnl-recipe-search-pagination-item' + (i === this.currentPage ? ' current' : '') + '" data-page="' + i + '">' + i + '</div>';
            }

            // Next button
            paginationHtml += '<div class="dvnl-recipe-search-pagination-item' + (this.currentPage === this.totalPages ? ' disabled' : '') + '" data-page="' + (this.currentPage + 1) + '">→</div>';

            this.paginationContainer.html(paginationHtml);
        },

        // Get form data as an object
        getFormData: function() {
            const formData = {};

            // Get search keyword
            formData.s = this.searchInput.val();

            // Get selected categories
            formData.recipe_category = [];
            $('input[name="recipe_category[]"]:checked').each(function() {
                formData.recipe_category.push($(this).val());
            });

            // Get selected tags
            formData.recipe_tag = [];
            $('input[name="recipe_tag[]"]:checked').each(function() {
                formData.recipe_tag.push($(this).val());
            });

            // Get selected difficulty
            formData.difficulty = $('input[name="difficulty"]:checked').val();

            // Get selected time
            formData.total_time = $('input[name="total_time"]:checked').val();

            return formData;
        },

        // Update URL with search parameters
        updateUrl: function(formData) {
            const urlParams = new URLSearchParams();

            // Add search keyword
            if (formData.s) {
                urlParams.append('s', formData.s);
            }

            // Add categories
            if (formData.recipe_category && formData.recipe_category.length) {
                formData.recipe_category.forEach(function(cat) {
                    urlParams.append('recipe_category', cat);
                });
            }

            // Add tags
            if (formData.recipe_tag && formData.recipe_tag.length) {
                formData.recipe_tag.forEach(function(tag) {
                    urlParams.append('recipe_tag', tag);
                });
            }

            // Add difficulty
            if (formData.difficulty) {
                urlParams.append('difficulty', formData.difficulty);
            }

            // Add time
            if (formData.total_time) {
                urlParams.append('total_time', formData.total_time);
            }

            // Add page
            if (formData.page && formData.page > 1) {
                urlParams.append('page', formData.page);
            }

            // Update browser history
            const newUrl = window.location.pathname + (urlParams.toString() ? '?' + urlParams.toString() : '');
            window.history.pushState({}, '', newUrl);
        },

        // Reset all filters and perform a new search
        resetFilters: function() {
            this.resetFiltersWithoutSearch();
            this.currentPage = 1;
            this.performSearch();
        },

        // Reset all filters without performing a search
        resetFiltersWithoutSearch: function() {
            this.searchInput.val('');
            $('input[type="checkbox"]').prop('checked', false);
            $('input[type="radio"]').prop('checked', false);
        },

        // Show error message
        showError: function(message) {
            this.resultsGrid.html('<div class="dvnl-recipe-search-error">' + message + '</div>').show();
            this.resultsCount.hide();
            this.paginationContainer.hide();
        },

        // Check if element is in viewport
        isElementInViewport: function(el) {
            const rect = el.getBoundingClientRect();
            return (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth)
            );
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        if ($('.dvnl-recipe-search').length) {
            RecipeSearch.init();
        }
    });

})(jQuery);