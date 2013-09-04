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

( function( $ ){

	/**
	 * Handles the AJAX Upload.
	 *
	 * @type {Object}
	 */
	var imageUploader = {
		$el: $( '#image-uploader' ),
		$wrapper: $( '#image-uploader-wrapper' ),
		$selectButton: $( '#image-upload-button' ),
		$imagesToUpload: $( '#images' ),
		$container: $( '#image-container' ),
		$galleryContainer: $( '#gallery-container' ),
		$createGalleryButton: $( '#create-gallery-button' ),
		currentImages: [],
		events: {},

		/**
		 * Init stuff. Bind button clicks and events.
		 *
		 * @return {void}
		 */
		init: function() {
			this.$selectButton.on( 'click', $.proxy( this.triggerFileSelect, this ) );
			this.$imagesToUpload.on( 'change', $.proxy( this.imagesChanged, this ) );
			this.$container.on( 'click', '.delete-image', $.proxy( this.removeImage, this ) );
			this.$createGalleryButton.on( 'click', $.proxy( this.createGallery, this ) );

			$( this.events ).on( 'images.selected', $.proxy( this.recalculateHeights, this ) );
			$( this.events ).on( 'images.selected', $.proxy( this.toggleGalleryContainer, this ) );
			$( this.events ).on( 'images.readyToUpload', $.proxy( this.uploadImages, this ) );
		},

		/**
		 * Template for a single image.
		 *
		 * @param  {object} data
		 * @return {object}
		 */
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
								.addClass( 'control-label' )
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
								.addClass( 'control-label' )
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

		/**
		 * Toggles the visibility of the gallery box for title and
		 * description.
		 *
		 * @return {void}
		 */
		toggleGalleryContainer: function() {
			this.$galleryContainer.toggleClass( 'hidden', this.currentImages.length === 0 );
		},

		/**
		 * Trigges a button click to select files.
		 *
		 * @param  {object} e
		 * @return {void}
		 */
		triggerFileSelect: function( e ) {
			this.$imagesToUpload.trigger( 'click' );
		},

		/**
		 * Removes an image from the view.
		 *
		 * @param  {object} e
		 * @return {void}
		 */
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

		/**
		 * Callback method, which receives the user selected files.
		 *
		 * @param  {object} e
		 * @return {void}
		 */
		imagesChanged: function( e ) {
			var newImages = this.$imagesToUpload.prop( 'files' ),
				self = this,
				todo = [];

			// Go to each image and add it to queue
			$.each( newImages, function( index, value ) {
				var image = newImages[ index ];
				var hash = md5( image.name + image.size + image.type );

				if ( -1 === self.currentImages.indexOf( hash ) ) {
					self.currentImages.push( hash );
					todo.push( image );
				} else {
					self.showError( 'File <strong>' + image.name + '</strong> exists already!' );
				}
			} );

			// Reset the form again, to let the user select more images
			self.$imagesToUpload.parent( 'form' ).trigger( 'reset' );

			// Start the preview process
			self.previewImages( todo );
		},

		/**
		 * Creates a preview of the selected files. It loads the files via FileReader()
		 * and creates an canvas of the image.
		 *
		 * @param  {array} images
		 * @return {void}
		 */
		previewImages: function( images ) {
			var self = this, count, i = 0;

			count = images.length;

			// Go to each image and load it from disk to the browser
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

				// Only images < 3 MB
				if ( image.size > 3145728 ) {
					self.showError( 'File <strong>' + image.name + '</strong> exceeds size limit of 3 MB!' );
					count = count - 1;
					self.currentImages.splice( self.currentImages.indexOf( hash ), 1 );
					return;
				}

				// When file is loaded
				reader.onload = function ( file ) {
					var imageO = new Image();
					imageO.src = file.target.result;

					// When image is loaded
					imageO.onload = function() {
						// Remove an existing one (mostly a broken one)
						self.$container.find( '[data-hash="' + hash + '"]' ).remove();

						self.$container.prepend(
							self.template( {
								hash : hash,
								filename: image.name,
								image: self.imageToCanvas( this ),
							} )
						);

						i = i + 1;
						if ( i === count ) {
							$( self.events ).trigger( 'images.selected', { images: images } );
						}

						reader = null;
						imageO = null;
					}
				}

				reader.readAsDataURL( image );
			} );
		},

		/**
		 * Convert the image to an canvas.
		 *
		 * @param  {Object} image
		 * @return {string}
		 */
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

		/**
		 * Retrieve calculated resized dimensions.
		 *
		 * @param  {int} currWidth
		 * @param  {int} currHeigth
		 * @param  {int} maxWidth
		 * @param  {int} maxHeight
		 * @return {object}
		 */
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

		/**
		 * Recalculates the heights of the image wrapper to get always four images
		 * in a line.
		 *
		 * @param  {object} e
		 * @param  {object} data
		 * @return {void}
		 */
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

		/**
		 * Uploads the images via AJAX request and FormData.
		 *
		 * @param  {object} e
		 * @param  {object} data
		 * @return {void}
		 */
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
					error: function( result ) {
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

		/**
		 * Called when upload progress was successfully.
		 *
		 * @param  {object} result
		 * @param  {string} hash
		 * @return {void}
		 */
		imageUploadSuccess: function( result, hash ) {
			var $imageWrapper = this.$container.find( '[data-hash="' + hash + '"]' );
			var $progress = $( '.progress-bar', $imageWrapper );
			$progress.width( '100%' );

			$( '.image-description', $imageWrapper ).attr( 'name', 'images[' + result.id + '][description]' );
			$( '.image-title', $imageWrapper ).attr( 'name', 'images[' + result.id + '][title]' );

			$progress.parent( '.progress' ).delay( 1000 ).fadeOut( 300, function() {
				$(this).remove();
				$imageWrapper.removeClass( 'loading' );
			} );
		},

		/**
		 * Called when upload progress failed.
		 *
		 * @param  {object} result
		 * @param  {string} hash
		 * @return {void}
		 */
		imageUploadError: function( result, hash ) {
			var self = this;
			var $imageWrapper = self.$container.find( '[data-hash="' + hash + '"]' );
			var $progress = $( '.progress-bar', $imageWrapper );

			$imageWrapper.addClass( 'broken' );

			$progress.addClass( 'progress-bar-danger' );

			$( '.image-description', $imageWrapper ).prop( 'disabled', true );
			$( '.image-title', $imageWrapper ).prop( 'disabled', true );

			if ( result === 5 ) {
				self.showError( 'Uploads directory is not writeable! Please check if the root dir is writeable, try to change permissions to 777.' );
			}

			// Allow the broken image to be uploaded again
			self.currentImages.splice( self.currentImages.indexOf( hash ), 1 );
		},

		/**
		 * Handles upload status.
		 *
		 * @param  {object} e
		 * @param  {string} hash
		 * @return {void}
		 */
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

		/**
		 * Checks if all required fields are set.
		 *
		 * @return {void}
		 */
		createGallery: function() {
			var self = this;
			var hasError = false;

			// Check pending AJAX requests
			if ( $.active > 0 ) {
				alert( 'There are pending uploads. Please wait until they finished.' );
				return false;
			}

			// Check each image title and description
			$.each( self.currentImages, function( index, hash ) {
				var $imageWrapper = self.$container.find( '[data-hash="' + hash + '"]' );
				var $title = $( '.image-title', $imageWrapper );
				var $desc = $( '.image-description', $imageWrapper );

				// Remove old classes
				$title.parent( '.form-group' ).removeClass( 'has-error' );
				$desc.parent( '.form-group' ).removeClass( 'has-error' );

				if ( $.trim( $title.val() ) === '' ) {
					hasError = true;
					$title.parent( '.form-group' ).addClass( 'has-error' );
				}

				if ( $.trim( $desc.val() ) === '' ) {
					hasError = true;
					$desc.parent( '.form-group' ).addClass( 'has-error' );
				}

			} );

			var $galleryTitle = $( '#gallery-title', self.$galleryContainer );
			var $galleryDesc = $( '#gallery-description', self.$galleryContainer );

			// Remove old classes
			$galleryTitle.parent( '.form-group' ).removeClass( 'has-error' );
			$galleryDesc.parent( '.form-group' ).removeClass( 'has-error' );

			// Check gallery title
			if ( $.trim( $galleryTitle.val() ) === '' ) {
				hasError = true;
				$galleryTitle.parent( '.form-group' ).addClass( 'has-error' );
			}

			// Check gallery description
			if ( $.trim( $galleryDesc.val() ) === '' ) {
				hasError = true;
				$galleryDesc.parent( '.form-group' ).addClass( 'has-error' );
			}

			if ( hasError )
				return false;
		},

		/**
		 * Prints an error alert.
		 *
		 * @param  {string} text
		 * @return {void}
		 */
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

	// DOM is ready.
	$( function() {
		imageUploader.init();
	});

} )( jQuery );
