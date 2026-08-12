<?php
/**
 * Admin settings screen.
 *
 * @package Kibworks_Student_Dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the settings page and the option group.
 */
class Kibworks_Student_Dashboard_Admin {

	const PAGE_SLUG      = 'kibworks-student-dashboard';
	const SETTINGS_GROUP = 'kbw_sd_settings_group';

	/**
	 * Hook everything up.
	 */
	public function init() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ), 20 );
		add_action( 'admin_init', array( $this, 'register_plugin_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
	}

	/**
	 * Add the settings page under the LearnDash menu.
	 */
	public function add_admin_menu() {
		add_submenu_page(
			'learndash-lms',
			__( 'Student Dashboard Settings', 'kibworks-student-dashboard-for-learndash' ),
			__( 'Student Dashboard', 'kibworks-student-dashboard-for-learndash' ),
			'manage_options',
			self::PAGE_SLUG,
			array( $this, 'render_settings_page' )
		);
	}

	/**
	 * Register the three option arrays.
	 */
	public function register_plugin_settings() {
		register_setting(
			self::SETTINGS_GROUP,
			'kbw_sd_colors',
			array( 'sanitize_callback' => array( $this, 'sanitize_colors' ) )
		);

		register_setting(
			self::SETTINGS_GROUP,
			'kbw_sd_fonts',
			array( 'sanitize_callback' => array( $this, 'sanitize_settings' ) )
		);

		register_setting(
			self::SETTINGS_GROUP,
			'kbw_sd_settings',
			array( 'sanitize_callback' => array( $this, 'sanitize_settings' ) )
		);
	}

	/**
	 * Colours get their own callback so an unusable value is rejected at save
	 * time rather than silently producing a broken CSS declaration later.
	 *
	 * @param mixed $input Raw submitted value.
	 * @return array
	 */
	public function sanitize_colors( $input ) {
		if ( ! is_array( $input ) ) {
			return array();
		}

		$clean = array();

		foreach ( $input as $key => $value ) {
			$key = sanitize_key( $key );

			if ( '' === $key ) {
				continue;
			}

			$color = kbw_sd_sanitize_css_color( is_scalar( $value ) ? wp_unslash( $value ) : '' );

			if ( '' !== $color ) {
				$clean[ $key ] = $color;
			}
		}

		return $clean;
	}

	/**
	 * Sanitize a flat settings array.
	 *
	 * @param mixed $input Raw submitted value.
	 * @return array
	 */
	public function sanitize_settings( $input ) {
		if ( ! is_array( $input ) ) {
			return array();
		}

		$clean = array();

		foreach ( $input as $key => $value ) {
			$key = sanitize_key( $key );

			if ( '' === $key ) {
				continue;
			}

			// Nested values are not part of this plugin's settings shape.
			if ( ! is_scalar( $value ) ) {
				continue;
			}

			$value = wp_unslash( $value );

			if ( false !== strpos( $key, 'url' ) ) {
				$clean[ $key ] = esc_url_raw( $value );
			} else {
				$clean[ $key ] = sanitize_text_field( $value );
			}
		}

		return $clean;
	}

	/**
	 * Load the admin assets on our screen only.
	 *
	 * @param string $hook_suffix Current admin page.
	 */
	public function enqueue_admin_assets( $hook_suffix ) {
		if ( 'learndash-lms_page_' . self::PAGE_SLUG !== $hook_suffix ) {
			return;
		}

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_media();

		wp_enqueue_style( 'kbw-sd-admin-style', KBW_SD_PLUGIN_URL . 'assets/admin-style.css', array(), KBW_SD_VERSION );
		wp_enqueue_script( 'kbw-sd-admin-script', KBW_SD_PLUGIN_URL . 'assets/admin-scripts.js', array( 'jquery', 'wp-color-picker' ), KBW_SD_VERSION, true );
	}

	/**
	 * Render the settings screen.
	 */
	public function render_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		include KBW_SD_PLUGIN_DIR . 'includes/admin-settings-ui.php';
	}
}
