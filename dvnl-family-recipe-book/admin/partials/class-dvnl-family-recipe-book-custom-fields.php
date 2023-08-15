<?php
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'partials/class-dvnl-family-recipe-book-field-repeater.php';

class Dvnl_Family_Recipe_Book_Custom_Fields {
    private $field;
    private $id;
    private $type;
    private $label;
    private $options;

    public function __construct( $args ) {
        $this->field = $args['field'];
        $this->id = $args['field']['id'];
        $this->type = $args['field']['type'];
        $this->label = $args['field']['label'];
        isset( $args['field']['options'] ) ? $this->options = $args['field']['options'] : null;
    }

    public function render_field_select() {
        ?>
        <label for="<?php echo $this->id ?>"><?php echo $this->label ?></label>
        <select
            class="widefat"
            id="<?php echo $this->id ?>"
            name="<?php echo $this->id ?>">
            <?php foreach ( $this->options as $key => $value ) : ?>
                <option value="<?php echo $key ?>" <?php selected( esc_attr( get_post_meta( get_the_ID(), $this->id, true ) ), $key ); ?>><?php echo $value ?></option>
            <?php endforeach; ?>
        </select>
        <?php
    }

    public function render_field_text() {
        ?>
        <label for="<?php echo $this->id ?>"><?php echo $this->label ?></label>
        <input
            class="widefat"
            id="<?php echo $this->id ?>"
            type="<?php echo $this->type ?>"
            name="<?php echo $this->id ?>"
            value="<?php echo esc_attr( get_post_meta( get_the_ID(), $this->id, true ) ); ?>">
        <?php
    }

    public function render_field_repeater() {
        $repeater = new Dvnl_Family_Recipe_Book_Field_Repeater( $this->field );
        $repeater->dvnl_repeatable_meta_box_display();
    }

    public function render_field_textarea() {
        ?>
        <label for="<?php echo $this->id ?>"><?php echo $this->label ?></label>
        <textarea
            id="<?php echo $this->id ?>"
            name="<?php echo $this->id ?>"
            rows="2"
            cols="40">
                <?php echo esc_attr( get_post_meta( get_the_ID(), $this->id, true ) ); ?>
            </textarea>
        <?php
    }

}

?>
