jQuery(function( $ ) {
	'use strict';

	/* -----------------------------------------
	Responsive Menus Init with mmenu
	----------------------------------------- */
	var $mainNav   = $( '.navigation' );
	var $mobileNav = $( '#mobilemenu' );

	$mainNav.clone().removeAttr( 'id' ).removeClass().appendTo( $mobileNav );
	$mobileNav.find( 'li' ).removeAttr( 'id' );

	$mobileNav.mmenu({
		onClick: {
			close: true
		},
		offCanvas: {
			position: 'top',
			zposition: 'front'
		},
		"autoHeight": true,
		"navbars": [
			{
				"position": "top",
				"content": [
					"prev",
					"title",
					"close"
				]
			}
		]
	});

	/* -----------------------------------------
	Main Navigation Init
	----------------------------------------- */
	$mainNav.superfish({
		delay: 300,
		animation: { opacity: 'show', height: 'show' },
		speed: 'fast',
		dropShadows: false
	});

	/* -----------------------------------------
	Sticky Header
	----------------------------------------- */
	var header = $( '.header.sticky' );

	if ( header.length > 0 ) {
		var sticky = new Waypoint.Sticky( {
			element: header[0]
		} );
	}

	function addShrunkHeaderClass() {
		if ( $(document).scrollTop() > 15 ) {
			if ( !header.hasClass('shrunk') ) {
				header.addClass('shrunk');
			}
		} else {
			if ( header.hasClass('shrunk') ) {
				header.removeClass('shrunk');
			}
		}
	}

	addShrunkHeaderClass();

	$(window).scroll(function() {
		addShrunkHeaderClass();
	});

	/* -----------------------------------------
	Smooth Scrolling
	----------------------------------------- */
	var offset = 80;

	$( 'a[href*=#]:not([href=#])' ).not( '.mobile-trigger, .mm-btn' ).click(function() {
		if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
			var target = $(this.hash);
			target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
			if (target.length) {
				$('html,body').animate({
					scrollTop: target.offset().top - offset
				}, 400);

				return false;
			}
		}
	});

	if ( window.location.hash.length ) {
		var target = $(window.location.hash);

		$('html,body').animate({
			scrollTop: target.offset().top - offset
		}, 400);
	}

	/* -----------------------------------------
	Waypoints
	----------------------------------------- */
	var $sections = $('section.widget'),
		$nav = $mainNav.find( 'a' );

	function determineActiveNavItem(id) {
		$nav.parent().removeClass( 'current' );

		$mainNav.find('a[href*="#' + id + '"]').parent().addClass('current');
	}

	$sections.waypoint({
		handler: function( direction, element ) {
			if ( direction === 'down' ) {
				var $section = $(this.element);
				determineActiveNavItem($section.attr('id'));
			}
		},
		offset: 160
	});

	$sections.waypoint({
		handler: function(direction) {
			if ( direction === 'up' ) {
				var $section = $(this.element);
				determineActiveNavItem($section.attr('id'));
			}
		},
		offset: function() {
			return -this.element.clientHeight + offset;
		}
	});

	/* -----------------------------------------
	Team Lightbox
	----------------------------------------- */
	$( '.item-team' ).on( 'click', function( e )  {
		var $content = $( this ).find( '.item-team-details' );

		$.magnificPopup.open({
			items: {
				src: $content,
				type: 'inline'
			},
			mainClass: 'mfp-with-zoom',
			midClick: true,
		})
	});

	/* -----------------------------------------
	Responsive Videos with fitVids
	----------------------------------------- */
	$( 'body' ).fitVids();

	/* -----------------------------------------
	Image Lightbox
	----------------------------------------- */
	$( '.ci-lightbox' ).magnificPopup({
		type: 'image',
		mainClass: 'mfp-with-zoom',
		gallery: {
			enabled: true
		},
		zoom: {
			enabled: true
		}
	} );


	/* -----------------------------------------
	Map Init
	----------------------------------------- */
	function map_init(lat, lng, zoom, tipText, map_id) {
		if ( typeof google === 'object' && typeof google.maps === 'object' ) {
			var myLatlng = new google.maps.LatLng( lat, lng );
			var mapOptions = {
				zoom: zoom, center: myLatlng, mapTypeId: google.maps.MapTypeId.ROADMAP,
				scrollwheel: false
			};

			var map = new google.maps.Map( document.getElementById( map_id ), mapOptions );

			var contentString = '<div class="tip-content">' + tipText + '</div>';

			var infowindow = new google.maps.InfoWindow( {
				content: contentString
			} );

			var marker = new google.maps.Marker( {
				position: myLatlng, map: map
			} );

			google.maps.event.addListener( marker, 'click', function() {
				infowindow.open( map, marker );
			} );
		}
	}

	var $cmap = $( '.ci-map' );
	if ( $cmap.length ) {
		$cmap.each(function() {
			var that = $ (this ),
				lat = that.data( 'lat'),
				lng = that.data( 'lng' ),
				zoom = that.data( 'zoom' ),
				tipText = that.data( 'tooltip-txt' ),
				mapid = that.attr( 'id' );

			map_init( lat, lng, zoom, tipText, mapid );
		});
	}

	$( '.map-toggle' ).on( 'click', function(e) {
		e.preventDefault();
		$(this).siblings( '.widget-wrap' ).toggleClass( 'ci-hide' );
	});


	/* -----------------------------------------
	Animations
	----------------------------------------- */
	var wow = new WOW({
		mobile: false,
		offset: 200
	});

	wow.init();

	$( window ).on( 'load', function() {

		$('.item-list').find('div[class^="col"]').matchHeight();

		/* -----------------------------------------
		FlexSlider Init
		----------------------------------------- */
		var homeSlider = $( '.main-slider' );

		if ( homeSlider.length ) {
			var slideshow      = homeSlider.data( 'slideshow' ),
				animation      = homeSlider.data( 'animation' ),
				slideshowSpeed = homeSlider.data( 'slideshowspeed' ),
				animationSpeed = homeSlider.data( 'animationspeed' );

			homeSlider.flexslider({
				animation     : animation,
				slideshow     : slideshow,
				slideshowSpeed: slideshowSpeed,
				animationSpeed: animationSpeed,
				namespace: 'ci-',
				prevText: '',
				nextText: '',
				controlNav: false,
				start: function( slider ) {
					slider.removeClass( 'loading' );
				}
			});
		}
	});

});
