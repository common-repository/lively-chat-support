<?php
/*
Plugin Name: Lively Chat via SMS
Plugin URI: http://www.livelychatsupport.com
Description: The best FREE live chat for your WP website (supports images) - forget the hosted chat services.
Version: 2.0.5
Contributors: dallas22ca
Author: Dallas Read
Author URI: http://www.DallasRead.com
Text Domain: lively-chat-support
Donate link: Just purchase an addon!
Tags: free live chat, live chat, live support, online chat, customer service
Requires at least: 3.0.1
Tested up to: 4.9.4
Stable tag: 2.0.5
License: MIT

Copyright (c) 2013-2017 Dallas Read.

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/

/*
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);
*/

define('LIVELYCHATSUPPORT_ROOT', dirname(__FILE__));
define('LIVELYCHATSUPPORT_ADMIN', is_admin());

add_action('init', 'LivelyChatSupportInit');

function LivelyChatSupportInit() {
    /* Start Remetric */

    global $IS_REMETRIC;
    // delete_option('livelychatsupport_is_remetric');
    // delete_option('remetric_publishable_key');
    // delete_option('remetric_access_token');

    if (isset($_REQUEST['IS_REMETRIC']) && current_user_can("manage_options")) {
        $is_remetric = (int) $_REQUEST['IS_REMETRIC'];

        update_option("livelychatsupport_is_remetric", $is_remetric == 0 ? 0 : 1);

        if ($is_remetric == 0) {
            header('Location: ?page=livelychatsupport');
            die();
        } else if ($is_remetric == 1) {
            header('Location: ?page=remetric');
            die();
        }
    }

    $IS_REMETRIC = (int) get_option("livelychatsupport_is_remetric", 1);

    if ($IS_REMETRIC == 1) {
        require_once LIVELYCHATSUPPORT_ROOT . "/remetric/remetric.php";
        return;
    } else if ($IS_REMETRIC == 2) {
        if ($_GET['page'] == 'livelychatsupport') {
            require_once LIVELYCHATSUPPORT_ROOT . "/remetric-upsell/remetric-upsell.php";
        }
    }

    /* End Remetric */

    global $livelychatsupport_version;
    global $livelychatsupport_db_version;
    global $livelychatsupport_addon_version;

    $livelychatsupport_version = "2.0.5";
    $livelychatsupport_db_version = 1.11;
    $livelychatsupport_addon_version = 1.5;

    require_once LIVELYCHATSUPPORT_ROOT . "/admin/includes/functions.php";
    require_once LIVELYCHATSUPPORT_ROOT . "/chatbox/includes/functions.php";
    require_once LIVELYCHATSUPPORT_ROOT . "/shared/includes/functions.php";

    register_activation_hook( __FILE__,                     "LivelyChatSupport_installation" );
    register_deactivation_hook( __FILE__,                   "LivelyChatSupport_uninstallation" );

    add_action( "admin_menu",                               "register_LivelyChatSupport_admin_menu" );
    add_action( "wp_footer",                                "LivelyChatSupport_frontend_footer" );
    add_action( "wp_loaded",                                "LivelyChatSupport_set_cookies");
    add_action( "plugins_loaded",                           "LivelyChatSupport_update_db_check" );

    add_action( "wp_ajax_poll",                             "LivelyChatSupport_poll" );
    add_action( "wp_ajax_add_convo",                        "LivelyChatSupport_add_convo" );
    add_action( "wp_ajax_read_convo",                       "LivelyChatSupport_read_convo" );
    add_action( "wp_ajax_create_chatbox_message",           "LivelyChatSupport_create_chatbox_message" );
    add_action( "wp_ajax_subscribe",                        "LivelyChatSupport_subscribe" );
    add_action( "wp_ajax_save_survey",                      "LivelyChatSupport_save_survey" );
    add_action( "wp_ajax_cache_support",                    "LivelyChatSupport_cache_support" );
    add_action( "wp_ajax_delete_all_convos",                "LivelyChatSupport_delete_all_convos" );
    add_action( "wp_ajax_find_visitors",                    "LivelyChatSupport_find_visitors" );
    add_action( "wp_ajax_delete_history",                   "LivelyChatSupport_delete_history" );
    add_action( "wp_ajax_two_hide",                               "LivelyChatSupport_two_hide" );
    add_action( "wp_ajax_two_submit",                           "LivelyChatSupport_two_submit" );

    add_action( "wp_ajax_nopriv_poll",                      "LivelyChatSupport_poll" );
    add_action( "wp_ajax_nopriv_subscribe",                 "LivelyChatSupport_subscribe" );
    add_action( "wp_ajax_nopriv_create_chatbox_message",    "LivelyChatSupport_create_chatbox_message" );
    add_action( "wp_ajax_nopriv_save_survey",               "LivelyChatSupport_save_survey" );
    add_action( "wp_ajax_nopriv_cache_support",             "LivelyChatSupport_cache_support" );
    add_action( "wp_ajax_nopriv_delete_history",            "LivelyChatSupport_delete_history" );

    add_action( "show_user_profile",                        "LivelyChatSupport_user_profile_fields" );
    add_action( "edit_user_profile",                        "LivelyChatSupport_user_profile_fields" );
    add_action( "user_new_form",                            "LivelyChatSupport_user_profile_fields" );

    add_action( "user_register",                            "LivelyChatSupport_save_user_profile_fields");
    add_action( "personal_options_update",                  "LivelyChatSupport_save_user_profile_fields");
    add_action( "edit_user_profile_update",                 "LivelyChatSupport_save_user_profile_fields");
}

?>
