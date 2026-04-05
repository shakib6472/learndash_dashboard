<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// 1. Get User Details
$learndash_premium_dashboard_user_id = get_current_user_id();
$learndash_premium_dashboard_current_user = wp_get_current_user();
$learndash_premium_dashboard_first_name = $learndash_premium_dashboard_current_user->user_firstname ? $learndash_premium_dashboard_current_user->user_firstname : $learndash_premium_dashboard_current_user->display_name;

// 2. Calculate Real Statistics
$learndash_premium_dashboard_enrolled_courses = learndash_user_get_enrolled_courses( $learndash_premium_dashboard_user_id );
$learndash_premium_dashboard_total_courses = empty( $learndash_premium_dashboard_enrolled_courses ) ? 0 : count( $learndash_premium_dashboard_enrolled_courses );

$learndash_premium_dashboard_total_steps_completed = 0;
$learndash_premium_dashboard_total_percentage_sum = 0;
$learndash_premium_dashboard_certificates_earned = 0;

$learndash_premium_dashboard_active_courses_data = array();

if ( $learndash_premium_dashboard_total_courses > 0 ) {
    foreach ( $learndash_premium_dashboard_enrolled_courses as $learndash_premium_dashboard_course_id ) {
        // Get Progress
        $learndash_premium_dashboard_progress = learndash_course_progress( array(
            'user_id'   => $learndash_premium_dashboard_user_id,
            'course_id' => $learndash_premium_dashboard_course_id,
            'array'     => true
        ) );
        
        $learndash_premium_dashboard_percentage = isset( $learndash_premium_dashboard_progress['percentage'] ) ? $learndash_premium_dashboard_progress['percentage'] : 0;
        $learndash_premium_dashboard_completed_steps = isset( $learndash_premium_dashboard_progress['completed'] ) ? $learndash_premium_dashboard_progress['completed'] : 0;
        
        $learndash_premium_dashboard_total_percentage_sum += $learndash_premium_dashboard_percentage;
        $learndash_premium_dashboard_total_steps_completed += $learndash_premium_dashboard_completed_steps;
        
        // Check for Certificates
        if ( learndash_get_course_certificate_link( $learndash_premium_dashboard_course_id, $learndash_premium_dashboard_user_id ) ) {
            $learndash_premium_dashboard_certificates_earned++;
        }
        
        // Store course data to display in the "My Courses" compact view later
        if ( $learndash_premium_dashboard_percentage < 100 ) {
            $learndash_premium_dashboard_active_courses_data[$learndash_premium_dashboard_course_id] = $learndash_premium_dashboard_percentage;
        }
    }
}

// Calculate Average Completion Rate
$learndash_premium_dashboard_avg_completion_rate = $learndash_premium_dashboard_total_courses > 0 ? round( $learndash_premium_dashboard_total_percentage_sum / $learndash_premium_dashboard_total_courses ) : 0;

// Sort active courses so the ones with highest progress show first
arsort( $learndash_premium_dashboard_active_courses_data );
?>

