( function( $ ){

	var imageUploader = {
		$el: $( '#image-uploader' ),
		$button: $( '#image-upload-button' ),
		$imagesToUpload: $( '#images' ),
		$container: $( '#image-container' ),
		currentImages: [],

		init: function() {
			//this.$el.on( 'submit', $.proxy( this.uploadRequest, this ) );
			this.$button.on( 'click', $.proxy( this.triggerFileSelect, this ) );
			this.$imagesToUpload.on( 'change', $.proxy( this.imagesChanged, this ) );
			//this.$imagesToUpload.on( 'change', $.proxy( this.previewImages, this ) );
		},

		template: function( data ) {
			return $( '<div/>' )
				.attr( 'data-hash', data.hash )
				.addClass( 'upload-image-wrapper clearfix' )
				.append(
					$( '<img/>' )
						.addClass( 'img-thumbnail not-loaded' )
						.attr( 'src', data.imageSrc )
				)
				.append(
					$( '<div/>' )
						.addClass( 'progress' )
						.append(
							$( '<div/>' )
							.addClass( 'progress-bar' )
							.width( '5%' )
						)
				)
				.append(
					$( '<div/>')
						.addClass( 'form-group')
						.append(
							$( '<label/>')
								.attr( 'for', 'image-description-' + data.hash )
								.append(
									'Description:'
								)
						)
						.append(
							$( '<textarea/>')
								.addClass( 'form-control image-description' )
								.attr( 'rows', 3 )
								.attr( 'id', 'image-description-' + data.hash )
						)
				)
				.append(
					$( '<button/>')
						.attr( 'type', 'button' )
						.addClass( 'btn btn-danger btn-xs pull-right')
						.html(
							'Delete'
						)
				);
		},

		triggerFileSelect: function( e ) {
			this.$imagesToUpload.trigger( 'click' );
		},

		imagesChanged: function( e ) {
			var newImages = this.$imagesToUpload.prop( 'files' ),
				self = this,
				todo = [];

			$.each( newImages, function( index, value ) {
				var image = newImages[ index ];
				var hash = md5( image.name + image.size + image.type );

				if ( ! ( hash in self.currentImages ) ) {
					self.currentImages[ hash ] = image;
					todo.push( image );
				}
			} );

			self.$el.trigger( 'reset' );

			self.previewImages( todo );
			self.uploadImages( todo );
		},

		previewImages: function( images ) {
			var self = this;

			$.each( images, function( index, value ) {
				var reader = new FileReader();
				var image = images[ index ];
				var hash = md5( image.name + image.size + image.type );

				reader.onload = function ( file ) {
					var image = new Image();
					image.src = file.target.result;

					image.onload = function() {
						self.$container.prepend(
							self.template( {
								'hash' : hash,
								imageSrc : this.src
							} )
						);
					}
				}

				reader.readAsDataURL( image );
			} );

			// Call recalculateHeights after a small delay
			setTimeout( self.recalculateHeights, 500 );
		},

		recalculateHeights: function() {
			var i = 0, tmp = [], max = 0;

			$( '.upload-image-wrapper' ).each( function() {
				$el = $( this );
				$el.height( 'auto' );
				//console.log($el.outerHeight());
				if ( i < 3 ) {
					max = Math.max( max, $el.outerHeight() );
					i = i + 1;
					tmp.push( $el );
				} else if ( i === 3 ) {
					max = Math.max( max, $el.outerHeight() );
					tmp.push( $el );

					// we have 4 items
					$.each( tmp, function( index, value ) {
						value.height( max + 'px' );
					} );

					i = 0;
					max = 0;
					tmp = [];
				}
			} );
		},

		uploadImages: function( images ) {
			var self = this;
			$.each( images, function( index ) {
				var image = images[ index ];
				var hash = md5( image.name + image.size + image.type );
				var data = new FormData();
				data.append( 'image', image);
				data.append( 'hash', hash );
				$.ajax({
					url: ajaxURL + 'upload/',
					data: data,
					type: 'POST',
					processData: false,
					contentType: false,
					dataType: 'json',
					success: function( e ) {
						var image = self.$container.find( '[data-hash="' + e.hash + '"]' );
					},
					xhr: function() {
						// Add a custom event listener for progress status
						var xhr = $.ajaxSettings.xhr();
						if( xhr.upload )
							xhr.upload.addEventListener( 'progress', self.showProgress, false );

						return xhr;
					}
				} );
			} );
		},

		showProgress: function( e ) {
			if ( e.lengthComputable ) {
				var percentComplete = ( e.loaded / e.total ) * 100;
			}
		}
	}

	$( function() {
		imageUploader.init();
	});

})(jQuery);


