<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$settings = get_option( 'learndash_premium_dashboard_settings', array() );
$show_courses = !empty( $settings['show_courses'] );
$show_certificates = !empty( $settings['show_certificates'] );
$show_profile = !empty( $settings['show_profile'] );

// Logo Settings Setup
$logo_url    = $settings['logo_url'] ?? '';
$logo_width  = $settings['logo_width'] ?? '150';
$logo_height = $settings['logo_height'] ?? 'auto';

// Ensure standard CSS units (adds 'px' if the admin just typed a plain number)
$width_css  = is_numeric( $logo_width ) ? $logo_width . 'px' : $logo_width;
$height_css = is_numeric( $logo_height ) ? $logo_height . 'px' : $logo_height;
?>

<aside class="ldp-sidebar" id="ldp-sidebar">
    <div class="ldp-logo">
        <?php if ( !empty( $logo_url ) ) : ?>
            <img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( get_bloginfo('name') ); ?>" style="width: <?php echo esc_attr( $width_css ); ?>; height: <?php echo esc_attr( $height_css ); ?>; object-fit: contain;">
        <?php else : ?>
            <span class="ldp-logo-text"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
        <?php endif; ?>
        
        <button type="button" class="ldp-close-sidebar" id="ldp-close-btn" aria-label="Close Menu">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </div>
    
    <nav class="ldp-nav-menu">
        <a href="<?php echo esc_url( $current_url ); ?>" class="ldp-nav-item <?php echo ( $active_tab === 'dashboard' ) ? 'active' : ''; ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="7" height="7"></rect>
                <rect x="14" y="3" width="7" height="7"></rect>
                <rect x="14" y="14" width="7" height="7"></rect>
                <rect x="3" y="14" width="7" height="7"></rect>
            </svg>
            Dashboard
        </a>

        <?php if ( $show_courses ) : ?>
            <a href="<?php echo esc_url( trailingslashit( $current_url ) . 'courses/' ); ?>" class="ldp-nav-item <?php echo ( $active_tab === 'courses' ) ? 'active' : ''; ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>
                My Courses
            </a>
        <?php endif; ?>

        <?php if ( $show_certificates ) : ?>
            <a href="<?php echo esc_url( trailingslashit( $current_url ) . 'certificates/' ); ?>" class="ldp-nav-item <?php echo ( $active_tab === 'certificates' ) ? 'active' : ''; ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="8" r="7"></circle>
                    <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
                </svg>
                Certificates
            </a>
        <?php endif; ?>

        <?php if ( $show_profile ) : ?>
            <a href="<?php echo esc_url( trailingslashit( $current_url ) . 'profile/' ); ?>" class="ldp-nav-item <?php echo ( $active_tab === 'profile' ) ? 'active' : ''; ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                Profile
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
            Logout
        </a>
    </div>
</aside>