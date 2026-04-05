<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$current_user = wp_get_current_user();
$first_name = $current_user->user_firstname ? substr( $current_user->user_firstname, 0, 1 ) : '';
$last_name = $current_user->user_lastname ? substr( $current_user->user_lastname, 0, 1 ) : '';
$initials = strtoupper( $first_name . $last_name );

if ( empty( $initials ) ) {
    $initials = strtoupper( substr( $current_user->user_login, 0, 1 ) );
}

// Format the title nicely
$page_title = ucfirst( $active_tab );
if ( $active_tab === 'courses' ) {
    $page_title = 'My Courses';
}
?>

<header class="ldp-header-bar">
    <div class="ldp-header-title">
        <button type="button" class="ldp-menu-toggle" id="ldp-menu-btn" aria-label="Toggle Menu">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>
        <h1><?php echo esc_html( $page_title ); ?></h1>
    </div>
    
    <a href="<?php echo esc_url( trailingslashit( get_permalink() ) . 'profile/' ); ?>" class="ldp-user-avatar" title="Go to Profile">
        <?php echo esc_html( $initials ); ?>
    </a>
</header>