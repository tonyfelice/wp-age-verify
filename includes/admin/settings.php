<?php

// Don't access this directly, please
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Define the settings page.
 *
 * @since 0.1
 */
function sag_settings_page() { ?>

	<div class="wrap">

		<?php screen_icon(); ?>

		<h2><?php esc_html_e( 'SEO Age Gate Settings', 'seo-age-gate' ) ?></h2>

		<form action="options.php" method="post">

			<?php settings_fields( 'seo-age-gate' ); ?>

			<?php do_settings_sections( 'seo-age-gate' ); ?>

			<?php submit_button(); ?>

		</form>
	</div>

<?php }


/**********************************************************/
/******************** General Settings ********************/
/**********************************************************/

/**
 * Prints the general settings section heading.
 *
 * @since 0.1
 */
function sag_settings_callback_section_general() {

	// Something should go here
}

/**
 * Prints the "require for" settings field.
 *
 * @since 0.2
 */
function sag_settings_callback_require_for_field() { ?>

	<fieldset>
		<legend class="screen-reader-text">
			<span><?php esc_html_e( 'Require verification for', 'seo-age-gate' ); ?></span>
		</legend>
		<label>
			<input type="radio" name="_sag_require_for" value="site" <?php checked( 'site', get_option( '_sag_require_for', 'site' ) ); ?>/>
			 <?php esc_html_e( 'Entire site', 'seo-age-gate' ); ?><br />
		</label>
		<br />
		<label>
			<input type="radio" name="_sag_require_for" value="content" <?php checked( 'content', get_option( '_sag_require_for', 'site' ) ); ?>/>
			 <?php esc_html_e( 'Specific content', 'seo-age-gate' ); ?>
		</label>
	</fieldset>

<?php }

/**
 * Prints the "who to verify" settings field.
 *
 * @since 0.1
 */
function sag_settings_callback_always_verify_field() { ?>

	<fieldset>
		<legend class="screen-reader-text">
			<span><?php esc_html_e( 'Verify the age of', 'seo-age-gate' ); ?></span>
		</legend>
		<label>
			<input type="radio" name="_sag_always_verify" value="guests" <?php checked( 'guests', get_option( '_sag_always_verify', 'guests' ) ); ?>/>
			 <?php esc_html_e( 'Guests only', 'seo-age-gate' ); ?> <span class="description"><?php esc_html_e( 'Logged-in users will not need to verify their age.', 'seo-age-gate' ); ?></span><br />
		</label>
		<br />
		<label>
			<input type="radio" name="_sag_always_verify" value="all" <?php checked( 'all', get_option( '_sag_always_verify', 'guests' ) ); ?>/>
			 <?php esc_html_e( 'All visitors', 'seo-age-gate' ); ?>
		</label>
	</fieldset>

<?php }

/**
 * Prints the minimum age settings field.
 *
 * @since 0.1
 */
function sag_settings_callback_minimum_age_field() { ?>

	<input name="_sag_minimum_age" type="number" id="_sag_minimum_age" step="1" min="10" class="small-text" value="<?php echo esc_attr( get_option( '_sag_minimum_age', '21' ) ); ?>" /> <?php esc_html_e( 'years old or older to view this site', 'seo-age-gate' ); ?>

<?php }

/**
 * Prints the bypass variable settings field.
 *
 * @since 0.1
 */
function sag_settings_callback_bypass_field() { ?>

	<input name="_sag_bypass" type="text" id="_sag_bypass" value="<?php echo esc_attr( get_option( '_sag_bypass', 'bypass' ) ); ?>" /> <?php esc_html_e( 'allows spiders to bypass verification', 'seo-age-gate' ); ?>

<?php }

/**
 * Prints the cookie duration settings field.
 *
 * @since 0.1
 */
function sag_settings_callback_cookie_duration_field() { ?>

	<input name="_sag_cookie_duration" type="number" id="_sag_cookie_duration" step="15" size="20" class="small-text" value="<?php echo esc_attr( get_option( '_sag_cookie_duration', '720' ) ); ?>" /> <?php esc_html_e( 'minutes', 'seo-age-gate' ); ?>

<?php }

/**
 * Prints the membership settings field.
 *
 * @since 0.1
 */
