<?php
if (!defined('ABSPATH'))
    exit;

$colors = get_option('learndash_premium_dashboard_colors', array());
$fonts = get_option('learndash_premium_dashboard_fonts', array());
$settings = get_option('learndash_premium_dashboard_settings', array());

$available_fonts = array('Inter', 'Outfit', 'Poppins', 'Roboto', 'Open Sans', 'Montserrat', 'Lato', 'Rubik');
?>

<div class="wrap ldp-admin-wrap">
    <div class="ldp-admin-hero">
        <div>
            <p class="ldp-admin-eyebrow">LearnDash Premium Dashboard</p>
            <h1>Dashboard Settings</h1>
            <p>Adjust branding, typography, and tab visibility from one centralized hub.</p>
        </div>
    </div>
    <!-- Buttons -->
    <form method="post" action="options.php" class="ldp-admin-form">
        <?php settings_fields('ldp_settings_group'); ?>

        <div class="ldp-tabs-nav">
            <button type="button" class="ldp-tab-btn active" data-tab="tab-colors">Colors & Branding</button>
            <button type="button" class="ldp-tab-btn" data-tab="tab-fonts">Typography</button>
            <button type="button" class="ldp-tab-btn" data-tab="tab-advanced">Advanced Settings</button>
            <button type="button" class="ldp-tab-btn" data-tab="tab-shortcode">Shortcode</button>
            <button type="button" class="ldp-tab-btn" data-tab="tab-instructions">Instructions</button>
        </div>
        <!-- Colors & Branding -->
        <div id="tab-colors" class="ldp-tab-content active">
            <section class="ldp-admin-card">
                <div class="ldp-card-head">
                    <h2>Color Palette</h2>
                    <p class="ldp-card-note">Select your brand colors. The hex value is displayed next to the picker so
                        you can type or paste transparency codes (e.g. rgba(0,0,0,0.5)) manually if needed.</p>
                </div>

                <div class="ldp-field-grid">
                    <?php
                    $color_fields = array(
                        'primary' => 'Primary Brand Color (Buttons & Active States)',
                        'primary_hover' => 'Primary Hover Color',
                        'bg_main' => 'Main Dashboard Background',
                        'bg_card' => 'Card & Widget Background',
                        'text_main' => 'Main Body Text',
                        'heading_text' => 'Heading Text (H1-H6)',
                        'text_dim' => 'Dimmed/Secondary Text',
                        'border_color' => 'Global Border Color',
                        'input_bg' => 'Form Input Background',
                        'sidebar_background' => 'Sidebar Background',
                        'sidebar_item_background' => 'Sidebar Menu Item Default Background',
                        'sidebar_item_text' => 'Sidebar Menu Item Text',
                        'sidebar_item_hover_background' => 'Sidebar Menu Item Hover Background',
                        'sidebar_item_hover_text' => 'Sidebar Menu Item Hover Text',
                    );

                    foreach ($color_fields as $key => $label) {
                        $value = isset($colors[$key]) ? esc_attr($colors[$key]) : '';
                        echo '<div class="ldp-color-field-group">';
                        echo '<label for="color_' . $key . '">' . esc_html($label) . '</label>';
                        echo '<div class="ldp-color-input-wrap">';
                        echo '<input type="text" id="color_' . $key . '" name="learndash_premium_dashboard_colors[' . $key . ']" value="' . $value . '" class="ldp-color-picker-input" />';
                        echo '</div>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </section>
        </div>

        <!-- Typography -->
        <div id="tab-fonts" class="ldp-tab-content">
            <section class="ldp-admin-card">
                <div class="ldp-card-head">
                    <h2>Typography Settings</h2>
                    <p class="ldp-card-note">Select the Google Fonts to load for the dashboard.</p>
                </div>
                <div class="ldp-field-grid">
                    <div class="ldp-field">
                        <label for="font_primary">Primary Body Font</label>
                        <select id="font_primary" name="learndash_premium_dashboard_fonts[font_primary]">
                            <?php foreach ($available_fonts as $font): ?>
                                <option value="<?php echo esc_attr($font); ?>" <?php selected($fonts['font_primary'] ?? 'Inter', $font); ?>><?php echo esc_html($font); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="ldp-field">
                        <label for="font_secondary">Secondary Heading Font</label>
                        <select id="font_secondary" name="learndash_premium_dashboard_fonts[font_secondary]">
                            <?php foreach ($available_fonts as $font): ?>
                                <option value="<?php echo esc_attr($font); ?>" <?php selected($fonts['font_secondary'] ?? 'Outfit', $font); ?>><?php echo esc_html($font); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </section>
        </div>

        <!-- Advanced Settings -->
        <div id="tab-advanced" class="ldp-tab-content">
            <section class="ldp-admin-card">
                <div class="ldp-card-head">
                    <h2>Brand Logo</h2>
                    <p class="ldp-card-note">Upload or link your dashboard logo. Recommended size: <strong>160x40
                            pixels</strong>.</p>
                </div>
                <div class="ldp-field-grid">
                    <div class="ldp-field ldp-full-width">
                        <label>Logo Image</label>
                        <div class="ldp-media-uploader-wrap">
                            <div class="ldp-logo-preview" id="ldp_logo_preview">
                                <?php if (!empty($settings['logo_url'])): ?>
                                    <img src="<?php echo esc_url($settings['logo_url']); ?>" alt="Logo Preview" />
                                <?php endif; ?>
                            </div>
                            <div class="ldp-media-actions">
                                <input type="text" id="logo_url" name="learndash_premium_dashboard_settings[logo_url]"
                                    value="<?php echo esc_attr($settings['logo_url'] ?? ''); ?>"
                                    class="ldp-readonly-input" readonly placeholder="No image selected" />
                                <div class="ldp-media-btns">
                                    <button type="button" class="button button-secondary"
                                        id="ldp_upload_logo_btn">Select Image</button>
                                    <button type="button"
                                        class="button button-link-delete <?php echo empty($settings['logo_url']) ? 'hidden' : ''; ?>"
                                        id="ldp_remove_logo_btn">Remove</button>
                                </div>
                            </div>
                        </div>
                        <p class="description">Select your logo from the WordPress Media Library.</p>
                    </div>

                    <div class="ldp-field">
                        <label for="logo_width">Logo Width</label>
                        <input type="text" id="logo_width" name="learndash_premium_dashboard_settings[logo_width]"
                            value="<?php echo esc_attr($settings['logo_width'] ?? '150'); ?>"
                            placeholder="e.g. 150 or 100%" />
                        <p class="description">Enter numeric value (px) or percentage.</p>
                    </div>

                    <div class="ldp-field">
                        <label for="logo_height">Logo Height</label>
                        <input type="text" id="logo_height" name="learndash_premium_dashboard_settings[logo_height]"
                            value="<?php echo esc_attr($settings['logo_height'] ?? 'auto'); ?>"
                            placeholder="e.g. 40 or auto" />
                        <p class="description">Usually best left as "auto" to maintain aspect ratio.</p>
                    </div>
                </div>
            </section>
            <section class="ldp-admin-card mt-20">
                <div class="ldp-card-head">
                    <h2>Tab Visibility</h2>
                    <p class="ldp-card-note">Toggle which sections are available to students.</p>
                </div>
                <div class="ldp-field-grid">
                    <label class="ldp-switch">
                        <input type="checkbox" name="learndash_premium_dashboard_settings[show_courses]" value="1" <?php checked($settings['show_courses'] ?? '0', '1'); ?>>
                        <span>Show My Courses Tab</span>
                    </label>
                    <label class="ldp-switch">
                        <input type="checkbox" name="learndash_premium_dashboard_settings[show_certificates]" value="1"
                            <?php checked($settings['show_certificates'] ?? '0', '1'); ?>>
                        <span>Show Certificates Tab</span>
                    </label>
                    <label class="ldp-switch">
                        <input type="checkbox" name="learndash_premium_dashboard_settings[show_profile]" value="1" <?php checked($settings['show_profile'] ?? '0', '1'); ?>>
                        <span>Show Profile Settings Tab</span>
                    </label>
                </div>
            </section>

            <section class="ldp-admin-card mt-20">
                <div class="ldp-card-head">
                    <h2>Unauthorized Access</h2>
                    <p class="ldp-card-note">Choose what happens when a logged-out user tries to view the dashboard.</p>
                </div>
                <div class="ldp-field-grid">
                    <div class="ldp-field ldp-full-width">
                        <label for="unauth_action">Action for Logged-Out Users</label>
                        <select id="unauth_action" name="learndash_premium_dashboard_settings[unauth_action]">
                            <option value="form" <?php selected($settings['unauth_action'] ?? 'form', 'form'); ?>>Show
                                Login Form in Dashboard</option>
                            <option value="page" <?php selected($settings['unauth_action'] ?? 'form', 'page'); ?>>
                                Select a Page</option>
                            <option value="url" <?php selected($settings['unauth_action'] ?? 'form', 'url'); ?>>
                                Redirect to Custom URL</option>
                        </select>
                    </div>

                    <div class="ldp-field ldp-full-width <?php echo (($settings['unauth_action'] ?? 'form') !== 'page') ? 'hidden' : ''; ?>"
                        id="wrap_unauth_page">
                        <label>Select Login Page</label>
                        <?php
                        wp_dropdown_pages(array(
                            'name' => 'learndash_premium_dashboard_settings[unauth_redirect_page]',
                            'show_option_none' => '-- Select a Page --',
                            'option_none_value' => '',
                            'selected' => $settings['unauth_redirect_page'] ?? ''
                        ));
                        ?>
                    </div>

                    <div class="ldp-field ldp-full-width <?php echo (($settings['unauth_action'] ?? 'form') !== 'url') ? 'hidden' : ''; ?>"
                        id="wrap_unauth_url">
                        <label for="unauth_redirect_url">Custom Redirect URL</label>
                        <input type="url" id="unauth_redirect_url"
                            name="learndash_premium_dashboard_settings[unauth_redirect_url]"
                            value="<?php echo esc_attr($settings['unauth_redirect_url'] ?? ''); ?>"
                            placeholder="https://yoursite.com/login" />
                    </div>
                </div>
            </section>

            <section class="ldp-admin-card mt-20">
                <div class="ldp-card-head">
                    <h2>Registration Link</h2>
                    <p class="ldp-card-note">If "Anyone can register" is enabled in WordPress, where should the "Create
                        one" link point?</p>
                </div>
                <div class="ldp-field-grid">
                    <div class="ldp-field ldp-full-width">
                        <label for="register_link_type">Registration Page Location</label>
                        <select id="register_link_type" name="learndash_premium_dashboard_settings[register_link_type]">
                            <option value="default" <?php selected($settings['register_link_type'] ?? 'default', 'default'); ?>>Default WP Registration Page</option>
                            <option value="page" <?php selected($settings['register_link_type'] ?? 'default', 'page'); ?>>Select a Page</option>
                            <option value="url" <?php selected($settings['register_link_type'] ?? 'default', 'url'); ?>>
                                Custom URL</option>
                        </select>
                    </div>

                    <div class="ldp-field ldp-full-width <?php echo (($settings['register_link_type'] ?? 'default') !== 'page') ? 'hidden' : ''; ?>"
                        id="wrap_register_page">
                        <label>Select Registration Page</label>
                        <?php
                        wp_dropdown_pages(array(
                            'name' => 'learndash_premium_dashboard_settings[register_redirect_page]',
                            'show_option_none' => '-- Select a Page --',
                            'option_none_value' => '',
                            'selected' => $settings['register_redirect_page'] ?? ''
                        ));
                        ?>
                    </div>

                    <div class="ldp-field ldp-full-width <?php echo (($settings['register_link_type'] ?? 'default') !== 'url') ? 'hidden' : ''; ?>"
                        id="wrap_register_url">
                        <label for="register_redirect_url">Custom Registration URL</label>
                        <input type="url" id="register_redirect_url"
                            name="learndash_premium_dashboard_settings[register_redirect_url]"
                            value="<?php echo esc_attr($settings['register_redirect_url'] ?? ''); ?>"
                            placeholder="https://yoursite.com/register" />
                    </div>
                </div>
            </section>

            <section class="ldp-admin-card mt-20">
                <div class="ldp-card-head">
                    <h2>Logout Redirection</h2>
                    <p class="ldp-card-note">Redirect users to a specific page or URL after logging out.</p>
                </div>
                <div class="ldp-field-grid">
                    <label class="ldp-switch ldp-full-width">
                        <input type="checkbox" id="toggle_logout_redirect"
                            name="learndash_premium_dashboard_settings[logout_redirection]" value="1" <?php checked($settings['logout_redirection'] ?? '0', '1'); ?>>
                        <span>Enable Logout Redirection</span>
                    </label>

                    <div id="logout_redirect_options"
                        class="ldp-conditional-options <?php echo empty($settings['logout_redirection']) ? 'hidden' : ''; ?>">
                        <div class="ldp-field">
                            <label>Redirect Type</label>
                            <select id="logout_redirect_type"
                                name="learndash_premium_dashboard_settings[logout_redirect_type]">
                                <option value="page" <?php selected($settings['logout_redirect_type'] ?? 'page', 'page'); ?>>Select an existing page</option>
                                <option value="url" <?php selected($settings['logout_redirect_type'] ?? 'page', 'url'); ?>>Custom URL</option>
                            </select>
                        </div>

                        <div class="ldp-field" id="wrap_redirect_page">
                            <label>Select Page</label>
                            <?php
                            wp_dropdown_pages(array(
                                'name' => 'learndash_premium_dashboard_settings[logout_redirect_page]',
                                'show_option_none' => '-- Select a Page --',
                                'option_none_value' => '',
                                'selected' => $settings['logout_redirect_page'] ?? ''
                            ));
                            ?>
                        </div>

                        <div class="ldp-field" id="wrap_redirect_url">
                            <label>Custom URL</label>
                            <input type="url" name="learndash_premium_dashboard_settings[logout_redirect_url]"
                                value="<?php echo esc_attr($settings['logout_redirect_url'] ?? ''); ?>"
                                placeholder="https://..." />
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <!-- Shortcode -->
        <div id="tab-shortcode" class="ldp-tab-content">
            <section class="ldp-admin-card">
                <div class="ldp-card-head">
                    <h2>Dashboard Shortcode</h2>
                    <p class="ldp-card-note">Copy and paste this shortcode onto any WordPress page to render the premium
                        dashboard.</p>
                    <p class="ldp-card-note"><strong>Pro Tips:</strong> Use Elementor Canvas Page template to have best output. Or Try full screen mode. Without any Header & Footer and 100% width.</p>
                </div>

                <div
                    style="background: #f9fafb; padding: 24px; border-radius: 8px; border: 2px dashed #cbd5e1; display: flex; justify-content: space-between; align-items: center; margin-top: 10px;">
                    <code id="ldp_shortcode_text"
                        style="font-size: 20px; font-weight: 700; color: #4f46e5; background: none; padding: 0;">[learndash_premium_dashboard]</code>
                    <button type="button" class="button button-primary" id="ldp_copy_shortcode_btn"
                        style="display: flex; align-items: center; gap: 8px;">
                        <span class="dashicons dashicons-clipboard"></span> Copy Shortcode
                    </button>
                </div>
                <p id="ldp_copy_success"
                    style="color: #059669; font-weight: 600; display: none; margin-top: 10px; text-align: right;">
                    Shortcode copied to clipboard!</p>
            </section>
        </div>

        <!-- Instructions -->

        <div id="tab-instructions" class="ldp-tab-content">
            <section class="ldp-admin-card">
                <div class="ldp-card-head">
                    <h2>How to Use This Plugin</h2>
                    <p class="ldp-card-note">A quick guide to setting up and managing your LearnDash Premium Dashboard.
                    </p>
                </div>

                <div style="line-height: 1.6; color: #374151;">
                    <h3 style="margin-top: 0;">1. Displaying the Dashboard</h3>
                    <p>To display the dashboard to your students, simply create a new WordPress Page (e.g., "Student
                        Portal") and paste the shortcode <code>[learndash_premium_dashboard]</code> into the content
                        area. The dashboard will automatically handle the routing for courses, certificates, and profile
                        settings.</p>

                    <h3 style="margin-top: 24px;">2. Branding & Colors</h3>
                    <p>Navigate to the <strong>Colors & Branding</strong> tab to match the dashboard to your theme.
                        Ensure you set a <strong>Primary Brand Color</strong>, as this controls all the buttons, active
                        links, and progress bars throughout the user interface.</p>

                    <h3 style="margin-top: 24px;">3. Controlling Access</h3>
                    <p>Under the <strong>Advanced Settings</strong> tab, you can control what happens if a logged-out
                        user tries to access the dashboard page. You can choose to display an elegant login form
                        directly on the page, or seamlessly redirect them to a custom sales page or login portal.</p>

                    <h3 style="margin-top: 24px;">4. Certificate Management</h3>
                    <p>The "Certificates" tab on the frontend will automatically populate with any courses that have a
                        certificate attached to them in the LearnDash course settings. Users will see a lock icon until
                        they reach 100% completion, at which point the download link will unlock.</p>

                    <hr style="margin: 30px 0; border: 0; border-top: 1px solid #e5e7eb;">

                    <h3 style="margin-top: 0;">Need Support?</h3>
                    <p>For issues, feature requests, or custom development inquiries, please contact
                        <strong>shakib6472@hotmail.com</strong> or visit <a href="https://github.com/shakib6472/"
                            target="_blank">https://github.com/shakib6472/</a>.
                    </p>
                </div>
            </section>
        </div>

        <div class="ldp-admin-footer">
            <?php submit_button('Save Settings', 'primary', 'submit', false); ?>
        </div>
    </form>
</div>