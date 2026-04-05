<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get current user ID
$learndash_premium_dashboard_user_id = get_current_user_id();

// Fetch enrolled courses for the current user
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
            <h1 class="ldp-section-title"><?php echo esc_html__( 'My Courses', 'learndash-premium-dashboard' ); ?></h1>
            <p class="ldp-section-desc"><?php echo esc_html__( 'Track your enrolled training programs.', 'learndash-premium-dashboard' ); ?></p>
        </div>
        
        <div class="ldp-view-controls">
            <button type="button" class="ldp-view-btn active" data-view="list" aria-label="<?php esc_attr_e( 'List View', 'learndash-premium-dashboard' ); ?>">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
            </button>
            <button type="button" class="ldp-view-btn" data-view="grid" aria-label="<?php esc_attr_e( 'Grid View', 'learndash-premium-dashboard' ); ?>">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
            </button>
        </div>
    </div>

    <div class="ldp-course-list" id="ldp-course-list-container">
        <?php 
        if ( ! empty( $learndash_premium_dashboard_enrolled_courses ) ) : 
            
            foreach ( $learndash_premium_dashboard_enrolled_courses as $learndash_premium_dashboard_course_id ) :
                $learndash_premium_dashboard_course_title = get_the_title( $learndash_premium_dashboard_course_id );
                $learndash_premium_dashboard_course_link  = get_permalink( $learndash_premium_dashboard_course_id );
                
                // Get Course Thumbnail
                $learndash_premium_dashboard_course_thumb = get_the_post_thumbnail_url( $learndash_premium_dashboard_course_id, 'medium' );
                if ( ! $learndash_premium_dashboard_course_thumb ) {
                    $learndash_premium_dashboard_settings = get_option( 'learndash_premium_dashboard_settings', array() );
                    $learndash_premium_dashboard_course_thumb = !empty($learndash_premium_dashboard_settings['logo_url']) ? $learndash_premium_dashboard_settings['logo_url'] : 'https://via.placeholder.com/300x200?text=' . urlencode( esc_html__( 'No Image', 'learndash-premium-dashboard' ) );
                }

                $learndash_premium_dashboard_progress = learndash_course_progress( array(
                    'user_id'   => $learndash_premium_dashboard_user_id,
                    'course_id' => $learndash_premium_dashboard_course_id,
                    'array'     => true
                ) );
                
                $learndash_premium_dashboard_percentage = isset( $learndash_premium_dashboard_progress['percentage'] ) ? $learndash_premium_dashboard_progress['percentage'] : 0;
                $learndash_premium_dashboard_percentagetofill = isset( $learndash_premium_dashboard_progress['percentage'] ) ? $learndash_premium_dashboard_progress['percentage'] : 1;
                $learndash_premium_dashboard_completed  = isset( $learndash_premium_dashboard_progress['completed'] ) ? $learndash_premium_dashboard_progress['completed'] : 0;
                $learndash_premium_dashboard_total      = isset( $learndash_premium_dashboard_progress['total'] ) ? $learndash_premium_dashboard_progress['total'] : 0;
                
                // --- USE OUR CUSTOM SMART RESUME FUNCTION ---
                if ( $learndash_premium_dashboard_percentage < 100 ) {
                    $learndash_premium_dashboard_action_link = learndash_premium_dashboard_get_smart_resume_url( $learndash_premium_dashboard_course_id, $learndash_premium_dashboard_user_id );
                } else {
                    $learndash_premium_dashboard_action_link = $learndash_premium_dashboard_course_link; // If 100%, just go to course page
                }

                // Button Text
                if ( $learndash_premium_dashboard_percentage == 0 ) {
                    $learndash_premium_dashboard_btn_text = __( 'Start Course', 'learndash-premium-dashboard' );
                    $learndash_premium_dashboard_percentagetofill = 1;
                } elseif ( $learndash_premium_dashboard_percentage >= 100 ) {
                    // if course is completed, check the course is in cert_courses or not
                    if ( in_array( $learndash_premium_dashboard_course_id, $learndash_premium_dashboard_cert_courses ) ) {
                        $learndash_premium_dashboard_action_link = learndash_get_course_certificate_link( $learndash_premium_dashboard_course_id, $learndash_premium_dashboard_user_id );
                        $learndash_premium_dashboard_btn_text = __( 'Download Certificate', 'learndash-premium-dashboard' );
                    } else {
                        $learndash_premium_dashboard_btn_text = __( 'Completed', 'learndash-premium-dashboard' );
                    } 
                } else {
                    $learndash_premium_dashboard_btn_text = __( 'Continue', 'learndash-premium-dashboard' );
                }
                ?>
                
                <div class="ldp-card ldp-course-flex">
                    <img src="<?php echo esc_url( $learndash_premium_dashboard_course_thumb ); ?>" alt="<?php echo esc_attr( $learndash_premium_dashboard_course_title ); ?>" class="ldp-course-img">
                    
                    <div class="ldp-course-details">
                        <h3><?php echo esc_html( $learndash_premium_dashboard_course_title ); ?></h3>
                        <p class="ldp-text-dim">
                            <?php 
                            /* translators: 1: Number of completed steps, 2: Total number of steps */
                            echo esc_html( sprintf( __( '%1$s of %2$s Steps Completed', 'learndash-premium-dashboard' ), $learndash_premium_dashboard_completed, $learndash_premium_dashboard_total ) ); 
                            ?>
                        </p>
                        
                        <div class="ldp-progress-wrap">
                            <div class="ldp-progress-bg">
                                <div class="ldp-progress-fill" style="width: <?php echo esc_attr( $learndash_premium_dashboard_percentagetofill ); ?>%;"></div>
                            </div>
                            <span class="ldp-progress-text">
                                <?php 
                                /* translators: %s: Percentage of course completed */
                                echo esc_html( sprintf( __( '%s%% complete', 'learndash-premium-dashboard' ), $learndash_premium_dashboard_percentage ) ); 
                                ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="ldp-course-action">
                        <a href="<?php echo esc_url( $learndash_premium_dashboard_action_link ); ?>" class="ldp-btn ldp-btn-primary">
                            <?php echo esc_html( $learndash_premium_dashboard_btn_text ); ?>
                        </a>
                    </div>
                </div>

            <?php endforeach; ?>
            
        <?php else : ?>
            <div class="ldp-card">
                <p class="ldp-text-dim"><?php echo esc_html__( 'You are not currently enrolled in any courses.', 'learndash-premium-dashboard' ); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>