function sag_settings_callback_membership_field() { ?>

	<fieldset>
		<legend class="screen-reader-text">
			<span><?php esc_html_e( 'Membership', 'seo-age-gate' ); ?></span>
		</legend>
		<label for="_sag_membership">
			<input name="_sag_membership" type="checkbox" id="_sag_membership" value="1" <?php checked( 1, get_option( '_sag_membership', 1 ) ); ?>/>
			 <?php esc_html_e( 'Require users to confirm their age before registering to this site', 'seo-age-gate' ); ?>
		</label>
	</fieldset>

<?php }


/**********************************************************/
/******************** Display Settings ********************/
/**********************************************************/

/**
 * Prints the display settings section heading.
 *
 * @since 0.1
 */
function sag_settings_callback_section_display() {

	echo '<p>' . esc_html__( 'These settings change the look of your overlay. You can use <code>%s</code> to display the minimum age number from the setting above.', 'seo-age-gate' ) . '</p>';
}

/**
 * Prints the modal heading settings field.
 *
 * @since 0.1
 */
function sag_settings_callback_heading_field() { ?>

	<input name="_sag_heading" type="text" id="_sag_heading" value="<?php echo esc_attr( get_option( '_sag_heading', __( 'You must be %s years old to visit this site.', 'seo-age-gate' ) ) ); ?>" class="regular-text" />

<?php }

/**
 * Prints the modal description settings field.
 *
 * @since 0.1
 */
function sag_settings_callback_description_field() { ?>

	<input name="_sag_description" type="text" id="_sag_description" value="<?php echo esc_attr( get_option( '_sag_description', __( 'Please verify your age', 'seo-age-gate' ) ) ); ?>" class="regular-text" />

<?php }

/**
 * Prints the input type settings field.
 *
 * @since 0.1
 */
function sag_settings_callback_input_type_field() { ?>

	<select name="_sag_input_type" id="_sag_input_type">
		<option value="dropdowns" <?php selected( 'dropdowns', get_option( '_sag_input_type', 'dropdowns' ) ); ?>><?php esc_html_e( 'Date dropdowns', 'seo-age-gate' ); ?></option>
		<option value="inputs" <?php selected( 'inputs', get_option( '_sag_input_type', 'dropdowns' ) ); ?>><?php esc_html_e( 'Inputs', 'seo-age-gate' ); ?></option>
		<option value="checkbox" <?php selected( 'checkbox', get_option( '_sag_input_type', 'dropdowns' ) ); ?>><?php esc_html_e( 'Confirm checkbox', 'seo-age-gate' ); ?></option>
	</select>

<?php }

/**
 * Prints the styling settings field.
 *
 * @since 0.1
 */
function sag_settings_callback_styling_field() { ?>

	<fieldset>
		<legend class="screen-reader-text">
			<span><?php esc_html_e( 'Styling', 'seo-age-gate' ); ?></span>
		</legend>
		<label for="_sag_styling">
			<input name="_sag_styling" type="checkbox" id="_sag_styling" value="1" <?php checked( 1, get_option( '_sag_styling', 1 ) ); ?>/>
			 <?php esc_html_e( 'Use built-in CSS on the front-end (recommended)', 'seo-age-gate' ); ?>
		</label>
	</fieldset>

<?php }

/**
 * Prints the overlay color settings field.
 *
 * @since 0.1
 */
function sag_settings_callback_overlay_color_field() { ?>

	<fieldset>

		<legend class="screen-reader-text">
			<span><?php esc_html_e( 'Overlay Color', 'seo-age-gate' ); ?></span>
		</legend>

		<?php $default_color = ' data-default-color="#fff"'; ?>

		<input type="text" name="_sag_overlay_color" id="_sag_overlay_color" value="#<?php echo esc_attr( sag_get_overlay_color() ); ?>"<?php echo $default_color ?> />

	</fieldset>

<?php }

/**
 * Prints the background color settings field.
 *
 * @since 0.1
 */
function sag_settings_callback_bgcolor_field() { ?>

	<fieldset>

		<legend class="screen-reader-text">
			<span><?php esc_html_e( 'Background Color' ); ?></span>
		</legend>

		<?php $default_color = '';

		if ( current_theme_supports( 'custom-background', 'default-color' ) )
			$default_color = ' data-default-color="#' . esc_attr( get_theme_support( 'custom-background', 'default-color' ) ) . '"'; ?>

		<input type="text" name="_sag_bgcolor" id="_sag_bgcolor" value="#<?php echo esc_attr( sag_get_background_color() ); ?>"<?php echo $default_color ?> />

	</fieldset>

<?php }
