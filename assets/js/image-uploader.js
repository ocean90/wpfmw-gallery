( function( $ ){

	var imageUploader = {
		$el: $( '#image-uploader' ),
		$container: $( '#image-container' ),

		init: function() {
			this.$el.on( 'submit', $.proxy( this.uploadRequest, this ) );
			this.$el.on( 'change', $.proxy( this.previewImages, this ) );
		},

		uploadRequest: function( e ) {
			e.preventDefault();
			var data = new FormData();
			var images = $( '#images', this.$el ).prop( 'files' );
			console.log(  );
		},

		previewImages: function( e ) {
			e.preventDefault();
			var images = $( '#images', this.$el ).prop( 'files' );
			var self = this;
			this.$container.empty();

			$.each( images, function( index, value ) {
				var reader = new FileReader();
				reader.onload = function ( file ) {
					var image = new Image();
					image.src = file.target.result;

					image.onload = function() {
						console.log(this.width);
						self.$container.append(
							$( '<img/>').addClass( 'img-thumbnail image-to-upload' ).attr('src', this.src )
						);
					}
				}
				console.log( images[ index ].name );
				reader.readAsDataURL( images[ index ] );
			} );
		}
	}

	$( function() {
		imageUploader.init();
	});

})(jQuery);


