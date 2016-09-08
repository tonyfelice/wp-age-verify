<?php
/**
 * Define the admin class
 *
 * @since 0.0.1
 *
 * @package Age_Verify\Admin
 */

// Don't allow this file to be accessed directly.
if ( ! defined( 'WPINC' ) ) {
	die();
}

/**
 * The admin class.
 *
 * @since 0.0.1
 */
final class Age_Verify_Admin {

	/**
	 * The only instance of this class.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected static $instance = null;

	/**
	 * Get the only instance of this class.
	 *
	 * @since 0.0.1
	 *
	 * @return object $instance The only instance of this class.
	 */
	public static function get_instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Prevent cloning of this class.
	 *
	 * @since 0.0.1
	 *
	 * @return void
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'seo-age-gate' ), Age_Verify::VERSION );
	}

	/**
	 * Prevent unserializing of this class.
	 *
	 * @since 0.0.1
	 *
	 * @return void
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'seo-age-gate' ), Age_Verify::VERSION );
	}

	/**
	 * Construct the class!
	 *
	 * @since 0.0.1
	 *
	 * @return void
	 */
	public function __construct() {

		/**
		 * The settings callbacks.
		 */
		require( plugin_dir_path( __FILE__ ) . 'settings.php' );

		// Add the settings page.
		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );

		// Add and register the settings sections and fields.
		add_action( 'admin_init', array( $this, 'register_settings' ) );

		// Add the "Settings" link to the plugin row.
		add_filter( 'plugin_action_links_seo-age-gate/seo-age-gate.php', array( $this, 'add_settings_link' ), 10 );

		// Enqueue the script.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Only load with post-specific stuff if enabled.
		if ( 'content' == get_option( '_sag_require_for' ) ) {

			// Add a "restrict" checkbox to individual posts/pages.
			add_action( 'post_submitbox_misc_actions', array( $this, 'add_submitbox_checkbox' ) );

			// Save the "restrict" checkbox value.
			add_action( 'save_post', array( $this, 'save_post' ) );

		}
	}

	/**
	 * Add to the settings page.
	 *
	 * @since 0.0.1
	 *
	 * @return void
	 */
	public function add_settings_page() {

		add_options_page (
			__( 'SEO Age Gate', 'seo-age-gate' ),
			__( 'SEO Age Gate', 'seo-age-gate' ),
			'manage_options',
			'seo-age-gate',
			'sag_settings_page'
		);
	}

	/**
	 * Add and register the settings sections and fields.
	 *
	 * @since 0.0.1
	 *
	 * @return void
	 */
	public function register_settings() {

		/* General Section */
		add_settings_section( 'sag_settings_general', null, 'sag_settings_callback_section_general', 'seo-age-gate' );

	 	// What to protect (entire site or specific content)
		add_settings_field( '_sag_require_for', __( 'Require verification for', 'seo-age-gate' ), 'sag_settings_callback_require_for_field', 'seo-age-gate', 'sag_settings_general' );
	 	register_setting  ( 'seo-age-gate', '_sag_require_for', 'esc_attr' );

	 	// Who to verify (logged in or all)
		add_settings_field( '_sag_always_verify', __( 'Verify the age of', 'seo-age-gate' ), 'sag_settings_callback_always_verify_field', 'seo-age-gate', 'sag_settings_general' );
	 	register_setting  ( 'seo-age-gate', '_sag_always_verify', 'esc_attr' );

	 	// Bypass variable
	 	add_settings_field( '_sag_bypass', __( 'Bypass Variable', 'seo-age-gate' ), 'sag_settings_callback_bypass_field', 'seo-age-gate', 'sag_settings_general' );
	 	register_setting  ( 'seo-age-gate', '_sag_bypass', 'intval' );

	 	// Minimum Age
		add_settings_field( '_sag_minimum_age', '<label for="_sag_minimum_age">' . __( 'Visitors must be', 'seo-age-gate' ) . '</label>', 'sag_settings_callback_minimum_age_field', 'seo-age-gate', 'sag_settings_general' );
	 	register_setting  ( 'seo-age-gate', '_sag_minimum_age', 'intval' );

	 	// Memory Length
	 	add_settings_field( '_sag_cookie_duration', '<label for="_sag_cookie_duration">' . __( 'Remember visitors for', 'seo-age-gate' ) . '</label>', 'sag_settings_callback_cookie_duration_field', 'seo-age-gate', 'sag_settings_general' );
	 	register_setting  ( 'seo-age-gate', '_sag_cookie_duration', 'intval' );

	 	add_settings_field( '_sag_membership', __( 'Membership', 'seo-age-gate' ), 'sag_settings_callback_membership_field', 'seo-age-gate', 'sag_settings_general' );
	 	register_setting  ( 'seo-age-gate', '_sag_membership', 'intval' );

	 	/* Display Section */
	 	add_settings_section( 'sag_settings_display', __( 'Display Options', 'seo-age-gate' ), 'sag_settings_callback_section_display', 'seo-age-gate' );

	 	// Heading
	 	add_settings_field( '_sag_heading', '<label for="_sag_heading">' . __( 'Overlay Heading', 'seo-age-gate' ) . '</label>', 'sag_settings_callback_heading_field', 'seo-age-gate', 'sag_settings_display' );
	 	register_setting  ( 'seo-age-gate', '_sag_heading', 'esc_attr' );

	 	// Description
	 	add_settings_field( '_sag_description', '<label for="_sag_description">' . __( 'Overlay Description', 'seo-age-gate' ) . '</label>', 'sag_settings_callback_description_field', 'seo-age-gate', 'sag_settings_display' );
	 	register_setting  ( 'seo-age-gate', '_sag_description', 'esc_attr' );

	 	// Input Type
	 	add_settings_field( '_sag_input_type', '<label for="_sag_input_type">' . __( 'Verify ages using', 'seo-age-gate' ) . '</label>', 'sag_settings_callback_input_type_field', 'seo-age-gate', 'sag_settings_display' );
	 	register_setting  ( 'seo-age-gate', '_sag_input_type', 'esc_attr' );

	 	// Enable CSS
	 	add_settings_field( '_sag_styling', __( 'Styling', 'seo-age-gate' ), 'sag_settings_callback_styling_field', 'seo-age-gate', 'sag_settings_display' );
	 	register_setting  ( 'seo-age-gate', '_sag_styling', 'intval' );

	 	// Overlay Color
	 	add_settings_field( '_sag_overlay_color', __( 'Overlay Color', 'seo-age-gate' ), 'sag_settings_callback_overlay_color_field', 'seo-age-gate', 'sag_settings_display' );
	 	register_setting  ( 'seo-age-gate', '_sag_overlay_color', array( $this, 'validate_color' ) );

	 	// Background Color
	 	add_settings_field( '_sag_bgcolor', __( 'Background Color', 'seo-age-gate' ), 'sag_settings_callback_bgcolor_field', 'seo-age-gate', 'sag_settings_display' );
	 	register_setting  ( 'seo-age-gate', '_sag_bgcolor', array( $this, 'validate_color' ) );

		do_action( 'sag_register_settings' );
	}

	/**
	 * Add a direct link to the SEO Age Gate settings page from the plugins page.
	 *
	 * @since 0.0.1
	 *
	 * @param array  $actions The links beneath the plugin's name.
	 * @param string $file    The plugin filename.
	 * @return string
	 */
	public function add_settings_link( $actions ) {

		$settings_link = '<a href="' . esc_url( add_query_arg( 'page', 'seo-age-gate', admin_url( 'options-general.php' ) ) ) . '">';
			$settings_link .= __( 'Settings', 'seo-age-gate' );
		$settings_link .='</a>';

		array_unshift( $actions, $settings_link );

		return $actions;
	}

	/**
	 * Validates the color inputs from the settings.
	 *
	 * @since 0.0.1
	 *
	 * @param  string $color A color hex.
	 * @return string $color The validated color hex.
	 */
	public function validate_color( $color ) {

		$color = preg_replace( '/[^0-9a-fA-F]/', '', $color );

		if ( strlen( $color ) == 6 || strlen( $color ) == 3 ) {
			$color = $color;
		} else {
			$color = '';
		}

		return $color;
	}

	/**
	 * Enqueue the scripts.
	 *
	 * @since 0.0.1
	 *
	 * @param string $page The current admin page.
	 * @return void
	 */
	public function enqueue_scripts( $page ) {

		if ( 'settings_page_seo-age-gate' != $page ) {
			return;
		}

		wp_enqueue_style( 'wp-color-picker' );

		wp_enqueue_script( 'sag-admin-scripts', plugin_dir_url( __FILE__ ) . 'assets/scripts.js', array(
			'jquery',
			'wp-color-picker'
		) );
	}

	/**
	 * Add a "restrict" checkbox to individual posts/pages.
	 *
	 * @since 0.0.1
	 *
	 * @return void
	 */
	public function add_submitbox_checkbox() { ?>

		<div class="misc-pub-section verify-age">

			<?php wp_nonce_field( 'sag_save_post', 'sag_nonce' ); ?>

			<input type="checkbox" name="_sag_needs_verify" id="_sag_needs_verify" value="1" <?php checked( 1, get_post_meta( get_the_ID(), '_sag_needs_verify', true ) ); ?> />
			<label for="_sag_needs_verify" class="selectit">
				<?php esc_html_e( 'Require age verification for this content', 'seo-age-gate' ); ?>
			</label>

		</div><!-- .misc-pub-section -->

	<?php }

	/**
	 * Save the "restrict" checkbox value.
	 *
	 * @since 0.0.1
	 *
	 * @param int $post_id The current post ID.
	 * @return void
	 */
	public function save_post( $post_id ) {

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		$nonce = ( isset( $_POST['sag_nonce'] ) ) ? $_POST['sag_nonce'] : '';

		if ( ! wp_verify_nonce( $nonce, 'sag_save_post' ) ) {
			return;
		}

		$needs_verify = ( isset( $_POST['_sag_needs_verify'] ) ) ? (int) $_POST['_sag_needs_verify'] : 0;

		update_post_meta( $post_id, '_sag_needs_verify', $needs_verify );
	}
}
