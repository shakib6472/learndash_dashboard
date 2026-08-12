<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// 1. Get User Details
$kbw_sd_user_id = get_current_user_id();
$kbw_sd_current_user = wp_get_current_user();
$kbw_sd_first_name = $kbw_sd_current_user->user_firstname ? $kbw_sd_current_user->user_firstname : $kbw_sd_current_user->display_name;

// 2. Calculate Real Statistics
$kbw_sd_enrolled_courses = learndash_user_get_enrolled_courses( $kbw_sd_user_id );
$kbw_sd_total_courses = empty( $kbw_sd_enrolled_courses ) ? 0 : count( $kbw_sd_enrolled_courses );

$kbw_sd_total_steps_completed = 0;
$kbw_sd_total_percentage_sum = 0;
$kbw_sd_certificates_earned = 0;

$kbw_sd_active_courses_data = array();

if ( $kbw_sd_total_courses > 0 ) {
    foreach ( $kbw_sd_enrolled_courses as $kbw_sd_course_id ) {
        // Get Progress
        $kbw_sd_progress = learndash_course_progress( array(
            'user_id'   => $kbw_sd_user_id,
            'course_id' => $kbw_sd_course_id,
            'array'     => true
        ) );
        
        $kbw_sd_percentage = isset( $kbw_sd_progress['percentage'] ) ? $kbw_sd_progress['percentage'] : 0;
        $kbw_sd_completed_steps = isset( $kbw_sd_progress['completed'] ) ? $kbw_sd_progress['completed'] : 0;
        
        $kbw_sd_total_percentage_sum += $kbw_sd_percentage;
        $kbw_sd_total_steps_completed += $kbw_sd_completed_steps;
        
        // Check for Certificates
        if ( learndash_get_course_certificate_link( $kbw_sd_course_id, $kbw_sd_user_id ) ) {
            $kbw_sd_certificates_earned++;
        }
        
        // Store course data to display in the "My Courses" compact view later
        if ( $kbw_sd_percentage < 100 ) {
            $kbw_sd_active_courses_data[$kbw_sd_course_id] = $kbw_sd_percentage;
        }
    }
}

// Calculate Average Completion Rate
$kbw_sd_avg_completion_rate = $kbw_sd_total_courses > 0 ? round( $kbw_sd_total_percentage_sum / $kbw_sd_total_courses ) : 0;

// Sort active courses so the ones with highest progress show first
arsort( $kbw_sd_active_courses_data );
?>

