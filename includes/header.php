<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$learndash_premium_dashboard_current_user = wp_get_current_user();
$learndash_premium_dashboard_first_name = $learndash_premium_dashboard_current_user->user_firstname ? substr( $learndash_premium_dashboard_current_user->user_firstname, 0, 1 ) : '';
$learndash_premium_dashboard_last_name = $learndash_premium_dashboard_current_user->user_lastname ? substr( $learndash_premium_dashboard_current_user->user_lastname, 0, 1 ) : '';
$learndash_premium_dashboard_initials = strtoupper( $learndash_premium_dashboard_first_name . $learndash_premium_dashboard_last_name );

if ( empty( $learndash_premium_dashboard_initials ) ) {
    $learndash_premium_dashboard_initials = strtoupper( substr( $learndash_premium_dashboard_current_user->user_login, 0, 1 ) );
}

// Format the title nicely and make it translation-ready
// Note: $active_tab comes from the core class include
$learndash_premium_dashboard_active_tab = isset( $active_tab ) ? $active_tab : 'dashboard';

if ( $learndash_premium_dashboard_active_tab === 'courses' ) {
    $learndash_premium_dashboard_page_title = __( 'My Courses', 'learndash-premium-dashboard' );
} elseif ( $learndash_premium_dashboard_active_tab === 'certificates' ) {
    $learndash_premium_dashboard_page_title = __( 'My Certificates', 'learndash-premium-dashboard' );
} elseif ( $learndash_premium_dashboard_active_tab === 'profile' ) {
    $learndash_premium_dashboard_page_title = __( 'Profile Settings', 'learndash-premium-dashboard' );
} else {
    $learndash_premium_dashboard_page_title = __( 'Dashboard', 'learndash-premium-dashboard' );
}
?>

<header class="ldp-header-bar">
    <div class="ldp-header-title">
        <button type="button" class="ldp-menu-toggle" id="ldp-menu-btn" aria-label="<?php esc_attr_e( 'Toggle Menu', 'learndash-premium-dashboard' ); ?>">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>
        <h1><?php echo esc_html( $learndash_premium_dashboard_page_title ); ?></h1>
    </div>
    
    <a href="<?php echo esc_url( trailingslashit( get_permalink() ) . 'profile/' ); ?>" class="ldp-user-avatar" title="<?php esc_attr_e( 'Go to Profile', 'learndash-premium-dashboard' ); ?>">
        <?php echo esc_html( $learndash_premium_dashboard_initials ); ?>
    </a>
</header>