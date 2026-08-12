<?php
/**
 * Activation routine.
 *
 * @package Kibworks_Student_Dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Seeds the default options and registers the rewrite rules.
 */
class Kibworks_Student_Dashboard_Activator {

	/**
	 * Runs on plugin activation.
	 */
	public static function activate() {
		self::set_default_colors();
		self::set_default_fonts();
		self::set_default_settings();

		/**
		 * The endpoints are normally registered on `init`, which has already fired by
		 * the time an activation hook runs. Without registering them here first, the
		 * flush below would rebuild the rules without them and every endpoint URL
		 * would 404 until permalinks were saved by hand.
		 */
		Kibworks_Student_Dashboard_Core::add_endpoints();

		flush_rewrite_rules();
	}

	/**
	 * Remove the rewrite rules we added.
	 */
	public static function deactivate() {
		flush_rewrite_rules();
	}

	/**
	 * Default colour palette.
	 */
	private static function set_default_colors() {
		$defaults = array(
			'primary'                       => '#4f46e5',
			'primary_hover'                 => '#4338ca',
			'bg_main'                       => '#f3f4f6',
			'bg_card'                       => '#ffffff',
			'text_main'                     => '#111827',
			'heading_text'                  => '#111827',
			'text_dim'                      => '#6b7280',
			'border_color'                  => '#e5e7eb',
			'input_bg'                      => '#ffffff',
			'sidebar_background'            => '#ffffff',
			'sidebar_item_background'       => 'transparent',
			'sidebar_item_text'             => '#4b5563',
			'sidebar_item_hover_background' => '#f3f4f6',
			'sidebar_item_hover_text'       => '#4f46e5',
			'progress_bg'                   => '#e5e7eb',
			'progress_text'                 => '#6b7280',
			'cert_bg'                       => '#ffffff',
			'cert_border'                   => '#e5e7eb',
			'overlay_bg'                    => 'rgba(0, 0, 0, 0.5)',
		);

		if ( ! get_option( 'kbw_sd_colors' ) ) {
			add_option( 'kbw_sd_colors', $defaults );
		}
	}

	/**
	 * Default typography.
	 */
	private static function set_default_fonts() {
		$defaults = array(
			'font_primary'   => 'Inter',
			'font_secondary' => 'Outfit',
		);

		if ( ! get_option( 'kbw_sd_fonts' ) ) {
			add_option( 'kbw_sd_fonts', $defaults );
		}
	}

	/**
	 * Default behaviour settings.
	 */
	private static function set_default_settings() {
		$defaults = array(
			// Branding.
			'logo_url'               => '',
			'logo_width'             => '150',
			'logo_height'            => 'auto',

			// Tab visibility.
			'show_courses'           => '1',
			'show_certificates'      => '1',
			'show_profile'           => '1',

			// Off by default: hiding the admin bar changes the whole site, so it is
			// the site owner's call rather than something the plugin assumes.
			'hide_admin_bar'         => '0',

			// Logout.
			'logout_redirection'     => '0',
			'logout_redirect_type'   => 'page',
			'logout_redirect_url'    => '',
			'logout_redirect_page'   => '',

			// Logged-out visitors.
			'unauth_action'          => 'form',
			'unauth_redirect_page'   => '',
			'unauth_redirect_url'    => '',

			// Registration link.
			'register_link_type'     => 'default',
			'register_redirect_page' => '',
			'register_redirect_url'  => '',
		);

		if ( ! get_option( 'kbw_sd_settings' ) ) {
			add_option( 'kbw_sd_settings', $defaults );
		}
	}
}
