<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$user_id = get_current_user_id();
$current_user = wp_get_current_user();

$message = '';
$message_type = ''; // 'success' or 'error'

// Handle Form Submissions
if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['ldp_action'] ) ) {
    
    // --- UPDATE PROFILE INFO ---
    if ( $_POST['ldp_action'] === 'update_profile' ) {
        if ( ! isset( $_POST['ldp_profile_nonce'] ) || ! wp_verify_nonce( $_POST['ldp_profile_nonce'], 'ldp_update_profile_action' ) ) {
            $message = 'Security check failed. Please refresh and try again.';
            $message_type = 'error';
        } else {
            $first_name = sanitize_text_field( $_POST['ldp_first_name'] );
            $last_name  = sanitize_text_field( $_POST['ldp_last_name'] );
            $email      = sanitize_email( $_POST['ldp_email'] );
            $phone      = sanitize_text_field( $_POST['ldp_phone'] );

            $userdata = array(
                'ID'         => $user_id,
                'first_name' => $first_name,
                'last_name'  => $last_name,
                'user_email' => $email,
            );

            // Update core user data
            $user_id_updated = wp_update_user( $userdata );

            if ( is_wp_error( $user_id_updated ) ) {
                $message = $user_id_updated->get_error_message();
                $message_type = 'error';
            } else {
                // Update phone number (Stored as user meta)
                update_user_meta( $user_id, 'ldp_phone', $phone );
                
                $message = 'Profile information updated successfully.';
                $message_type = 'success';
                
                // Refresh user object to display new data immediately
                $current_user = wp_get_current_user();
            }
        }
    }

    // --- UPDATE PASSWORD ---
    if ( $_POST['ldp_action'] === 'update_password' ) {
        if ( ! isset( $_POST['ldp_password_nonce'] ) || ! wp_verify_nonce( $_POST['ldp_password_nonce'], 'ldp_update_password_action' ) ) {
            $message = 'Security check failed. Please refresh and try again.';
            $message_type = 'error';
        } else {
            $current_pass = $_POST['ldp_current_pass'];
            $new_pass     = $_POST['ldp_new_pass'];
            $confirm_pass = $_POST['ldp_confirm_pass'];

            if ( empty( $current_pass ) || empty( $new_pass ) || empty( $confirm_pass ) ) {
                $message = 'Please fill out all password fields.';
                $message_type = 'error';
            } elseif ( $new_pass !== $confirm_pass ) {
                $message = 'Your new passwords do not match.';
                $message_type = 'error';
            } elseif ( ! wp_check_password( $current_pass, $current_user->user_pass, $user_id ) ) {
                $message = 'Your current password is incorrect.';
                $message_type = 'error';
            } else {
                // Set the new password
                wp_set_password( $new_pass, $user_id );
                
                // wp_set_password automatically logs the user out. We need to log them back in seamlessly.
                $creds = array(
                    'user_login'    => $current_user->user_login,
                    'user_password' => $new_pass,
                    'remember'      => true
                );
                wp_signon( $creds, false );
                
                $message = 'Password updated successfully.';
                $message_type = 'success';
            }
        }
    }
}

// Fetch current values to pre-fill the form
$current_first_name = $current_user->user_firstname;
$current_last_name  = $current_user->user_lastname;
$current_email      = $current_user->user_email;
$current_phone      = get_user_meta( $user_id, 'ldp_phone', true );
?>

<div class="ldp-dashboard-content">
    
    <?php if ( ! empty( $message ) ) : ?>
        <div class="ldp-alert ldp-alert-<?php echo esc_attr( $message_type ); ?>">
            <?php echo esc_html( $message ); ?>
        </div>
    <?php endif; ?>

    <h1 class="ldp-section-title">Profile Settings</h1>
    
    <div class="ldp-card ldp-card-narrow">
        <form class="ldp-form" action="" method="post">
            <?php wp_nonce_field( 'ldp_update_profile_action', 'ldp_profile_nonce' ); ?>
            <input type="hidden" name="ldp_action" value="update_profile">
            
            <div class="ldp-form-grid">
                <div class="ldp-form-group">
                    <label for="ldp_first_name">First Name</label>
                    <input type="text" id="ldp_first_name" name="ldp_first_name" class="ldp-form-control" value="<?php echo esc_attr( $current_first_name ); ?>" required>
                </div>
                <div class="ldp-form-group">
                    <label for="ldp_last_name">Last Name</label>
                    <input type="text" id="ldp_last_name" name="ldp_last_name" class="ldp-form-control" value="<?php echo esc_attr( $current_last_name ); ?>" required>
                </div>
                <div class="ldp-form-group">
                    <label for="ldp_email">Email</label>
                    <input type="email" id="ldp_email" name="ldp_email" class="ldp-form-control" value="<?php echo esc_attr( $current_email ); ?>" required>
                </div>
                <div class="ldp-form-group">
                    <label for="ldp_phone">Phone</label>
                    <input type="text" id="ldp_phone" name="ldp_phone" class="ldp-form-control" value="<?php echo esc_attr( $current_phone ); ?>">
                </div>
            </div>
            <button type="submit" class="ldp-btn ldp-btn-primary">Save Changes</button>
        </form>
    </div>

    <h1 class="ldp-section-title ldp-mt-40">Change Password</h1>
    
    <div class="ldp-card ldp-card-narrow">
        <form class="ldp-form" action="" method="post">
            <?php wp_nonce_field( 'ldp_update_password_action', 'ldp_password_nonce' ); ?>
            <input type="hidden" name="ldp_action" value="update_password">
            
            <div class="ldp-form-grid">
                <div class="ldp-form-group ldp-col-span-2">
                    <label for="ldp_current_pass">Current Password</label>
                    <input type="password" id="ldp_current_pass" name="ldp_current_pass" class="ldp-form-control" required>
                </div>
                <div class="ldp-form-group">
                    <label for="ldp_new_pass">New Password</label>
                    <input type="password" id="ldp_new_pass" name="ldp_new_pass" class="ldp-form-control" required>
                </div>
                <div class="ldp-form-group">
                    <label for="ldp_confirm_pass">Confirm New Password</label>
                    <input type="password" id="ldp_confirm_pass" name="ldp_confirm_pass" class="ldp-form-control" required>
                </div>
            </div>
            <button type="submit" class="ldp-btn ldp-btn-primary">Update Password</button>
        </form>
    </div>
</div>