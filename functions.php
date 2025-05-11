<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function enqueue_styles_admin_wgsrz(){
    wp_enqueue_style('admin_styles',plugin_dir_url(__FILE__).'src/styles/backend/css/style.css');
    wp_enqueue_script('admin_scriptsv',plugin_dir_url(__FILE__).'src/scripts/backend/js/admin.js',array('jquery'),strtotime("now"),true);

      $consumer_key = get_option('consumer_key');
      $consumer_secret = get_option('consumer_secret');

      wp_localize_script('admin_scriptsv', 'wc_api_credentials', [
        'nonce' => wp_create_nonce('wc_api_credentials_nonce'),
        'ajax_url' => admin_url('admin-ajax.php'),
        'consumer_key' => $consumer_key,
        'consumer_secret' => $consumer_secret,
    ]);
    
  }
  add_action('admin_enqueue_scripts','enqueue_styles_admin_wgsrz');


add_action('wp_ajax_postWoocommerceReviewsObject','postWoocommerceReviewsObject');
add_action('wp_ajax_nopriv_postWoocommerceReviewsObject','postWoocommerceReviewsObject');

function postWoocommerceReviewsObject(){
    $getReviewsObject = $_REQUEST['dataReviewsObject'];
      require  __DIR__  .'/api/google-sheet-api.php';
     die();
}


?>