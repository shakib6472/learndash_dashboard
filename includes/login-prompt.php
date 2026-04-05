<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Determine the correct Registration URL
$settings = get_option( 'learndash_premium_dashboard_settings', array() );
$register_type = $settings['register_link_type'] ?? 'default';
$registration_url = wp_registration_url(); // Default fallback

if ( $register_type === 'page' && !empty( $settings['register_redirect_page'] ) ) {
    $registration_url = get_permalink( intval( $settings['register_redirect_page'] ) );
} elseif ( $register_type === 'url' && !empty( $settings['register_redirect_url'] ) ) {
    $registration_url = esc_url_raw( $settings['register_redirect_url'] );
}

?>

<div class="ldp-login-prompt">
    <div class="ldp-login-card">
        <div class="ldp-login-header">
            <svg class="ldp-login-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            </svg>
            <h2 class="ldp-login-title">Access Restricted</h2>
            <p class="ldp-login-subtitle">Please sign in to access your dashboard.</p>
        </div>

        <div class="ldp-login-form-wrap">
            <?php
            wp_login_form( array(
                'redirect'       => get_permalink(), 
                'label_username' => 'Username or Email',
                'label_password' => 'Password',
                'label_remember' => 'Remember Me',
                'label_log_in'   => 'Sign In',
                'form_id'        => 'ldp-loginform',
                'remember'       => true
            ) );
            ?>
        </div>

        <?php if ( get_option( 'users_can_register' ) ) : ?>
            <p class="ldp-login-register">
                Don't have an account? <a href="<?php echo esc_url( $registration_url ); ?>">Create one</a>
            </p>
        <?php endif; ?>
    </div>
</div>