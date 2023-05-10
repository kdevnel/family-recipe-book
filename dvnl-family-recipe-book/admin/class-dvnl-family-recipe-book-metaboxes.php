<?php
/**
 * The metabox functionality of the plugin.
 *
 * @link       https://devnel.blog
 * @since      2.0.0
 *
 * @package    Dvnl_Family_Recipe_Book
 * @subpackage Dvnl_Family_Recipe_Book/admin
 */

/**
 * Metabox and custom field configurations class.
 */
class Dvnl_Family_Recipe_Book_Metaboxes {
	/**
	 * Create a details metabox for the recipe post type.
	 *
	 * @return void
	 */
	public function create_recipe_metaboxes() {
		$metabox_args = array(
            array(
                'id'  => 'dvnl_family_recipe_book_recipe_repeater_test',
                'title'  => __( 'Repeater Test', 'dvnl-family-recipe-book' ),
                'callback'  => array( $this, 'render_recipe_metabox_repeater' ),
                'screen'  => 'dvnl_recipes',
                'context'  => 'normal',
                'priority'  => 'high',
                'callback_args'  => array(
                    'nonce'  => 'dvnl_recipe_repeater_test_nonce',
                    'fields'  => array(
                        array(
                            'id'  => 'dvnl_recipe_repeater_test',
                            'label'  => __( 'Repeater Test', 'dvnl-family-recipe-book' ),
                            'type'  => 'repeater',
                            'options'  => array(
                                array(
                                    'id'  => 'dvnl_recipe_repeater_test_text',
                                    'label'  => __( 'Text', 'dvnl-family-recipe-book' ),
                                    'type'  => 'text',
                                ),
                                array(
                                    'id'  => 'dvnl_recipe_repeater_test_select',
                                    'label'  => __( 'Select', 'dvnl-family-recipe-book' ),
                                    'type'  => 'select',
                                    'options'  => array(
                                        'option_one'  => __( 'Option One', 'dvnl-family-recipe-book' ),
                                        'option_two'  => __( 'Option Two', 'dvnl-family-recipe-book' ),
                                        'option_three'  => __( 'Option Three', 'dvnl-family-recipe-book' ),
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
			array(
				'id'            => 'dvnl_family_recipe_book_recipe_details',
				'title'         => __( 'Recipe Details', 'dvnl-family-recipe-book' ),
				'callback'      => array( $this, 'render_recipe_metabox_templates' ),
				'screen'        => 'dvnl_recipes',
				'context'       => 'normal',
				'priority'      => 'high',
				'callback_args' => array(
					'nonce'  => 'dvnl_recipe_details_nonce',
					'fields' => array(
						array(
							'id'    => 'dvnl_original_author',
							'label' => __( 'Original Author', 'dvnl-family-recipe-book' ),
							'type'  => 'text',
						),
						array(
							'id'    => 'dvnl_published_date',
							'label' => __( 'Published Date', 'dvnl-family-recipe-book' ),
							'type'  => 'date',
						),
						array(
							'id'    => 'dvnl_cost',
							'label' => __( 'Cost', 'dvnl-family-recipe-book' ),
							'type'  => 'number',
						),
						array(
							'id'    => 'dvnl_url',
							'label' => __( 'URL', 'dvnl-family-recipe-book' ),
							'type'  => 'url',
						),
						array(
							'id'	=> 'dvnl_video',
							'label'	=> __( 'Video', 'dvnl-family-recipe-book' ),
							'type'	=> 'url',
						),
						array(
							'id'    => 'dvnl_servings',
							'label' => __( 'Servings', 'dvnl-family-recipe-book' ),
							'type'  => 'number',
						),
						array(
							'id'      => 'dvnl_difficulty',
							'label'   => __( 'Difficulty', 'dvnl-family-recipe-book' ),
							'type'    => 'select',
							'options' => array(
								'easy'   => __( 'Easy', 'dvnl-family-recipe-book' ),
								'medium' => __( 'Medium', 'dvnl-family-recipe-book' ),
								'hard'   => __( 'Hard', 'dvnl-family-recipe-book' ),
							),
						),
					),
				),
			),
			array(
				'id'            => 'dvnl_family_recipe_book_recipe_timings',
				'title'         => __( 'Recipe Timings', 'dvnl-family-recipe-book' ),
				'callback'      => array( $this, 'render_recipe_metabox_templates' ),
				'screen'        => 'dvnl_recipes',
				'context'       => 'normal',
				'priority'      => 'high',
				'callback_args' => array(
					'nonce'  => 'dvnl_recipe_timings_nonce',
					'fields' => array(
						array(
							'id'    => 'dvnl_prep_time',
							'label' => __( 'Prep Time', 'dvnl-family-recipe-book' ),
							'type'  => 'number',
						),
						array(
							'id'    => 'dvnl_cook_time',
							'label' => __( 'Cook Time', 'dvnl-family-recipe-book' ),
							'type'  => 'number',
						),
						array(
							'id'    => 'dvnl_rest_time',
							'label' => __( 'Rest Time', 'dvnl-family-recipe-book' ),
							'type'  => 'number',
						),
						array(
							'id'    => 'dvnl_total_time',
							'label' => __( 'Total Time', 'dvnl-family-recipe-book' ),
							'type'  => 'number',
						),
					),
				),
			),
			array(
				'id'            => 'dvnl_family_recipe_book_recipe_ingredients',
				'title'         => __( 'Ingredients', 'dvnl-family-recipe-book' ),
				'callback'      => array( $this, 'render_recipe_metabox_templates' ),
				'screen'        => 'dvnl_recipes',
				'context'       => 'normal',
				'priority'      => 'high',
				'callback_args' => array(
					'nonce' => 'dvnl_recipe_ingredients_nonce',
					'fields' => array(
						array(
							'id'    => 'dvnl_ingredients',
							'label' => __( 'Ingredients', 'dvnl-family-recipe-book' ),
							'type'  => 'textarea',
						),
					),
					// 'template' => 'partials/dvnl-family-recipe-book-recipe-ingredients-metabox.php',
				),
			),
			array(
				'id'            => 'dvnl_family_recipe_book_recipe_instructions',
				'title'         => __( 'Instructions', 'dvnl-family-recipe-book' ),
				'callback'      => array( $this, 'render_recipe_metabox_templates' ),
				'screen'        => 'dvnl_recipes',
				'context'       => 'normal',
				'priority'      => 'high',
				'callback_args' => array(
					'nonce' => 'dvnl_recipe_instructions_nonce',
					'fields' => array(
						array(
							'id'    => 'dvnl_instructions',
							'label' => __( 'Instructions', 'dvnl-family-recipe-book' ),
							'type'  => 'textarea',
						),
					),
					// 'template' => 'partials/dvnl-family-recipe-book-recipe-instructions-metabox.php',
				),
			),
			array(
				'id'            => 'dvnl_family_recipe_book_recipe_nutrition',
				'title'         => __( 'Nutrition', 'dvnl-family-recipe-book' ),
				'callback'      => array( $this, 'render_recipe_metabox_templates' ),
				'screen'        => 'dvnl_recipes',
				'context'       => 'normal',
				'priority'      => 'high',
				'callback_args' => array(
					'nonce' => 'dvnl_recipe_nutrition_nonce',
					'fields' => array(
						array(
							'id'    => 'dvnl_protein',
							'label' => __( 'Protein', 'dvnl-family-recipe-book' ),
							'type'  => 'text',
						),
						array(
							'id'    => 'dvnl_carbs',
							'label' => __( 'Carbohydrates', 'dvnl-family-recipe-book' ),
							'type'  => 'text',
						),
						array(
							'id'    => 'dvnl_fat',
							'label' => __( 'Fat', 'dvnl-family-recipe-book' ),
							'type'  => 'text',
						),
						array(
							'id'	=> 'dvnl_total_energy',
							'label' => __( 'Total Energy', 'dvnl-family-recipe-book' ),
							'type'	=> 'text',
						),
						array(
							'id'    => 'dvnl_cholesterol',
							'label' => __( 'Cholesterol', 'dvnl-family-recipe-book' ),
							'type'  => 'text',
						),
						array(
							'id'    => 'dvnl_sodium',
							'label' => __( 'Sodium', 'dvnl-family-recipe-book' ),
							'type'  => 'text',
						),
						array(
							'id'    => 'dvnl_fibre',
							'label' => __( 'Fibre', 'dvnl-family-recipe-book' ),
							'type'  => 'text',
						),
					),
					// 'template' => 'partials/dvnl-family-recipe-book-recipe-nutrition-metabox.php',
				),
			),
			array(
				'id'			=> 'dvnl_family_recipe_book_recipe_notes',
				'title'			=> __( 'Notes', 'dvnl-family-recipe-book' ),
				'callback'		=> array( $this, 'render_recipe_metabox_templates' ),
				'screen'		=> 'dvnl_recipes',
				'context'		=> 'normal',
				'priority'		=> 'high',
				'callback_args'	=> array(
					'nonce'		=> 'dvnl_recipe_notes_nonce',
					'fields'	=> array(
						array(
							'id'	=> 'dvnl_notes',
							'label'	=> __( 'Notes', 'dvnl-family-recipe-book' ),
							'type'	=> 'textarea',
						),
					),
				),
			),
		);

		// loop through and register each metabox.
		foreach ( $metabox_args as $args ) {
			$this->register_single_metabox( $args );
		}
	}

	/**
	 * Register a single metabox.
	 *
	 * @param array $values The metabox arguments.
	 * @return void
	 */
	private function register_single_metabox( $values ) {
        if ( $values['id'] === 'dvnl_family_recipe_book_recipe_repeater_test' ) {
            $this->render_recipe_metabox_repeater( $values, null );
            return;
        }
		add_meta_box( $values['id'], $values['title'], $values['callback'], $values['screen'], $values['context'], $values['priority'], $values['callback_args'] );
	}

	/**
	 * Render the recipe metaboxes dynamically.
	 *
     * @param         WP_Post $post The post object.
     * @param array   $metabox The metabox arguments.
	 * @return void
	 */
	public function render_recipe_metabox_templates( $post, $metabox ) {
		if ( isset( $metabox['args']['template'] ) ) {
			include plugin_dir_path( __FILE__ ) . $metabox['args']['template'];
			return;
		}

		echo '<div class="dvnl-recipes metabox">';
		foreach ( $metabox['args']['fields'] as $field ) {
			load_template( plugin_dir_path( __FILE__ ) . 'partials/dvnl-family-recipe-book-recipe-metabox.php', false, array( 'nonce' => $metabox['args']['nonce'], 'field' => $field ) );
		}
		echo '</div>';

	}

    public function render_recipe_metabox_repeater( $post, $metabox ) {
        require_once plugin_dir_path( __FILE__ ) . 'partials/class-dvnl-family-recipe-book-field-repeater.php';
        $repeater = new Dvnl_Family_Recipe_Book_Field_Repeater();
        $repeater->dvnl_add_meta_boxes();
    }

	/**
	 * Generic method for saving date in the recipe metaboxes.
     *
     * @param int $post_id The post ID.
     * @return void
	 */
	public function save_recipe_metaboxes( $post_id ) {
		$field_args = array(
			array(
				'nonce'  => 'dvnl_recipe_details_nonce',
				'fields' => array(
					'dvnl_original_author',
					'dvnl_published_date',
					'dvnl_cost',
					'dvnl_url',
				),
			),
			array(
				'nonce'  => 'dvnl_recipe_ingredients_nonce',
				'fields' => array(
					'dvnl_ingredients',
				),
			),
		);

		foreach ( $field_args as $args ) {
			$this->save_single_metabox( $post_id, $args );
		};
	}

	/**
	 * Save the recipe details metabox.
     *
     * @param int   $post_id The post ID.
     * @param array $args The metabox arguments.
     * @return void
	 */
	public function save_single_metabox( $post_id, $args ) {

		// verify nonce.
		if ( ! isset( $_POST[ $args['nonce'] ] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( sanitize_key( $_POST[ $args['nonce'] ] ), 'dvnl_recipe_submit' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// verify not a revision.
		$parent_id = wp_is_post_revision( $post_id );
		if ( $parent_id ) {
			$post_id = $parent_id;
		}

		// save the data.
		foreach ( $args['fields'] as $field ) {
			if ( array_key_exists( $field, $_POST ) ) {
				update_post_meta( $post_id, $field, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
			}
		}

	}

}
