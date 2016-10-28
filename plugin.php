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

        // Tell PHPMailer to use an external SMTP
        $phpmailer->isSMTP();
        
        $phpmailer->Host = 'localhost';
        $phpmailer->Port = 2525; // For my local fake SMTP server
        
        // Protocol to use
        $phpmailer->SMTPSecure = ''; // 'ssl' or 'tls'
        
        // Credentials required?
        //$phpmailer->SMTPAuth = true;
        //$phpmailer->Username = '';
        //$phpmailer->Password = '';
        
        // Some SMTP servers (badly configured) have problem with AutoTLS
        $phpmailer->SMTPAutoTLS = false;
        
        error_log('Sending with SMTP');
    }

}

new WPMailTutorial();

