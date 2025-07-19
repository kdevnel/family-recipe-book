<?php
/**
 * Sharing Class
 *
 * @package Family_Recipe_Book
 */

namespace dvnl;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sharing Class
 *
 * Handles social sharing functionality for recipes.
 */
class Sharing {

	   /**
		* Settings instance
		*
		* @var Settings
		*/
	   private $settings;

	   /**
		* Initialize the class
		*
		* @param Settings $settings Settings instance.
		*/
	   public function __construct( Settings $settings ) {
			   $this->settings = $settings;

			   // Only proceed if sharing is enabled
			   if ( 'yes' !== $this->settings->get_option( 'enable_sharing', 'yes' ) ) {
					   return;
			   }

		// Add sharing buttons to recipe content
		add_filter( 'the_content', array( $this, 'add_sharing_buttons' ) );

		// Add sharing styles
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_sharing_styles' ) );

		// Add sharing script
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_sharing_script' ) );
	}

	/**
	 * Add sharing buttons to recipe content
	 *
	 * @param string $content Post content.
	 * @return string Modified content.
	 */
	public function add_sharing_buttons( $content ) {
		// Only add to recipe post type
		if ( ! is_singular( 'recipe' ) ) {
			return $content;
		}

		// Get post data
		$post_id = get_the_ID();
		$post_title = get_the_title();
		$post_url = urlencode( get_permalink() );
		$post_title_encoded = urlencode( $post_title );
		$site_name = urlencode( get_bloginfo( 'name' ) );

		// Create sharing buttons
		$buttons = '<div class="dvnl-recipe-sharing">';
		$buttons .= '<h3>' . esc_html__( 'Share This Recipe', 'family-recipe-book' ) . '</h3>';
		$buttons .= '<div class="dvnl-recipe-sharing-buttons">';

		// Facebook
		$facebook_url = 'https://www.facebook.com/sharer/sharer.php?u=' . $post_url;
		$buttons .= '<a href="' . esc_url( $facebook_url ) . '" class="dvnl-recipe-sharing-button dvnl-recipe-sharing-facebook" target="_blank" rel="noopener noreferrer">';
		$buttons .= '<span class="dashicons dashicons-facebook"></span>';
		$buttons .= '<span class="dvnl-recipe-sharing-label">' . esc_html__( 'Facebook', 'family-recipe-book' ) . '</span>';
		$buttons .= '</a>';

		// Twitter/X
		$twitter_url = 'https://twitter.com/intent/tweet?text=' . $post_title_encoded . '&url=' . $post_url . '&via=' . $site_name;
		$buttons .= '<a href="' . esc_url( $twitter_url ) . '" class="dvnl-recipe-sharing-button dvnl-recipe-sharing-twitter" target="_blank" rel="noopener noreferrer">';
		$buttons .= '<span class="dashicons dashicons-twitter"></span>';
		$buttons .= '<span class="dvnl-recipe-sharing-label">' . esc_html__( 'Twitter', 'family-recipe-book' ) . '</span>';
		$buttons .= '</a>';

		// Pinterest
		$pinterest_url = 'https://pinterest.com/pin/create/button/?url=' . $post_url . '&description=' . $post_title_encoded;

		// Add image if available
		if ( has_post_thumbnail( $post_id ) ) {
			$pinterest_url .= '&media=' . urlencode( get_the_post_thumbnail_url( $post_id, 'large' ) );
		}

		$buttons .= '<a href="' . esc_url( $pinterest_url ) . '" class="dvnl-recipe-sharing-button dvnl-recipe-sharing-pinterest" target="_blank" rel="noopener noreferrer">';
		$buttons .= '<span class="dashicons dashicons-pinterest"></span>';
		$buttons .= '<span class="dvnl-recipe-sharing-label">' . esc_html__( 'Pinterest', 'family-recipe-book' ) . '</span>';
		$buttons .= '</a>';

		// Email
		$email_subject = sprintf( __( 'Check out this recipe: %s', 'family-recipe-book' ), $post_title );
		$email_body = sprintf( __( "I thought you might enjoy this recipe: %s\n\n%s", 'family-recipe-book' ), $post_title, get_permalink() );
		$email_url = 'mailto:?subject=' . urlencode( $email_subject ) . '&body=' . urlencode( $email_body );

		$buttons .= '<a href="' . esc_url( $email_url ) . '" class="dvnl-recipe-sharing-button dvnl-recipe-sharing-email">';
		$buttons .= '<span class="dashicons dashicons-email"></span>';
		$buttons .= '<span class="dvnl-recipe-sharing-label">' . esc_html__( 'Email', 'family-recipe-book' ) . '</span>';
		$buttons .= '</a>';

		// WhatsApp (only on mobile)
		$whatsapp_text = sprintf( __( 'Check out this recipe: %s %s', 'family-recipe-book' ), $post_title, get_permalink() );
		$whatsapp_url = 'https://api.whatsapp.com/send?text=' . urlencode( $whatsapp_text );

		$buttons .= '<a href="' . esc_url( $whatsapp_url ) . '" class="dvnl-recipe-sharing-button dvnl-recipe-sharing-whatsapp" target="_blank" rel="noopener noreferrer">';
		$buttons .= '<span class="dashicons dashicons-whatsapp"></span>';
		$buttons .= '<span class="dvnl-recipe-sharing-label">' . esc_html__( 'WhatsApp', 'family-recipe-book' ) . '</span>';
		$buttons .= '</a>';

		$buttons .= '</div>'; // .dvnl-recipe-sharing-buttons
		$buttons .= '</div>'; // .dvnl-recipe-sharing

		// Add buttons after content
		return $content . $buttons;
	}

