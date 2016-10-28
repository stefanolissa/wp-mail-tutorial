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
 */
class WPMailTutorial {

    function __construct() {

        // Here we intercept the hooks needed to change the from address and the from name
        add_filter('wp_mail_from', array($this, 'hook_wp_mail_from'));
        add_filter('wp_mail_from_name', array($this, 'hook_wp_mail_from_name'));
        
        // Please, admin related code should be executed only in the admin context (be green save energy)
        if (is_admin()) {
            add_action('admin_menu', array($this, 'hook_admin_menu'));
        }
    }
    
    function hook_admin_menu() {
        // The shortest way to add a menu entry
        add_options_page('WP Mail Tutorial', 'WP Mail Tutorial', 'manage_options', 'wp-mail-tutorial/settings.php');
    }

    function hook_wp_mail_from($value) {
        error_log(__METHOD__);
        error_log(print_r($value, true));
        // We just change the address with a replace, but the address can be saved in an option.
        return str_replace('wordpress@', 'admin@', $value);
    }

    function hook_wp_mail_from_name($value) {
        error_log(__METHOD__);
        error_log(print_r($value, true));
        // The original from name is "WordPress" we change it to the blog name
        // (the entity decode is required since for emails we need to use the plain blog name)
        return html_entity_decode(get_option('blogname'), ENT_QUOTES, 'UTF-8');
    }
}

new WPMailTutorial();

