<?php
/**
 * Display of the recipe details metabox
 *
 * @link       https://devnel.blog
 * @since      2.0.0
 *
 * @package    Dvnl_Family_Recipe_Book
 * @subpackage Dvnl_Family_Recipe_Book/admin/partials
 */
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'partials/class-dvnl-family-recipe-book-custom-fields.php';
$custom_fields = new Dvnl_Family_Recipe_Book_Custom_Fields();
$nonce = $args['nonce'];
$id = $args['field']['id'];
$label = $args['field']['label'];
$type = $args['field']['type'];
?>


<p class="meta-options dvnl-recipes field">
	<?php
    switch ( $type ) {
        case 'select':
            return $custom_fields->render_select_field( $args );
            break;

        case 'repeater':
            return $custom_fields->render_repeater_field( $args );
            break;

        default:
            return $custom_fields->render_text_field( $args );
            break;
    }
	?>
</p>
