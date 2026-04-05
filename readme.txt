=== LearnDash Premium Dashboard ===
Contributors: shakib6472
Tags: learndash, lms, dashboard, student portal, elearning
Requires at least: 5.2
Tested up to: 6.5
Stable tag: 1.0.0
Requires PHP: 7.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A premium dashboard plugin for LearnDash LMS, designed to enhance the user experience and provide advanced features for course and certificate management.

== Description ==

LearnDash Premium Dashboard is a modern and highly customizable frontend dashboard plugin for LearnDash LMS. Designed to enhance the student experience, it provides a distraction-free environment for course management, profile updates, and certificate downloads.

= Features =

* White-Label Branding: Completely customize the dashboard to match your brand. Upload your own logo, select custom primary and background colors, and choose from a curated list of Google Fonts directly from the WordPress admin panel.
* Smart Resume: The "My Courses" tab intelligently tracks user progress. If a user clicks "Resume", they are taken directly to the exact lesson or topic they left off on, rather than the course overview page.
* Dynamic Statistics: The main dashboard calculates real-time metrics, including total enrolled courses, total steps completed, certificates earned, and the average completion rate across all courses.
* Certificate Management: A dedicated tab that lists all courses offering certificates. It clearly displays locked certificates for unfinished courses and provides a direct PDF download link for earned certificates.
* Frontend Profile Management: Students can update their first name, last name, email, phone number, and securely change their password without ever seeing the default WordPress backend.
* Access Control & Redirection: Restrict dashboard access to logged-in users only. Choose to show a native login form, redirect to a specific WordPress page, or redirect to a custom URL for unauthorized users.
* Clean URL Structure: Utilizes WordPress rewrite endpoints for clean, professional URLs.

== Installation ==

1. Ensure that LearnDash LMS is installed and activated on your WordPress site.
2. Upload the plugin files to the `/wp-content/plugins/learndash-premium-dashboard` directory, or install the plugin through the WordPress plugins screen directly.
3. Activate the plugin through the 'Plugins' screen in WordPress.
4. Create a new page in WordPress (e.g., "Student Portal").
5. Add the shortcode `[learndash_premium_dashboard]` to the page content.
6. Navigate to LearnDash LMS > Dashboard Modify to configure your branding and settings.

== Frequently Asked Questions ==

= Where do I find the settings? =
After activation, a new menu item called "Dashboard Modify" will appear under the main LearnDash LMS menu in your WordPress admin dashboard.

= Does this require LearnDash to work? =
Yes, this plugin is an add-on for LearnDash LMS and requires the core LearnDash plugin to be active.

== Screenshots ==

1. The main dashboard view showing active courses and statistics.
2. The admin settings panel for customizing colors and typography.

== Changelog ==

= 1.0.0 =
* Initial release.