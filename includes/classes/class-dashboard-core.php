<?php
if (!defined('ABSPATH')) {
    exit;
}

class LD_Premium_Dashboard_Core
{

    public function init()
    {
        // Load admin class if in the admin area
        if (is_admin()) {
            require_once LDP_SKB_PLUGIN_DIR . 'includes/classes/class-admin.php';
            $admin = new LD_Premium_Dashboard_Admin();
            $admin->init();
        }

        // Frontend Hooks
        add_action('init', array($this, 'add_endpoints'));
        add_filter('query_vars', array($this, 'add_query_vars'));
        add_shortcode('learndash_premium_dashboard', array($this, 'render_dashboard'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        add_action('wp_logout', array($this, 'handle_logout_redirect'));

        // NEW: Hook to handle unauthenticated redirects safely
        add_action('template_redirect', array($this, 'handle_unauth_redirect'));
    }

    public function add_endpoints()
    {
        add_rewrite_endpoint('courses', EP_PAGES);
        add_rewrite_endpoint('certificates', EP_PAGES);
        add_rewrite_endpoint('profile', EP_PAGES);
    }

    public function add_query_vars($vars)
    {
        $vars[] = 'courses';
        $vars[] = 'certificates';
        $vars[] = 'profile';
        return $vars;
    }

    public function enqueue_assets()
    {
        // Only enqueue if the shortcode is on the page (optimization)
        global $post;
        if (is_a($post, 'WP_Post') && !has_shortcode($post->post_content, 'learndash_premium_dashboard')) {
            return;
        }

        $fonts = get_option('learndash_premium_dashboard_fonts', array());
        $font_primary = !empty($fonts['font_primary']) ? $fonts['font_primary'] : 'Inter';
        $font_secondary = !empty($fonts['font_secondary']) ? $fonts['font_secondary'] : 'Outfit';

        $font_url = "https://fonts.googleapis.com/css2?family=" . urlencode($font_primary) . ":wght@400;500;600;700&family=" . urlencode($font_secondary) . ":wght@400;500;600;700&display=swap";

        wp_enqueue_style('ldp-google-fonts', $font_url, array(), null);
        
        wp_enqueue_style('ldp-style', LDP_SKB_PLUGIN_URL . 'assets/style.css', array(), LDP_SKB_VERSION);
        wp_enqueue_script('ldp-script', LDP_SKB_PLUGIN_URL . 'assets/scripts.js', array(), LDP_SKB_VERSION, true);

        // Inject Dynamic CSS Variables
        $colors = get_option('learndash_premium_dashboard_colors', array());
        $custom_css = ":root {\n";
        foreach ($colors as $key => $val) {
            $custom_css .= "--ldp_skb-" . str_replace('_', '-', $key) . ": " . sanitize_hex_color($val) . ";\n"; 
        }
        $custom_css .= "--ldp_skb-font-primary: '{$font_primary}', sans-serif;\n";
        $custom_css .= "--ldp_skb-font-secondary: '{$font_secondary}', sans-serif;\n";
        $custom_css .= "}";

        wp_add_inline_style('ldp-style', $custom_css);
    }

    public function render_dashboard()
    {
        // Dependency Check: Ensure LearnDash is active
        include_once(ABSPATH . 'wp-admin/includes/plugin.php');
        if (!is_plugin_active('sfwd-lms/sfwd_lms.php')) {
            return '<div style="padding: 20px; background: #fee2e2; color: #991b1b; border-radius: 8px; border: 1px solid #f87171;"><strong>Error:</strong> LearnDash LMS must be installed and activated to use the Premium Dashboard.</div>';
        }

        if (!is_user_logged_in()) {
            ob_start();
            include LDP_SKB_PLUGIN_DIR . 'includes/login-prompt.php';
            $output = ob_get_clean();

            // Clean whitespace to prevent wpautop issues on the login prompt
            $output = preg_replace('/[\r\n\t]+/', ' ', $output);
            return preg_replace('/>\s+</', '><', $output);
        }

        global $wp_query;
        $active_tab = 'dashboard';
        if (isset($wp_query->query_vars['courses']))
            $active_tab = 'courses';
        elseif (isset($wp_query->query_vars['certificates']))
            $active_tab = 'certificates';
        elseif (isset($wp_query->query_vars['profile']))
            $active_tab = 'profile';

        $current_url = get_permalink();

        ob_start();
        echo '<div class="ldp-dashboard-wrapper">';
        echo '<div class="ldp-sidebar-overlay" id="overlay"></div>';

        include LDP_SKB_PLUGIN_DIR . 'includes/nav.php';

        echo '<main class="ldp-main-content">';
        include LDP_SKB_PLUGIN_DIR . 'includes/header.php';

        $file_path = LDP_SKB_PLUGIN_DIR . "includes/{$active_tab}.php";
        if (file_exists($file_path)) {
            include $file_path;
        }

        echo '</main></div>';

        $output = ob_get_clean();

        // THE FIX: Completely neutralize wpautop without affecting global site filters
        // 1. Convert all line breaks, carriage returns, and tabs into a single space
        $output = preg_replace('/[\r\n\t]+/', ' ', $output);

        // 2. Remove any remaining spaces that sit strictly between HTML tags
        $output = preg_replace('/>\s+</', '><', $output);

        return $output;
    }

    public function handle_logout_redirect()
    {
        $settings = get_option('learndash_premium_dashboard_settings', array());

        if (!empty($settings['logout_redirection'])) {
            $redirect_url = home_url();

            if ($settings['logout_redirect_type'] === 'url' && !empty($settings['logout_redirect_url'])) {
                $redirect_url = esc_url_raw($settings['logout_redirect_url']);
            } elseif ($settings['logout_redirect_type'] === 'page' && !empty($settings['logout_redirect_page'])) {
                $redirect_url = get_permalink(intval($settings['logout_redirect_page']));
            }

            wp_redirect($redirect_url);
            exit;
        }
    }

    // NEW METHOD: Redirects user before headers are sent if configured
    public function handle_unauth_redirect()
    {
        if (is_user_logged_in())
            return;

        global $post;
        if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'learndash_premium_dashboard')) {
            $settings = get_option('learndash_premium_dashboard_settings', array());
            $unauth_action = $settings['unauth_action'] ?? 'form';

            if ($unauth_action === 'url' && !empty($settings['unauth_redirect_url'])) {
                wp_redirect(esc_url_raw($settings['unauth_redirect_url']));
                exit;
            } elseif ($unauth_action === 'page' && !empty($settings['unauth_redirect_page'])) {
                wp_redirect(get_permalink(intval($settings['unauth_redirect_page'])));
                exit;
            }
        }
    }

}