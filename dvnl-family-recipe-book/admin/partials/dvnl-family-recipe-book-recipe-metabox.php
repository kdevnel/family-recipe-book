<?php
/**
 * Dvnl Family Recipe Book Details Metabox
 *
 * @package Dvnl_Family_Recipe_Book
 */

/**
 * Display of the recipe details metabox
 *
 * @link       https://devnel.blog
 * @since      2.0.0
 *
 * @subpackage Dvnl_Family_Recipe_Book/admin/partials
 */
require_once plugin_dir_path( __DIR__ ) . 'partials/class-dvnl-family-recipe-book-custom-fields.php';
$custom_fields = new Dvnl_Family_Recipe_Book_Custom_Fields( $args );
$field_id      = $args['field']['id'];
$field_label   = $args['field']['label'];
$field_type    = $args['field']['type'];
?>


<div class="meta-options dvnl-recipes field">
	<?php
	switch ( $field_type ) {
		case 'text':
		case 'url':
		case 'date':
		case 'number':
			$custom_fields->render_field_text();
			break;
		case 'select':
			$custom_fields->render_field_select();
			break;
		case 'textarea':
			$custom_fields->render_field_textarea();
			break;
		case 'button':
			echo '<div>Button field not yet implemented.</div>';
			break;
		case 'repeater':
			$custom_fields->render_field_repeater();
			break;
		default:
			echo 'Field type not found: ' . esc_html( $field_type ) . '.';
	}
	?>
</div>
