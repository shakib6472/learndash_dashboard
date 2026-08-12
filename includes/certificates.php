<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$kbw_sd_user_id = get_current_user_id();
$kbw_sd_enrolled_courses = learndash_user_get_enrolled_courses( $kbw_sd_user_id );
$kbw_sd_cert_courses = array();

// Filter only courses that actually have a certificate assigned to them
if ( ! empty( $kbw_sd_enrolled_courses ) ) {
    foreach ( $kbw_sd_enrolled_courses as $kbw_sd_course_id ) {
        $kbw_sd_cert_id = learndash_get_setting( $kbw_sd_course_id, 'certificate' );
        if ( ! empty( $kbw_sd_cert_id ) ) {
            $kbw_sd_cert_courses[] = $kbw_sd_course_id;
        }
    }
}
?>

<div class="kbw-sd-dashboard-content">
    <div class="kbw-sd-section-header-wrap">
        <div>
            <h1 class="kbw-sd-section-title"><?php echo esc_html__( 'My Certificates', 'kibworks-student-dashboard-for-learndash' ); ?></h1>
            <p class="kbw-sd-section-desc"><?php echo esc_html__( 'View and download certificates from your enrolled courses.', 'kibworks-student-dashboard-for-learndash' ); ?></p>
        </div>
    </div>
    
    <div class="kbw-sd-course-list">
        <?php 
        if ( ! empty( $kbw_sd_cert_courses ) ) : 
            
            foreach ( $kbw_sd_cert_courses as $kbw_sd_course_id ) :
                $kbw_sd_course_title = get_the_title( $kbw_sd_course_id );
                
                // LearnDash native function to get the certificate URL if earned
                $kbw_sd_cert_link = learndash_get_course_certificate_link( $kbw_sd_course_id, $kbw_sd_user_id );
                
                // If a link exists, they earned it.
                $kbw_sd_is_earned = ! empty( $kbw_sd_cert_link );

                // Get smart resume URL for the locked state
                $kbw_sd_continue_link = kbw_sd_get_smart_resume_url( $kbw_sd_course_id, $kbw_sd_user_id );
                ?>
                
                <div class="kbw-sd-card kbw-sd-course-flex <?php echo esc_attr( $kbw_sd_is_earned ? '' : 'kbw-sd-cert-locked' ); ?>">
                    
                    <div class="kbw-sd-cert-icon">
                        <?php if ( $kbw_sd_is_earned ) : ?>
                            <svg viewBox="0 0 24 24" fill="none" stroke="var(--kbw-sd-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="40" height="40">
                                <circle cx="12" cy="8" r="7"></circle>
                                <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
                            </svg>
                        <?php else : ?>
                            <svg viewBox="0 0 24 24" fill="none" stroke="var(--kbw-sd-text-dim)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="40" height="40">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                        <?php endif; ?>
                    </div>
                    
                    <div class="kbw-sd-course-details">
                        <h3><?php echo esc_html( $kbw_sd_course_title ); ?></h3>
                        <?php if ( $kbw_sd_is_earned ) : ?>
                            <p class="kbw-sd-text-dim" style="color: var(--kbw-sd-primary); font-weight: 500;"><?php echo esc_html__( 'Certificate Earned!', 'kibworks-student-dashboard-for-learndash' ); ?></p>
                        <?php else : ?>
                            <p class="kbw-sd-text-dim"><?php echo esc_html__( 'Complete the course to unlock this certificate.', 'kibworks-student-dashboard-for-learndash' ); ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="kbw-sd-course-action">
                        <?php if ( $kbw_sd_is_earned ) : ?>
                            <a href="<?php echo esc_url( $kbw_sd_cert_link ); ?>" target="_blank" class="kbw-sd-btn kbw-sd-btn-primary">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="7 10 12 15 17 10"></polyline>
                                    <line x1="12" y1="15" x2="12" y2="3"></line>
                                </svg>
                                <?php echo esc_html__( 'Download PDF', 'kibworks-student-dashboard-for-learndash' ); ?>
                            </a>
                        <?php else : ?>
                            <a href="<?php echo esc_url( $kbw_sd_continue_link ); ?>" class="kbw-sd-btn kbw-sd-btn-disabled">
                                <?php echo esc_html__( 'Continue Course', 'kibworks-student-dashboard-for-learndash' ); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    
                </div>

            <?php endforeach; ?>
            
        <?php else : ?>
            <div class="kbw-sd-card">
                <p class="kbw-sd-text-dim"><?php echo esc_html__( 'No certificates available. Enroll in a course that offers a certificate to see them here.', 'kibworks-student-dashboard-for-learndash' ); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>