jQuery(document).ready(function ($) {
	$('.use_reward').maskMoney({ thousands: '', decimal: '.', allowZero: true, allowNegative: false });
	$('.use_reward').each(function () { // function to apply mask on load!
		$(this).maskMoney('mask', $(this).val());
	});
	$("#reward_number").on("keydown", function (event) {
		if (!((event.which > 95 && event.which < 106) || (event.which > 47 && event.which < 58) || event.which == 8)) {
			return false;
		}
	});

	$('.Resend').on('click', function () {
		var orderid = $(this).data('orderid');
		$(this).after('Sent');
		$(this).remove();
		$.ajax({
			type: "POST",
			url: '/wp-admin/admin-ajax.php',
			data: { "action": "resend", "orderid": orderid },
			success: function (data) {
			}
		});
	});

//    setInterval(function () {
//       $.ajax({            type: "POST",
//            url: '/wp-admin/admin-ajax.php',
//            data: {"action": "rewards"},
//            success: function (data) {
//                if (data) {
//                    $('a.your_rewards').html(data);
//                }
//            }
//        })
//    }, 10000);
	$('.set-reward').on('click', function () {
		var reward_points = $(this).prev('.use_reward').val();

		reward_points = Math.abs(reward_points);
                
		$(this).prev('.use_reward').val(reward_points);

		var original_price = parseFloat($(this).closest('.item-details').find('.original_price').val()); // e.g. "69.00"
		var original_rewards = parseFloat($(this).closest('.item-details').find('.original_rewards').val());

		// this is "39.99" on the Developer page, "$39.99" on the single product page (as the product page uses WooCommerce price output)
		var base_price = parseFloat($(this).closest('.item-details').find('.base_price').text().replace(/[^0-9.]*/g, ''));
		// base_price = base_price.replace('$', ''); // remove "$" if it is here

		var user_rewards = $(this).closest('.item-details').find('.amount').html().split('$'); // e.g. "$220.36"
 
user_rewards = Math.abs(user_rewards[1]);
            
      
		var current_price = original_price - reward_points;
		var limit_price = original_price - base_price;

		 //console.log('base_price', base_price, 'limit_price:', limit_price, 'current_price:', current_price, 'user_rewards', user_rewards, 'reward_points', reward_points);

		if (reward_points > user_rewards && ( reward_points.toFixed(2) !== user_rewards.toFixed(2)) ) { // using .toFixed(2) for special case where floats are almost equal
			alert("You cannot use more than $" + user_rewards.toFixed(2) + ' in rewards money');
			$(this).prev('.use_reward').val("");
		} else if (current_price < base_price && ( current_price.toFixed(2) !== base_price.toFixed(2))) {
			alert("You cannot use more than $" + limit_price.toFixed(2) + ' in rewards money');
			$(this).prev('.use_reward').val("");
		} else {
			$(this).closest('.item-details').find('.price-value').html(current_price.toFixed(2));
			$('.price-box').html("$" + current_price.toFixed(2));
			$(this).closest('.item-details').find('.use_rewards').val(reward_points.toFixed(2));
			var reward_box = original_rewards - reward_points;
			$('.info span.amount').html("$" + reward_box.toFixed(2));
			$(".rewardbox").html(reward_box.toFixed(2));
		}
	});
	$('#order_search').on('keypress', function () {
		if (e.which == 13) {
			$('#search_order_form').submit();
		}
	});
	$('#wallet_select').on('change', function () {
		$('#wallet_filter').submit();
	});
});