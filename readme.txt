=== Kibworks Student Dashboard for LearnDash ===
Contributors: shakib6472
Tags: learndash, dashboard, lms, student profile, course progress
Requires at least: 6.0
Tested up to: 7.0
Requires PHP: 7.2
Stable tag: 1.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A front-end student dashboard for LearnDash: course progress, certificates and profile settings on one page, added with a single shortcode.

== Description ==

Kibworks Student Dashboard gives LearnDash students one place to see everything about their learning, without sending them into wp-admin.

Add the shortcode `[kibworks_student_dashboard]` to any page and students get a sidebar dashboard with four sections:

* **Dashboard** — enrolled course count, steps completed, certificates earned, average completion rate, and a "continue learning" list.
* **My Courses** — every enrolled course with its progress bar and a resume button that links to the exact lesson or topic they left off at.
* **Certificates** — earned certificates ready to download, and locked ones with a link back into the course.
* **Profile** — first name, last name, email, phone and password, editable from the front end.

The sections use clean URLs off the page you place the shortcode on, for example `/dashboard/courses/` and `/dashboard/profile/`.

= Styling =

Every colour is a CSS custom property you can set from the settings screen, along with the two Google Fonts used, your logo, and which tabs are visible. Nothing is hard-coded, so the dashboard can be made to match your theme.

= Requirements =

LearnDash must be installed and active. If it is not, the shortcode renders a notice instead of the dashboard.

= Third-party services =

This plugin loads the two selected fonts from Google Fonts (https://fonts.googleapis.com) so the dashboard renders with your chosen typography. No data other than the standard font request is sent. Google's terms are at https://policies.google.com/terms and its privacy policy at https://policies.google.com/privacy. No other external service is contacted, and all images and scripts are bundled with the plugin.

= Not affiliated with LearnDash =

This is an independent add-on. LearnDash is a trademark of Liquid Web, LLC, and this plugin is not affiliated with, endorsed by, or sponsored by them.

== Installation ==

1. Install and activate LearnDash.
2. Upload the plugin folder to `/wp-content/plugins/`, or install the zip through Plugins > Add New > Upload Plugin.
3. Activate the plugin.
4. Create a page and add the shortcode `[kibworks_student_dashboard]`.
5. Adjust colours, fonts, logo and tabs under LearnDash LMS > Student Dashboard.

== Frequently Asked Questions ==

= The section URLs give a 404 =

The section URLs are rewrite endpoints, registered when the plugin activates. If they 404, deactivate and reactivate the plugin, or visit Settings > Permalinks and press Save, which rebuilds the rules.

= Can I change what the resume button links to? =

It already links to the first lesson or topic the student has not completed. When everything is done it links to the course itself.

= Can students change their email address? =

Yes, from the Profile tab. An address already used by another account is rejected.

= Does the admin bar have to be hidden for students? =

No. Hiding it is off by default and can be switched on in the settings if you prefer students not to see it.

== Changelog ==

= 1.1.0 =
* Renamed the plugin and all of its internals to the Kibworks namespace. The shortcode is now `[kibworks_student_dashboard]`.
* Fixed a serious bug where passwords were passed through `sanitize_text_field()`. Passwords containing `<`, `>`, tabs or leading and trailing spaces were altered before being saved or checked, which could lock a user out of their own account.
* Fixed profile and password forms being processed during page output, which prevented the login cookie from being set after a password change.
* Fixed the sidebar overlay and other colours never rendering, because `transparent` and `rgba()` values were discarded by the colour sanitizer and produced empty CSS declarations.
* Fixed the section endpoints not being registered on activation, which made `/courses/`, `/certificates/` and `/profile/` return 404 on a fresh install.
* Replaced the remote placeholder image service with a bundled image, so the plugin no longer depends on a third-party host.
* Hiding the admin bar is now an opt-in setting instead of being applied to the whole site automatically.
* Font names and colour values are now sanitized before being written into inline CSS.
* Escaped remaining attribute output and made the admin menu labels translatable.
* Added a translation template in `/languages`.
* Removed an unused duplicate stylesheet.

= 1.0.0 =
* Initial release.

== Upgrade Notice ==

= 1.1.0 =
Important fixes for password changes and course progress display. The shortcode has changed to `[kibworks_student_dashboard]`, so update any page still using the old one.
