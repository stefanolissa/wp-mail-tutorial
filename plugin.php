<?php

/*
  Plugin Name: wp_mail tutorial
  Plugin URI: http://www.satollo.net/examples/ex-wpmail-hooks
  Description: Adds a debug menu to the admin bar that shows query, cache, and other helpful debugging information.
  Author: satollo
  Version: 0.0.1
  Author URI: https://www.satollo.net/
 */

class WPMailTutorial {

    function __construct() {

        add_filter('wp_mail_from', array($this, 'hook_wp_mail_from'));
        add_filter('wp_mail_from_name', array($this, 'hook_wp_mail_from_name'));
        
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
        return str_replace('wordpress@', 'admin@', $value);
    }

    function hook_wp_mail_from_name($value) {
        error_log(__METHOD__);
        error_log(print_r($value, true));
        return html_entity_decode(get_option('blogname'), ENT_QUOTES, 'UTF-8');
    }
}

new WPMailTutorial();

