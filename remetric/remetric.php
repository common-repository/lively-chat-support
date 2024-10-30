<?php

class Remetric {
  public static $remetric_instance;
  const version = '1.1.0';
  const debug = false;

  public static function init() {
    if ( is_null( self::$remetric_instance ) ) { self::$remetric_instance = new Remetric(); }
    return self::$remetric_instance;
  }

  private function __construct() {
    define('REMETRIC_ROOT', dirname(__FILE__));

    add_action( 'admin_init',                  array( $this, 'admin_init' ) );
    add_action( 'admin_menu',                  array( $this, 'menu_page' ) );
    add_action( 'wp_footer',                   array( $this, 'wp_footer' ) );
    add_action( 'wp_ajax_remetric_save_keys',  array( $this, 'save_keys' ) );
  }

  public static function admin_init() {
    wp_enqueue_script( array( 'jquery' ) );
  }

  public static function get_vars() {
    $cdn_url = self::debug ? 'http://localhost:8080/dist' : 'https://cdn.remetric.com';
    $api_url = self::debug ? 'http://localhost:3000' : 'https://api.remetric.com';
    $api_url = 'https://api.remetric.com';

    return (object) array(
      'publishable_key' => get_option( 'remetric_publishable_key', '' ),
      'access_token' => get_option( 'remetric_access_token', '' ),
      'api_url' => $api_url,
      'cdn_url' => $cdn_url,
      'admin_js' => $cdn_url . '/remetric-admin.min.js',
      'admin_css' => $cdn_url . '/remetric-admin.min.css',
      'marketing_js' => $cdn_url . '/remetric.js',
      'marketing_css' => $cdn_url . '/remetric.css',
      'version' => self::version,
      'whitelabel' => defined('REMETRIC_WHITELABEL') ? REMETRIC_WHITELABEL : '',
      'target' => '#remetric-admin'
    );
  }

  public static function wp_footer() {
    $remetric = self::get_vars();

    if (!empty($remetric->publishable_key)) {
      require_once 'marketing.php';
    }
  }

  public static function admin_page() {
    $remetric = self::get_vars();
    require_once 'page.php';
  }

  public static function menu_page() {
    add_menu_page( 'Lively Chat', 'Lively Chat', 7, 'remetric', array('Remetric', 'admin_page'), 'dashicons-welcome-widgets-menus', 25 );
  }

  public static function save_keys() {
    if (isset($_REQUEST['remetric_publishable_key'])) {
        $remetric_publishable_key = sanitize_text_field($_REQUEST['remetric_publishable_key']);
        update_option( 'remetric_publishable_key', $remetric_publishable_key );
    }

    if (isset($_REQUEST['remetric_access_token'])) {
        $remetric_access_token = sanitize_text_field($_REQUEST['remetric_access_token']);
        update_option( 'remetric_access_token', $remetric_access_token );
    }

    die();
  }
}

Remetric::init();

?>
