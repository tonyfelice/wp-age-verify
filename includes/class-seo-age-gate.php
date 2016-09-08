<?php
/**
 * Define the main plugin class
 *
 * @since 0.0.1
 *
 * @package Age_Verify
 */

// Don't allow this file to be accessed directly.
if ( ! defined( 'WPINC' ) ) {
	die();
}

/**
 * The main class.
 *
 * @since 0.0.1
 */
final class Age_Verify {

	/**
	 * The plugin version.
	 *
	 * @since 0.0.1
	 */
	const VERSION = '0.3.0';

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
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'seo-age-gate' ), self::VERSION );
	}

	/**
	 * Prevent unserializing of this class.
	 *
	 * @since 0.0.1
	 *
	 * @return void
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'seo-age-gate' ), self::VERSION );
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
		 * Require the necessary files.
		 */
		$this->require_files();

		/**
		 * Add the necessary action hooks.
		 */
		$this->add_actions();
	}

	/**
	 * Require the necessary files.
	 *
	 * @since 0.0.1
	 *
	 * @return void
	 */
	private function require_files() {

		/**
		 * The helper functions.
		 */
		require( plugin_dir_path( __FILE__ ) . 'functions.php' );
	}

	/**
	 * Add the necessary action hooks.
	 *
	 * @since 0.0.1
	 *
	 * @return void
	 */
	private function add_actions() {

		// Load the text domain for i18n.
		add_action( 'init', array( $this, 'load_textdomain' ) );

		// If checked in the settings, load the default and custom styles.
		if ( get_option( '_sag_styling', 1 ) == 1 ) {

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );

			add_action( 'wp_head', array( $this, 'custom_styles' ) );

		}

		// Maybe display the overlay.
		add_action( 'wp_footer', array( $this, 'verify_overlay' ) );

		// Maybe hide the content of a restricted content type.
		add_action( 'the_content', array( $this, 'restrict_content' ) );

		// Verify the visitor's input.
		add_action( 'template_redirect', array( $this, 'verify' ) );

		// If checked in the settings, add to the registration form.
		if ( sag_confirmation_required() ) {

			add_action( 'register_form', 'sag_register_form' );

			add_action( 'register_post', 'sag_register_check', 10, 3 );

		}
	}

	/**
	 * Load the text domain.
	 *
	 * Based on the bbPress implementation.
	 *
	 * @since 0.0.1
	 *
	 * @return The textdomain or false on failure.
	 */
	public function load_textdomain() {

		$locale = get_locale();
		$locale = apply_filters( 'plugin_locale',  $locale, 'seo-age-gate' );
		$mofile = sprintf( 'seo-age-gate-%s.mo', $locale );

		$mofile_local  = plugin_dir_path( dirname( __FILE__ ) ) . 'languages/' . $mofile;
		$mofile_global = WP_LANG_DIR . '/seo-age-gate/' . $mofile;

		if ( file_exists( $mofile_local ) )
			return load_textdomain( 'seo-age-gate', $mofile_local );

		if ( file_exists( $mofile_global ) )
			return load_textdomain( 'seo-age-gate', $mofile_global );

		load_plugin_textdomain( 'seo-age-gate' );

		return false;
	}

	/**
	 * Enqueue the styles.
	 *
	 * @since 0.0.1
	 *
	 * @return void
	 */
	public function enqueue_styles() {

		wp_enqueue_style( 'sag-styles', plugin_dir_url( __FILE__ ) . 'assets/styles.css' );
	}

	/**
	 * Print the custom colors, as defined in the admin.
	 *
	 * @since 0.0.1
	 *
	 * @return void
	 */
	public function custom_styles() { ?>

		<style type="text/css">

			#sag-overlay-wrap {
				background: #<?php echo esc_attr( sag_get_background_color() ); ?>;
			}

			#sag-overlay {
				background: #<?php echo esc_attr( sag_get_overlay_color() ); ?>;
			}

		</style>

		<?php
		/**
		* Trigger action after setting the custom color styles.
		*/
		do_action( 'sag_custom_styles' );
	}

	/**
	 * Print the actual overlay if the visitor needs verification.
	 *
	 * @since 0.0.1
	 *
	 * @return void
	 */
	public function verify_overlay() {

		if ( ! sag_needs_verification() ) {
			return;
		}

		// Disable page caching by W3 Total Cache.
		define( 'DONOTCACHEPAGE', true ); ?>

		<div id="sag-overlay-wrap">

			<?php do_action( 'sag_before_modal' ); ?>

			<div id="sag-overlay">

				<h1><?php esc_html_e( sag_get_the_heading() ); ?></h1>

				<?php if ( sag_get_the_desc() )
					echo '<p>' . esc_html( sag_get_the_desc() ). '</p>'; ?>

				<?php do_action( 'sag_before_form' ); ?>

				<?php sag_verify_form(); ?>

				<?php do_action( 'sag_after_form' ); ?>

			</div>

			<?php do_action( 'sag_after_modal' ); ?>

		</div>
	<?php }

	/**
	 * Hide the content if it is age restricted.
	 *
	 * @since 0.0.1
	 *
	 * @param  string $content The object content.
	 * @return string $content The object content or an age-restricted message if needed.
	 */
	 public function restrict_content( $content ) {

		if ( ! sag_only_content_restricted() ) {
			return $content;
		}

		if ( is_singular() ) {
			return $content;
		}

		if ( ! sag_content_is_restricted() ) {
			return $content;
		}

		return sprintf( apply_filters( 'sag_restricted_content_message', __( 'You must be %1s years old to view this content.', 'seo-age-gate' ) . ' <a href="%2s">' . __( 'Please verify your age', 'seo-age-gate' ) . '</a>.' ),
			esc_html( sag_get_minimum_age() ),
			esc_url( get_permalink( get_the_ID() ) )
		);
	 }

	/**
	 * Verify the visitor if the form was submitted.
	 *
	 * @since 0.0.1
	 *
	 * @return void
	 */
	public function verify() {

		if ( ! isset( $_POST['sag-nonce'] ) || ! wp_verify_nonce( $_POST['sag-nonce'], 'verify-age' ) )
			return;

		$redirect_url = remove_query_arg( array( 'age-verified', 'verify-error' ), wp_get_referer() );

		$is_verified  = false;

		$error = 1; // Catch-all in case something goes wrong

		$input_type   = sag_get_input_type();

		switch ( $input_type ) {


			case 'checkbox' :

				if ( isset( $_POST['sag_verify_confirm'] ) && (int) $_POST['sag_verify_confirm'] == 1 )
					$is_verified = true;
				else
					$error = 2; // Didn't check the box

				break;

			default :

				if ( checkdate( (int) $_POST['sag_verify_m'], (int) $_POST['sag_verify_d'], (int) $_POST['sag_verify_y'] ) ) :

					$age = sag_get_visitor_age( $_POST['sag_verify_y'], $_POST['sag_verify_m'], $_POST['sag_verify_d'] );

				    if ( $age >= sag_get_minimum_age() )
						$is_verified = true;
					else
						$error = 3; // Not old enough

				else :

					$error = 4; // Invalid date

				endif;

				break;
		}

		$is_verified = apply_filters( 'sag_passed_verify', $is_verified );

		if ( $is_verified == true ) :

			do_action( 'sag_was_verified' );

			if ( isset( $_POST['sag_verify_remember'] ) )
				$cookie_duration = time() +  ( sag_get_cookie_duration() * 60 );
			else
				$cookie_duration = 0;

			setcookie( 'age-verified', 1, $cookie_duration, COOKIEPATH, COOKIE_DOMAIN, false );

			wp_redirect( esc_url_raw( $redirect_url ) . '?age-verified=' . wp_create_nonce( 'age-verified' ) );
			exit;

		else :

			do_action( 'sag_was_not_verified' );

			wp_redirect( esc_url_raw( add_query_arg( 'verify-error', $error, $redirect_url ) ) );
			exit;

		endif;
	}
}
