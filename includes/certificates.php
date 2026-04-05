<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$learndash_premium_dashboard_user_id = get_current_user_id();
$learndash_premium_dashboard_enrolled_courses = learndash_user_get_enrolled_courses( $learndash_premium_dashboard_user_id );
$learndash_premium_dashboard_cert_courses = array();

// Filter only courses that actually have a certificate assigned to them
if ( ! empty( $learndash_premium_dashboard_enrolled_courses ) ) {
    foreach ( $learndash_premium_dashboard_enrolled_courses as $learndash_premium_dashboard_course_id ) {
        $learndash_premium_dashboard_cert_id = learndash_get_setting( $learndash_premium_dashboard_course_id, 'certificate' );
        if ( ! empty( $learndash_premium_dashboard_cert_id ) ) {
            $learndash_premium_dashboard_cert_courses[] = $learndash_premium_dashboard_course_id;
        }
    }
}
?>

<div class="ldp-dashboard-content">
    <div class="ldp-section-header-wrap">
        <div>
            <h1 class="ldp-section-title"><?php echo esc_html__( 'My Certificates', 'learndash-premium-dashboard' ); ?></h1>
            <p class="ldp-section-desc"><?php echo esc_html__( 'View and download certificates from your enrolled courses.', 'learndash-premium-dashboard' ); ?></p>
        </div>
    </div>
    
    <div class="ldp-course-list">
        <?php 
        if ( ! empty( $learndash_premium_dashboard_cert_courses ) ) : 
            
            foreach ( $learndash_premium_dashboard_cert_courses as $learndash_premium_dashboard_course_id ) :
                $learndash_premium_dashboard_course_title = get_the_title( $learndash_premium_dashboard_course_id );
                
                // LearnDash native function to get the certificate URL if earned
                $learndash_premium_dashboard_cert_link = learndash_get_course_certificate_link( $learndash_premium_dashboard_course_id, $learndash_premium_dashboard_user_id );
                
                // If a link exists, they earned it.
                $learndash_premium_dashboard_is_earned = ! empty( $learndash_premium_dashboard_cert_link );

                // Get smart resume URL for the locked state
                $learndash_premium_dashboard_continue_link = learndash_premium_dashboard_get_smart_resume_url( $learndash_premium_dashboard_course_id, $learndash_premium_dashboard_user_id );
                ?>
                
                <div class="ldp-card ldp-course-flex <?php echo $learndash_premium_dashboard_is_earned ? '' : 'ldp-cert-locked'; ?>">
                    
                    <div class="ldp-cert-icon">
                        <?php if ( $learndash_premium_dashboard_is_earned ) : ?>
                            <svg viewBox="0 0 24 24" fill="none" stroke="var(--ldp_skb-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="40" height="40">
                                <circle cx="12" cy="8" r="7"></circle>
                                <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
                            </svg>
                        <?php else : ?>
                            <svg viewBox="0 0 24 24" fill="none" stroke="var(--ldp_skb-text-dim)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="40" height="40">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                        <?php endif; ?>
                    </div>
                    
                    <div class="ldp-course-details">
                        <h3><?php echo esc_html( $learndash_premium_dashboard_course_title ); ?></h3>
                        <?php if ( $learndash_premium_dashboard_is_earned ) : ?>
                            <p class="ldp-text-dim" style="color: var(--ldp_skb-primary); font-weight: 500;"><?php echo esc_html__( 'Certificate Earned!', 'learndash-premium-dashboard' ); ?></p>
                        <?php else : ?>
                            <p class="ldp-text-dim"><?php echo esc_html__( 'Complete the course to unlock this certificate.', 'learndash-premium-dashboard' ); ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="ldp-course-action">
                        <?php if ( $learndash_premium_dashboard_is_earned ) : ?>
                            <a href="<?php echo esc_url( $learndash_premium_dashboard_cert_link ); ?>" target="_blank" class="ldp-btn ldp-btn-primary">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="7 10 12 15 17 10"></polyline>
                                    <line x1="12" y1="15" x2="12" y2="3"></line>
                                </svg>
                                <?php echo esc_html__( 'Download PDF', 'learndash-premium-dashboard' ); ?>
                            </a>
                        <?php else : ?>
                            <a href="<?php echo esc_url( $learndash_premium_dashboard_continue_link ); ?>" class="ldp-btn ldp-btn-disabled">
                                <?php echo esc_html__( 'Continue Course', 'learndash-premium-dashboard' ); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    
                </div>

            <?php endforeach; ?>
            
        <?php else : ?>
            <div class="ldp-card">
                <p class="ldp-text-dim"><?php echo esc_html__( 'No certificates available. Enroll in a course that offers a certificate to see them here.', 'learndash-premium-dashboard' ); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>