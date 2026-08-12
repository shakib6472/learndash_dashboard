<?php
/**
 * Front-end controller.
 *
 * @package Kibworks_Student_Dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the endpoints, the shortcode, the assets and the profile form handling.
 */
class Kibworks_Student_Dashboard_Core {

	const SHORTCODE = 'kibworks_student_dashboard';

	/**
	 * Endpoints added to page URLs, e.g. /dashboard/courses/.
	 *
	 * @var string[]
	 */
	public static $endpoints = array( 'courses', 'certificates', 'profile' );

	/**
	 * Hook everything up.
	 */
	public function init() {
		if ( is_admin() ) {
			require_once KBW_SD_PLUGIN_DIR . 'includes/classes/class-admin.php';
			$admin = new Kibworks_Student_Dashboard_Admin();
			$admin->init();
		}

		add_action( 'init', array( __CLASS__, 'add_endpoints' ) );
		add_filter( 'query_vars', array( $this, 'add_query_vars' ) );
		add_shortcode( self::SHORTCODE, array( $this, 'render_dashboard' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'wp_logout', array( $this, 'handle_logout_redirect' ) );

		/**
		 * Both of these must run before any output is sent, because they set
		 * cookies and send redirects.
		 */
		add_action( 'template_redirect', array( $this, 'handle_unauth_redirect' ) );
		add_action( 'template_redirect', array( $this, 'handle_profile_forms' ) );
	}

	/**
	 * Static so the activator can call it before flushing the rewrite rules.
	 */
	public static function add_endpoints() {
		foreach ( self::$endpoints as $endpoint ) {
			add_rewrite_endpoint( $endpoint, EP_PAGES );
		}
	}

	/**
	 * Register the endpoint query vars.
	 *
	 * @param string[] $vars Existing query vars.
	 * @return string[]
	 */
	public function add_query_vars( $vars ) {
		return array_merge( $vars, self::$endpoints );
	}

	/**
	 * Is the current post the page holding our shortcode?
	 *
	 * @return bool
	 */
	protected function is_dashboard_page() {
		$post = get_post();

		return is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, self::SHORTCODE );
	}

	/**
	 * Which tab the current request is asking for.
	 *
	 * @return string
	 */
	protected function get_active_tab() {
		global $wp_query;

		foreach ( self::$endpoints as $endpoint ) {
			if ( isset( $wp_query->query_vars[ $endpoint ] ) ) {
				return $endpoint;
			}
		}

		return 'dashboard';
	}

	/**
	 * Enqueue the front-end assets, only on the page that holds the shortcode.
	 */
	public function enqueue_assets() {
		if ( ! $this->is_dashboard_page() ) {
			return;
		}

		$fonts          = get_option( 'kbw_sd_fonts', array() );
		$font_primary   = kbw_sd_sanitize_font_family( $fonts['font_primary'] ?? '', 'Inter' );
		$font_secondary = kbw_sd_sanitize_font_family( $fonts['font_secondary'] ?? '', 'Outfit' );

		$font_url = add_query_arg(
			array(
				'family'  => rawurlencode( $font_primary ) . ':wght@400;500;600;700&family=' . rawurlencode( $font_secondary ) . ':wght@400;500;600;700',
				'display' => 'swap',
			),
			'https://fonts.googleapis.com/css2'
		);

		wp_enqueue_style( 'kbw-sd-google-fonts', $font_url, array(), KBW_SD_VERSION );
		wp_enqueue_style( 'kbw-sd-style', KBW_SD_PLUGIN_URL . 'assets/style.css', array(), KBW_SD_VERSION );
		wp_enqueue_script( 'kbw-sd-script', KBW_SD_PLUGIN_URL . 'assets/scripts.js', array(), KBW_SD_VERSION, true );

		wp_add_inline_style( 'kbw-sd-style', $this->build_inline_css( $font_primary, $font_secondary ) );
	}

	/**
	 * Build the :root custom properties from the saved colour settings.
	 *
	 * Values that do not sanitize to a usable CSS colour are skipped entirely
	 * rather than emitted empty, which would produce an invalid declaration.
	 *
	 * @param string $font_primary   Sanitized primary font name.
	 * @param string $font_secondary Sanitized secondary font name.
	 * @return string
	 */
	protected function build_inline_css( $font_primary, $font_secondary ) {
		$colors = get_option( 'kbw_sd_colors', array() );
		$css    = ":root {\n";

		if ( is_array( $colors ) ) {
			foreach ( $colors as $key => $value ) {
				$color = kbw_sd_sanitize_css_color( $value );

				if ( '' === $color ) {
					continue;
				}

				$name = preg_replace( '/[^a-z0-9-]/', '', str_replace( '_', '-', strtolower( (string) $key ) ) );

				if ( '' === $name ) {
					continue;
				}

				$css .= '--kbw-sd-' . $name . ': ' . $color . ";\n";
			}
		}

		$css .= "--kbw-sd-font-primary: '" . $font_primary . "', sans-serif;\n";
		$css .= "--kbw-sd-font-secondary: '" . $font_secondary . "', sans-serif;\n";
		$css .= '}';

		return $css;
	}

