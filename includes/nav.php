<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$learndash_premium_dashboard_settings = get_option( 'learndash_premium_dashboard_settings', array() );
$learndash_premium_dashboard_show_courses = !empty( $learndash_premium_dashboard_settings['show_courses'] );
$learndash_premium_dashboard_show_certificates = !empty( $learndash_premium_dashboard_settings['show_certificates'] );
$learndash_premium_dashboard_show_profile = !empty( $learndash_premium_dashboard_settings['show_profile'] );

// Logo Settings Setup
$learndash_premium_dashboard_logo_url    = $learndash_premium_dashboard_settings['logo_url'] ?? '';
$learndash_premium_dashboard_logo_width  = $learndash_premium_dashboard_settings['logo_width'] ?? '150';
$learndash_premium_dashboard_logo_height = $learndash_premium_dashboard_settings['logo_height'] ?? 'auto';

// Ensure standard CSS units (adds 'px' if the admin just typed a plain number)
$learndash_premium_dashboard_width_css  = is_numeric( $learndash_premium_dashboard_logo_width ) ? $learndash_premium_dashboard_logo_width . 'px' : $learndash_premium_dashboard_logo_width;
$learndash_premium_dashboard_height_css = is_numeric( $learndash_premium_dashboard_logo_height ) ? $learndash_premium_dashboard_logo_height . 'px' : $learndash_premium_dashboard_logo_height;

// Bridge variables passed from the core class
$learndash_premium_dashboard_active_tab = isset( $active_tab ) ? $active_tab : 'dashboard';
$learndash_premium_dashboard_current_url = isset( $current_url ) ? $current_url : '';
?>

<aside class="ldp-sidebar" id="ldp-sidebar">
    <div class="ldp-logo">
        <?php if ( !empty( $learndash_premium_dashboard_logo_url ) ) : ?>
            <img src="<?php echo esc_url( $learndash_premium_dashboard_logo_url ); ?>" alt="<?php echo esc_attr( get_bloginfo('name') ); ?>" style="width: <?php echo esc_attr( $learndash_premium_dashboard_width_css ); ?>; height: <?php echo esc_attr( $learndash_premium_dashboard_height_css ); ?>; object-fit: contain;">
        <?php else : ?>
            <span class="ldp-logo-text"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
        <?php endif; ?>
        
        <button type="button" class="ldp-close-sidebar" id="ldp-close-btn" aria-label="<?php esc_attr_e( 'Close Menu', 'learndash-premium-dashboard' ); ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </div>
    
    <nav class="ldp-nav-menu">
        <a href="<?php echo esc_url( $learndash_premium_dashboard_current_url ); ?>" class="ldp-nav-item <?php echo ( $learndash_premium_dashboard_active_tab === 'dashboard' ) ? 'active' : ''; ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="7" height="7"></rect>
                <rect x="14" y="3" width="7" height="7"></rect>
                <rect x="14" y="14" width="7" height="7"></rect>
                <rect x="3" y="14" width="7" height="7"></rect>
            </svg>
            <?php echo esc_html__( 'Dashboard', 'learndash-premium-dashboard' ); ?>
        </a>

        <?php if ( $learndash_premium_dashboard_show_courses ) : ?>
            <a href="<?php echo esc_url( trailingslashit( $learndash_premium_dashboard_current_url ) . 'courses/' ); ?>" class="ldp-nav-item <?php echo ( $learndash_premium_dashboard_active_tab === 'courses' ) ? 'active' : ''; ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>
                <?php echo esc_html__( 'My Courses', 'learndash-premium-dashboard' ); ?>
            </a>
        <?php endif; ?>

        <?php if ( $learndash_premium_dashboard_show_certificates ) : ?>
            <a href="<?php echo esc_url( trailingslashit( $learndash_premium_dashboard_current_url ) . 'certificates/' ); ?>" class="ldp-nav-item <?php echo ( $learndash_premium_dashboard_active_tab === 'certificates' ) ? 'active' : ''; ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="8" r="7"></circle>
                    <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
                </svg>
                <?php echo esc_html__( 'Certificates', 'learndash-premium-dashboard' ); ?>
            </a>
        <?php endif; ?>

        <?php if ( $learndash_premium_dashboard_show_profile ) : ?>
            <a href="<?php echo esc_url( trailingslashit( $learndash_premium_dashboard_current_url ) . 'profile/' ); ?>" class="ldp-nav-item <?php echo ( $learndash_premium_dashboard_active_tab === 'profile' ) ? 'active' : ''; ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                <?php echo esc_html__( 'Profile', 'learndash-premium-dashboard' ); ?>
            </a>
        <?php endif; ?>
    </nav>
    <div class="ldp-sidebar-footer">
        <a href="<?php echo esc_url( wp_logout_url( get_permalink() ) ); ?>" class="ldp-nav-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <polyline points="16 17 21 12 16 7"></polyline>
                <line x1="21" y1="12" x2="9" y2="12"></line>
            </svg>
            <?php echo esc_html__( 'Logout', 'learndash-premium-dashboard' ); ?>
        </a>
    </div>
</aside>