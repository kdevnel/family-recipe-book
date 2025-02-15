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
	 * Definition of the recipe metaboxes
	 *
	 * @return array the metaboxes to render
	 */
	private function get_recipe_metaboxes() {
		$metabox_args = array(
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
							'id'    => 'dvnl_video',
							'label' => __( 'Video', 'dvnl-family-recipe-book' ),
							'type'  => 'url',
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
				'id'            => 'dvnl_family_recipe_book_recipe_ingredients_repeater',
				'title'         => __( 'Ingredients', 'dvnl-family-recipe-book' ),
				'callback'      => array( $this, 'render_recipe_metabox_templates' ),
				'screen'        => 'dvnl_recipes',
				'context'       => 'normal',
				'priority'      => 'high',
				'callback_args' => array(
					'nonce'  => 'dvnl_recipe_ingredients_repeater_nonce',
					'fields' => array(
						array(
							'id'      => 'dvnl_recipe_ingredients_repeater_field_ingredients',
							'label'   => __( 'Ingredients', 'dvnl-family-recipe-book' ),
							'type'    => 'repeater',
							'options' => array(
								array(
									'id'    => 'quantity',
									'label' => __( 'Quantity', 'dvnl-family-recipe-book' ),
									'type'  => 'number',
								),
								array(
									'id'      => 'unit',
									'label'   => __( 'Unit', 'dvnl-family-recipe-book' ),
									'type'    => 'select',
									'options' => array(
										'tsp'      => __( 'Teaspoon', 'dvnl-family-recipe-book' ),
										'tbsp'     => __( 'Tablespoon', 'dvnl-family-recipe-book' ),
										'cup'      => __( 'Cup', 'dvnl-family-recipe-book' ),
										'ml'       => __( 'Millilitre', 'dvnl-family-recipe-book' ),
										'l'        => __( 'Litre', 'dvnl-family-recipe-book' ),
										'g'        => __( 'Gram', 'dvnl-family-recipe-book' ),
										'kg'       => __( 'Kilogram', 'dvnl-family-recipe-book' ),
										'oz'       => __( 'Ounce', 'dvnl-family-recipe-book' ),
										'lb'       => __( 'Pound', 'dvnl-family-recipe-book' ),
										'pinch'    => __( 'Pinch', 'dvnl-family-recipe-book' ),
										'piece'    => __( 'Piece', 'dvnl-family-recipe-book' ),
										'to_taste' => __( 'To Taste', 'dvnl-family-recipe-book' ),
									),
								),
								array(
									'id'    => 'ingredient_name',
									'label' => __( 'Ingredient Name', 'dvnl-family-recipe-book' ),
									'type'  => 'text',
								),
								array(
									'id'    => 'note',
									'label' => __( 'Note', 'dvnl-family-recipe-book' ),
									'type'  => 'text',
								),
							),
						),
					),
				),
			),
			array(
				'id'            => 'dvnl_family_recipe_book_recipe_repeater_instructions_repeater',
				'title'         => __( 'Instructions', 'dvnl-family-recipe-book' ),
				'callback'      => array( $this, 'render_recipe_metabox_templates' ),
				'screen'        => 'dvnl_recipes',
				'context'       => 'normal',
				'priority'      => 'high',
				'callback_args' => array(
					'nonce'  => 'dvnl_recipe_instructions_repeater_nonce',
					'fields' => array(
						array(
							'id'      => 'dvnl_recipe_instructions_repeater_field_instructions',
							'label'   => __( 'Instructions', 'dvnl-family-recipe-book' ),
							'type'    => 'repeater',
							'options' => array(
								array(
									'id'    => 'instruction_steps',
									'label' => __( 'Steps', 'dvnl-family-recipe-book' ),
									'type'  => 'text',
								),
							),
						),
					),
				),
			),
			array(
				'id'            => 'dvnl_family_recipe_book_recipe_notes',
				'title'         => __( 'Notes', 'dvnl-family-recipe-book' ),
				'callback'      => array( $this, 'render_recipe_metabox_templates' ),
				'screen'        => 'dvnl_recipes',
				'context'       => 'normal',
				'priority'      => 'high',
				'callback_args' => array(
					'nonce'  => 'dvnl_recipe_notes_nonce',
					'fields' => array(
						array(
							'id'    => 'dvnl_notes',
							'label' => __( 'Notes', 'dvnl-family-recipe-book' ),
							'type'  => 'textarea',
						),
					),
				),
			),
		);
		return $metabox_args;
	}

	/**
	 *
	 * Register all metaboxes for the recipe post type.
	 *
	 * @return void
	 */
	public function register_recipe_metaboxes() {
		foreach ( $this->get_recipe_metaboxes() as $args ) {
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
		wp_nonce_field( 'dvnl_recipe_submit', $metabox['args']['nonce'] );
		foreach ( $metabox['args']['fields'] as $field ) {
			load_template(
				plugin_dir_path( __FILE__ ) . 'partials/dvnl-family-recipe-book-recipe-metabox.php',
				false,
				array(
					'nonce' => $metabox['args']['nonce'],
					'field' => $field,
				)
			);
		}
		echo '</div>';
	}

	/**
	 * Generic method for saving date in the recipe metaboxes.
	 *
	 * @param int $post_id The post ID.
	 * @return void
	 */
	public function save_recipe_metaboxes( $post_id ) {
		foreach ( $this->get_recipe_metaboxes() as $args ) {
			$this->save_single_metabox( $post_id, $args );
		}
	}

	/**
	 * Save the recipe details metabox.
	 *
	 * @param int   $post_id The post ID.
	 * @param array $args The metabox arguments.
	 * @return void
	 */
	public function save_single_metabox( $post_id, $args ) {
		$nonce = $args['callback_args']['nonce'];
		// verify nonce.
		if ( ! isset( $_POST[ $nonce ] ) || ! wp_verify_nonce( sanitize_key( $_POST[ $nonce ] ), 'dvnl_recipe_submit' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// verify not a revision.
		$parent_id = wp_is_post_revision( $post_id );
		if ( $parent_id ) {
			$post_id = $parent_id;
		}

		$fields = $args['callback_args']['fields'];
		// save the data.
		foreach ( $fields as $field ) {
			if ( 'repeater' === $field['type'] ) {
				$this->save_repeater_field( $post_id, $field );
			} elseif ( isset( $_POST[ sanitize_key( $field['id'] ) ] ) ) {
				$value = sanitize_text_field( wp_unslash( $_POST[ sanitize_key( $field['id'] ) ] ) );
				switch ( $field['type'] ) {
					case 'date':
						if ( ! empty( $value ) && strtotime( $value ) !== false ) {
							$date = DateTime::createFromFormat( 'Y-m-d', $value );
							if ( $date instanceof DateTime ) {
								update_post_meta( $post_id, $field['id'], $date->format( 'Y-m-d' ) );
							}
						} else {
							delete_post_meta( $post_id, $field['id'] );
						}
						break;
					default:
						update_post_meta( $post_id, $field['id'], sanitize_text_field( $value ) );
				}
			}
		}
	}

	/**
	 * Save a repeater field's data.
	 *
	 * @param int   $post_id The post ID.
	 * @param array $field The field configuration.
	 * @return void
	 */
	private function save_repeater_field( $post_id, $field ) {
		// Verify the nonce for this specific repeater field.
		$nonce_name   = 'dvnl_repeatable_meta_box_nonce_' . $field['id'];
		$nonce_action = 'dvnl_repeatable_meta_box_nonce_action_' . $field['id'];

		if ( ! isset( $_POST[ $nonce_name ] ) || ! wp_verify_nonce( sanitize_key( $_POST[ $nonce_name ] ), $nonce_action ) ) {
			return;
		}

		$old = get_post_meta( $post_id, $field['id'], true );
		$new = array();

		// Check if we have data for this repeater.
		$post_key = sanitize_key( $field['id'] );

		// Process each field type from the form.
		foreach ( $field['options'] as $sub_field ) {
			// Look for fields with the pattern {repeater_id}_{sub_field_id}_{index}.
			$field_pattern = $post_key . '_' . $sub_field['id'] . '_';

			foreach ( $_POST as $key => $value ) {
				if ( strpos( $key, $field_pattern ) === 0 ) {
					$index = substr( $key, strlen( $field_pattern ) );

					if ( ! isset( $new[ $index ] ) ) {
						$new[ $index ] = array();
					}

					// Unslash the value before sanitization.
					$raw_value = wp_unslash( $value );

					switch ( $sub_field['type'] ) {
						case 'text':
						case 'date':
							$new[ $index ][ $sub_field['id'] ] = sanitize_text_field( $raw_value );
							break;
						case 'url':
							$new[ $index ][ $sub_field['id'] ] = esc_url_raw( $raw_value );
							break;
						case 'number':
							$new[ $index ][ $sub_field['id'] ] = absint( $raw_value );
							break;
						case 'select':
							if ( isset( $sub_field['options'] ) && in_array( $raw_value, array_keys( $sub_field['options'] ), true ) ) {
								$new[ $index ][ $sub_field['id'] ] = sanitize_text_field( $raw_value );
							}
							break;
					}
				}
			}
		}

		// Remove any empty rows.
		$new = array_filter(
			$new,
			function ( $row ) {
				return ! empty( array_filter( $row ) );
			}
		);

		// Reindex array to ensure sequential keys.
		$new = array_values( $new );

		if ( ! empty( $new ) && $new !== $old ) {
			update_post_meta( $post_id, $field['id'], $new );
		} elseif ( empty( $new ) && $old ) {
			delete_post_meta( $post_id, $field['id'], $old );
		}
	}
}
