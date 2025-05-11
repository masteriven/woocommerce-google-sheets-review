jQuery(function($){
  $('input[name="update_sheet"]').on('click',function(c){
  let consumer_key = wc_api_credentials.consumer_key;
  let consumer_secret = wc_api_credentials.consumer_secret;
  let baseUrl = location.origin;
  let urlReviews = baseUrl+'/wp-json/wc/v3/products/reviews?consumer_key='+consumer_key+'&consumer_secret='+consumer_secret+'';
  $.get(urlReviews,function(response){
    let reviewsObj = {
      action: 'postWoocommerceReviewsObject',
      dataType:'json',
      dataReviewsObject:response
    };
    $.post('admin-ajax.php',reviewsObj,function(e){
      let response = JSON.parse(e);
      console.log(response);
      if(response.status == 'success'){
        $('.update_sheet_status').text(response.message);
        $('.update_sheet_status').css('color','green');
      }else if(response.status == 'fail'){
        $('.update_sheet_status').text(response.message);
        $('.update_sheet_status').css('color','red');
      }
    });
  });
  c.preventDefault();
  return false;
  });
});