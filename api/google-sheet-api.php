<?php

use Google\Client;
use Google\Service\Sheets;

// // Load Google Sheets API

$application_name = null;
$spreadsheet_id = null;

if(!empty(get_option('application_name'))){
    $application_name = get_option('application_name');
}

if(!empty(get_option('spreadsheet_id'))){
    $spreadsheet_id = get_option('spreadsheet_id');
}

$client = new Client();
$client->setApplicationName($application_name);
$service = new Sheets($client);
$spreadsheet_range = 'Sheet1!A1';
$client->setRedirectUri(get_site_url().'/wp-admin/admin.php?page=woocommerce-google-sheets-reviews');
$service_account_file = __DIR__ . '/client_secret.json';
$client->setAccessType('offline');

putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $service_account_file);
$client->useApplicationDefaultCredentials();
$client->setScopes([Sheets::SPREADSHEETS]);

$values = [["Product ID","Product Name","Product Link","Product Categories","Reviewer", "Rating", "Review",'Status','Reviewer Avatar Urls','Verified','Date','Time']];
 if(isset($getReviewsObject)){
foreach( $getReviewsObject as $review){
     $dateTime = explode('T',$review['date_created']);
     $date = $dateTime[0];
     $time = $dateTime[1];
    $productCategories = get_the_terms ( $review['product_id'], 'product_cat' );
    $categories = [];
    foreach($productCategories as $cat){
        $categories[] = $cat->name;
    }

     $values[] = [
        $review['product_id'],
        $review['product_name'],
        $review['product_permalink'],
        implode(',',$categories),
        $review['reviewer'],
        $review['rating'],
        strip_tags($review['review']),
        $review['status'],
        implode(",",$review['reviewer_avatar_urls']),
        $review['verified'],
        $date,
        $time,
    ];
}

//Push data to Google Sheets
$body = new Google\Service\Sheets\ValueRange([
    'values' => $values
]);

    $statusUpdate = ['status' => null , 'message' => ''];
    try{
        $params = ['valueInputOption' => 'RAW'];
        $result = $service->spreadsheets_values->update($spreadsheet_id, $spreadsheet_range, $body, $params);
        $message = "{$result->getUpdatedCells()} cells updated.";
        $status = 'success';
        $statusUpdate = ['status' => $status , 'message' =>  $message];
    }catch(Exception $e){
        $message =  'Update failed: ' . $e->getMessage();
        $status = 'fail';
        $statusUpdate = ['status' => $status , 'message' =>  $message];
    }

    echo json_encode($statusUpdate);

}

?>