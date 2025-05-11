<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete plugin options

delete_option('consumer_key');
delete_option('consumer_secret');
delete_option('application_name');
delete_option('spreadsheet_id');
delete_option('google_sheet_preview_link');
