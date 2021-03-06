<?php

/*
 * creates the settings page for the plugin
*/

class cscf_settings
{
    public
    function __construct()
    {

        if (is_admin()) {
            add_action('admin_menu', array(
                $this,
                'add_plugin_page'
            ));
            add_action('admin_init', array(
                $this,
                'page_init'
            ));
        }
    }

    public
    function add_plugin_page()
    {

        // This page will be under "Settings"
        add_options_page(__('Contact Form Settings', 'clean-and-simple-contact-form-by-meg-nicholas'), __('Contact Form', 'clean-and-simple-contact-form-by-meg-nicholas'), 'manage_options', 'contact-form-settings', array(
            $this,
            'create_admin_page'
        ));
    }

    public
    function create_admin_page()
    {
        ?>

        <h2><?php _e('Clean and Simple Contact Form Settings', 'clean-and-simple-contact-form-by-meg-nicholas'); ?></h2>
        <hr/>

        <div style="float:right;position: relative;width:250px;">

            <div style="border:1px solid;padding:5px;margin-bottom: 8px;text-align:center;">
                <h3><?php _e("Donate $10, $20 or $50!", "clean-and-simple-contact-form-by-meg-nicholas"); ?></h3>

                <div>
                    <p><?php _e("If you like this plugin, please donate to support development and maintenance of:", "clean-and-simple-contact-form-by-meg-nicholas"); ?></p>

                    <h3><?php _e("Clean and Simple Contact Form!", "clean-and-simple-contact-form-by-meg-nicholas"); ?></h3>

                    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                        <input type="hidden" name="cmd" value="_s-xclick">
                        <input type="hidden" name="hosted_button_id" value="ALAGLZ3APUZMW">
                        <input type="image" src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_donateCC_LG.gif"
                               border="0" name="submit" alt="PayPal – The safer, easier way to pay online.">
                        <img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1"
                             height="1">
                    </form>

                </div>
            </div>
        </div>
        <div style="float:left;">
            <p><?php _e('You are using version', 'clean-and-simple-contact-form-by-meg-nicholas'); ?> <?php echo CSCF_VERSION_NUM; ?></p>

            <p><?php _e('If you find this plugin useful please consider', 'clean-and-simple-contact-form-by-meg-nicholas'); ?>
                <a target="_blank"
                   href="http://wordpress.org/support/view/plugin-reviews/<?php echo CSCF_PLUGIN_NAME; ?>">
                    <?php _e('leaving a review', 'clean-and-simple-contact-form-by-meg-nicholas'); ?>
                </a>
                . <?php _e('Thank you!', 'clean-and-simple-contact-form-by-meg-nicholas'); ?>
            </p>

            <?php if (cscf_PluginSettings::IsJetPackContactFormEnabled()) { ?>
                <p class="highlight">
                    <?php _e('NOTICE: You have JetPack\'s Contact Form enabled please deactivate it or use the shortcode [cscf-contact-form] instead.', 'clean-and-simple-contact-form-by-meg-nicholas'); ?>
                    &nbsp; <a target="_blank"
                              href="http://www.megnicholas.co.uk/articles/clean-and-simple-contact-form-and-jetpack/"><?php _e('Read More', 'clean-and-simple-contact-form-by-meg-nicholas'); ?></a>
                </p>
            <?php } ?>

            <p class="howto"><?php _e("Please Note: To add the contact form to your page please add the text", "clean-and-simple-contact-form-by-meg-nicholas"); ?>
                <code>[cscf-contact-form]</code> <?php _e("to your post or page.", "clean-and-simple-contact-form-by-meg-nicholas"); ?></p>

            <form method="post" action="options.php">
                <?php
                submit_button();

                /* This prints out all hidden setting fields*/
                settings_fields('test_option_group');
                do_settings_sections('contact-form-settings');

                submit_button();
                ?>
            </form>
        </div>
    <?php
    }

