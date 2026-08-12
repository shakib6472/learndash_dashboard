<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Determine the correct Registration URL
$kbw_sd_settings = get_option( 'kbw_sd_settings', array() );
$kbw_sd_register_type = $kbw_sd_settings['register_link_type'] ?? 'default';
$kbw_sd_registration_url = wp_registration_url(); // Default fallback

if ( $kbw_sd_register_type === 'page' && !empty( $kbw_sd_settings['register_redirect_page'] ) ) {
    $kbw_sd_registration_url = get_permalink( intval( $kbw_sd_settings['register_redirect_page'] ) );
} elseif ( $kbw_sd_register_type === 'url' && !empty( $kbw_sd_settings['register_redirect_url'] ) ) {
    $kbw_sd_registration_url = esc_url_raw( $kbw_sd_settings['register_redirect_url'] );
}

?>

<div class="kbw-sd-login-prompt">
    <div class="kbw-sd-login-card">
        <div class="kbw-sd-login-header">
            <svg class="kbw-sd-login-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            </svg>
            <h2 class="kbw-sd-login-title"><?php echo esc_html__( 'Access Restricted', 'kibworks-student-dashboard-for-learndash' ); ?></h2>
            <p class="kbw-sd-login-subtitle"><?php echo esc_html__( 'Please sign in to access your dashboard.', 'kibworks-student-dashboard-for-learndash' ); ?></p>
        </div>

        <div class="kbw-sd-login-form-wrap">
            <?php
            wp_login_form( array(
                'redirect'       => get_permalink(), 
                'label_username' => esc_html__( 'Username or Email', 'kibworks-student-dashboard-for-learndash' ),
                'label_password' => esc_html__( 'Password', 'kibworks-student-dashboard-for-learndash' ),
                'label_remember' => esc_html__( 'Remember Me', 'kibworks-student-dashboard-for-learndash' ),
                'label_log_in'   => esc_html__( 'Sign In', 'kibworks-student-dashboard-for-learndash' ),
                'form_id'        => 'kbw-sd-loginform',
                'remember'       => true
            ) );
            ?>
        </div>

        <?php if ( get_option( 'users_can_register' ) ) : ?>
            <p class="kbw-sd-login-register">
                <?php echo esc_html__( 'Don\'t have an account?', 'kibworks-student-dashboard-for-learndash' ); ?> <a href="<?php echo esc_url( $kbw_sd_registration_url ); ?>"><?php echo esc_html__( 'Create one', 'kibworks-student-dashboard-for-learndash' ); ?></a>
            </p>
        <?php endif; ?>
    </div>
</div>