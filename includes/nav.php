<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$kbw_sd_settings = get_option( 'kbw_sd_settings', array() );
$kbw_sd_show_courses = !empty( $kbw_sd_settings['show_courses'] );
$kbw_sd_show_certificates = !empty( $kbw_sd_settings['show_certificates'] );
$kbw_sd_show_profile = !empty( $kbw_sd_settings['show_profile'] );

// Logo Settings Setup
$kbw_sd_logo_url    = $kbw_sd_settings['logo_url'] ?? '';
$kbw_sd_logo_width  = $kbw_sd_settings['logo_width'] ?? '150';
$kbw_sd_logo_height = $kbw_sd_settings['logo_height'] ?? 'auto';

// Ensure standard CSS units (adds 'px' if the admin just typed a plain number)
$kbw_sd_width_css  = is_numeric( $kbw_sd_logo_width ) ? $kbw_sd_logo_width . 'px' : $kbw_sd_logo_width;
$kbw_sd_height_css = is_numeric( $kbw_sd_logo_height ) ? $kbw_sd_logo_height . 'px' : $kbw_sd_logo_height;

// Bridge variables passed from the core class
$kbw_sd_active_tab = isset( $active_tab ) ? $active_tab : 'dashboard';
$kbw_sd_current_url = isset( $current_url ) ? $current_url : '';
?>

<aside class="kbw-sd-sidebar" id="kbw-sd-sidebar">
    <div class="kbw-sd-logo">
        <?php if ( !empty( $kbw_sd_logo_url ) ) : ?>
            <img src="<?php echo esc_url( $kbw_sd_logo_url ); ?>" alt="<?php echo esc_attr( get_bloginfo('name') ); ?>" style="width: <?php echo esc_attr( $kbw_sd_width_css ); ?>; height: <?php echo esc_attr( $kbw_sd_height_css ); ?>; object-fit: contain;">
        <?php else : ?>
            <span class="kbw-sd-logo-text"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
        <?php endif; ?>
        
        <button type="button" class="kbw-sd-close-sidebar" id="kbw-sd-close-btn" aria-label="<?php esc_attr_e( 'Close Menu', 'kibworks-student-dashboard-for-learndash' ); ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </div>
    
    <nav class="kbw-sd-nav-menu">
        <a href="<?php echo esc_url( $kbw_sd_current_url ); ?>" class="kbw-sd-nav-item <?php echo esc_attr( ( $kbw_sd_active_tab === 'dashboard' ) ? 'active' : '' ); ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="7" height="7"></rect>
                <rect x="14" y="3" width="7" height="7"></rect>
                <rect x="14" y="14" width="7" height="7"></rect>
                <rect x="3" y="14" width="7" height="7"></rect>
            </svg>
            <?php echo esc_html__( 'Dashboard', 'kibworks-student-dashboard-for-learndash' ); ?>
        </a>

        <?php if ( $kbw_sd_show_courses ) : ?>
            <a href="<?php echo esc_url( trailingslashit( $kbw_sd_current_url ) . 'courses/' ); ?>" class="kbw-sd-nav-item <?php echo esc_attr( ( $kbw_sd_active_tab === 'courses' ) ? 'active' : '' ); ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>
                <?php echo esc_html__( 'My Courses', 'kibworks-student-dashboard-for-learndash' ); ?>
            </a>
        <?php endif; ?>

        <?php if ( $kbw_sd_show_certificates ) : ?>
            <a href="<?php echo esc_url( trailingslashit( $kbw_sd_current_url ) . 'certificates/' ); ?>" class="kbw-sd-nav-item <?php echo esc_attr( ( $kbw_sd_active_tab === 'certificates' ) ? 'active' : '' ); ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="8" r="7"></circle>
                    <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
                </svg>
                <?php echo esc_html__( 'Certificates', 'kibworks-student-dashboard-for-learndash' ); ?>
            </a>
        <?php endif; ?>

        <?php if ( $kbw_sd_show_profile ) : ?>
            <a href="<?php echo esc_url( trailingslashit( $kbw_sd_current_url ) . 'profile/' ); ?>" class="kbw-sd-nav-item <?php echo esc_attr( ( $kbw_sd_active_tab === 'profile' ) ? 'active' : '' ); ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                <?php echo esc_html__( 'Profile', 'kibworks-student-dashboard-for-learndash' ); ?>
            </a>
        <?php endif; ?>
    </nav>
    <div class="kbw-sd-sidebar-footer">
        <a href="<?php echo esc_url( wp_logout_url( get_permalink() ) ); ?>" class="kbw-sd-nav-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <polyline points="16 17 21 12 16 7"></polyline>
                <line x1="21" y1="12" x2="9" y2="12"></line>
            </svg>
            <?php echo esc_html__( 'Logout', 'kibworks-student-dashboard-for-learndash' ); ?>
        </a>
    </div>
</aside>