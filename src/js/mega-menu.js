(function ($) {
	'use strict';

	/**
	 * @param $scope The widget wrapper element as a jQuery element
	 * @param $ The jQuery alias
	 */
	var WidgetMegaMenu = function ($scope, $) {
		var main     = $scope.find( '.boostify-main-navigation' );
		var sub      = main.find( '.sub-menu' );
		var itemMega = main.find( '.menu-item-has-mega' );
		var toggle   = $scope.find( '.boostify-menu-toggle' );
		var nav      = $scope.find( '.boostify-menu-sidebar' );
		var close    = $scope.find( '.boostify--close-menu-side-bar' );
		console.log( sub );
		sub.each(
			function ( index ) {
				if ( sub.hasClass( 'sub-mega-menu' ) ) {
					$( this ).wrap( '<div class="boostify-menu-child boostify-mega-sub">' );
				} else {
					$( this ).wrap( '<div class="boostify-menu-child">' );
				}
			}
		);

		// Set With Mega Menu
		itemMega.each(
			function( index ) {
				var item  = $( this );
				var mega  = item.find( '.boostify-mega-sub' );
				var width = $( window ).width();
				var left  = item.offset().left;
				mega.css({ 'left' : '-' + left + 'px', 'width' : width });
			}
		);


		// Show Menu Mobile
		toggle.on('click', function(e) {
			e.preventDefault();

			if ( nav.hasClass( 'show' ) ) {
				nav.removeClass( 'show' );
				overlay.removeClass('show');

			} else {
				nav.addClass('show');
				overlay.addClass('show');
			}
		});


	};


	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/ht-hf-mega-menu.default', WidgetMegaMenu);
	});
})(jQuery);