<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get current user ID
$kbw_sd_user_id = get_current_user_id();

// Fetch enrolled courses for the current user
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
            <h1 class="kbw-sd-section-title"><?php echo esc_html__( 'My Courses', 'kibworks-student-dashboard-for-learndash' ); ?></h1>
            <p class="kbw-sd-section-desc"><?php echo esc_html__( 'Track your enrolled training programs.', 'kibworks-student-dashboard-for-learndash' ); ?></p>
        </div>
        
        <div class="kbw-sd-view-controls">
            <button type="button" class="kbw-sd-view-btn active" data-view="list" aria-label="<?php esc_attr_e( 'List View', 'kibworks-student-dashboard-for-learndash' ); ?>">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
            </button>
            <button type="button" class="kbw-sd-view-btn" data-view="grid" aria-label="<?php esc_attr_e( 'Grid View', 'kibworks-student-dashboard-for-learndash' ); ?>">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
            </button>
        </div>
    </div>

    <div class="kbw-sd-course-list" id="kbw-sd-course-list-container">
        <?php 
        if ( ! empty( $kbw_sd_enrolled_courses ) ) : 
            
            foreach ( $kbw_sd_enrolled_courses as $kbw_sd_course_id ) :
                $kbw_sd_course_title = get_the_title( $kbw_sd_course_id );
                $kbw_sd_course_link  = get_permalink( $kbw_sd_course_id );
                
                // Get Course Thumbnail
                $kbw_sd_course_thumb = get_the_post_thumbnail_url( $kbw_sd_course_id, 'medium' );
                if ( ! $kbw_sd_course_thumb ) {
                    $kbw_sd_course_thumb = kbw_sd_get_placeholder_image_url();
                }

                $kbw_sd_progress = learndash_course_progress( array(
                    'user_id'   => $kbw_sd_user_id,
                    'course_id' => $kbw_sd_course_id,
                    'array'     => true
                ) );
                
                $kbw_sd_percentage = isset( $kbw_sd_progress['percentage'] ) ? $kbw_sd_progress['percentage'] : 0;
                $kbw_sd_percentagetofill = isset( $kbw_sd_progress['percentage'] ) ? $kbw_sd_progress['percentage'] : 1;
                $kbw_sd_completed  = isset( $kbw_sd_progress['completed'] ) ? $kbw_sd_progress['completed'] : 0;
                $kbw_sd_total      = isset( $kbw_sd_progress['total'] ) ? $kbw_sd_progress['total'] : 0;
                
                // --- USE OUR CUSTOM SMART RESUME FUNCTION ---
                if ( $kbw_sd_percentage < 100 ) {
                    $kbw_sd_action_link = kbw_sd_get_smart_resume_url( $kbw_sd_course_id, $kbw_sd_user_id );
                } else {
                    $kbw_sd_action_link = $kbw_sd_course_link; // If 100%, just go to course page
                }

                // Button Text
                if ( $kbw_sd_percentage == 0 ) {
                    $kbw_sd_btn_text = __( 'Start Course', 'kibworks-student-dashboard-for-learndash' );
                    $kbw_sd_percentagetofill = 1;
                } elseif ( $kbw_sd_percentage >= 100 ) {
                    // if course is completed, check the course is in cert_courses or not
                    if ( in_array( $kbw_sd_course_id, $kbw_sd_cert_courses ) ) {
                        $kbw_sd_action_link = learndash_get_course_certificate_link( $kbw_sd_course_id, $kbw_sd_user_id );
                        $kbw_sd_btn_text = __( 'Download Certificate', 'kibworks-student-dashboard-for-learndash' );
                    } else {
                        $kbw_sd_btn_text = __( 'Completed', 'kibworks-student-dashboard-for-learndash' );
                    } 
                } else {
                    $kbw_sd_btn_text = __( 'Continue', 'kibworks-student-dashboard-for-learndash' );
                }
                ?>
                
                <div class="kbw-sd-card kbw-sd-course-flex">
                    <img src="<?php echo esc_url( $kbw_sd_course_thumb ); ?>" alt="<?php echo esc_attr( $kbw_sd_course_title ); ?>" class="kbw-sd-course-img">
                    
                    <div class="kbw-sd-course-details">
                        <h3><?php echo esc_html( $kbw_sd_course_title ); ?></h3>
                        <p class="kbw-sd-text-dim">
                            <?php 
                            /* translators: 1: Number of completed steps, 2: Total number of steps */
                            echo esc_html( sprintf( __( '%1$s of %2$s Steps Completed', 'kibworks-student-dashboard-for-learndash' ), $kbw_sd_completed, $kbw_sd_total ) ); 
                            ?>
                        </p>
                        
                        <div class="kbw-sd-progress-wrap">
                            <div class="kbw-sd-progress-bg">
                                <div class="kbw-sd-progress-fill" style="width: <?php echo esc_attr( $kbw_sd_percentagetofill ); ?>%;"></div>
                            </div>
                            <span class="kbw-sd-progress-text">
                                <?php 
                                /* translators: %s: Percentage of course completed */
                                echo esc_html( sprintf( __( '%s%% complete', 'kibworks-student-dashboard-for-learndash' ), $kbw_sd_percentage ) ); 
                                ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="kbw-sd-course-action">
                        <a href="<?php echo esc_url( $kbw_sd_action_link ); ?>" class="kbw-sd-btn kbw-sd-btn-primary">
                            <?php echo esc_html( $kbw_sd_btn_text ); ?>
                        </a>
                    </div>
                </div>

            <?php endforeach; ?>
            
        <?php else : ?>
            <div class="kbw-sd-card">
                <p class="kbw-sd-text-dim"><?php echo esc_html__( 'You are not currently enrolled in any courses.', 'kibworks-student-dashboard-for-learndash' ); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>