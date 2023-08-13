<?php
/**
 * Repeater field for use in metaboxes
 *
 * @link       https://devnel.blog
 * @since      2.0.0
 *
 * @package    Dvnl_Family_Recipe_Book
 * @subpackage Dvnl_Family_Recipe_Book/admin
 */
class Dvnl_Family_Recipe_Book_Field_Repeater {
    private $field;
    private $id;
    private $type;
    private $label;
    private $options;

    public function __construct( $field_args ) {
        $this->field = $field_args;
        $this->id = $field_args['id'];
        $this->type = $field_args['type'];
        $this->label = $field_args['label'];
        isset( $field_args['options'] ) ? $this->options = $field_args['options'] : null;
    }

    function dvnl_get_sample_options() {
        $options = array (
            'Option 1' => 'option1',
            'Option 2' => 'option2',
            'Option 3' => 'option3',
            'Option 4' => 'option4',
        );

        return $options;
    }

    function dvnl_repeatable_meta_box_display() {
        global $post;

        // echo '<pre>';
        // var_dump($this->field['options']);
        // echo '</pre>';
        // return;

        $repeatable_fields = get_post_meta($post->ID, $this->id, true);
        $options = $this->dvnl_get_sample_options();

        ?>
        <script type="text/javascript">
        jQuery(document).ready(function( $ ){
            $( '#add-row' ).on('click', function() {
                var row = $( '.empty-row.screen-reader-text' ).clone(true);
                row.removeClass( 'empty-row screen-reader-text' );
                row.insertBefore( '#repeatable-fieldset-one tbody>tr:last' );
                return false;
            });

            $( '.remove-row' ).on('click', function() {
                $(this).parents('tr').remove();
                return false;
            });
        });
        </script>

        <table id="repeatable-fieldset-one" width="100%">
        <thead>
            <tr>
                <?php
                $table_headers = array();
                foreach ( $this->options as $sub_field ) { ?>
                    <th><?php echo $sub_field['label']; ?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
        <?php
        if ( $repeatable_fields ) :
            foreach ( $repeatable_fields as $field ) { ?>
            <tr>
                <td><input type="text" class="widefat" name="name[]" value="<?php if($field['name'] != '') echo esc_attr( $field['name'] ); ?>" /></td>

                <td>
                    <select name="select[]">
                    <?php foreach ( $options as $label => $value ) : ?>
                    <option value="<?php echo $value; ?>"<?php selected( $field['select'], $value ); ?>><?php echo $label; ?></option>
                    <?php endforeach; ?>
                    </select>
                </td>

                <td><input type="text" class="widefat" name="url[]" value="<?php if ($field['url'] != '') echo esc_attr( $field['url'] ); else echo 'http://'; ?>" /></td>

                <td><a class="button remove-row" href="#">Remove</a></td>
            </tr>
            <?php
            }
        else :
            // display a blank repeater field
            ?>
            <tr>
                <!-- <td><input type="text" class="widefat" name="name[]" /></td>

                <td>
                    <select name="select[]">
                    <?php foreach ( $this->options as $label => $value ) : ?>
                    <option value="<?php echo $value; ?>"><?php echo $label; ?></option>
                    <?php endforeach; ?>
                    </select>
                </td>

                <td><input type="text" class="widefat" name="url[]" value="http://" /></td>

                <td><a class="button remove-row" href="#">Remove</a></td> -->

                <?php
                // TODO: Implement a dynamic field display system

                // Generate a foreach loop that creates a table from the options array. Table headings are the label field and then the row content should be the type field.
                // The type field should be a switch statement that loads a template file based on the type.
                // The template file should be a function that takes the field as an argument and displays the field.
                // The template file should be loaded with load_template().
                // The template file should be located in the admin/partials/ folder.
                foreach ( $this->options as $sub_field ) { ?>
                    <td>
                        <?php
                        switch ( $sub_field['type'] ) {
                            case 'text':
                                echo '<input type="text" class="widefat" name="' . $sub_field['id'] . '[]" />';
                                break;
                            case 'select':
                                echo '<select name="' . $sub_field['id'] . '[]">';
                                foreach ( $sub_field['options'] as $label => $value ) {
                                    echo '<option value="' . $value . '">' . $label . '</option>';
                                }
                                echo '</select>';
                                break;
                            case 'url':
                                echo '<input type="text" class="widefat" name="' . $sub_field['id'] . '[]" value="http://" />';
                                break;
                            case 'button':
                                echo '<a class="button remove-row" href="#">Remove</a>';
                                break;
                            default:
                                echo 'Error: Field type not found.';
                        }
                        ?>
                    </td>
            <?php } ?>
            </tr>
        <?php endif; ?>

        <!-- empty hidden one for jQuery -->
        <tr class="empty-row screen-reader-text">
            <td><input type="text" class="widefat" name="name[]" /></td>

            <td>
                <select name="select[]">
                <?php foreach ( $this->options as $label => $value ) : ?>
                <option value="<?php echo $value; ?>"><?php echo $label; ?></option>
                <?php endforeach; ?>
                </select>
            </td>

            <td><input type="text" class="widefat" name="url[]" value="http://" /></td>

            <td><a class="button remove-row" href="#">Remove</a></td>
        </tr>
        </tbody>
        </table>

        <p><a id="add-row" class="button" href="#">Add another</a></p>
        <?php
    }

    // TODO: implement save add_action('save_post', 'dvnl_repeatable_meta_box_save');

    function dvnl_repeatable_meta_box_save($post_id) {
        if ( ! isset( $_POST['dvnl_repeatable_meta_box_nonce'] ) ||
        ! wp_verify_nonce( $_POST['dvnl_repeatable_meta_box_nonce'], 'dvnl_repeatable_meta_box_nonce' ) )
            return;

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return;

        if (!current_user_can('edit_post', $post_id))
            return;

        $old = get_post_meta($post_id, 'repeatable_fields', true);
        $new = array();
        $options = $this->dvnl_get_sample_options();

        $names = $_POST['name'];
        $selects = $_POST['select'];
        $urls = $_POST['url'];

        $count = count( $names );

        for ( $i = 0; $i < $count; $i++ ) {
            if ( $names[$i] != '' ) :
                $new[$i]['name'] = stripslashes( strip_tags( $names[$i] ) );

                if ( in_array( $selects[$i], $options ) )
                    $new[$i]['select'] = $selects[$i];
                else
                    $new[$i]['select'] = '';

                if ( $urls[$i] == 'http://' )
                    $new[$i]['url'] = '';
                else
                    $new[$i]['url'] = stripslashes( $urls[$i] ); // and however you want to sanitize
            endif;
        }

        if ( !empty( $new ) && $new != $old )
            update_post_meta( $post_id, 'repeatable_fields', $new );
        elseif ( empty($new) && $old )
            delete_post_meta( $post_id, 'repeatable_fields', $old );
    }
}