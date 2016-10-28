<?php

/*
  Plugin Name: wp_mail tutorial
  Plugin URI: http://www.satollo.net/examples/ex-wpmail-hooks
  Description: Adds a debug menu to the admin bar that shows query, cache, and other helpful debugging information.
  Author: satollo
  Version: 0.0.1
  Author URI: https://www.satollo.net/
 */

/**
 * The class uses "error_log" to write debug information. If in your wp-config.php
 * you add:
 * 
 * define('WP_DEBUG', true);
 * define('WP_DEBUG_DISPLAY', false);
 * define('WP_DEBUG_LOG', true);
 * 
 * everything is logged in the file wp-content/debug.log. Very useful.
 * 
 * This file is formatted using ALT-SHIFT-F in Netbeans.
 */
class WPMailTutorial {

    function __construct() {

        // Here we intercept the hook to manipulate the whole PHPMailer object
        add_action('phpmailer_init', array($this, 'hook_phpmailer_init'));

        // Please, admin related code should be executed only in the admin context (be green save energy)
        if (is_admin()) {
            add_action('admin_menu', array($this, 'hook_admin_menu'));
        }
    }

    function hook_admin_menu() {
        // The shortest way to add a menu entry
        add_options_page('WP Mail Tutorial', 'WP Mail Tutorial', 'manage_options', 'wp-mail-tutorial/settings.php');
    }

    /**
     * $phpmailer is an object so changes are "global".
     * 
     * @param PHPMailer $phpmailer
     */
    function hook_phpmailer_init($phpmailer) {
        error_log(__METHOD__);

        if ($phpmailer->ContentType !== 'text/plain') {
            error_log('Not plain text content type (' . $phpmailer->ContentType . ')... nothing to do');
            return;
        }

        // The alternative body will be the original plain text message
        $phpmailer->AltBody = $phpmailer->Body;
        
        // Now we htmlize...
        $body = $phpmailer->Body;
        $body = wpautop($body);
        $body = make_clickable($body);

        // Templating...
        // We encapsulate in a table to center, give a background and a title (tables are
        // widely used in HTML emails since there are many old clients which do not appreciated
        // CSS rules).
        
        $body = '<table width="600" bgcolor="#f4f4f4" align="center"><tr><td>' . $body . '</td></tr></table>';
        
        // Finally we just add some stadard HTML tags (most email clients ignore them).
        // \r\n are added only to make the message source more readable they do not afftect the HTML
        // rendering.
        $phpmailer->Body = "<html>\r\n" .
                "<head>\r\n<title>" . esc_html($phpmailer->Subject) . "</title>\r\n</head>\r\n" .
                "<body>\r\n" . 
                $body .
                "\r\n</body>\r\n</html>";
        
        // or $phpmailer->ContentType = 'text/html'    
        $phpmailer->isHTML();
        
        error_log('Htmlized, yeah!');
    }

}

new WPMailTutorial();

