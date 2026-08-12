<?php
/**
 * Plugin Name:       Kibworks Student Dashboard for LearnDash
 * Plugin URI:        https://github.com/shakib6472/kibworks-student-dashboard
 * Description:       A front-end student dashboard for LearnDash, with course progress, certificates and profile management on one page.
 * Version:           1.1.0
 * Requires at least: 6.0
 * Requires PHP:      7.2
 * Author:            Shakib Shown
 * Author URI:        https://github.com/shakib6472/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       kibworks-student-dashboard-for-learndash
 * Domain Path:       /languages
 *
 * @package Kibworks_Student_Dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'KBW_SD_VERSION', '1.1.0' );
define( 'KBW_SD_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'KBW_SD_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once KBW_SD_PLUGIN_DIR . 'includes/helpers.php';
require_once KBW_SD_PLUGIN_DIR . 'includes/classes/class-activator.php';
require_once KBW_SD_PLUGIN_DIR . 'includes/classes/class-dashboard-core.php';

register_activation_hook( __FILE__, array( 'Kibworks_Student_Dashboard_Activator', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Kibworks_Student_Dashboard_Activator', 'deactivate' ) );

/**
 * Boot the plugin.
 */
function kbw_sd_run() {
	$plugin = new Kibworks_Student_Dashboard_Core();
	$plugin->init();
}

kbw_sd_run();

add_action( 'after_setup_theme', 'kbw_sd_maybe_hide_admin_bar' );

/**
 * Optionally hide the admin bar for users who cannot manage the site.
 *
 * Off by default. This affects the whole front end, not just the dashboard page,
 * so it is left to the site owner to switch on rather than being forced.
 */
function kbw_sd_maybe_hide_admin_bar() {
	$settings = get_option( 'kbw_sd_settings', array() );

	if ( empty( $settings['hide_admin_bar'] ) ) {
		return;
	}

	if ( ! current_user_can( 'manage_options' ) && ! is_admin() ) {
		show_admin_bar( false );
	}
}
