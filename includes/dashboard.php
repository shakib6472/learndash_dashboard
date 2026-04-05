<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="ldp-dashboard-content">
    <h1 class="ldp-section-title">Welcome back, John</h1>
    <p class="ldp-section-desc">Here is your training progress overview.</p>

    <div class="ldp-stats-grid">
        <div class="ldp-card ldp-stat-card">
            <h3>3</h3>
            <p class="ldp-text-dim">Enrolled Courses</p>
        </div>
        <div class="ldp-card ldp-stat-card">
            <h3>28</h3>
            <p class="ldp-text-dim">Hours Completed</p>
        </div>
        <div class="ldp-card ldp-stat-card">
            <h3>1</h3>
            <p class="ldp-text-dim">Certificates Earned</p>
        </div>
        <div class="ldp-card ldp-stat-card">
            <h3>65%</h3>
            <p class="ldp-text-dim">Completion Rate</p>
        </div>
    </div>

    <div class="ldp-dashboard-layout">
        <div class="ldp-main-column">
            <div class="ldp-section-header">
                <h2>My Courses</h2>
                <a href="<?php echo esc_url( trailingslashit( $current_url ) . 'courses/' ); ?>" class="ldp-link">View All</a>
            </div>
            
            <div class="ldp-card ldp-course-compact">
                <img src="https://via.placeholder.com/80" alt="Course Thumbnail" class="ldp-course-thumb">
                <div class="ldp-course-info">
                    <h3>Armed Security Officer Certification</h3>
                    <div class="ldp-progress-wrap">
                        <div class="ldp-progress-bg">
                            <div class="ldp-progress-fill ldp-w-75"></div>
                        </div>
                        <span class="ldp-progress-text">75% complete</span>
                    </div>
                </div>
                <button type="button" class="ldp-btn ldp-btn-primary">Resume</button>
            </div>
        </div>

        <div class="ldp-side-column">
            <div class="ldp-section-header">
                <h2>Recent Activity</h2>
            </div>
            <div class="ldp-card">
                <ul class="ldp-activity-list">
                    <li class="ldp-activity-item">
                        <span class="ldp-activity-dot"></span>
                        <div class="ldp-activity-content">
                            <strong>Completed module</strong>
                            <p>Use of Force Continuum</p>
                            <small>2 hours ago</small>
                        </div>
                    </li>
                    <li class="ldp-activity-item">
                        <span class="ldp-activity-dot"></span>
                        <div class="ldp-activity-content">
                            <strong>Passed quiz</strong>
                            <p>Firearm Safety Assessment</p>
                            <small>Yesterday</small>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>