    public
    function page_init()
    {
        add_settings_section('section_recaptcha', '<h3>' . __('ReCAPTCHA Settings', 'clean-and-simple-contact-form-by-meg-nicholas') . '</h3>', array(
            $this,
            'print_section_info_recaptcha'
        ), 'contact-form-settings');
        register_setting('test_option_group', CSCF_OPTIONS_KEY, array(
            $this,
            'check_form'
        ));
        add_settings_field('use_recaptcha', __('Use reCAPTCHA :', 'clean-and-simple-contact-form-by-meg-nicholas'), array(
            $this,
            'create_fields'
        ), 'contact-form-settings', 'section_recaptcha', array(
            'use_recaptcha'
        ));
        add_settings_field('theme', __('reCAPTCHA Theme :', 'clean-and-simple-contact-form-by-meg-nicholas'), array(
            $this,
            'create_fields'
        ), 'contact-form-settings', 'section_recaptcha', array(
            'theme'
        ));
        add_settings_field('recaptcha_public_key', __('reCAPTCHA Public Key :', 'clean-and-simple-contact-form-by-meg-nicholas'), array(
            $this,
            'create_fields'
        ), 'contact-form-settings', 'section_recaptcha', array(
            'recaptcha_public_key'
        ));
        add_settings_field('recaptcha_private_key', __('reCAPTCHA Private Key :', 'clean-and-simple-contact-form-by-meg-nicholas'), array(
            $this,
            'create_fields'
        ), 'contact-form-settings', 'section_recaptcha', array(
            'recaptcha_private_key'
        ));
        add_settings_section('section_message', '<h3>' . __('Message Settings', 'clean-and-simple-contact-form-by-meg-nicholas') . '</h3>', array(
            $this,
            'print_section_info_message'
        ), 'contact-form-settings');
        add_settings_field('recipient_emails', __('Recipient Emails :', 'clean-and-simple-contact-form-by-meg-nicholas'), array(
            $this,
            'create_fields'
        ), 'contact-form-settings', 'section_message', array(
            'recipient_emails'
        ));
        add_settings_field('confirm-email', __('Confirm Email Address :', 'clean-and-simple-contact-form-by-meg-nicholas'), array(
            $this,
            'create_fields'
        ), 'contact-form-settings', 'section_message', array(
            'confirm-email'
        ));
        add_settings_field('email-sender', __('Allow users to email themselves a copy :', 'clean-and-simple-contact-form-by-meg-nicholas'), array(
            $this,
            'create_fields'
        ), 'contact-form-settings', 'section_message', array(
            'email-sender'
        ));
        add_settings_field('contact-consent', '<span style="color:red;">' . __('*New*','clean-and-simple-contact-form-by-meg-nicholas') . '</span> ' . __('Add a consent checkbox :', 'clean-and-simple-contact-form-by-meg-nicholas'), array(
            $this,
            'create_fields'
        ), 'contact-form-settings', 'section_message', array(
            'contact-consent'
        ));
        add_settings_field('contact-consent-msg', '<span style="color:red;">' . __('*New*','clean-and-simple-contact-form-by-meg-nicholas') . '</span> ' . __('Consent message :', 'clean-and-simple-contact-form-by-meg-nicholas'), array(
            $this,
            'create_fields'
        ), 'contact-form-settings', 'section_message', array(
            'contact-consent-msg'
        ));
        add_settings_field('override-from', __('Override \'From\' Address :', 'clean-and-simple-contact-form-by-meg-nicholas'), array(
            $this,
            'create_fields'
        ), 'contact-form-settings', 'section_message', array(
            'override-from'
        ));
        add_settings_field('from-email', __('\'From\' Email Address :', 'clean-and-simple-contact-form-by-meg-nicholas'), array(
            $this,
            'create_fields'
        ), 'contact-form-settings', 'section_message', array(
            'from-email'
        ));
        add_settings_field('subject', __('Email Subject :', 'clean-and-simple-contact-form-by-meg-nicholas'), array(
            $this,
            'create_fields'
        ), 'contact-form-settings', 'section_message', array(
            'subject'
        ));
        add_settings_field('message', __('Message :', 'clean-and-simple-contact-form-by-meg-nicholas'), array(
            $this,
            'create_fields'
        ), 'contact-form-settings', 'section_message', array(
            'message'
        ));
        add_settings_field('sent_message_heading', __('Message Sent Heading :', 'clean-and-simple-contact-form-by-meg-nicholas'), array(
            $this,
            'create_fields'
        ), 'contact-form-settings', 'section_message', array(
            'sent_message_heading'
        ));
        add_settings_field('sent_message_body', __('Message Sent Content :', 'clean-and-simple-contact-form-by-meg-nicholas'), array(
            $this,
            'create_fields'
        ), 'contact-form-settings', 'section_message', array(
            'sent_message_body'
        ));
        add_settings_section('section_styling', '<h3>' . __('Styling and Validation', 'clean-and-simple-contact-form-by-meg-nicholas') . '</h3>', array(
            $this,
            'print_section_info_styling'
        ), 'contact-form-settings');
        add_settings_field('load_stylesheet', __('Use the plugin default stylesheet (un-tick to use your theme style sheet instead) :', 'clean-and-simple-contact-form-by-meg-nicholas'), array(
            $this,
            'create_fields'
        ), 'contact-form-settings', 'section_styling', array(
            'load_stylesheet'
        ));
        add_settings_field('use_client_validation', __('Use client side validation (AJAX) :', 'clean-and-simple-contact-form-by-meg-nicholas'), array(
            $this,
            'create_fields'
        ), 'contact-form-settings', 'section_styling', array(
            'use_client_validation'
        ));
    }

