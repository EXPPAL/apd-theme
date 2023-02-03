jQuery(function(){
        
    jQuery('.woocommerce').on( 'click', 'table.shop_table span.add-airdrop', function() {
  	jQuery(this).hide();
        jQuery(this).next().show(); // show loader gif
  
        var airdrop_id = jQuery(this).data('airdrop-id');
        
        request_url = airdrops_data.ajax_url + '?action=claim_airdrop';
        
        jQuery.ajax({
            url: request_url,
            //dataType: 'json',
            data: { airdrop_id: airdrop_id },
            type: 'POST',
            success: function(response) {
                window.location.reload(true);
            }
        });
    });
});