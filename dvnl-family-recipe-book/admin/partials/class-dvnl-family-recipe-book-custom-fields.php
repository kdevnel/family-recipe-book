<?php
/**
 * Dvnl Family Recipe Book Custom Fields
 *
 * @package Dvnl_Family_Recipe_Book
 */

/**
 * Custom fields handler for the Family Recipe Book plugin.
 */

require_once plugin_dir_path( __DIR__ ) . 'partials/class-dvnl-family-recipe-book-field-repeater.php';

/**
 * The custom fields rendering class.
 *
 * Handles the rendering of different types of custom fields in the recipe metabox.
 *
 * @since      1.0.0
 * @package    Dvnl_Family_Recipe_Book
 * @subpackage Dvnl_Family_Recipe_Book/admin/partials
 */
class Dvnl_Family_Recipe_Book_Custom_Fields {
	/**
	 * The field configuration array.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    array
	 */
	private $field;

	/**
	 * The field ID.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string
	 */
	private $id;

	/**
	 * The field type.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string
	 */
	private $type;

	/**
	 * The field label.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string
	 */
	private $label;

	/**
	 * The field options for select fields.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    array|null
	 */
	private $options;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param array $args The field configuration array.
	 */
	public function __construct( $args ) {
		$this->field = $args['field'];
		$this->id    = $args['field']['id'];
		$this->type  = $args['field']['type'];
		$this->label = $args['field']['label'];
		isset( $args['field']['options'] ) ? $this->options = $args['field']['options'] : null;
	}

	/**
	 * Render a select field.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function render_field_select() {
		?>
		<label for="<?php echo esc_attr( $this->id ); ?>"><?php echo esc_html( $this->label ); ?></label>
		<select
			class="widefat"
			id="<?php echo esc_attr( $this->id ); ?>"
			name="<?php echo esc_attr( $this->id ); ?>">
			<?php foreach ( $this->options as $key => $value ) : ?>
				<option value="<?php echo esc_attr( $key ); ?>" <?php selected( esc_attr( get_post_meta( get_the_ID(), $this->id, true ) ), $key ); ?>><?php echo esc_html( $value ); ?></option>
			<?php endforeach; ?>
		</select>
		<?php
	}

	/**
	 * Render a text field.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function render_field_text() {
		?>
		<label for="<?php echo esc_attr( $this->id ); ?>"><?php echo esc_html( $this->label ); ?></label>
		<input
			class="widefat"
			id="<?php echo esc_attr( $this->id ); ?>"
			type="<?php echo esc_attr( $this->type ); ?>"
			name="<?php echo esc_attr( $this->id ); ?>"
			value="<?php echo esc_attr( get_post_meta( get_the_ID(), $this->id, true ) ); ?>">
		<?php
	}

	/**
	 * Render a repeater field.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function render_field_repeater() {
		$repeater = new Dvnl_Family_Recipe_Book_Field_Repeater( $this->field );
		$repeater->dvnl_repeatable_meta_box_display();
	}

	/**
	 * Render a textarea field.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function render_field_textarea() {
		?>
		<label for="<?php echo esc_attr( $this->id ); ?>"><?php echo esc_html( $this->label ); ?></label>
		<textarea
			id="<?php echo esc_attr( $this->id ); ?>"
			name="<?php echo esc_attr( $this->id ); ?>"
			rows="2"
			cols="40"><?php echo esc_textarea( get_post_meta( get_the_ID(), $this->id, true ) ); ?></textarea>
		<?php
	}
}