	/**
	 * Shortcode output.
	 *
	 * @return string
	 */
	public function render_dashboard() {
		if ( ! class_exists( 'SFWD_LMS' ) ) {
			return '<div class="kbw-sd-alert kbw-sd-alert-error">' . esc_html__( 'LearnDash LMS must be installed and activated to use this dashboard.', 'kibworks-student-dashboard-for-learndash' ) . '</div>';
		}

		if ( ! is_user_logged_in() ) {
			ob_start();
			include KBW_SD_PLUGIN_DIR . 'includes/login-prompt.php';

			return $this->tidy_output( ob_get_clean() );
		}

		$active_tab  = $this->get_active_tab();
		$current_url = get_permalink();
		$notice      = $this->get_notice_code();

		ob_start();
		echo '<div class="kbw-sd-dashboard-wrapper">';
		echo '<div class="kbw-sd-sidebar-overlay" id="kbw-sd-overlay"></div>';

		include KBW_SD_PLUGIN_DIR . 'includes/nav.php';

		echo '<main class="kbw-sd-main-content">';
		include KBW_SD_PLUGIN_DIR . 'includes/header.php';

		$file_path = KBW_SD_PLUGIN_DIR . 'includes/' . $active_tab . '.php';
		if ( file_exists( $file_path ) ) {
			include $file_path;
		}

		echo '</main></div>';

		return $this->tidy_output( ob_get_clean() );
	}

	/**
	 * Collapse whitespace so wpautop cannot inject stray paragraphs into the markup.
	 *
	 * @param string $html Rendered markup.
	 * @return string
	 */
	protected function tidy_output( $html ) {
		$html = preg_replace( '/[\r\n\t]+/', ' ', $html );

		return preg_replace( '/>\s+</', '><', $html );
	}

	/**
	 * Read the notice code set by the redirect after a form submission.
	 *
	 * @return string
	 */
	protected function get_notice_code() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only display flag, no action taken.
		if ( empty( $_GET['kbw_sd_notice'] ) ) {
			return '';
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only display flag, no action taken.
		return sanitize_key( wp_unslash( $_GET['kbw_sd_notice'] ) );
	}

