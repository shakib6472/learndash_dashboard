<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$learndash_premium_dashboard_user_id = get_current_user_id();
$learndash_premium_dashboard_current_user = wp_get_current_user();

$learndash_premium_dashboard_message = '';
$learndash_premium_dashboard_message_type = ''; // 'success' or 'error'

// Handle Form Submissions
if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['ldp_action'] ) ) {
    
    // Extra security check: Ensure user is actually logged in before processing POST
    if ( ! is_user_logged_in() ) {
        return;
    }

    $learndash_premium_dashboard_action = sanitize_text_field( wp_unslash( $_POST['ldp_action'] ) );

    // --- UPDATE PROFILE INFO ---
    if ( $learndash_premium_dashboard_action === 'update_profile' ) {
        $learndash_premium_dashboard_profile_nonce = isset( $_POST['ldp_profile_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ldp_profile_nonce'] ) ) : '';

        if ( empty( $learndash_premium_dashboard_profile_nonce ) || ! wp_verify_nonce( $learndash_premium_dashboard_profile_nonce, 'ldp_update_profile_action' ) ) {
            $learndash_premium_dashboard_message = __( 'Security check failed. Please refresh and try again.', 'learndash-premium-dashboard' );
            $learndash_premium_dashboard_message_type = 'error';
        } else {
            $learndash_premium_dashboard_first_name = isset( $_POST['ldp_first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['ldp_first_name'] ) ) : '';
            $learndash_premium_dashboard_last_name  = isset( $_POST['ldp_last_name'] ) ? sanitize_text_field( wp_unslash( $_POST['ldp_last_name'] ) ) : '';
            $learndash_premium_dashboard_email      = isset( $_POST['ldp_email'] ) ? sanitize_email( wp_unslash( $_POST['ldp_email'] ) ) : '';
            $learndash_premium_dashboard_phone      = isset( $_POST['ldp_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['ldp_phone'] ) ) : '';

            $learndash_premium_dashboard_userdata = array(
                'ID'         => $learndash_premium_dashboard_user_id,
                'first_name' => $learndash_premium_dashboard_first_name,
                'last_name'  => $learndash_premium_dashboard_last_name,
                'user_email' => $learndash_premium_dashboard_email,
            );

            // Update core user data
            $learndash_premium_dashboard_user_id_updated = wp_update_user( $learndash_premium_dashboard_userdata );

            if ( is_wp_error( $learndash_premium_dashboard_user_id_updated ) ) {
                $learndash_premium_dashboard_message = $learndash_premium_dashboard_user_id_updated->get_error_message();
                $learndash_premium_dashboard_message_type = 'error';
            } else {
                // Update phone number (Stored as user meta)
                update_user_meta( $learndash_premium_dashboard_user_id, 'ldp_phone', $learndash_premium_dashboard_phone );
                
                $learndash_premium_dashboard_message = __( 'Profile information updated successfully.', 'learndash-premium-dashboard' );
                $learndash_premium_dashboard_message_type = 'success';
                
                // Refresh user object to display new data immediately
                $learndash_premium_dashboard_current_user = wp_get_current_user();
            }
        }
    }

    // --- UPDATE PASSWORD ---
    if ( $learndash_premium_dashboard_action === 'update_password' ) {
        $learndash_premium_dashboard_password_nonce = isset( $_POST['ldp_password_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ldp_password_nonce'] ) ) : '';

        if ( empty( $learndash_premium_dashboard_password_nonce ) || ! wp_verify_nonce( $learndash_premium_dashboard_password_nonce, 'ldp_update_password_action' ) ) {
            $learndash_premium_dashboard_message = __( 'Security check failed. Please refresh and try again.', 'learndash-premium-dashboard' );
            $learndash_premium_dashboard_message_type = 'error';
        } else {
            // Passwords should only be unslashed, NOT sanitized, to preserve special characters
            $learndash_premium_dashboard_current_pass = isset( $_POST['ldp_current_pass'] ) ? wp_unslash( $_POST['ldp_current_pass'] ) : '';
            $learndash_premium_dashboard_new_pass     = isset( $_POST['ldp_new_pass'] ) ? wp_unslash( $_POST['ldp_new_pass'] ) : '';
            $learndash_premium_dashboard_confirm_pass = isset( $_POST['ldp_confirm_pass'] ) ? wp_unslash( $_POST['ldp_confirm_pass'] ) : '';

            if ( empty( $learndash_premium_dashboard_current_pass ) || empty( $learndash_premium_dashboard_new_pass ) || empty( $learndash_premium_dashboard_confirm_pass ) ) {
                $learndash_premium_dashboard_message = __( 'Please fill out all password fields.', 'learndash-premium-dashboard' );
                $learndash_premium_dashboard_message_type = 'error';
            } elseif ( $learndash_premium_dashboard_new_pass !== $learndash_premium_dashboard_confirm_pass ) {
                $learndash_premium_dashboard_message = __( 'Your new passwords do not match.', 'learndash-premium-dashboard' );
                $learndash_premium_dashboard_message_type = 'error';
            } elseif ( ! wp_check_password( $learndash_premium_dashboard_current_pass, $learndash_premium_dashboard_current_user->user_pass, $learndash_premium_dashboard_user_id ) ) {
                $learndash_premium_dashboard_message = __( 'Your current password is incorrect.', 'learndash-premium-dashboard' );
                $learndash_premium_dashboard_message_type = 'error';
            } else {
                // Set the new password
                wp_set_password( $learndash_premium_dashboard_new_pass, $learndash_premium_dashboard_user_id );
                
                // wp_set_password automatically logs the user out. We need to log them back in seamlessly.
                $learndash_premium_dashboard_creds = array(
                    'user_login'    => $learndash_premium_dashboard_current_user->user_login,
                    'user_password' => $learndash_premium_dashboard_new_pass,
                    'remember'      => true
                );
                wp_signon( $learndash_premium_dashboard_creds, false );
                
                $learndash_premium_dashboard_message = __( 'Password updated successfully.', 'learndash-premium-dashboard' );
                $learndash_premium_dashboard_message_type = 'success';
            }
        }
    }
}

// Fetch current values to pre-fill the form
$learndash_premium_dashboard_current_first_name = $learndash_premium_dashboard_current_user->user_firstname;
$learndash_premium_dashboard_current_last_name  = $learndash_premium_dashboard_current_user->user_lastname;
$learndash_premium_dashboard_current_email      = $learndash_premium_dashboard_current_user->user_email;
$learndash_premium_dashboard_current_phone      = get_user_meta( $learndash_premium_dashboard_user_id, 'ldp_phone', true );
?>

<div class="ldp-dashboard-content">
    
    <?php if ( ! empty( $learndash_premium_dashboard_message ) ) : ?>
        <div class="ldp-alert ldp-alert-<?php echo esc_attr( $learndash_premium_dashboard_message_type ); ?>">
            <?php echo esc_html( $learndash_premium_dashboard_message ); ?>
        </div>
    <?php endif; ?>

    <h1 class="ldp-section-title"><?php echo esc_html__( 'Profile Settings', 'learndash-premium-dashboard' ); ?></h1>
    
    <div class="ldp-card ldp-card-narrow">
        <form class="ldp-form" action="" method="post">
            <?php wp_nonce_field( 'ldp_update_profile_action', 'ldp_profile_nonce' ); ?>
            <input type="hidden" name="ldp_action" value="update_profile">
            
            <div class="ldp-form-grid">
                <div class="ldp-form-group">
                    <label for="ldp_first_name"><?php echo esc_html__( 'First Name', 'learndash-premium-dashboard' ); ?></label>
                    <input type="text" id="ldp_first_name" name="ldp_first_name" class="ldp-form-control" value="<?php echo esc_attr( $learndash_premium_dashboard_current_first_name ); ?>" required>
                </div>
                <div class="ldp-form-group">
                    <label for="ldp_last_name"><?php echo esc_html__( 'Last Name', 'learndash-premium-dashboard' ); ?></label>
                    <input type="text" id="ldp_last_name" name="ldp_last_name" class="ldp-form-control" value="<?php echo esc_attr( $learndash_premium_dashboard_current_last_name ); ?>" required>
                </div>
                <div class="ldp-form-group">
                    <label for="ldp_email"><?php echo esc_html__( 'Email', 'learndash-premium-dashboard' ); ?></label>
                    <input type="email" id="ldp_email" name="ldp_email" class="ldp-form-control" value="<?php echo esc_attr( $learndash_premium_dashboard_current_email ); ?>" required>
                </div>
                <div class="ldp-form-group">
                    <label for="ldp_phone"><?php echo esc_html__( 'Phone', 'learndash-premium-dashboard' ); ?></label>
                    <input type="text" id="ldp_phone" name="ldp_phone" class="ldp-form-control" value="<?php echo esc_attr( $learndash_premium_dashboard_current_phone ); ?>">
                </div>
            </div>
            <button type="submit" class="ldp-btn ldp-btn-primary"><?php echo esc_html__( 'Save Changes', 'learndash-premium-dashboard' ); ?></button>
        </form>
    </div>

    <h1 class="ldp-section-title ldp-mt-40"><?php echo esc_html__( 'Change Password', 'learndash-premium-dashboard' ); ?></h1>
    
    <div class="ldp-card ldp-card-narrow">
        <form class="ldp-form" action="" method="post">
            <?php wp_nonce_field( 'ldp_update_password_action', 'ldp_password_nonce' ); ?>
            <input type="hidden" name="ldp_action" value="update_password">
            
            <div class="ldp-form-grid">
                <div class="ldp-form-group ldp-col-span-2">
                    <label for="ldp_current_pass"><?php echo esc_html__( 'Current Password', 'learndash-premium-dashboard' ); ?></label>
                    <input type="password" id="ldp_current_pass" name="ldp_current_pass" class="ldp-form-control" required>
                </div>
                <div class="ldp-form-group">
                    <label for="ldp_new_pass"><?php echo esc_html__( 'New Password', 'learndash-premium-dashboard' ); ?></label>
                    <input type="password" id="ldp_new_pass" name="ldp_new_pass" class="ldp-form-control" required>
                </div>
                <div class="ldp-form-group">
                    <label for="ldp_confirm_pass"><?php echo esc_html__( 'Confirm New Password', 'learndash-premium-dashboard' ); ?></label>
                    <input type="password" id="ldp_confirm_pass" name="ldp_confirm_pass" class="ldp-form-control" required>
                </div>
            </div>
            <button type="submit" class="ldp-btn ldp-btn-primary"><?php echo esc_html__( 'Update Password', 'learndash-premium-dashboard' ); ?></button>
        </form>
    </div>
</div>