<?php
class Dvnl_Family_Recipe_Book_Repeater_Fields {
    // private $field;
    private $id;
    private $type;
    private $label;
    private $options;

    public function __construct( $args ) {
        // $this->field = $args['field'];
        $this->id = $args['id'];
        $this->type = $args['type'];
        $this->label = $args['label'];
        isset( $args['options'] ) ? $this->options = $args['options'] : null;
    }

    public function render_field_repeater_select() {
        ?>
        <label class="screen-reader-text" for="<?php echo $this->id ?>"><?php echo $this->label ?></label>
        <select
            id="<?php echo $this->id ?>"
            name="<?php echo $this->id ?>">
            <?php foreach ( $this->options as $key => $value ) : ?>
                <option value="<?php echo $key ?>" <?php selected( esc_attr( get_post_meta( get_the_ID(), $this->id, true ) ), $key ); ?>><?php echo $value ?></option>
            <?php endforeach; ?>
        </select>
        <?php
    }

    public function render_field_repeater_text() {
        ?>
        <label class="screen-reader-text" for="<?php echo $this->id ?>"><?php echo $this->label ?></label>
        <input
            class="widefat"
            id="<?php echo $this->id ?>"
            type="<?php echo $this->type ?>"
            name="<?php echo $this->id ?>"
            value="<?php echo esc_attr( get_post_meta( get_the_ID(), $this->id, true ) ); ?>">
        <?php
    }

}

?>