<div class="kbw-sd-dashboard-content">
    <h1 class="kbw-sd-section-title">
        <?php 
        /* translators: %s: User's first name */
        echo esc_html( sprintf( __( 'Welcome back, %s', 'kibworks-student-dashboard-for-learndash' ), ucfirst( $kbw_sd_first_name ) ) ); 
        ?>
    </h1>
    <p class="kbw-sd-section-desc"><?php echo esc_html__( 'Here is your training progress overview.', 'kibworks-student-dashboard-for-learndash' ); ?></p>

    <div class="kbw-sd-stats-grid">
        <div class="kbw-sd-card kbw-sd-stat-card">
            <h3><?php echo esc_html( $kbw_sd_total_courses ); ?></h3>
            <p class="kbw-sd-text-dim"><?php echo esc_html__( 'Enrolled Courses', 'kibworks-student-dashboard-for-learndash' ); ?></p>
        </div>
        <div class="kbw-sd-card kbw-sd-stat-card">
            <h3><?php echo esc_html( $kbw_sd_total_steps_completed ); ?></h3>
            <p class="kbw-sd-text-dim"><?php echo esc_html__( 'Steps Completed', 'kibworks-student-dashboard-for-learndash' ); ?></p>
        </div>
        <div class="kbw-sd-card kbw-sd-stat-card">
            <h3><?php echo esc_html( $kbw_sd_certificates_earned ); ?></h3>
            <p class="kbw-sd-text-dim"><?php echo esc_html__( 'Certificates Earned', 'kibworks-student-dashboard-for-learndash' ); ?></p>
        </div>
        <div class="kbw-sd-card kbw-sd-stat-card">
            <h3><?php echo esc_html( $kbw_sd_avg_completion_rate ); ?>%</h3>
            <p class="kbw-sd-text-dim"><?php echo esc_html__( 'Avg. Completion Rate', 'kibworks-student-dashboard-for-learndash' ); ?></p>
        </div>
    </div>

    <div class="kbw-sd-dashboard-layout">
        <div class="kbw-sd-main-column">
            <div class="kbw-sd-section-header">
                <h2><?php echo esc_html__( 'Continue Learning', 'kibworks-student-dashboard-for-learndash' ); ?></h2>
                <a href="<?php echo esc_url( trailingslashit( $current_url ) . 'courses/' ); ?>" class="kbw-sd-link"><?php echo esc_html__( 'View All', 'kibworks-student-dashboard-for-learndash' ); ?></a>
            </div>
            
            <?php 
            if ( ! empty( $kbw_sd_active_courses_data ) ) : 
                // Display up to 2 active courses
                $kbw_sd_count = 0;
                foreach ( $kbw_sd_active_courses_data as $kbw_sd_course_id => $kbw_sd_percentage ) :
                    if ( $kbw_sd_count >= 2 ) break;
                    
                    $kbw_sd_course_title = get_the_title( $kbw_sd_course_id );
                    
                    // Get Thumbnail
                    $kbw_sd_course_thumb = get_the_post_thumbnail_url( $kbw_sd_course_id, 'thumbnail' );
                    if ( ! $kbw_sd_course_thumb ) {
                        $kbw_sd_course_thumb = kbw_sd_get_placeholder_image_url();
                    }

                    // Smart Resume Link
                    $kbw_sd_action_link = kbw_sd_get_smart_resume_url( $kbw_sd_course_id, $kbw_sd_user_id );
                    
                    // Set percentage to at least 1 so the bar is slightly visible
                    $kbw_sd_percentagetofill = $kbw_sd_percentage > 0 ? $kbw_sd_percentage : 1;
                    ?>
                    
                    <div class="kbw-sd-card kbw-sd-course-compact" style="margin-bottom: 16px;">
                        <img src="<?php echo esc_url( $kbw_sd_course_thumb ); ?>" alt="<?php echo esc_attr( $kbw_sd_course_title ); ?>" class="kbw-sd-course-thumb" style="object-fit: cover;">
                        <div class="kbw-sd-course-info">
                            <h3 style="margin-top: 0; margin-bottom: 8px;"><?php echo esc_html( $kbw_sd_course_title ); ?></h3>
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
                        <a href="<?php echo esc_url( $kbw_sd_action_link ); ?>" class="kbw-sd-btn kbw-sd-btn-primary"><?php echo esc_html__( 'Resume', 'kibworks-student-dashboard-for-learndash' ); ?></a>
                    </div>
                    
                    <?php 
                    $kbw_sd_count++;
                endforeach; 
            else : 
            ?>
                <div class="kbw-sd-card">
                    <p class="kbw-sd-text-dim"><?php echo esc_html__( 'You don\'t have any courses in progress right now.', 'kibworks-student-dashboard-for-learndash' ); ?></p>
                </div>
            <?php endif; ?>
        </div>

        <div class="kbw-sd-side-column">
            <div class="kbw-sd-section-header">
                <h2><?php echo esc_html__( 'Recent Enrollments', 'kibworks-student-dashboard-for-learndash' ); ?></h2>
            </div>
            <div class="kbw-sd-card">
                <?php if ( ! empty( $kbw_sd_enrolled_courses ) ) : ?>
                    <ul class="kbw-sd-activity-list">
                        <?php 
                        // Show the last 3 enrolled courses
                        $kbw_sd_recent_enrolls = array_slice( array_reverse( $kbw_sd_enrolled_courses ), 0, 3 );
                        foreach ( $kbw_sd_recent_enrolls as $kbw_sd_course_id ) : 
                            $kbw_sd_course_title = get_the_title( $kbw_sd_course_id );
                        ?>
                            <li class="kbw-sd-activity-item">
                                <span class="kbw-sd-activity-dot"></span>
                                <div class="kbw-sd-activity-content">
                                   <a href="<?php echo esc_url(get_permalink( $kbw_sd_course_id)); ?>"><strong style="line-height: 1.2; display: block; margin-bottom: 4px;"><?php echo esc_html( $kbw_sd_course_title ); ?></strong></a> 
                                    <small style="color: var(--kbw-sd-text-dim);"><?php echo esc_html__( 'Enrolled', 'kibworks-student-dashboard-for-learndash' ); ?></small>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <p class="kbw-sd-text-dim" style="margin: 0; font-size: 0.85rem;"><?php echo esc_html__( 'No recent activity.', 'kibworks-student-dashboard-for-learndash' ); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>