    public
    function check_form($input)
    {

        //recaptcha theme
        if (isset($input['theme'])) $input['theme'] = filter_var($input['theme'], FILTER_SANITIZE_STRING);

        //recaptcha_public_key
        if (isset($input['recaptcha_public_key'])) $input['recaptcha_public_key'] = filter_var($input['recaptcha_public_key'], FILTER_SANITIZE_STRING);

        //recaptcha_private_key
        if (isset($input['recaptcha_private_key'])) $input['recaptcha_private_key'] = filter_var($input['recaptcha_private_key'], FILTER_SANITIZE_STRING);

        //sent_message_heading
        $input['sent_message_heading'] = filter_var($input['sent_message_heading'], FILTER_SANITIZE_STRING);

        //sent_message_body
        $input['sent_message_body'] = filter_var($input['sent_message_body'], FILTER_SANITIZE_STRING);

        //message
        $input['message'] = filter_var($input['message'], FILTER_SANITIZE_STRING);

        //recipient_emails
        foreach ($input['recipient_emails'] as $key => $recipient) {
            if (!filter_var($input['recipient_emails'][$key], FILTER_VALIDATE_EMAIL)) {
                unset($input['recipient_emails'][$key]);
            }
        }

        //from
        if (!filter_var($input['from-email'], FILTER_VALIDATE_EMAIL)) {
            unset($input['from-email']);
        }

        //subject
        $input['subject'] = trim(filter_var($input['subject'], FILTER_SANITIZE_STRING));
        if (empty($input['subject'])) {
            unset($input['subject']);
        }

        if (isset($_POST['add_recipient'])) {
            $input['recipient_emails'][] = "";
        }

        if (isset($_POST['remove_recipient'])) {
            foreach ($_POST['remove_recipient'] as $key => $element) {
                unset($input['recipient_emails'][$key]);
            }
        }

        //tidy up the keys
        $tidiedRecipients = array();
        foreach ($input['recipient_emails'] as $recipient) {
            $tidiedRecipients[] = $recipient;
        }
        $input['recipient_emails'] = $tidiedRecipients;


        return $input;
    }

    public
    function print_section_info_recaptcha()
    {
        print __('Enter your reCAPTCHA settings below :', 'clean-and-simple-contact-form-by-meg-nicholas');
        print "<p>" . __('To use reCAPTCHA you must get an API key from', 'clean-and-simple-contact-form-by-meg-nicholas') . " <a target='_blank' href='" . csf_RecaptchaV2::$signUpUrl . "'>Google reCAPTCHA</a></p>";
    }

    public
    function print_section_info_message()
    {
        print __('Enter your message settings below :', 'clean-and-simple-contact-form-by-meg-nicholas');
    }

    public
    function print_section_info_styling()
    {

        //print 'Enter your styling settings below:';

    }

