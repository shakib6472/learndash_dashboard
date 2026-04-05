# LearnDash Premium Dashboard

A premium, modern, and highly customizable frontend dashboard plugin for LearnDash LMS. Designed to enhance the student experience, it provides a distraction-free environment for course management, profile updates, and certificate downloads.

**Version:** 1.0.0  
**Requires PHP:** 7.2 or higher  
**Requires WP:** 5.2 or higher  
**Author:** Shakib Shown  

## Features

* **White-Label Branding:** Completely customize the dashboard to match your brand. Upload your own logo, select custom primary and background colors, and choose from a curated list of Google Fonts directly from the WordPress admin panel.
* **Smart Resume:** The "My Courses" tab intelligently tracks user progress. If a user clicks "Resume", they are taken directly to the exact lesson or topic they left off on, rather than the course overview page.
* **Dynamic Statistics:** The main dashboard calculates real-time metrics, including total enrolled courses, total steps completed, certificates earned, and the average completion rate across all courses.
* **Certificate Management:** A dedicated tab that lists all courses offering certificates. It clearly displays locked certificates for unfinished courses and provides a direct PDF download link for earned certificates.
* **Frontend Profile Management:** Students can update their first name, last name, email, phone number, and securely change their password without ever seeing the default WordPress backend.
* **Access Control & Redirection:** * Restrict dashboard access to logged-in users only.
    * Choose to show a native login form, redirect to a specific WordPress page, or redirect to a custom URL for unauthorized users.
    * Set custom logout redirection paths.
    * Set custom registration link paths.
* **Clean URL Structure:** Utilizes WordPress rewrite endpoints for clean, professional URLs (e.g., `yoursite.com/dashboard/courses/`).

## Installation

1. Ensure that **LearnDash LMS** is installed and activated on your WordPress site.
2. Download the plugin folder or zip file.
3. Upload the `learndash_premium_dashboard` folder to the `/wp-content/plugins/` directory, or upload the zip file via the **Plugins > Add New** menu in WordPress.
4. Activate the plugin through the 'Plugins' menu in WordPress.

## Usage

1. Create a new page in WordPress (e.g., "Dashboard" or "Student Portal").
2. Add the following shortcode to the page content:
   `[learndash_premium_dashboard]`
3. Publish the page.
4. Navigate to **LearnDash LMS > Dashboard Modify** in the WordPress admin menu to configure your branding, tab visibility, and redirection settings.

## Admin Settings Overview

The plugin adds a new settings panel under the LearnDash LMS menu with three main tabs:

### 1. Colors & Branding
* Set Primary and Hover colors for buttons and active states.
* Customize Backgrounds for the main dashboard, cards, and sidebar.
* Adjust Text Colors for headings, body text, and muted text.

### 2. Typography
* Select a Primary Body Font and Secondary Heading Font from popular Google Fonts (Inter, Outfit, Poppins, Roboto, etc.).

### 3. Advanced Settings
* **Brand Logo:** Upload a custom logo to replace the default text in the sidebar.
* **Tab Visibility:** Toggle the visibility of the "My Courses", "Certificates", and "Profile" tabs.
* **Unauthorized Access:** Define what happens when a logged-out user tries to access the dashboard page.
* **Registration Link:** Control where the "Create one" link points on the login form.
* **Logout Redirection:** Specify where users are sent after clicking Logout in the dashboard sidebar.

## Support

For issues, feature requests, or custom development inquiries, please contact shakib6472@hotmail.com or visit https://github.com/shakib6472/