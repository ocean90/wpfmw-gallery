( function( $ ){

	var imageUploader = {
		$el: $( '#image-uploader' ),
		$wrapper: $( '#image-uploader-wrapper' ),
		$button: $( '#image-upload-button' ),
		$imagesToUpload: $( '#images' ),
		$container: $( '#image-container' ),
		$galleryContainer: $( '#gallery-container' ),
		currentImages: [],
		events: {},

		init: function() {
			this.$button.on( 'click', $.proxy( this.triggerFileSelect, this ) );
			this.$imagesToUpload.on( 'change', $.proxy( this.imagesChanged, this ) );

			this.$container.on( 'click', '.delete-image', $.proxy( this.removeImage, this ) );

			$( this.events ).on( 'images.selected', $.proxy( this.recalculateHeights, this ) );
			$( this.events ).on( 'images.selected', $.proxy( this.toggleGalleryContainer, this ) );
			$( this.events ).on( 'images.readyToUpload', $.proxy( this.uploadImages, this ) );
		},

		template: function( data ) {
			return $( '<div/>' )
				.attr( 'data-hash', data.hash )
				.addClass( 'upload-image-wrapper clearfix loading' )
				.append(
					data.image
				)
				.append(
					$( '<div/>' )
						.addClass( 'progress' )
						.append(
							$( '<div/>' )
							.addClass( 'progress-bar progress-bar-success' )
							.width( '5%' )
						)
				)
				.append(
					$( '<small/>' )
						.addClass( 'help-block' )
						.append(
							$( '<strong/>' )
								.text(
									'Filename: '
								)
						)
						.append(
							data.filename
						)
				)
				.append(
					$( '<div/>')
						.addClass( 'form-group')
						.append(
							$( '<label/>')
								.attr( 'for', 'image-title-' + data.hash )
								.text(
									'Title'
								)
						)
						.append(
							$( '<input/>')
								.addClass( 'form-control image-title' )
								.attr( 'placeholder', 'Enter Image Title' )
								.attr( 'id', 'image-title-' + data.hash )
						)
				)
				.append(
					$( '<div/>')
						.addClass( 'form-group')
						.append(
							$( '<label/>')
								.attr( 'for', 'image-description-' + data.hash )
								.text(
									'Description'
								)
						)
						.append(
							$( '<textarea/>')
								.addClass( 'form-control image-description' )
								.attr( 'rows', 3 )
								.attr( 'placeholder', 'Enter Image Description' )
								.attr( 'id', 'image-description-' + data.hash )
						)
				)
				.append(
					$( '<button/>')
						.attr( 'type', 'button' )
						.addClass( 'btn btn-danger btn-xs pull-right delete-image' )
						.attr( 'data-hash', data.hash )
						.text(
							'Delete'
						)
				);
		},

		toggleGalleryContainer: function() {
			this.$galleryContainer.toggleClass( 'hidden', this.currentImages.length === 0 );
		},

		triggerFileSelect: function( e ) {
			this.$imagesToUpload.trigger( 'click' );
		},

		removeImage: function( e ) {
			var self = this;
			var hash = $( e.target ).data( 'hash' );
			var $imageWrapper = self.$container.find( '[data-hash="' + hash + '"]' );
			self.currentImages.splice( self.currentImages.indexOf( hash ), 1 );

			$imageWrapper.fadeOut( 300, function() {
				$( this ).remove();
				self.recalculateHeights( {}, {} );
				self.toggleGalleryContainer();
			} );
		},

		imagesChanged: function( e ) {
			var newImages = this.$imagesToUpload.prop( 'files' ),
				self = this,
				todo = [];

			$.each( newImages, function( index, value ) {
				var image = newImages[ index ];
				var hash = md5( image.name + image.size + image.type );

				if ( ! ( hash in self.currentImages ) ) {
					self.currentImages.push( hash );
					todo.push( image );
				} else {
					self.showError( 'File <strong>' + image.name + '</strong> exists already!' );
				}
			} );

			self.$el.trigger( 'reset' );

			self.previewImages( todo );
		},

		previewImages: function( images ) {
			var self = this, count, i = 0;

			count = images.length;

			$.each( images, function( index, value ) {
				var image = images[ index ];

				var reader = new FileReader();
				var hash = md5( image.name + image.size + image.type );

				// Only process image files.
				if ( ! image.type.match( 'image.*' ) ) {
					self.showError( 'File <strong>' + image.name + '</strong> has the wrong file type!' );
					count = count - 1;
					self.currentImages.splice( self.currentImages.indexOf( hash ), 1 );
					return;
				}

				if ( image.size > 3145728 ) {
					self.showError( 'File <strong>' + image.name + '</strong> exceeds size limit of 3 MB!' );
					count = count - 1;
					self.currentImages.splice( self.currentImages.indexOf( hash ), 1 );
					return;
				}

				reader.onload = function ( file ) {
					var imageO = new Image();
					imageO.src = file.target.result;

					imageO.onload = function() {
						self.$container.prepend(
							self.template( {
								hash : hash,
								filename: image.name,
								image: self.imageToCanvas( this ),
							} )
						);

						i = i + 1;
						if ( i === count )
							$( self.events ).trigger( 'images.selected', { images: images } );
						}

						reader = null;
						imageO = null;
				}

				reader.readAsDataURL( image );
			} );
		},

		imageToCanvas: function( image ) {
			var $canvas = $( '<canvas/>' ).addClass( 'img-thumbnail' );
			var c = $canvas.get(0);
			var cx = c.getContext( '2d' );
			var dimensions = this.dimensions( image.width, image.height, 300 );

			c.width = dimensions.w;
			c.height = dimensions.h;

			cx.drawImage( image, 0, 0, dimensions.w, dimensions.h );

			return c;
		},

		dimensions: function( currWidth, currHeigth, maxWidth, maxHeight ) {
			maxWidth = maxWidth || 0;
			maxHeight = maxHeight || 0;

			if ( 0 === maxWidth && 0 === maxHeight ) {
				return { w: currWidth, h: currHeigth };
			}

			var widthRatio  = 1.0,
				heigthRatio = 1.0,
				didWidth = didHeight = false;

			if ( maxWidth > 0 && currWidth > maxWidth ) {
				widthRatio = maxWidth / currWidth;
				didWidth = true;
			}

			if ( maxHeight > 0 && currHeigth > maxHeight ) {
				heigthRatio = maxHeight / currHeigth;
				didHeight = true;
			}

			var maxRatio = Math.max( widthRatio, heigthRatio );
			var minRatio = Math.min( widthRatio, heigthRatio );

			var ratio;
			if ( parseInt( currWidth * maxRatio, 10 ) > maxWidth || parseInt( currHeigth * maxRatio, 10 ) > maxHeight ) {
				ratio = minRatio;
			} else {
				ratio = maxRatio;
			}

			w = parseInt( currWidth * ratio, 10 );
			h = parseInt( currHeigth * ratio, 10 );

			if ( didWidth && w === maxWidth - 1 ) {
				w = maxWidth;
			}
			if ( didHeight && h === maxHeight - 1 ) {
				h = maxHeight;
			}

			return { w: w, h: h };
		},

		recalculateHeights: function( e, data ) {
			var i = 0, j = 0, tmp = [], max = 0, self = this, $wrapper;

			$wrapper = $( '.upload-image-wrapper' );

			$wrapper.each( function() {
				$el = $( this );
				$el.height( 'auto' );

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

				j = j + 1;
				if ( ! $.isEmptyObject( data ) && j === $wrapper.length )
					$( self.events ).trigger( 'images.readyToUpload', data );
			} );
		},

		uploadImages: function( e, data ) {
			var self = this;

			$.each( data.images, function( index, image ) {
				var hash = md5( image.name + image.size + image.type );

				var data = new FormData();
				data.append( 'image', image);
				data.append( 'hash', hash );

				$.ajax( {
					url: ajaxURL + 'upload/',
					data: data,
					type: 'POST',
					processData: false,
					contentType: false,
					dataType: 'json',
					success: function( result ) {
						if ( result.id !== undefined )
							self.imageUploadSuccess( result, hash );
						else
							self.imageUploadError( result, hash );
					},
					xhr: function() {
						// Add a custom event listener for progress status
						var xhr = $.ajaxSettings.xhr();
						if( xhr.upload )
							xhr.upload.addEventListener( 'progress', function( e ) {
								self.showProgress( e, hash );
							}, false );

						return xhr;
					}
				} );
			} );
		},

		imageUploadSuccess: function( result, hash ) {
			var $imageWrapper = this.$container.find( '[data-hash="' + hash + '"]' );
			var $progress = $( '.progress-bar', $imageWrapper );
			$progress.width( '100%' );

			$( '.image-description', $imageWrapper ).attr( 'name', 'image[' + result.id + '][description]' );
			$( '.image-title', $imageWrapper ).attr( 'name', 'image[' + result.id + '][title]' );

			$progress.parent( '.progress' ).delay( 1000 ).fadeOut( 300, function() {
				$(this).remove();
				$imageWrapper.removeClass( 'loading' );
			} );
		},

		imageUploadError: function( result, hash ) {
			var self = this;
			var $imageWrapper = self.$container.find( '[data-hash="' + hash + '"]' );
			var $progress = $( '.progress-bar', $imageWrapper );

			$progress.addClass( 'progress-bar-danger' );

			$( '.image-description', $imageWrapper ).prop( 'disabled', true );
			$( '.image-title', $imageWrapper ).prop( 'disabled', true );

			if ( result === 5 ) {
				self.showError( 'Uploads directory is not writeable! Please check if the root dir is writeable, try to change permissions to 777.' );
			}
		},

		showProgress: function( e, hash ) {
			var $imageWrapper = this.$container.find( '[data-hash="' + hash + '"]' );
			var $progress = $( '.progress-bar', $imageWrapper );

			if ( e.lengthComputable ) {
				var percentComplete = ( e.loaded / e.total ) * 100;

				if ( percentComplete < 5 )
					percentComplete = 5;

				$progress.width( percentComplete + '%' );
			}
		},

		showError: function( text ) {
			this.$wrapper.before(
				$( '<div/>')
					.addClass( 'alert alert-danger alert-dismissable' )
					.append(
						' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'
					)
					.append(
						text
					)
			)
		}
	}

	$( function() {
		imageUploader.init();
	});

} )( jQuery );
