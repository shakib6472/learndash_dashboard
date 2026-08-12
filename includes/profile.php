<?php
/**
 * Profile tab.
 *
 * Display only. The forms are processed in Kibworks_Student_Dashboard_Core on
 * template_redirect, before any output, and this template is reached through a
 * redirect carrying a notice code.
 *
 * @package Kibworks_Student_Dashboard
 *
 * @var string $notice Notice code passed in from the core class.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$kbw_sd_user_id      = get_current_user_id();
$kbw_sd_current_user = wp_get_current_user();

$kbw_sd_notices = array(
	'profile_saved'  => array( 'success', __( 'Profile information updated successfully.', 'kibworks-student-dashboard-for-learndash' ) ),
	'pass_changed'   => array( 'success', __( 'Password updated successfully.', 'kibworks-student-dashboard-for-learndash' ) ),
	'nonce_failed'   => array( 'error', __( 'Security check failed. Please refresh the page and try again.', 'kibworks-student-dashboard-for-learndash' ) ),
	'profile_failed' => array( 'error', __( 'Your profile could not be updated. Please try again.', 'kibworks-student-dashboard-for-learndash' ) ),
	'email_taken'    => array( 'error', __( 'That email address is already used by another account.', 'kibworks-student-dashboard-for-learndash' ) ),
	'email_invalid'  => array( 'error', __( 'Please enter a valid email address.', 'kibworks-student-dashboard-for-learndash' ) ),
	'pass_empty'     => array( 'error', __( 'Please fill out all password fields.', 'kibworks-student-dashboard-for-learndash' ) ),
	'pass_mismatch'  => array( 'error', __( 'Your new passwords do not match.', 'kibworks-student-dashboard-for-learndash' ) ),
	'pass_wrong'     => array( 'error', __( 'Your current password is incorrect.', 'kibworks-student-dashboard-for-learndash' ) ),
	'pass_backslash' => array( 'error', __( 'Passwords may not contain the backslash character.', 'kibworks-student-dashboard-for-learndash' ) ),
);

$kbw_sd_notice_code = isset( $notice ) ? $notice : '';
$kbw_sd_notice      = isset( $kbw_sd_notices[ $kbw_sd_notice_code ] ) ? $kbw_sd_notices[ $kbw_sd_notice_code ] : null;

$kbw_sd_first_name = $kbw_sd_current_user->user_firstname;
$kbw_sd_last_name  = $kbw_sd_current_user->user_lastname;
$kbw_sd_email      = $kbw_sd_current_user->user_email;
$kbw_sd_phone      = get_user_meta( $kbw_sd_user_id, 'kbw_sd_phone', true );
?>

<div class="kbw-sd-dashboard-content">

	<?php if ( $kbw_sd_notice ) : ?>
		<div class="kbw-sd-alert kbw-sd-alert-<?php echo esc_attr( $kbw_sd_notice[0] ); ?>">
			<?php echo esc_html( $kbw_sd_notice[1] ); ?>
		</div>
	<?php endif; ?>

	<h1 class="kbw-sd-section-title"><?php echo esc_html__( 'Profile Settings', 'kibworks-student-dashboard-for-learndash' ); ?></h1>

	<div class="kbw-sd-card kbw-sd-card-narrow">
		<form class="kbw-sd-form" method="post">
			<?php wp_nonce_field( 'kbw_sd_update_profile', 'kbw_sd_profile_nonce' ); ?>
			<input type="hidden" name="kbw_sd_action" value="update_profile">

			<div class="kbw-sd-form-grid">
				<div class="kbw-sd-form-group">
					<label for="kbw_sd_first_name"><?php echo esc_html__( 'First Name', 'kibworks-student-dashboard-for-learndash' ); ?></label>
					<input type="text" id="kbw_sd_first_name" name="kbw_sd_first_name" class="kbw-sd-form-control" value="<?php echo esc_attr( $kbw_sd_first_name ); ?>" required>
				</div>
				<div class="kbw-sd-form-group">
					<label for="kbw_sd_last_name"><?php echo esc_html__( 'Last Name', 'kibworks-student-dashboard-for-learndash' ); ?></label>
					<input type="text" id="kbw_sd_last_name" name="kbw_sd_last_name" class="kbw-sd-form-control" value="<?php echo esc_attr( $kbw_sd_last_name ); ?>" required>
				</div>
				<div class="kbw-sd-form-group">
					<label for="kbw_sd_email"><?php echo esc_html__( 'Email', 'kibworks-student-dashboard-for-learndash' ); ?></label>
					<input type="email" id="kbw_sd_email" name="kbw_sd_email" class="kbw-sd-form-control" value="<?php echo esc_attr( $kbw_sd_email ); ?>" required>
				</div>
				<div class="kbw-sd-form-group">
					<label for="kbw_sd_phone"><?php echo esc_html__( 'Phone', 'kibworks-student-dashboard-for-learndash' ); ?></label>
					<input type="text" id="kbw_sd_phone" name="kbw_sd_phone" class="kbw-sd-form-control" value="<?php echo esc_attr( $kbw_sd_phone ); ?>">
				</div>
			</div>
			<button type="submit" class="kbw-sd-btn kbw-sd-btn-primary"><?php echo esc_html__( 'Save Changes', 'kibworks-student-dashboard-for-learndash' ); ?></button>
		</form>
	</div>

	<h1 class="kbw-sd-section-title kbw-sd-mt-40"><?php echo esc_html__( 'Change Password', 'kibworks-student-dashboard-for-learndash' ); ?></h1>

	<div class="kbw-sd-card kbw-sd-card-narrow">
		<form class="kbw-sd-form" method="post">
			<?php wp_nonce_field( 'kbw_sd_update_password', 'kbw_sd_password_nonce' ); ?>
			<input type="hidden" name="kbw_sd_action" value="update_password">

			<div class="kbw-sd-form-grid">
				<div class="kbw-sd-form-group kbw-sd-col-span-2">
					<label for="kbw_sd_current_pass"><?php echo esc_html__( 'Current Password', 'kibworks-student-dashboard-for-learndash' ); ?></label>
					<input type="password" id="kbw_sd_current_pass" name="kbw_sd_current_pass" class="kbw-sd-form-control" autocomplete="current-password" required>
				</div>
				<div class="kbw-sd-form-group">
					<label for="kbw_sd_new_pass"><?php echo esc_html__( 'New Password', 'kibworks-student-dashboard-for-learndash' ); ?></label>
					<input type="password" id="kbw_sd_new_pass" name="kbw_sd_new_pass" class="kbw-sd-form-control" autocomplete="new-password" required>
				</div>
				<div class="kbw-sd-form-group">
					<label for="kbw_sd_confirm_pass"><?php echo esc_html__( 'Confirm New Password', 'kibworks-student-dashboard-for-learndash' ); ?></label>
					<input type="password" id="kbw_sd_confirm_pass" name="kbw_sd_confirm_pass" class="kbw-sd-form-control" autocomplete="new-password" required>
				</div>
			</div>
			<button type="submit" class="kbw-sd-btn kbw-sd-btn-primary"><?php echo esc_html__( 'Update Password', 'kibworks-student-dashboard-for-learndash' ); ?></button>
		</form>
	</div>
</div>
