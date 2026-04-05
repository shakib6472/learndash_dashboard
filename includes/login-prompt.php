<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Determine the correct Registration URL
$learndash_premium_dashboard_settings = get_option( 'learndash_premium_dashboard_settings', array() );
$learndash_premium_dashboard_register_type = $learndash_premium_dashboard_settings['register_link_type'] ?? 'default';
$learndash_premium_dashboard_registration_url = wp_registration_url(); // Default fallback

if ( $learndash_premium_dashboard_register_type === 'page' && !empty( $learndash_premium_dashboard_settings['register_redirect_page'] ) ) {
    $learndash_premium_dashboard_registration_url = get_permalink( intval( $learndash_premium_dashboard_settings['register_redirect_page'] ) );
} elseif ( $learndash_premium_dashboard_register_type === 'url' && !empty( $learndash_premium_dashboard_settings['register_redirect_url'] ) ) {
    $learndash_premium_dashboard_registration_url = esc_url_raw( $learndash_premium_dashboard_settings['register_redirect_url'] );
}

?>

<div class="ldp-login-prompt">
    <div class="ldp-login-card">
        <div class="ldp-login-header">
            <svg class="ldp-login-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            </svg>
            <h2 class="ldp-login-title"><?php echo esc_html__( 'Access Restricted', 'learndash-premium-dashboard' ); ?></h2>
            <p class="ldp-login-subtitle"><?php echo esc_html__( 'Please sign in to access your dashboard.', 'learndash-premium-dashboard' ); ?></p>
        </div>

        <div class="ldp-login-form-wrap">
            <?php
            wp_login_form( array(
                'redirect'       => get_permalink(), 
                'label_username' => esc_html__( 'Username or Email', 'learndash-premium-dashboard' ),
                'label_password' => esc_html__( 'Password', 'learndash-premium-dashboard' ),
                'label_remember' => esc_html__( 'Remember Me', 'learndash-premium-dashboard' ),
                'label_log_in'   => esc_html__( 'Sign In', 'learndash-premium-dashboard' ),
                'form_id'        => 'ldp-loginform',
                'remember'       => true
            ) );
            ?>
        </div>

        <?php if ( get_option( 'users_can_register' ) ) : ?>
            <p class="ldp-login-register">
                <?php echo esc_html__( 'Don\'t have an account?', 'learndash-premium-dashboard' ); ?> <a href="<?php echo esc_url( $learndash_premium_dashboard_registration_url ); ?>"><?php echo esc_html__( 'Create one', 'learndash-premium-dashboard' ); ?></a>
            </p>
        <?php endif; ?>
    </div>
</div>