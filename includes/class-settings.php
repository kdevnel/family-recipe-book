<?php
/**
 * Settings Class
 *
 * @package Family_Recipe_Book
 */

namespace dvnl;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Settings Class
 *
 * Handles the admin settings page for the plugin.
 */
class Settings {

	/**
	 * Settings options
	 *
	 * @var array
	 */
	private $options;

	/**
	 * Settings page slug
	 *
	 * @var string
	 */
	private $page_slug = 'family-recipe-book-settings';

	/**
	 * Option name in the database
	 *
	 * @var string
	 */
	private $option_name = 'dvnl_family_recipe_book_settings';

	/**
	 * Initialize the class
	 */
	public function __construct() {
		// Get options from database
		$this->options = get_option( $this->option_name, $this->get_default_options() );

		// Add admin menu
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );

		// Register settings
		add_action( 'admin_init', array( $this, 'register_settings' ) );

		// Add settings link to plugins page
		add_filter( 'plugin_action_links_' . DVNL_FAMILY_RECIPE_BOOK_PLUGIN_BASENAME, array( $this, 'add_settings_link' ) );
	}

	/**
	 * Get default options
	 *
	 * @return array Default options
	 */
	private function get_default_options() {
		return array(
			'enable_print_button' => 'yes',
			'enable_sharing' => 'yes',
			'default_difficulty' => 'medium',
			'schema_rating' => 'yes',
			'custom_css' => '',
		);
	}

	/**
	 * Add admin menu
	 */
	public function add_admin_menu() {
		add_submenu_page(
			'edit.php?post_type=recipe',
			__( 'Recipe Book Settings', 'family-recipe-book' ),
			__( 'Settings', 'family-recipe-book' ),
			'manage_options',
			$this->page_slug,
			array( $this, 'render_settings_page' )
		);
	}

	/**
	 * Register settings
	 */
	public function register_settings() {
		register_setting(
			$this->option_name,
			$this->option_name,
			array( $this, 'sanitize_settings' )
		);

		// General Settings Section
		add_settings_section(
			'dvnl_family_recipe_book_general_section',
			__( 'General Settings', 'family-recipe-book' ),
			array( $this, 'render_general_section' ),
			$this->page_slug
		);

		// Print Button Setting
		add_settings_field(
			'enable_print_button',
			__( 'Enable Print Button', 'family-recipe-book' ),
			array( $this, 'render_checkbox_field' ),
			$this->page_slug,
			'dvnl_family_recipe_book_general_section',
			array(
				'id' => 'enable_print_button',
				'description' => __( 'Add a print button to recipe pages', 'family-recipe-book' ),
			)
		);

		// Sharing Setting
		add_settings_field(
			'enable_sharing',
			__( 'Enable Social Sharing', 'family-recipe-book' ),
			array( $this, 'render_checkbox_field' ),
			$this->page_slug,
			'dvnl_family_recipe_book_general_section',
			array(
				'id' => 'enable_sharing',
				'description' => __( 'Add social sharing buttons to recipe pages', 'family-recipe-book' ),
			)
		);

		// Default Difficulty Setting
		add_settings_field(
			'default_difficulty',
			__( 'Default Difficulty', 'family-recipe-book' ),
			array( $this, 'render_select_field' ),
			$this->page_slug,
			'dvnl_family_recipe_book_general_section',
			array(
				'id' => 'default_difficulty',
				'description' => __( 'Set the default difficulty level for new recipes', 'family-recipe-book' ),
				'options' => array(
					'easy' => __( 'Easy', 'family-recipe-book' ),
					'medium' => __( 'Medium', 'family-recipe-book' ),
					'hard' => __( 'Hard', 'family-recipe-book' ),
				),
			)
		);

		// Schema Settings Section
		add_settings_section(
			'dvnl_family_recipe_book_schema_section',
			__( 'Schema.org Settings', 'family-recipe-book' ),
			array( $this, 'render_schema_section' ),
			$this->page_slug
		);

		// Schema Rating Setting
		add_settings_field(
			'schema_rating',
			__( 'Enable Recipe Ratings', 'family-recipe-book' ),
			array( $this, 'render_checkbox_field' ),
			$this->page_slug,
			'dvnl_family_recipe_book_schema_section',
			array(
				'id' => 'schema_rating',
				'description' => __( 'Add rating functionality to recipes and include in Schema.org markup', 'family-recipe-book' ),
			)
		);

		// Advanced Settings Section
		add_settings_section(
			'dvnl_family_recipe_book_advanced_section',
			__( 'Advanced Settings', 'family-recipe-book' ),
			array( $this, 'render_advanced_section' ),
			$this->page_slug
		);

		// Custom CSS Setting
		add_settings_field(
			'custom_css',
			__( 'Custom CSS', 'family-recipe-book' ),
			array( $this, 'render_textarea_field' ),
			$this->page_slug,
			'dvnl_family_recipe_book_advanced_section',
			array(
				'id' => 'custom_css',
				'description' => __( 'Add custom CSS to style your recipes', 'family-recipe-book' ),
			)
		);
	}

	/**
	 * Sanitize settings
	 *
	 * @param array $input Settings input.
	 * @return array Sanitized settings.
	 */
	public function sanitize_settings( $input ) {
		$sanitized = array();

		// Sanitize checkbox fields
		$checkbox_fields = array( 'enable_print_button', 'enable_sharing', 'schema_rating' );
		foreach ( $checkbox_fields as $field ) {
			$sanitized[ $field ] = isset( $input[ $field ] ) ? 'yes' : 'no';
		}

		// Sanitize select fields
		if ( isset( $input['default_difficulty'] ) ) {
			$sanitized['default_difficulty'] = sanitize_text_field( $input['default_difficulty'] );
		}

		// Sanitize textarea fields
		if ( isset( $input['custom_css'] ) ) {
			$sanitized['custom_css'] = sanitize_textarea_field( $input['custom_css'] );
		}

		return $sanitized;
	}

	/**
	 * Render general section
	 */
	public function render_general_section() {
		echo '<p>' . esc_html__( 'Configure general settings for the Family Recipe Book plugin.', 'family-recipe-book' ) . '</p>';
	}

	/**
	 * Render schema section
	 */
	public function render_schema_section() {
		echo '<p>' . esc_html__( 'Configure Schema.org settings for better SEO.', 'family-recipe-book' ) . '</p>';
	}

	/**
	 * Render advanced section
	 */
	public function render_advanced_section() {
		echo '<p>' . esc_html__( 'Advanced settings for customizing the plugin.', 'family-recipe-book' ) . '</p>';
	}

	/**
	 * Render checkbox field
	 *
	 * @param array $args Field arguments.
	 */
	public function render_checkbox_field( $args ) {
		$id = $args['id'];
		$description = isset( $args['description'] ) ? $args['description'] : '';
		$checked = isset( $this->options[ $id ] ) && 'yes' === $this->options[ $id ] ? 'checked' : '';

		echo '<label for="' . esc_attr( $id ) . '">';
		echo '<input type="checkbox" id="' . esc_attr( $id ) . '" name="' . esc_attr( $this->option_name ) . '[' . esc_attr( $id ) . ']" value="yes" ' . esc_attr( $checked ) . ' />';
		echo ' ' . esc_html( $description );
		echo '</label>';
	}

	/**
	 * Render select field
	 *
	 * @param array $args Field arguments.
	 */
	public function render_select_field( $args ) {
		$id = $args['id'];
		$description = isset( $args['description'] ) ? $args['description'] : '';
		$options = isset( $args['options'] ) ? $args['options'] : array();
		$value = isset( $this->options[ $id ] ) ? $this->options[ $id ] : '';

		echo '<select id="' . esc_attr( $id ) . '" name="' . esc_attr( $this->option_name ) . '[' . esc_attr( $id ) . ']">';
		foreach ( $options as $option_value => $option_label ) {
			echo '<option value="' . esc_attr( $option_value ) . '" ' . selected( $value, $option_value, false ) . '>' . esc_html( $option_label ) . '</option>';
		}
		echo '</select>';
		echo '<p class="description">' . esc_html( $description ) . '</p>';
	}

	/**
	 * Render textarea field
	 *
	 * @param array $args Field arguments.
	 */
	public function render_textarea_field( $args ) {
		$id = $args['id'];
		$description = isset( $args['description'] ) ? $args['description'] : '';
		$value = isset( $this->options[ $id ] ) ? $this->options[ $id ] : '';

		echo '<textarea id="' . esc_attr( $id ) . '" name="' . esc_attr( $this->option_name ) . '[' . esc_attr( $id ) . ']" rows="5" class="large-text code">' . esc_textarea( $value ) . '</textarea>';
		echo '<p class="description">' . esc_html( $description ) . '</p>';
	}

	/**
	 * Render settings page
	 */
	public function render_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form method="post" action="options.php">
				<?php
				settings_fields( $this->option_name );
				do_settings_sections( $this->page_slug );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Add settings link to plugins page
	 *
	 * @param array $links Plugin action links.
	 * @return array Modified plugin action links.
	 */
	public function add_settings_link( $links ) {
		$settings_link = '<a href="' . admin_url( 'edit.php?post_type=recipe&page=' . $this->page_slug ) . '">' . __( 'Settings', 'family-recipe-book' ) . '</a>';
		array_unshift( $links, $settings_link );
		return $links;
	}

	/**
	 * Get a specific option
	 *
	 * @param string $key Option key.
	 * @param mixed  $default Default value.
	 * @return mixed Option value.
	 */
	public function get_option( $key, $default = false ) {
		return isset( $this->options[ $key ] ) ? $this->options[ $key ] : $default;
	}
}

// Initialize the class.
new Settings();