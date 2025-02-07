<?php
/**
 * Repeater field rendering class.
 *
 * @package    Dvnl_Family_Recipe_Book
 * @subpackage Dvnl_Family_Recipe_Book/admin/partials
 */

/**
 * Class for rendering individual fields within a repeater.
 */
class Dvnl_Family_Recipe_Book_Repeater_Fields {
	/**
	 * Field identifier.
	 *
	 * @var string
	 */
	private $id;

	/**
	 * Field type.
	 *
	 * @var string
	 */
	private $type;

	/**
	 * Field label.
	 *
	 * @var string
	 */
	private $label;

	/**
	 * Field options for select fields.
	 *
	 * @var array
	 */
	private $options;

	/**
	 * Field name attribute.
	 *
	 * @var string
	 */
	private $name;

	/**
	 * Field value.
	 *
	 * @var mixed
	 */
	private $value;

	/**
	 * Initialize the class.
	 *
	 * @param array $args Field configuration arguments.
	 */
	public function __construct( $args ) {
		$this->id      = $args['id'];
		$this->type    = $args['type'];
		$this->label   = $args['label'];
		$this->name    = isset( $args['name'] ) ? $args['name'] : $args['id'];
		$this->value   = isset( $args['value'] ) ? $args['value'] : '';
		$this->options = isset( $args['options'] ) ? $args['options'] : array();
	}

	/**
	 * Render a select field.
	 */
	public function render_field_repeater_select() {
		?>
		<label class="screen-reader-text" for="<?php echo esc_attr( $this->id ); ?>"><?php echo esc_html( $this->label ); ?></label>
		<select
			id="<?php echo esc_attr( $this->id ); ?>"
			name="<?php echo esc_attr( $this->name ); ?>"
			data-base-name="<?php echo esc_attr( $this->name ); ?>">
			<?php foreach ( $this->options as $key => $value ) : ?>
				<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $this->value, $key ); ?>><?php echo esc_html( $value ); ?></option>
			<?php endforeach; ?>
		</select>
		<?php
	}

	/**
	 * Render a text field.
	 */
	public function render_field_repeater_text() {
		?>
		<label class="screen-reader-text" for="<?php echo esc_attr( $this->id ); ?>"><?php echo esc_html( $this->label ); ?></label>
		<input
			class="widefat"
			id="<?php echo esc_attr( $this->id ); ?>"
			type="<?php echo esc_attr( $this->type ); ?>"
			name="<?php echo esc_attr( $this->name ); ?>"
			value="<?php echo esc_attr( $this->value ); ?>"
			data-base-name="<?php echo esc_attr( $this->name ); ?>">
		<?php
	}
}

?>