	/**
	 * Enqueue sharing styles
	 */
	public function enqueue_sharing_styles() {
		// Only enqueue on recipe pages
		if ( ! is_singular( 'recipe' ) ) {
			return;
		}

		wp_enqueue_style(
			'dvnl-family-recipe-book-sharing',
			DVNL_FAMILY_RECIPE_BOOK_PLUGIN_URL . 'assets/css/sharing.css',
			array(),
			DVNL_FAMILY_RECIPE_BOOK_VERSION
		);

		// Add inline sharing button styles
		$sharing_css = '
			.dvnl-recipe-sharing {
				margin-top: 40px;
				padding: 20px;
				background-color: #f9f9f9;
				border-radius: 4px;
				border: 1px solid #e0e0e0;
			}

			.dvnl-recipe-sharing h3 {
				margin-top: 0;
				margin-bottom: 15px;
				font-size: 18px;
				color: #333;
			}

			.dvnl-recipe-sharing-buttons {
				display: flex;
				flex-wrap: wrap;
				gap: 10px;
			}

			.dvnl-recipe-sharing-button {
				display: inline-flex;
				align-items: center;
				padding: 8px 16px;
				border-radius: 4px;
				color: #fff;
				text-decoration: none;
				font-size: 14px;
				transition: all 0.2s ease;
			}

			.dvnl-recipe-sharing-button:hover {
				opacity: 0.9;
				transform: translateY(-2px);
			}

			.dvnl-recipe-sharing-button .dashicons {
				margin-right: 8px;
			}

			.dvnl-recipe-sharing-facebook {
				background-color: #3b5998;
			}

			.dvnl-recipe-sharing-twitter {
				background-color: #1da1f2;
			}

			.dvnl-recipe-sharing-pinterest {
				background-color: #bd081c;
			}

			.dvnl-recipe-sharing-email {
				background-color: #777;
			}

			.dvnl-recipe-sharing-whatsapp {
				background-color: #25d366;
				display: none;
			}

			@media (max-width: 768px) {
				.dvnl-recipe-sharing-whatsapp {
					display: inline-flex;
				}

				.dvnl-recipe-sharing-buttons {
					flex-direction: column;
				}

				.dvnl-recipe-sharing-button {
					width: 100%;
				}
			}

			@media print {
				.dvnl-recipe-sharing {
					display: none;
				}
			}
		';

		wp_add_inline_style( 'dvnl-family-recipe-book-sharing', $sharing_css );

		// Enqueue dashicons
		wp_enqueue_style( 'dashicons' );
	}

	/**
	 * Enqueue sharing script
	 */
	public function enqueue_sharing_script() {
		// Only enqueue on recipe pages
		if ( ! is_singular( 'recipe' ) ) {
			return;
		}

		wp_enqueue_script(
			'dvnl-family-recipe-book-sharing',
			DVNL_FAMILY_RECIPE_BOOK_PLUGIN_URL . 'assets/js/sharing.js',
			array( 'jquery' ),
			DVNL_FAMILY_RECIPE_BOOK_VERSION,
			true
		);
	}
}
