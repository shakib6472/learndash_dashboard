<?php
if (!defined('ABSPATH')) {
    exit;
}

class LD_Premium_Dashboard_Activator
{

    public static function activate()
    {
        self::set_default_colors();
        self::set_default_fonts();
        self::set_default_settings();

        flush_rewrite_rules();
    }

    private static function set_default_colors()
    {
        $default_colors = array(
            'primary' => '#4f46e5',
            'primary_hover' => '#4338ca',
            'bg_main' => '#f3f4f6',
            'bg_card' => '#ffffff',
            'text_main' => '#111827',
            'heading_text' => '#111827',
            'text_dim' => '#6b7280',
            'border_color' => '#e5e7eb',
            'input_bg' => '#ffffff',
            'sidebar_background' => '#ffffff',
            'sidebar_item_background' => 'transparent',
            'sidebar_item_text' => '#4b5563',
            'sidebar_item_hover_background' => '#f3f4f6',
            'sidebar_item_hover_text' => '#4f46e5',
            'progress_bg' => '#e5e7eb',
            'progress_text' => '#6b7280',
            'cert_bg' => '#ffffff',
            'cert_border' => '#e5e7eb',
            'overlay_bg' => 'rgba(0, 0, 0, 0.5)'
        );

        if (!get_option('learndash_premium_dashboard_colors')) {
            add_option('learndash_premium_dashboard_colors', $default_colors);
        }
    }

    private static function set_default_fonts()
    {
        $default_fonts = array(
            'font_primary' => 'Inter',
            'font_secondary' => 'Outfit',
        );

        if (!get_option('learndash_premium_dashboard_fonts')) {
            add_option('learndash_premium_dashboard_fonts', $default_fonts);
        }
    }

   private static function set_default_settings() {
        $default_settings = array(
            // Branding Settings
            'logo_url'               => '',
            'logo_width'             => '150',
            'logo_height'            => 'auto',
            
            // Tab Visibility
            'show_courses'           => '1',
            'show_certificates'      => '1',
            'show_profile'           => '1',
            
            // Logout Settings
            'logout_redirection'     => '0',
            'logout_redirect_type'   => 'page',
            'logout_redirect_url'    => '',
            'logout_redirect_page'   => '',
            
            // Unauthorized Access Settings
            'unauth_action'          => 'form', // 'form', 'page', or 'url'
            'unauth_redirect_page'   => '',
            'unauth_redirect_url'    => '',
            
            // Registration Settings
            'register_link_type'     => 'default', // 'default', 'page', or 'url'
            'register_redirect_page' => '',
            'register_redirect_url'  => ''
        );

        if ( ! get_option( 'learndash_premium_dashboard_settings' ) ) {
            add_option( 'learndash_premium_dashboard_settings', $default_settings );
        }
    }
    
}