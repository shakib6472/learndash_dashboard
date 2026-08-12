<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$kbw_sd_current_user = wp_get_current_user();
$kbw_sd_first_name = $kbw_sd_current_user->user_firstname ? substr( $kbw_sd_current_user->user_firstname, 0, 1 ) : '';
$kbw_sd_last_name = $kbw_sd_current_user->user_lastname ? substr( $kbw_sd_current_user->user_lastname, 0, 1 ) : '';
$kbw_sd_initials = strtoupper( $kbw_sd_first_name . $kbw_sd_last_name );

if ( empty( $kbw_sd_initials ) ) {
    $kbw_sd_initials = strtoupper( substr( $kbw_sd_current_user->user_login, 0, 1 ) );
}

// Format the title nicely and make it translation-ready
// Note: $active_tab comes from the core class include
$kbw_sd_active_tab = isset( $active_tab ) ? $active_tab : 'dashboard';

if ( $kbw_sd_active_tab === 'courses' ) {
    $kbw_sd_page_title = __( 'My Courses', 'kibworks-student-dashboard-for-learndash' );
} elseif ( $kbw_sd_active_tab === 'certificates' ) {
    $kbw_sd_page_title = __( 'My Certificates', 'kibworks-student-dashboard-for-learndash' );
} elseif ( $kbw_sd_active_tab === 'profile' ) {
    $kbw_sd_page_title = __( 'Profile Settings', 'kibworks-student-dashboard-for-learndash' );
} else {
    $kbw_sd_page_title = __( 'Dashboard', 'kibworks-student-dashboard-for-learndash' );
}
?>

<header class="kbw-sd-header-bar">
    <div class="kbw-sd-header-title">
        <button type="button" class="kbw-sd-menu-toggle" id="kbw-sd-menu-btn" aria-label="<?php esc_attr_e( 'Toggle Menu', 'kibworks-student-dashboard-for-learndash' ); ?>">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>
        <h1><?php echo esc_html( $kbw_sd_page_title ); ?></h1>
    </div>
    
    <a href="<?php echo esc_url( trailingslashit( get_permalink() ) . 'profile/' ); ?>" class="kbw-sd-user-avatar" title="<?php esc_attr_e( 'Go to Profile', 'kibworks-student-dashboard-for-learndash' ); ?>">
        <?php echo esc_html( $kbw_sd_initials ); ?>
    </a>
</header>