<?php
/**
 * Dvnl Family Recipe Book Field Repeater
 *
 * @package Dvnl_Family_Recipe_Book
 */

/**
 * Provides repeater field functionality for metaboxes
 *
 * This class handles the creation and management of repeatable field groups
 * within WordPress metaboxes. It supports dynamic field types and manages
 * the storage and retrieval of repeatable data.
 *
 * @subpackage Dvnl_Family_Recipe_Book/admin/partials
 * @link       https://devnel.blog
 * @since      2.0.0
 * @author     Kyle Nel <kyle@devnel.com>
 */
class Dvnl_Family_Recipe_Book_Field_Repeater {
	/**
	 * The complete field configuration array
	 *
	 * @since 2.0.0
	 * @var array
	 */
	private $field;

	/**
	 * The field identifier
	 *
	 * @since 2.0.0
	 * @var string
	 */
	private $id;

	/**
	 * The field type
	 *
	 * @since 2.0.0
	 * @var string
	 */
	private $type;

	/**
	 * The field label
	 *
	 * @since 2.0.0
	 * @var string
	 */
	private $label;

	/**
	 * Array of sub-fields configuration
	 *
	 * @since 2.0.0
	 * @var array
	 */
	private $options;

	/**
	 * Array of custom field configurations
	 *
	 * @since 2.0.0
	 * @var array
	 */
	private $custom_fields;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 2.0.0
	 * @param array $field_args Configuration array for the repeater field.
	 */
	public function __construct( $field_args ) {
		$this->field   = $field_args;
		$this->id      = $field_args['id'];
		$this->type    = $field_args['type'];
		$this->label   = $field_args['label'];
		$this->options = isset( $field_args['options'] ) ? $field_args['options'] : null;

		require_once plugin_dir_path( __DIR__ ) . 'partials/class-dvnl-family-recipe-book-repeater-fields.php';
	}

	/**
	 * Get the nonce name for this specific repeater field.
	 *
	 * @since 2.0.0
	 * @return string The nonce name.
	 */
	private function get_nonce_name() {
		return 'dvnl_repeatable_meta_box_nonce_' . $this->id;
	}

	/**
	 * Get the nonce action for this specific repeater field.
	 *
	 * @since 2.0.0
	 * @return string The nonce action.
	 */
	private function get_nonce_action() {
		return 'dvnl_repeatable_meta_box_nonce_action_' . $this->id;
	}

	/**
	 * Renders blank repeater fields for the empty template row.
	 *
	 * @since 2.0.0
	 * @param string|int $index The index for the field names.
	 * @return void
	 */
	private function dvnl_render_blank_repeater_fields( $index ) {
		foreach ( $this->options as $sub_field ) {
			$field_args      = array_merge(
				$sub_field,
				array(
					'value'    => '',
					'name'     => $this->id . '_' . $sub_field['id'],
					'class'    => 'widefat',
					'required' => false,
				)
			);
			$repeater_fields = new Dvnl_Family_Recipe_Book_Repeater_Fields( $field_args );
			?>
			<td>
				<?php
				switch ( $sub_field['type'] ) {
					case 'text':
					case 'url':
					case 'date':
					case 'number':
						$repeater_fields->render_field_repeater_text( $index );
						break;
					case 'select':
						$repeater_fields->render_field_repeater_select( $index );
						break;
					case 'button':
						echo '<div>Button field not yet implemented.</div>';
						break;
					default:
						echo esc_html( sprintf( 'Error: Field type %s not found.', $sub_field['type'] ) );
				}
				?>
			</td>
			<?php
		}
	}

