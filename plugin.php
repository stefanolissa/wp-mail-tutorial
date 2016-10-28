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
            
            // We are just playing, this line should be removed
            $this->hook_activation();
        }
        
        register_activation_hook(__FILE__, array($this, 'hook_activation'));
    }
    
    /**
     * @global wpdb $wpdb
     */
    function hook_activation() {
        global $wpdb;
    
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta(
        "CREATE TABLE `{$wpdb->prefix}mail_tutorial` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `data` longtext,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    function hook_admin_menu() {
        // The shortest way to add a menu entry
        add_options_page('WP Mail Tutorial', 'WP Mail Tutorial', 'manage_options', 'wp-mail-tutorial/settings.php');
    }

    /**
     * Instead of using globals we can eventually declare the $phpmailer arg as
     * &$phpmailer so we can change the object used by wp_mail(), but this way should be
     * easier to understand.
     * 
     * It's not required to restore the original $phpmailer since wp_mail() checks its type
     * and eventually it instantiates a new object. Restoring it save that extra work.
     * 
     * We do not use the arg of this function and we don't care of other plugins (disable them!).
     * 
     * @param PHPMailer $phpmailerarg
     * @global PHPMailer $phpmailer
     * @global wpdb $wpdb
     */
    function hook_phpmailer_init($phpmailerarg) {
        global $wpdb, $phpmailer;
        
        error_log(__METHOD__);
        
        // Save the email
        $data = array('subject'=>$phpmailer->Subject, 'to'=>$phpmailer->getToAddresses());
        $wpdb->insert($wpdb->prefix . 'mail_tutorial', array('data'=>json_encode($data, JSON_PRETTY_PRINT)));
        
        // Build and keep a fake PHPMailer instance
        static $fake_phpmailer;
        if (!$fake_phpmailer) $fake_phpmailer = new FakePHPMailer();
        
        // Here we replace the global $phpmailer which will be restore after the sending (see the class below)
        $phpmailer = $fake_phpmailer;
    }

}

new WPMailTutorial();

class FakePHPMailer {

    var $real_phpmailer = null;

    function __construct() {
        global $phpmailer;
        $this->real_phpmailer = $phpmailer;
    }

    function Send() {
        global $phpmailer;
        
        error_log(__METHOD__);
        
        // Restores the real phpmailer so WordPress does not create a new object on subsequent wp_mail() calls.
        $phpmailer = $this->real_phpmailer;
        return true;
    }

}
