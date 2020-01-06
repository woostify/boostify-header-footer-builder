(function ($) {

	'use strict';

	$('#display-on').on('change', function(e) {
		var postType = $(this).val();
		console.log( postType );
		var data = {
			action: 'boostify_hf_post_admin',
			_ajax_nonce: admin.nonce,
			post_type: postType,
		};
		$.ajax({
			type: 'POST',
			url: admin.url,
			data: data,
			beforeSend: function (data) {
				$( '#ht_hf_setting' ).addClass('loading');
			},

			success: function (data) {
				$( '#ht_hf_setting' ).removeClass('loading');
				$( '.child-item' ).html( data );
			},
		});
	});

})(jQuery);
