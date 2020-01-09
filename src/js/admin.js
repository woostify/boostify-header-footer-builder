(function ($) {

	'use strict';

	// Show Auto Complete
	$('#display-on').on('change', function(e) {
		var postType = $(this).val();
		var data = {
			action: 'boostify_hf_load_autocomplate',//boostify_hf_post_admin
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


	// Load post
	$('body').on( 'keyup', '.boostify--hf-post-name', function () {
		var postType = $('input[name=bhf_post_type]').val();
		var keyword = $(this).val();

		console.log( postType );
		var data = {
			action: 'boostify_hf_post_admin',//boostify_hf_post_admin
			_ajax_nonce: admin.nonce,
			post_type: postType,
			key: keyword,
		};
		$.ajax({
			type: 'GET',
			url: admin.url,
			data: data,
			beforeSend: function (data) {
				$( '#ht_hf_setting' ).addClass('loading');
			},
			success: function (data) {
				$( '#ht_hf_setting' ).removeClass('loading');
				$( '.boostify-data' ).html( data );
			},
		});
	} );

	// Select Post
	$('body').on( 'click', '.boostify-hf-list-post .post-item', function () {

		var listPost = $( 'input[name=bhf_post]' ).val();
		var all = $('.boostify-select-all-post');
		var parent = all.parents('.boostify-section-select-post');
		if ( ! all.hasClass('hidden') ) {
			all.addClass('hidden');
		}
		if ( ! listPost || 'all' == listPost ) {
			listPost = [];
		} else {
			listPost = listPost.split(',');
		}
		if ( ! parent.hasClass( 'has-option' ) ) {
			parent.addClass( 'has-option' );
		}
		var id = $(this).attr('data-item');
		var title = $(this).html();
		if ( ! listPost.includes(id) ) {
			listPost.push( id );
			var html = '<span class="boostify-auto-complete-key">' + 
							'<span class="boostify-title">' + title + '</span>' +
							'<span class="btn-boostify-auto-complete-delete ion-close" data-item="' + id + '"></span>'
						'</span>';
			$('.boostify--hf-post-name').before(html);
		}
		$( 'input[name=bhf_post]' ).val(listPost);
		$( '.boostify--hf-post-name' ).val('');

	} );

	// Focus Input Field
	$('body').on('click', '.boostify-auto-complete-field', function(e) {
		var form = $('.boostify--hf-post-name');
		$( form , this ).focus();
	});

	// Delete Post
	$( 'body' ).on('click', '.btn-boostify-auto-complete-delete', function(e) {
		var id =$(this).attr('data-item');
		var listPost = $( 'input[name=bhf_post]' ).val();
		var render = $('.boostify-section-render--post');
		var parent = render.parents('.boostify-section-select-post');
		listPost = listPost.replace( ',' + id, '' );
		listPost = listPost.replace( id, '' );
		if ( listPost == '' ) {
			listPost = 'all';
			render.addClass('hidden');
			parent.removeClass('render--post').addClass('select-all');
			parent.removeClass('has-option');
			render.siblings( '.boostify-select-all-post' ).removeClass('hidden');
			$( '.boostify-data' ).html( '' );
		}
		$( 'input[name=bhf_post]' ).val(listPost);
		$(this).parents('.boostify-auto-complete-key').remove();
	});

	// Select All Post
	$('body').on('click', '.boostify-select-all-post', function(e) {
		var render = $('.boostify-section-render--post');
		var parents = $(this).parents('.boostify-section-select-post');

		if ( render.hasClass( 'hidden' ) ) {
			render.removeClass('hidden');
			parents.removeClass('select-all').addClass('render--post');
		} else {
			render.addClass('hidden');
			parents.removeClass('render--post').addClass('select-all');
		}
	});

})(jQuery);