	/**
	 * Handle the profile and password forms.
	 *
	 * This runs on template_redirect rather than inside the shortcode: changing a
	 * password destroys the session and signs the user back in, which sets cookies,
	 * and cookies cannot be sent once the page has started rendering.
	 */
	public function handle_profile_forms() {
		if ( ! is_user_logged_in() ) {
			return;
		}

		if ( ! isset( $_SERVER['REQUEST_METHOD'] ) || 'POST' !== strtoupper( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_METHOD'] ) ) ) ) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- the nonce is checked per action below.
		if ( empty( $_POST['kbw_sd_action'] ) ) {
			return;
		}

		if ( ! $this->is_dashboard_page() ) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- the nonce is checked per action below.
		$action  = sanitize_key( wp_unslash( $_POST['kbw_sd_action'] ) );
		$user_id = get_current_user_id();

		if ( 'update_profile' === $action ) {
			$notice = $this->process_profile_update( $user_id );
		} elseif ( 'update_password' === $action ) {
			$notice = $this->process_password_update( $user_id );
		} else {
			return;
		}

		$redirect = add_query_arg(
			'kbw_sd_notice',
			$notice,
			trailingslashit( get_permalink() ) . 'profile/'
		);

		wp_safe_redirect( $redirect );
		exit;
	}

	/**
	 * Save the profile fields.
	 *
	 * @param int $user_id Current user.
	 * @return string Notice code.
	 */
	protected function process_profile_update( $user_id ) {
		$nonce = isset( $_POST['kbw_sd_profile_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['kbw_sd_profile_nonce'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'kbw_sd_update_profile' ) ) {
			return 'nonce_failed';
		}

		$result = wp_update_user(
			array(
				'ID'         => $user_id,
				'first_name' => isset( $_POST['kbw_sd_first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['kbw_sd_first_name'] ) ) : '',
				'last_name'  => isset( $_POST['kbw_sd_last_name'] ) ? sanitize_text_field( wp_unslash( $_POST['kbw_sd_last_name'] ) ) : '',
				'user_email' => isset( $_POST['kbw_sd_email'] ) ? sanitize_email( wp_unslash( $_POST['kbw_sd_email'] ) ) : '',
			)
		);

		if ( is_wp_error( $result ) ) {
			$code = $result->get_error_code();

			if ( 'existing_user_email' === $code ) {
				return 'email_taken';
			}

			if ( 'invalid_email' === $code ) {
				return 'email_invalid';
			}

			return 'profile_failed';
		}

		$phone = isset( $_POST['kbw_sd_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['kbw_sd_phone'] ) ) : '';
		update_user_meta( $user_id, 'kbw_sd_phone', $phone );

		return 'profile_saved';
	}

	/**
	 * Change the user's password.
	 *
	 * @param int $user_id Current user.
	 * @return string Notice code.
	 */
	protected function process_password_update( $user_id ) {
		$nonce = isset( $_POST['kbw_sd_password_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['kbw_sd_password_nonce'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'kbw_sd_update_password' ) ) {
			return 'nonce_failed';
		}

		/**
		 * Passwords are deliberately neither sanitized nor unslashed.
		 *
		 * WordPress core reads them raw: wp_signon() passes $_POST['pwd'] to
		 * wp_check_password() exactly as it arrives, and edit_user() only trims
		 * $_POST['pass1'] before hashing it. Because wp_magic_quotes() has already
		 * slashed the value, the hash core stores is the hash of the slashed string,
		 * and wp-login.php later checks that same slashed string.
		 *
		 * Applying wp_unslash() here would therefore store the hash of a different
		 * string than the one the login form will present, and any password
		 * containing a quote would stop working after it was changed - the user
		 * would be locked out of their own account. sanitize_text_field() is worse
		 * still: it strips angle brackets and collapses whitespace.
		 *
		 * Backslashes are rejected below for the same reason core rejects them.
		 *
		 * phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
		 */
		$current_pass = isset( $_POST['kbw_sd_current_pass'] ) && is_string( $_POST['kbw_sd_current_pass'] ) ? trim( $_POST['kbw_sd_current_pass'] ) : '';
		$new_pass     = isset( $_POST['kbw_sd_new_pass'] ) && is_string( $_POST['kbw_sd_new_pass'] ) ? trim( $_POST['kbw_sd_new_pass'] ) : '';
		$confirm_pass = isset( $_POST['kbw_sd_confirm_pass'] ) && is_string( $_POST['kbw_sd_confirm_pass'] ) ? trim( $_POST['kbw_sd_confirm_pass'] ) : '';
		// phpcs:enable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash

		if ( '' === $current_pass || '' === $new_pass || '' === $confirm_pass ) {
			return 'pass_empty';
		}

		if ( false !== strpos( wp_unslash( $new_pass ), '\\' ) ) {
			return 'pass_backslash';
		}

		if ( $new_pass !== $confirm_pass ) {
			return 'pass_mismatch';
		}

		$user = get_userdata( $user_id );

		if ( ! $user || ! wp_check_password( $current_pass, $user->user_pass, $user_id ) ) {
			return 'pass_wrong';
		}

		wp_set_password( $new_pass, $user_id );

		/**
		 * wp_set_password() invalidates every session for this user, so sign them
		 * straight back in. Only safe because this runs before any output.
		 */
		wp_signon(
			array(
				'user_login'    => $user->user_login,
				'user_password' => $new_pass,
				'remember'      => true,
			),
			is_ssl()
		);

		return 'pass_changed';
	}

	/**
	 * Redirect after logout when the site owner configured one.
	 */
	public function handle_logout_redirect() {
		$settings = get_option( 'kbw_sd_settings', array() );

		if ( empty( $settings['logout_redirection'] ) ) {
			return;
		}

		$redirect_url = home_url();

		if ( 'url' === ( $settings['logout_redirect_type'] ?? '' ) && ! empty( $settings['logout_redirect_url'] ) ) {
			$redirect_url = $settings['logout_redirect_url'];
		} elseif ( 'page' === ( $settings['logout_redirect_type'] ?? '' ) && ! empty( $settings['logout_redirect_page'] ) ) {
			$redirect_url = get_permalink( (int) $settings['logout_redirect_page'] );
		}

		wp_safe_redirect( $redirect_url ? $redirect_url : home_url() );
		exit;
	}

	/**
	 * Send logged-out visitors elsewhere when the site owner configured that.
	 */
	public function handle_unauth_redirect() {
		if ( is_user_logged_in() || ! $this->is_dashboard_page() ) {
			return;
		}

		$settings = get_option( 'kbw_sd_settings', array() );
		$action   = $settings['unauth_action'] ?? 'form';

		if ( 'url' === $action && ! empty( $settings['unauth_redirect_url'] ) ) {
			wp_safe_redirect( $settings['unauth_redirect_url'] );
			exit;
		}

		if ( 'page' === $action && ! empty( $settings['unauth_redirect_page'] ) ) {
			$url = get_permalink( (int) $settings['unauth_redirect_page'] );

			if ( $url ) {
				wp_safe_redirect( $url );
				exit;
			}
		}
	}
}
