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
				.addClass( 'image-wrapper' )
				.append(
					$( '<img/>' )
					.addClass( 'img-thumbnail' )
					.attr( 'src', data.imageSrc )
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
							//$( '<img/>' ).addClass( 'img-thumbnail image-to-upload' ).attr( 'src', this.src )
						);
					}
				}

				reader.readAsDataURL( image );
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
						console.log( e.hash );

						console.log( self.$container.find( '[data-hash="' + e.hash + '"]' ));
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
				console.log(percentComplete);
			}
		}
	}

	$( function() {
		imageUploader.init();
	});

})(jQuery);


