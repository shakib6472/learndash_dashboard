<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$user_id = get_current_user_id();
$enrolled_courses = learndash_user_get_enrolled_courses( $user_id );
$cert_courses = array();

// Filter only courses that actually have a certificate assigned to them
if ( ! empty( $enrolled_courses ) ) {
    foreach ( $enrolled_courses as $course_id ) {
        $cert_id = learndash_get_setting( $course_id, 'certificate' );
        if ( ! empty( $cert_id ) ) {
            $cert_courses[] = $course_id;
        }
    }
}
?>

<div class="ldp-dashboard-content">
    <div class="ldp-section-header-wrap">
        <div>
            <h1 class="ldp-section-title">My Certificates</h1>
            <p class="ldp-section-desc">View and download certificates from your enrolled courses.</p>
        </div>
    </div>
    
    <div class="ldp-course-list">
        <?php 
        if ( ! empty( $cert_courses ) ) : 
            
            foreach ( $cert_courses as $course_id ) :
                $course_title = get_the_title( $course_id );
                
                // LearnDash native function to get the certificate URL if earned
                $cert_link = learndash_get_course_certificate_link( $course_id, $user_id );
                
                // If a link exists, they earned it.
                $is_earned = ! empty( $cert_link );

                // Get smart resume URL for the locked state
                $continue_link = ldp_get_smart_resume_url( $course_id, $user_id );
                ?>
                
                <div class="ldp-card ldp-course-flex <?php echo $is_earned ? '' : 'ldp-cert-locked'; ?>">
                    
                    <div class="ldp-cert-icon">
                        <?php if ( $is_earned ) : ?>
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
                        <h3><?php echo esc_html( $course_title ); ?></h3>
                        <?php if ( $is_earned ) : ?>
                            <p class="ldp-text-dim" style="color: var(--ldp_skb-primary); font-weight: 500;">Certificate Earned!</p>
                        <?php else : ?>
                            <p class="ldp-text-dim">Complete the course to unlock this certificate.</p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="ldp-course-action">
                        <?php if ( $is_earned ) : ?>
                            <a href="<?php echo esc_url( $cert_link ); ?>" target="_blank" class="ldp-btn ldp-btn-primary">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="7 10 12 15 17 10"></polyline>
                                    <line x1="12" y1="15" x2="12" y2="3"></line>
                                </svg>
                                Download PDF
                            </a>
                        <?php else : ?>
                            <a href="<?php echo esc_url( $continue_link ); ?>" class="ldp-btn ldp-btn-disabled">
                                Continue Course
                            </a>
                        <?php endif; ?>
                    </div>
                    
                </div>

            <?php endforeach; ?>
            
        <?php else : ?>
            <div class="ldp-card">
                <p class="ldp-text-dim">No certificates available. Enroll in a course that offers a certificate to see them here.</p>
            </div>
        <?php endif; ?>
    </div>
</div>