/**
 * Gallery - A project for 'WPF - Moderne Webanwendungen' at
 * Cologne University of Applied Sciences.
 *
 * @author    Dominik Schilling <dominik.schilling@smail.fh-koeln.de>
 * @author    Laura Hermann <laura.hermann@smail.fh-koeln.de>
 * @author    Dario Vizzaccaro <dario.vizzaccaro@smail.fh-koeln.de>
 * @link      https://github.com/ocean90/wpfmw-gallery
 * @license   MIT
 */

( function( $ ) {

	/**
	 * Handles AJAX request to GeoNames API.
	 *
	 * @type {Object}
	 */
	var locationInfo = {
		apiEndpoint: 'http://api.geonames.org/countrySubdivisionJSON?username=ocean90',
		$el: null,

		/**
		 * Inits the request.
		 *
		 * @param  {object} el
		 * @return {void}
		 */
		request: function( el ) {
			this.$el = el;

			el.prop( 'disabled', true ).text( 'Loading...');

			var lat = el.data( 'lat' );
			var lng = el.data( 'lng' );

			var result = this.loadData( lat, lng );
		},

		/**
		 * AJAX request.
		 *
		 * @param  {float} lat
		 * @param  {float} lng
		 * @return {void}
		 */
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

		/**
		 * Prints an error if AJAX request failed.
		 *
		 * @param  {string} message
		 * @return {void}
		 */
		showError: function( message ) {
			this.$el.before(
				'<p class="text-danger">Error: ' + message + '</p>'
			);
			el.prop( 'disabled', false ).text( 'Load Location Infos' );
		},

		/**
		 * Prints the data returned by the AJAX request.
		 *
		 * @param  {Object} data
		 * @return {void}
		 */
		showData: function( data ) {
			this.$el.before(
				'<p><strong>County:</strong> ' + data.countryName + '</p>' +
				'<p><strong>State:</strong> ' + data.adminName1 + '</p>'
			);

			this.$el.hide();
		}
	}

	/**
	 * DOM is ready.
	 */
	$( function() {
		// Disabled auto carousel
		$( '.carousel' ).carousel( { interval: false } );

		// Bind 'Get Location Info' button
		$( '.fetch-loaction-infos' ).on( 'click', function( e ) {
			e.preventDefault();
			locationInfo.request( $( this ) )
		} );

		// Init and customize FancyBox
		$( '.fancybox' )
			.attr( 'rel', 'gallery' )
			.fancybox( {
				beforeLoad: function() {
					var self = this;
					var src = $( self.element ).data( 'full-image' );
					var image = new Image();
					var $container = $( self.content );

					image.src = src;
					image.onload = function() {
						var $image = $( '.image', $container );
						$( '.loading', self.content ).remove();
						$image.attr( 'src', src );
					}

				},
				padding : 0,
				margin : [20, 60, 20, 60],
				openEffect  : 'none',
  				closeEffect : 'none',
				prevEffect	: 'none',
				nextEffect	: 'none',
				helpers	: {
					overlay : {
						css : {
							'background' : 'rgba( 0, 0, 0, 0.95 )'
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