	/**
	 * Displays the repeatable meta box with all its fields.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function dvnl_repeatable_meta_box_display() {
		global $post;

		$repeatable_fields = get_post_meta( $post->ID, $this->id, true );
		$table_id          = 'repeatable-fieldset-' . esc_attr( $this->id );

		// Add nonce field for security with field-specific nonce.
		wp_nonce_field( $this->get_nonce_action(), $this->get_nonce_name() );
		?>
		<script type="text/javascript">
		jQuery(document).ready(function( $ ){
			$( '#<?php echo esc_js( $table_id ); ?>' ).on('click', '.add-row', function(e) {
				e.preventDefault();
				var $table = $(this).closest('table');
				var $row = $table.find( '.empty-row.screen-reader-text' ).clone(true);
				var rowCount = $table.find('tbody tr').not('.empty-row').length;

				// Update all input names in the new row with the correct index
				$row.find('input, select').each(function() {
					var baseName = $(this).data('base-name');
					$(this).attr('name', baseName + '_' + rowCount);
				});

				$row.removeClass( 'empty-row screen-reader-text' );
				$row.insertBefore( $table.find('tbody > tr:last') );
				return false;
			});

			$( '#<?php echo esc_js( $table_id ); ?>' ).on('click', '.remove-row', function(e) {
				e.preventDefault();
				if ($(this).closest('tbody').find('tr').not('.empty-row').length > 1) {
					var $table = $(this).closest('table');
					$(this).closest('tr').remove();

					// Reindex remaining rows
					$table.find('tbody tr').not('.empty-row').each(function(index) {
						$(this).find('input, select').each(function() {
							var baseName = $(this).data('base-name');
							$(this).attr('name', baseName + '_' + index);
						});
					});
				}
				return false;
			});
		});
		</script>

		<div class="dvnl-repeater-field-wrapper">
			<table id="<?php echo esc_attr( $table_id ); ?>" width="100%">
				<thead>
					<tr>
						<?php
						foreach ( $this->options as $sub_field ) {
							?>
							<th><?php echo esc_html( $sub_field['label'] ); ?></th>
							<?php
						}
						?>
						<th width="100"></th>
					</tr>
				</thead>
				<tbody>
				<?php
				if ( $repeatable_fields ) {
					foreach ( $repeatable_fields as $index => $field ) {
						$this->render_repeater_row( $field, $index );
					}
				} else {
					// If no existing data, show one visible blank row.
					?>
					<tr>
						<?php $this->dvnl_render_blank_repeater_fields( 0 ); ?>
						<td><a class="button remove-row" href="#"><?php echo esc_html__( 'Remove', 'dvnl-family-recipe-book' ); ?></a></td>
					</tr>
					<?php
				}
				?>

				<!-- empty hidden repeater for jQuery -->
				<tr class="empty-row screen-reader-text">
					<?php $this->dvnl_render_blank_repeater_fields( 'TEMPLATE' ); ?>
					<td><a class="button remove-row" href="#"><?php echo esc_html__( 'Remove', 'dvnl-family-recipe-book' ); ?></a></td>
				</tr>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="<?php echo count( $this->options ) + 1; ?>">
							<a class="button add-row" href="#"><?php echo esc_html__( 'Add another', 'dvnl-family-recipe-book' ); ?></a>
						</td>
					</tr>
				</tfoot>
			</table>
		</div>
		<?php
	}

	/**
	 * Renders a single row of the repeater with the given field data.
	 *
	 * @since 2.0.0
	 * @param array      $field_data The field data to populate the row with.
	 * @param string|int $index The index for the field names.
	 * @return void
	 */
	private function render_repeater_row( $field_data, $index ) {
		?>
		<tr>
			<?php
			foreach ( $this->options as $sub_field ) {
				$field_id        = $sub_field['id'];
				$value           = isset( $field_data[ $field_id ] ) ? $field_data[ $field_id ] : '';
				$repeater_fields = new Dvnl_Family_Recipe_Book_Repeater_Fields(
					array_merge(
						$sub_field,
						array(
							'value' => $value,
							'name'  => $this->id . '_' . $sub_field['id'] . '_' . $index,
						)
					)
				);
				?>
				<td>
					<?php
					switch ( $sub_field['type'] ) {
						case 'text':
						case 'url':
						case 'date':
						case 'number':
							$repeater_fields->render_field_repeater_text( $index );
							break;
						case 'select':
							$repeater_fields->render_field_repeater_select( $index );
							break;
						default:
							echo esc_html( sprintf( 'Error: Field type %s not found.', $sub_field['type'] ) );
					}
					?>
				</td>
				<?php
			}
			?>
			<td><a class="button remove-row" href="#"><?php echo esc_html__( 'Remove', 'dvnl-family-recipe-book' ); ?></a></td>
		</tr>
		<?php
	}
}