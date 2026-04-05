<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// 1. Get User Details
$user_id = get_current_user_id();
$current_user = wp_get_current_user();
$first_name = $current_user->user_firstname ? $current_user->user_firstname : $current_user->display_name;

// 2. Calculate Real Statistics
$enrolled_courses = learndash_user_get_enrolled_courses( $user_id );
$total_courses = empty( $enrolled_courses ) ? 0 : count( $enrolled_courses );

$total_steps_completed = 0;
$total_percentage_sum = 0;
$certificates_earned = 0;

$active_courses_data = array();

if ( $total_courses > 0 ) {
    foreach ( $enrolled_courses as $course_id ) {
        // Get Progress
        $progress = learndash_course_progress( array(
            'user_id'   => $user_id,
            'course_id' => $course_id,
            'array'     => true
        ) );
        
        $percentage = isset( $progress['percentage'] ) ? $progress['percentage'] : 0;
        $completed_steps = isset( $progress['completed'] ) ? $progress['completed'] : 0;
        
        $total_percentage_sum += $percentage;
        $total_steps_completed += $completed_steps;
        
        // Check for Certificates
        if ( learndash_get_course_certificate_link( $course_id, $user_id ) ) {
            $certificates_earned++;
        }
        
        // Store course data to display in the "My Courses" compact view later
        if ( $percentage < 100 ) {
            $active_courses_data[$course_id] = $percentage;
        }
    }
}

// Calculate Average Completion Rate
$avg_completion_rate = $total_courses > 0 ? round( $total_percentage_sum / $total_courses ) : 0;

// Sort active courses so the ones with highest progress show first
arsort( $active_courses_data );
?>

<div class="ldp-dashboard-content">
    <h1 class="ldp-section-title">Welcome back, <?php echo esc_html( ucfirst( $first_name ) ); ?></h1>
    <p class="ldp-section-desc">Here is your training progress overview.</p>

    <div class="ldp-stats-grid">
        <div class="ldp-card ldp-stat-card">
            <h3><?php echo esc_html( $total_courses ); ?></h3>
            <p class="ldp-text-dim">Enrolled Courses</p>
        </div>
        <div class="ldp-card ldp-stat-card">
            <h3><?php echo esc_html( $total_steps_completed ); ?></h3>
            <p class="ldp-text-dim">Steps Completed</p>
        </div>
        <div class="ldp-card ldp-stat-card">
            <h3><?php echo esc_html( $certificates_earned ); ?></h3>
            <p class="ldp-text-dim">Certificates Earned</p>
        </div>
        <div class="ldp-card ldp-stat-card">
            <h3><?php echo esc_html( $avg_completion_rate ); ?>%</h3>
            <p class="ldp-text-dim">Avg. Completion Rate</p>
        </div>
    </div>

    <div class="ldp-dashboard-layout">
        <div class="ldp-main-column">
            <div class="ldp-section-header">
                <h2>Continue Learning</h2>
                <a href="<?php echo esc_url( trailingslashit( $current_url ) . 'courses/' ); ?>" class="ldp-link">View All</a>
            </div>
            
            <?php 
            if ( ! empty( $active_courses_data ) ) : 
                // Display up to 2 active courses
                $count = 0;
                foreach ( $active_courses_data as $course_id => $percentage ) :
                    if ( $count >= 2 ) break;
                    
                    $course_title = get_the_title( $course_id );
                    
                    // Get Thumbnail
                    $course_thumb = get_the_post_thumbnail_url( $course_id, 'thumbnail' );
                    if ( ! $course_thumb ) {
                        $settings = get_option( 'learndash_premium_dashboard_settings', array() );
                        $course_thumb = !empty($settings['logo_url']) ? $settings['logo_url'] : 'https://via.placeholder.com/80x80?text=Course';
                    }

                    // Smart Resume Link
                    $action_link = ldp_get_smart_resume_url( $course_id, $user_id );
                    
                    // Set percentage to at least 1 so the bar is slightly visible
                    $percentagetofill = $percentage > 0 ? $percentage : 1;
                    ?>
                    
                    <div class="ldp-card ldp-course-compact" style="margin-bottom: 16px;">
                        <img src="<?php echo esc_url( $course_thumb ); ?>" alt="<?php echo esc_attr( $course_title ); ?>" class="ldp-course-thumb" style="object-fit: cover;">
                        <div class="ldp-course-info">
                            <h3 style="margin-top: 0; margin-bottom: 8px;"><?php echo esc_html( $course_title ); ?></h3>
                            <div class="ldp-progress-wrap">
                                <div class="ldp-progress-bg">
                                    <div class="ldp-progress-fill" style="width: <?php echo esc_attr( $percentagetofill ); ?>%;"></div>
                                </div>
                                <span class="ldp-progress-text"><?php echo esc_html( $percentage ); ?>% complete</span>
                            </div>
                        </div>
                        <a href="<?php echo esc_url( $action_link ); ?>" class="ldp-btn ldp-btn-primary">Resume</a>
                    </div>
                    
                    <?php 
                    $count++;
                endforeach; 
            else : 
            ?>
                <div class="ldp-card">
                    <p class="ldp-text-dim">You don't have any courses in progress right now.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="ldp-side-column">
            <div class="ldp-section-header">
                <h2>Recent Enrollments</h2>
            </div>
            <div class="ldp-card">
                <?php if ( ! empty( $enrolled_courses ) ) : ?>
                    <ul class="ldp-activity-list">
                        <?php 
                        // Show the last 3 enrolled courses
                        $recent_enrolls = array_slice( array_reverse( $enrolled_courses ), 0, 3 );
                        foreach ( $recent_enrolls as $course_id ) : 
                            $course_title = get_the_title( $course_id );
                        ?>
                            <li class="ldp-activity-item">
                                <span class="ldp-activity-dot"></span>
                                <div class="ldp-activity-content">
                                   <a href="<?php echo esc_url(get_permalink( $course_id)); ?>"><strong style="line-height: 1.2; display: block; margin-bottom: 4px;"><?php echo esc_html( $course_title ); ?></strong></a> 
                                    <small style="color: var(--ldp_skb-text-dim);">Enrolled</small>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <p class="ldp-text-dim" style="margin: 0; font-size: 0.85rem;">No recent activity.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>