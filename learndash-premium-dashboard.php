<?php
/** * Plugin Name:       LearnDash Premium Dashboard    
 * Plugin URI:        https://github.com/shakib6472/learndash_dashboard
 * Description:       A premium dashboard plugin for LearnDash LMS, designed to enhance the user experience and provide advanced features for course management and Certificate management.  
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Shakib Shown
 * Author URI:        https://github.com/shakib6472/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       learndash-premium-dashboard
 * Domain Path:       /languages
 */

if (!defined('ABSPATH')) {
    exit;
}

define('LDP_SKB_VERSION', '1.0.0');
define('LDP_SKB_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('LDP_SKB_PLUGIN_URL', plugin_dir_url(__FILE__));

// Require core classes
require_once LDP_SKB_PLUGIN_DIR . 'includes/helpers.php';
require_once LDP_SKB_PLUGIN_DIR . 'includes/classes/class-activator.php';
require_once LDP_SKB_PLUGIN_DIR . 'includes/classes/class-dashboard-core.php';

// Register activation hook
register_activation_hook(__FILE__, array('LD_Premium_Dashboard_Activator', 'activate'));

// Initialize the plugin
function learndash_premium_dashboard_run()
{
    $plugin = new LD_Premium_Dashboard_Core();
    $plugin->init();
}

learndash_premium_dashboard_run();

// Hide admin bar for non-administrators
add_action('after_setup_theme', 'learndash_premium_dashboard_hide_admin_bar_for_students');
function learndash_premium_dashboard_hide_admin_bar_for_students() {
    if (!current_user_can('manage_options') && !is_admin()) {
        show_admin_bar(false);
    }
}