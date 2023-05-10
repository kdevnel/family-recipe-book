<?php
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'class-dvnl-family-recipe-book-field-repeater.php';

class Dvnl_Family_Recipe_Book_Custom_Fields {

    public function render_select_field( $args ) {
        $id = $args['field']['id'];
        $options = $args['field']['options'];
        ?>
        <select
            id="<?php echo $id ?>"
            name="<?php echo $id ?>">
            <?php foreach ( $options as $key => $value ) : ?>
                <option value="<?php echo $key ?>" <?php selected( esc_attr( get_post_meta( get_the_ID(), $id, true ) ), $key ); ?>><?php echo $value ?></option>
            <?php endforeach; ?>
        </select>
        <?php
    }

    public function render_text_field( $args ) {
        $id = $args['field']['id'];
        $type = $args['field']['type'];
        ?>
        <input
            id="<?php echo $id ?>"
            type="<?php echo $type ?>"
            name="<?php echo $id ?>"
            value="<?php echo esc_attr( get_post_meta( get_the_ID(), $id, true ) ); ?>">
        <?php
    }

    public function render_repeater_field( $args ) {
        // $id = $args['field']['id'];
        // $label = $args['field']['label'];
        // $type = $args['field']['type'];
        // $options = $args['field']['options'];
        // $repeater = new Dvnl_Family_Recipe_Book_Field_Repeater();
        // $repeater->render_repeater_field( $id, $label, $type, $options );
        // $repeater = new Dvnl_Family_Recipe_Book_Field_Repeater();
        // $repeater->dvnl_repeatable_meta_box_display();
    }

}

?>