<div class="ldp-dashboard-content">
    <h1 class="ldp-section-title">
        <?php 
        /* translators: %s: User's first name */
        echo esc_html( sprintf( __( 'Welcome back, %s', 'learndash-premium-dashboard' ), ucfirst( $learndash_premium_dashboard_first_name ) ) ); 
        ?>
    </h1>
    <p class="ldp-section-desc"><?php echo esc_html__( 'Here is your training progress overview.', 'learndash-premium-dashboard' ); ?></p>

    <div class="ldp-stats-grid">
        <div class="ldp-card ldp-stat-card">
            <h3><?php echo esc_html( $learndash_premium_dashboard_total_courses ); ?></h3>
            <p class="ldp-text-dim"><?php echo esc_html__( 'Enrolled Courses', 'learndash-premium-dashboard' ); ?></p>
        </div>
        <div class="ldp-card ldp-stat-card">
            <h3><?php echo esc_html( $learndash_premium_dashboard_total_steps_completed ); ?></h3>
            <p class="ldp-text-dim"><?php echo esc_html__( 'Steps Completed', 'learndash-premium-dashboard' ); ?></p>
        </div>
        <div class="ldp-card ldp-stat-card">
            <h3><?php echo esc_html( $learndash_premium_dashboard_certificates_earned ); ?></h3>
            <p class="ldp-text-dim"><?php echo esc_html__( 'Certificates Earned', 'learndash-premium-dashboard' ); ?></p>
        </div>
        <div class="ldp-card ldp-stat-card">
            <h3><?php echo esc_html( $learndash_premium_dashboard_avg_completion_rate ); ?>%</h3>
            <p class="ldp-text-dim"><?php echo esc_html__( 'Avg. Completion Rate', 'learndash-premium-dashboard' ); ?></p>
        </div>
    </div>

    <div class="ldp-dashboard-layout">
        <div class="ldp-main-column">
            <div class="ldp-section-header">
                <h2><?php echo esc_html__( 'Continue Learning', 'learndash-premium-dashboard' ); ?></h2>
                <a href="<?php echo esc_url( trailingslashit( $current_url ) . 'courses/' ); ?>" class="ldp-link"><?php echo esc_html__( 'View All', 'learndash-premium-dashboard' ); ?></a>
            </div>
            
            <?php 
            if ( ! empty( $learndash_premium_dashboard_active_courses_data ) ) : 
                // Display up to 2 active courses
                $learndash_premium_dashboard_count = 0;
                foreach ( $learndash_premium_dashboard_active_courses_data as $learndash_premium_dashboard_course_id => $learndash_premium_dashboard_percentage ) :
                    if ( $learndash_premium_dashboard_count >= 2 ) break;
                    
                    $learndash_premium_dashboard_course_title = get_the_title( $learndash_premium_dashboard_course_id );
                    
                    // Get Thumbnail
                    $learndash_premium_dashboard_course_thumb = get_the_post_thumbnail_url( $learndash_premium_dashboard_course_id, 'thumbnail' );
                    if ( ! $learndash_premium_dashboard_course_thumb ) {
                        $learndash_premium_dashboard_settings = get_option( 'learndash_premium_dashboard_settings', array() );
                        $learndash_premium_dashboard_course_thumb = !empty($learndash_premium_dashboard_settings['logo_url']) ? $learndash_premium_dashboard_settings['logo_url'] : 'https://via.placeholder.com/80x80?text=Course';
                    }

                    // Smart Resume Link
                    $learndash_premium_dashboard_action_link = learndash_premium_dashboard_get_smart_resume_url( $learndash_premium_dashboard_course_id, $learndash_premium_dashboard_user_id );
                    
                    // Set percentage to at least 1 so the bar is slightly visible
                    $learndash_premium_dashboard_percentagetofill = $learndash_premium_dashboard_percentage > 0 ? $learndash_premium_dashboard_percentage : 1;
                    ?>
                    
                    <div class="ldp-card ldp-course-compact" style="margin-bottom: 16px;">
                        <img src="<?php echo esc_url( $learndash_premium_dashboard_course_thumb ); ?>" alt="<?php echo esc_attr( $learndash_premium_dashboard_course_title ); ?>" class="ldp-course-thumb" style="object-fit: cover;">
                        <div class="ldp-course-info">
                            <h3 style="margin-top: 0; margin-bottom: 8px;"><?php echo esc_html( $learndash_premium_dashboard_course_title ); ?></h3>
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
                        <a href="<?php echo esc_url( $learndash_premium_dashboard_action_link ); ?>" class="ldp-btn ldp-btn-primary"><?php echo esc_html__( 'Resume', 'learndash-premium-dashboard' ); ?></a>
                    </div>
                    
                    <?php 
                    $learndash_premium_dashboard_count++;
                endforeach; 
            else : 
            ?>
                <div class="ldp-card">
                    <p class="ldp-text-dim"><?php echo esc_html__( 'You don\'t have any courses in progress right now.', 'learndash-premium-dashboard' ); ?></p>
                </div>
            <?php endif; ?>
        </div>

        <div class="ldp-side-column">
            <div class="ldp-section-header">
                <h2><?php echo esc_html__( 'Recent Enrollments', 'learndash-premium-dashboard' ); ?></h2>
            </div>
            <div class="ldp-card">
                <?php if ( ! empty( $learndash_premium_dashboard_enrolled_courses ) ) : ?>
                    <ul class="ldp-activity-list">
                        <?php 
                        // Show the last 3 enrolled courses
                        $learndash_premium_dashboard_recent_enrolls = array_slice( array_reverse( $learndash_premium_dashboard_enrolled_courses ), 0, 3 );
                        foreach ( $learndash_premium_dashboard_recent_enrolls as $learndash_premium_dashboard_course_id ) : 
                            $learndash_premium_dashboard_course_title = get_the_title( $learndash_premium_dashboard_course_id );
                        ?>
                            <li class="ldp-activity-item">
                                <span class="ldp-activity-dot"></span>
                                <div class="ldp-activity-content">
                                   <a href="<?php echo esc_url(get_permalink( $learndash_premium_dashboard_course_id)); ?>"><strong style="line-height: 1.2; display: block; margin-bottom: 4px;"><?php echo esc_html( $learndash_premium_dashboard_course_title ); ?></strong></a> 
                                    <small style="color: var(--ldp_skb-text-dim);"><?php echo esc_html__( 'Enrolled', 'learndash-premium-dashboard' ); ?></small>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <p class="ldp-text-dim" style="margin: 0; font-size: 0.85rem;"><?php echo esc_html__( 'No recent activity.', 'learndash-premium-dashboard' ); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>