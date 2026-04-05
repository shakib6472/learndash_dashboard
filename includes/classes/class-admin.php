<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class LD_Premium_Dashboard_Admin {

    public function init() {
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ), 20 );
        add_action( 'admin_init', array( $this, 'register_plugin_settings' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
    }

    public function add_admin_menu() {
        add_submenu_page(
            'learndash-lms',
            'Dashboard Settings',
            'Dashboard Modify',
            'manage_options',
            'ld-dashboard-settings',
            array( $this, 'render_settings_page' )
        );
    }

    public function register_plugin_settings() {
        // Register clear, distinct settings arrays
        register_setting( 'ldp_settings_group', 'learndash_premium_dashboard_colors' );
        register_setting( 'ldp_settings_group', 'learndash_premium_dashboard_fonts' );
        register_setting( 'ldp_settings_group', 'learndash_premium_dashboard_settings' );
    }

    public function enqueue_admin_assets( $hook_suffix ) {
        if ( $hook_suffix !== 'learndash-lms_page_ld-dashboard-settings' ) {
            return;
        }

        // Enqueue native WP color picker
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_media(); // NEW: Loads the WP Media Library modal
        // Enqueue our custom admin assets
        wp_enqueue_style( 'ldp-admin-style', LDP_SKB_PLUGIN_URL . 'assets/admin-style.css', array(), LDP_SKB_VERSION );
        wp_enqueue_script( 'ldp-admin-script', LDP_SKB_PLUGIN_URL . 'assets/admin-scripts.js', array( 'jquery', 'wp-color-picker' ), LDP_SKB_VERSION, true );
    }

    public function render_settings_page() {
        include LDP_SKB_PLUGIN_DIR . 'includes/admin-settings-ui.php';
    }
}