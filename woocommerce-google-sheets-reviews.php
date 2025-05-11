<?php

/*
Plugin Name: Woocommerce Google Sheets Reviews 
Description: Inserting and getting reviews from Google Sheets
Author: Tal Rimer
Version: 1.0
License: GPLv3 or later License
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Text Domain: woocommerce-google-sheets-reviews
*/

require_once('functions.php');


if (!defined('ABSPATH')){
    exit;
    
}

if (file_exists( __DIR__ . '/vendor/autoload.php')) {
  require_once  __DIR__  . '/vendor/autoload.php';
}


add_action('admin_menu','woocommerce_reviews_google_sheets_menu');

  function woocommerce_reviews_google_sheets_menu(){
    add_menu_page(
      'Woocommerce Google Sheets Reviews',
      'Woocommerce Google Sheets Reviews',
      'manage_options',
      'woocommerce-google-sheets-reviews',
      'woocommerce_google_sheets_reviews_func',
      'dashicons-admin-customizer',
      12
    );
  }


  function woocommerce_google_sheets_reviews_func(){
    // if(isset($_POST['update_sheet'])){
    // //  require  __DIR__  .'/api/google-sheet-api.php';
    // }

   if(isset($_POST['consumer_key'])){
    update_option('consumer_key',$_POST['consumer_key']);
   }

   if(isset($_POST['consumer_secret'])){
    update_option('consumer_secret',$_POST['consumer_secret']);
   }

  
   if (
    isset($_POST['wgsr_json_nonce']) &&
    wp_verify_nonce($_POST['wgsr_json_nonce'], 'wgsr_json_upload') &&
    !empty($_FILES['wgsr_json']['name'])
  ) {

    $file = $_FILES['wgsr_json'];

    if ($file['error'] === UPLOAD_ERR_OK) {
        $upload_dir = plugin_dir_path(__FILE__) . 'api/';
        $filename = sanitize_file_name('client_secret.json');
        $target_path = $upload_dir . $filename;

        if (move_uploaded_file($file['tmp_name'], $target_path)) {
            echo "File uploaded to plugin folder: " . esc_html($target_path);
        } else {
            echo "Failed to move uploaded file.";
        }
    } else {
        echo "File upload error code: " . $file['error'];
    }
}

   if(isset($_POST['application_name'])){
    update_option('application_name',$_POST['application_name']);
   }

   if(isset($_POST['spreadsheet_id'])){
    update_option('spreadsheet_id',$_POST['spreadsheet_id']);
   }

   if(isset($_POST['google_sheet_preview_link'])){
    update_option(' google_sheet_preview_link',$_POST['google_sheet_preview_link']);
   }

   $consumer_key = get_option('consumer_key');
   $consumer_secret = get_option('consumer_secret');
   $application_name = get_option('application_name');
   $spreadsheet_id = get_option('spreadsheet_id');
   $google_sheet_preview_link = get_option('google_sheet_preview_link');

    echo '<h1 style="margin-bottom:25px;">Woocommerce Google Sheets Reviews</h1>';
    
    ?>
      <div class = "container">
          <form action="/wp-admin/admin.php?page=woocommerce-google-sheets-reviews" method="POST" enctype="multipart/form-data">
          <?php wp_nonce_field('wgsr_json_upload', 'wgsr_json_nonce'); ?>
            <div class = "api_woocommerce_keys_container" style="width:250px;">
              <span style="font-size:17px; font-weight:bold; display:block; margin-bottom: 10px;">Woocommerce API Credentials</span>
              <span  style="margin-bottom:10px; display:block;" >Consumer key</span>
              <input type ="text" name="consumer_key" style="margin-bottom:10px;" value="<?php echo  $consumer_key ?>"/>
              <span  style="margin-bottom:10px; display:block;" >Consumer secret</span>
              <input type ="text" name="consumer_secret" value="<?php echo  $consumer_secret ?>"/>
            </div>
            </br>
            <div class = "google_sheets_api_container" style="width:200px;">
               <span style="font-size:17px;  font-weight:bold; display:block; margin-bottom: 10px;">Google Sheets API</span>
               <span  style="margin-bottom:-9px; display:block;" >Client Secret json file</span>
               <br>
                <input type ="file" name="wgsr_json" style="margin-bottom:10px;" accept=".json" />
                <span style="margin-bottom:10px; display:block;" >Application Name</span>
                <input type ="text" name="application_name" style="margin-bottom:10px;"  value="<?php echo  $application_name ?>"/>
                <span  style="margin-bottom:10px; display:block;" >Spreadsheet ID</span>
                <input type ="text" name="spreadsheet_id" style="margin-bottom:10px;"  value="<?php echo  $spreadsheet_id ?>"/>
               <br>
            </div>
            <span  style="margin-bottom:10px; display:block;" >Google Sheet Preview Window Link</span>
            <input type ="text" name="google_sheet_preview_link" style="margin-bottom:10px;"  value="<?php echo  $google_sheet_preview_link ?>"/>
            <div class = "save_container" style="width:200px;">
              <button style="font-size:18px;" type="submit" name="submit_wgsr">Save</button>
              <input style="font-size:18px;" type ="submit" name="update_sheet" value="Update Sheet" /> 
              <br>
              <br>
              <span class="update_sheet_status" style="font-size:14px;"></span>
            </div>
         </form>
      </div>
      <iframe 
        src="<?php echo $google_sheet_preview_link ?>?widget=true&amp;headers=false" 
        width="100%" 
        height="600">
      </iframe>
    <?php
  }
?>