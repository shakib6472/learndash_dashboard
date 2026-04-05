jQuery(document).ready(function ($) {

    // 1. Tab Switching Logic
    $('.ldp-tab-btn').on('click', function (e) {
        e.preventDefault();

        // Remove active class from all buttons and content
        $('.ldp-tab-btn').removeClass('active');
        $('.ldp-tab-content').removeClass('active');

        // Add active class to clicked button and target content
        $(this).addClass('active');
        $('#' + $(this).data('tab')).addClass('active');
    });

    // 2. Initialize WP Color Picker
    // We initialize it, but our CSS will force the text input to remain visible
    if ($('.ldp-color-picker-input').length) {
        $('.ldp-color-picker-input').wpColorPicker({
            // The default WP color picker doesn't natively support RGBA visual picking, 
            // but by keeping the text field visible, users can paste RGBA codes directly.
            change: function (event, ui) {
                // You could add custom live-preview logic here in the future
            },
            clear: function () { }
        });
    }

    // 3. Conditional Logic: Logout Redirection
    const $toggleRedirect = $('#toggle_logout_redirect');
    const $redirectOptions = $('#logout_redirect_options');
    const $redirectType = $('#logout_redirect_type');
    const $wrapPage = $('#wrap_redirect_page');
    const $wrapUrl = $('#wrap_redirect_url');

    // 4. Conditional Logic: Unauthorized Access
    const $unauthAction = $('#unauth_action');
    const $wrapUnauthPage = $('#wrap_unauth_page');
    const $wrapUnauthUrl = $('#wrap_unauth_url');

    $unauthAction.on('change', function () {
        $wrapUnauthPage.addClass('ldp-hidden').hide();
        $wrapUnauthUrl.addClass('ldp-hidden').hide();

        if ($(this).val() === 'page') {
            $wrapUnauthPage.removeClass('ldp-hidden').slideDown(200);
        } else if ($(this).val() === 'url') {
            $wrapUnauthUrl.removeClass('ldp-hidden').slideDown(200);
        }
    });

    // 5. Conditional Logic: Registration Link
    const $registerLinkType = $('#register_link_type');
    const $wrapRegisterPage = $('#wrap_register_page');
    const $wrapRegisterUrl = $('#wrap_register_url');

    $registerLinkType.on('change', function () {
        $wrapRegisterPage.addClass('ldp-hidden').hide();
        $wrapRegisterUrl.addClass('ldp-hidden').hide();

        if ($(this).val() === 'page') {
            $wrapRegisterPage.removeClass('ldp-hidden').slideDown(200);
        } else if ($(this).val() === 'url') {
            $wrapRegisterUrl.removeClass('ldp-hidden').slideDown(200);
        }
    });


    // 6. Media Uploader for Brand Logo
    var mediaUploader;

    $('#ldp_upload_logo_btn').on('click', function (e) {
        e.preventDefault();

        // If the uploader object has already been created, reopen the dialog
        if (mediaUploader) {
            mediaUploader.open();
            return;
        }

        // Extend the wp.media object
        mediaUploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose a Logo',
            button: {
                text: 'Use this logo'
            },
            multiple: false // Only allow one file to be selected
        });

        // When a file is selected, grab the URL and set it as the text field's value
        mediaUploader.on('select', function () {
            var attachment = mediaUploader.state().get('selection').first().toJSON();

            // Set the ldp-hidden input value
            $('#logo_url').val(attachment.url);

            // Update the preview image
            $('#ldp_logo_preview').html('<img src="' + attachment.url + '" alt="Logo Preview" />');

            // Show the remove button
            $('#ldp_remove_logo_btn').removeClass('ldp-hidden');
        });

        // Open the uploader dialog
        mediaUploader.open();
    });

    // 7. Copy Shortcode Logic
    const copyBtn = document.getElementById('ldp_copy_shortcode_btn');
    const copyText = document.getElementById('ldp_shortcode_text');
    const copySuccess = document.getElementById('ldp_copy_success');

    if (copyBtn && copyText) {
        copyBtn.addEventListener('click', function () {
            // Create a temporary textarea to copy the text
            const tempTextArea = document.createElement("textarea");
            tempTextArea.value = copyText.innerText;
            document.body.appendChild(tempTextArea);

            // Select and copy
            tempTextArea.select();
            document.execCommand("copy");

            // Remove the temporary textarea
            document.body.removeChild(tempTextArea);

            // Show success message briefly
            copySuccess.style.display = 'block';
            setTimeout(function () {
                copySuccess.style.display = 'none';
            }, 3000);
        });
    }



    // Remove Logo Button
    $('#ldp_remove_logo_btn').on('click', function (e) {
        e.preventDefault();
        $('#logo_url').val(''); // Clear the input
        $('#ldp_logo_preview').html(''); // Clear the preview
        $(this).addClass('ldp-hidden'); // Hide the remove button
    });



    // Toggle main options wrapper
    $toggleRedirect.on('change', function () {
        if ($(this).is(':checked')) {
            $redirectOptions.removeClass('ldp-hidden').hide().slideDown(200);
        } else {
            $redirectOptions.slideUp(200, function () {
                $(this).addClass('ldp-hidden');
            });
        }
    });

    // Toggle URL vs Page selects
    $redirectType.on('change', function () {
        if ($(this).val() === 'url') {
            $wrapPage.hide();
            $wrapUrl.show();
        } else {
            $wrapUrl.hide();
            $wrapPage.show();
        }
    }).trigger('change'); // Trigger on load to set initial state

});