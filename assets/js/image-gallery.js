( function( $ ) {
	var locationInfo = {
		apiEndpoint: 'http://api.geonames.org/countrySubdivisionJSON?username=ocean90',
		$el: null,

		request: function( el ) {
			this.$el = el;

			el.prop( 'disabled', true ).text( 'Loading...');

			var lat = el.data( 'lat' );
			var lng = el.data( 'lng' );

			var result = this.loadData( lat, lng );
		},

		loadData: function( lat, lng ) {
			var self = this;

			$.ajax( {
				url: this.apiEndpoint + '&lat=' + lat + '&lng=' + lng,
			} ).done( function ( data ) {
				if ( data.status ) {
					self.showError( data.status.message );
				} else {
					self.showData( data )
				}
			} );
		},

		showError: function( message ) {
			this.$el.before(
				'<p class="text-danger">Error: ' + message + '</p>'
			);
			el.prop( 'disabled', false ).text( 'Load Location Infos');
		},

		showData: function( data ) {
			this.$el.before(
				'<p><strong>County:</strong> ' + data.countryName + '</p>' +
				'<p><strong>State:</strong> ' + data.adminName1 + '</p>'
			);

			this.$el.hide();
		}
	}

	$( function() {
		$( ".carousel" ).carousel( { interval: false } );

		$( '.fetch-loaction-infos' ).on( 'click', function( e ) {
			e.preventDefault();
			locationInfo.request( $( this ) )
		} );

		$( ".fancybox" )
			.attr( "rel", "gallery" )
			.fancybox( {
				beforeLoad: function() {
					var self = this;
					var src = $( self.element ).data( 'full-image' );
					var image = new Image();
					var $container = $( self.content );

					image.src = src;
					image.onload = function() {
						var $image = $( '.image', $container );
						$image.attr( 'src', src );
					}

				},
				afterLoad: function() {
				},
				padding : 0,
				margin : [20, 60, 20, 60],
				openEffect  : 'none',
  				closeEffect : 'none',
				prevEffect	: "none",
				nextEffect	: "none",
				helpers	: {
					overlay : {
						css : {
							'background' : 'rgba( 0, 0, 0, 0.95)'
						}
					},
					title: null,
					thumbs	: {
						width	: 50,
						height	: 50
					}
				}
			} );
	} );
} )( jQuery );