    public
    function create_fields($args)
    {
        $fieldname = $args[0];

        switch ($fieldname) {
            case 'use_recaptcha':
                $checked = cscf_PluginSettings::UseRecaptcha() == true ? "checked" : "";
                ?><input type="checkbox" <?php echo $checked; ?>  id="use_recaptcha"
                         name="<?php echo CSCF_OPTIONS_KEY; ?>[use_recaptcha]"><?php
                break;
            case 'load_stylesheet':
                $checked = cscf_PluginSettings::LoadStyleSheet() == true ? "checked" : "";
                ?><input type="checkbox" <?php echo $checked; ?>  id="load_stylesheet"
                         name="<?php echo CSCF_OPTIONS_KEY; ?>[load_stylesheet]"><?php
                break;
            case 'recaptcha_public_key':
                $disabled = cscf_PluginSettings::UseRecaptcha() == false ? "readonly" : "";
                ?><input <?php echo $disabled; ?> type="text" size="60" id="recaptcha_public_key"
                                                  name="<?php echo CSCF_OPTIONS_KEY; ?>[recaptcha_public_key]"
                                                  value="<?php echo cscf_PluginSettings::PublicKey(); ?>" /><?php
                break;
            case 'recaptcha_private_key':
                $disabled = cscf_PluginSettings::UseRecaptcha() == false ? "readonly" : "";
                ?><input <?php echo $disabled; ?> type="text" size="60" id="recaptcha_private_key"
                                                  name="<?php echo CSCF_OPTIONS_KEY; ?>[recaptcha_private_key]"
                                                  value="<?php echo cscf_PluginSettings::PrivateKey(); ?>" /><?php
                break;
            case 'recipient_emails':
                ?>
                <ul id="recipients"><?php
                foreach (cscf_PluginSettings::RecipientEmails() as $key => $recipientEmail) {
                    ?>
                    <li class="recipient_email" data-element="<?php echo $key; ?>">
                        <input class="enter_recipient" type="email" size="50"
                               name="<?php echo CSCF_OPTIONS_KEY; ?>[recipient_emails][<?php echo $key ?>]"
                               value="<?php echo $recipientEmail; ?>"/>
                        <input class="add_recipient" title="Add New Recipient" type="submit" name="add_recipient"
                               value="+">
                        <input class="remove_recipient" title="Remove This Recipient" type="submit"
                               name="remove_recipient[<?php echo $key; ?>]" value="-">
                    </li>

                <?php
                }
                ?></ul><?php
                break;
            case 'confirm-email':
                $checked = cscf_PluginSettings::ConfirmEmail() == true ? "checked" : "";
                ?><input type="checkbox" <?php echo $checked; ?>  id="confirm-email"
                         name="<?php echo CSCF_OPTIONS_KEY; ?>[confirm-email]"><?php
                break;
            case 'override-from':
                $checked = cscf_PluginSettings::OverrideFrom() == true ? "checked" : "";
                ?><input type="checkbox" <?php echo $checked; ?>  id="override-from"
                         name="<?php echo CSCF_OPTIONS_KEY; ?>[override-from]"><?php
                break;
            case 'email-sender':
                $checked = cscf_PluginSettings::EmailToSender() == true ? "checked" : "";
                ?><input type="checkbox" <?php echo $checked; ?>  id="email-sender"
                         name="<?php echo CSCF_OPTIONS_KEY; ?>[email-sender]"><?php
                break;
            case 'contact-consent':
                $checked = cscf_PluginSettings::ContactConsent() == true ? "checked" : "";
                ?><input type="checkbox" <?php echo $checked; ?>  id="contact-consent"
                         name="<?php echo CSCF_OPTIONS_KEY; ?>[contact-consent]"><?php
                break;
            case 'contact-consent-msg':
                ?><input type="text" size="60" id="contact-consent-msg"
                         name="<?php echo CSCF_OPTIONS_KEY; ?>[contact-consent-msg]"
                         value="<?php echo cscf_PluginSettings::ContactConsentMsg(); ?>"><?php
                break;
            case 'from-email':
                $disabled = cscf_PluginSettings::OverrideFrom() == false ? "readonly" : "";
                ?><input <?php echo $disabled; ?> type="text" size="60" id="from-email"
                                                  name="<?php echo CSCF_OPTIONS_KEY; ?>[from-email]"
                                                  value="<?php echo cscf_PluginSettings::FromEmail(); ?>" /><?php
                break;
            case 'subject':
                ?><input type="text" size="60" id="subject" name="<?php echo CSCF_OPTIONS_KEY; ?>[subject]"
                         value="<?php echo cscf_PluginSettings::Subject(); ?>" /><?php
                break;
            case 'sent_message_heading':
                ?><input type="text" size="60" id="sent_message_heading"
                         name="<?php echo CSCF_OPTIONS_KEY; ?>[sent_message_heading]"
                         value="<?php echo cscf_PluginSettings::SentMessageHeading(); ?>" /><?php
                break;
            case 'sent_message_body':
                ?><textarea cols="63" rows="8"
                            name="<?php echo CSCF_OPTIONS_KEY; ?>[sent_message_body]"><?php echo cscf_PluginSettings::SentMessageBody(); ?></textarea><?php
                break;
            case 'message':
                ?><textarea cols="63" rows="8"
                            name="<?php echo CSCF_OPTIONS_KEY; ?>[message]"><?php echo cscf_PluginSettings::Message(); ?></textarea><?php
                break;
            case 'theme':
                $theme = cscf_PluginSettings::Theme();
                $disabled = cscf_PluginSettings::UseRecaptcha() == false ? "disabled" : "";
                ?>
                <select <?php echo $disabled; ?> id="theme" name="<?php echo CSCF_OPTIONS_KEY; ?>[theme]">
                    <option <?php echo $theme == "light" ? "selected" : ""; ?>
                        value="light"><?php _e('Light', 'clean-and-simple-contact-form-by-meg-nicholas'); ?></option>
                    <option <?php echo $theme == "dark" ? "selected" : ""; ?>
                        value="dark"><?php _e('Dark', 'clean-and-simple-contact-form-by-meg-nicholas'); ?></option>
                </select>
                <?php
                break;
            case 'use_client_validation':
                $checked = cscf_PluginSettings::UseClientValidation() == true ? "checked" : "";
                ?><input type="checkbox" <?php echo $checked; ?>  id="use_client_validation"
                         name="<?php echo CSCF_OPTIONS_KEY; ?>[use_client_validation]"><?php
                break;
            default:
                break;
        }
    }
}

