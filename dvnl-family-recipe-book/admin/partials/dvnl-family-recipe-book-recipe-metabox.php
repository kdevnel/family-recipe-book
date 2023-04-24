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
//print_r( $args );
$nonce = $args['nonce'];
$id = $args['field']['id'];
$label = $args['field']['label'];
$type = $args['field']['type'];
?>

<?php wp_nonce_field( 'dvnl_recipe_submit', $nonce ); ?>
<p class="meta-options dvnl-recipes field">
	<label for="<?php echo $id ?>"><?php echo $label ?></label>

	<?php
	if ( $type === 'select' ) {
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
		return;
	} else {
		?>
		<input
			id="<?php echo $id ?>"
			type="<?php echo $type ?>"
			name="<?php echo $id ?>"
			value="<?php echo esc_attr( get_post_meta( get_the_ID(), $id, true ) ); ?>">
		<?php
		return;
	}
	?>
</p>
