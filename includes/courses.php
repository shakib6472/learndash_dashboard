<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

 

// Get current user ID
$user_id = get_current_user_id();

// Fetch enrolled courses for the current user
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
            <h1 class="ldp-section-title">My Courses</h1>
            <p class="ldp-section-desc">Track your enrolled training programs.</p>
        </div>
        
        <div class="ldp-view-controls">
            <button type="button" class="ldp-view-btn active" data-view="list" aria-label="List View">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
            </button>
            <button type="button" class="ldp-view-btn" data-view="grid" aria-label="Grid View">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
            </button>
        </div>
    </div>

    <div class="ldp-course-list" id="ldp-course-list-container">
        <?php 
        if ( ! empty( $enrolled_courses ) ) : 
            
            foreach ( $enrolled_courses as $course_id ) :
                $course_title = get_the_title( $course_id );
                $course_link  = get_permalink( $course_id );
                
                // Get Course Thumbnail
                $course_thumb = get_the_post_thumbnail_url( $course_id, 'medium' );
                if ( ! $course_thumb ) {
                    $settings = get_option( 'learndash_premium_dashboard_settings', array() );
                    $course_thumb = !empty($settings['logo_url']) ? $settings['logo_url'] : 'https://via.placeholder.com/300x200?text=' . urlencode( 'No Image' );
                }

                $progress = learndash_course_progress( array(
                    'user_id'   => $user_id,
                    'course_id' => $course_id,
                    'array'     => true
                ) );
                
                $percentage = isset( $progress['percentage'] ) ? $progress['percentage'] : 0;
                $percentagetofill = isset( $progress['percentage'] ) ? $progress['percentage'] : 1;
                $completed  = isset( $progress['completed'] ) ? $progress['completed'] : 0;
                $total      = isset( $progress['total'] ) ? $progress['total'] : 0;
                
                // --- USE OUR CUSTOM SMART RESUME FUNCTION ---
                if ( $percentage < 100 ) {
                    $action_link = ldp_get_smart_resume_url( $course_id, $user_id );
                } else {
                    $action_link = $course_link; // If 100%, just go to course page
                }

                // Button Text
                if ( $percentage == 0 ) {
                    $btn_text = 'Start Course';
                    $percentagetofill = 1;
                } elseif ( $percentage >= 100 ) {
                    // if course is completed, check the course is in $$cert_courses or not, if in cert_courses then show download certificate otherwise show view course
                    if ( in_array( $course_id, $cert_courses ) ) {
                        $action_link = learndash_get_course_certificate_link( $course_id, $user_id );
                        $btn_text = 'Download Certificate';
                    } else {
                        $btn_text = 'Completed';
                    } 
                } else {
                    $btn_text = 'Continue';
                }
                ?>
                
                <div class="ldp-card ldp-course-flex">
                    <img src="<?php echo esc_url( $course_thumb ); ?>" alt="<?php echo esc_attr( $course_title ); ?>" class="ldp-course-img">
                    
                    <div class="ldp-course-details">
                        <h3><?php echo esc_html( $course_title ); ?></h3>
                        <p class="ldp-text-dim"><?php echo esc_html( $completed . ' of ' . $total . ' Steps Completed' ); ?></p>
                        
                        <div class="ldp-progress-wrap">
                            <div class="ldp-progress-bg">
                                <div class="ldp-progress-fill" style="width: <?php echo esc_attr( $percentagetofill ); ?>%;"></div>
                            </div>
                            <span class="ldp-progress-text"><?php echo esc_html( $percentage ); ?>% complete</span>
                        </div>
                    </div>
                    
                    <div class="ldp-course-action">
                        <a href="<?php echo esc_url( $action_link ); ?>" class="ldp-btn ldp-btn-primary">
                            <?php echo esc_html( $btn_text ); ?>
                        </a>
                    </div>
                </div>

            <?php endforeach; ?>
            
        <?php else : ?>
            <div class="ldp-card">
                <p class="ldp-text-dim">You are not currently enrolled in any courses.</p>
            </div>
        <?php endif; ?>
    </div>
</div>