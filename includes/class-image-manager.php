<?php
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

/**
 * Handles images creation, etc
 */
class Image_Manager {

	/**
	 * Constructor.
	 */
	function __construct( ) {
	}

	/**
	 * Retrieves default thumbnail sizes.
	 *
	 * @return array Array with thumb sizes.
	 */
	public static function get_thumb_sizes() {
		return array(
			'thumb-400-800' => array( 'w' => 400, 'h' => 800, 'crop' => false ),
			'thumb-200-200' => array( 'w' => 200, 'h' => 200, 'crop' => true ),
			'thumb-350-260' => array( 'w' => 350, 'h' => 260, 'crop' => true ),
			'thumb-750-350' => array( 'w' => 750, 'h' => 350, 'crop' => true ),
			'thumb-1200-300' => array( 'w' => 1200, 'h' => 300, 'crop' => true ),
		);
	}

	/**
	 * Creats thumbs for an uploaded images.
	 *
	 * @param  array   $image Array with filepath and image ID.
	 * @return boolean        True on success, false on failure.
	 */
	public static function create_thumbs( $image ) {
		global $db;

		// Holds generated thumbs
		$thumbs = array();

		// Create a thumb for each size
		foreach ( self::get_thumb_sizes() as $key => $size ) {
			// Create a new instance
			$thumbnail = new Image_Editor( $image[ 'filepath' ] );

			// Load the image
			$thumbnail->load();

			// Resize/crop the image
			$result = $thumbnail->resize( $size[ 'w' ], $size[ 'h' ], $size[ 'crop' ] );

			if ( ! $result )
				continue;

			// Save the image to disk
			$result = $thumbnail->save();

			if ( ! $result )
				continue;

			// Remove not needed keys
			unset( $result[ 'path'] );
			unset( $result[ 'mime-type'] );

			// Add to queue to save
			$thumbs[ $key ] = $result;
		}

		if ( empty( $thumbs ) )
			return true;

		// Save thumbs infos as meta
		$query = $db->prepare(
			"INSERT INTO $db->imagemeta (`image_id`, `meta_key`, `meta_value` ) VALUES ( %d, %s, %s )",
			array(
				$image[ 'ID' ],
				'thumbs',
				maybe_serialize( $thumbs )
			)
		);

		$result = $db->query( $query );

		return $result;
	}

	/**
	 * Returns an url to a full image, or, if specified to an thumb.
	 *
	 * @param  Image_Model|int  $image  The image.
	 * @param  boolean|string   $thumb  False for full image, thumb key for thumb.
	 * @return string                   URL to image file.
	 */
	public static function get_url_of_image( $image, $thumb = false ) {
		// Get the user ID and filename of the image
		if ( $image instanceof Image_Model ) {
			$user_id = $image->user_id;
			$filename = $image->image_filename;
		} else if ( is_numeric( $image ) ) {
			$image = new Image_Model( $image );
			if ( empty( $image->ID ) ) {
				return false;
			} else {
				$user_id = $image->user_id;
				$filename = $image->image_filename;
			}
		} else {
			return false;
		}

		// Check if a thumb should be returned
		if ( $thumb && in_array( $thumb, array_keys( self::get_thumb_sizes() ) ) ) {
			if ( ! empty( $image->thumbs[ $thumb ] ) ) {
				$filename = $image->thumbs[ $thumb ][ 'file' ];
			}
		}

		return get_content_url( $user_id . '/' . $filename );
	}

	/**
	 * Creates an database entry for an image.
	 * Image title and description are left blank and needs to be filled
	 * later
	 *
	 * @param  string   $filename The name of the uploaded file.
	 * @return int|bool            False on failure, row ID on success.
	 */
	public static function create_image( $filename ) {
		global $db;

		$query = $db->prepare(
			"INSERT INTO $db->images (`user_id`, `image_uploaded`, `image_filename`, `image_title`, `image_description`) VALUES ( %d, %s, %s, %s, %s )",
			array(
				User_Manager::get_current_user()->ID,
				gmdate( 'Y-m-d H:i:s' ),
				$filename,
				'',
				''
			)
		);
		$result = $db->query( $query );

		if ( $result )
			return $db->insert_id;
		else
			return false;
	}

	/**
	 * Rotates the image based on exif data.
	 *
	 * @param  string $file      Full path to an image.
	 */
	public static function maybe_rotate_image( $file ) {
		// Check only for .jpg images
		if ( exif_imagetype( $file ) != IMAGETYPE_JPEG ) {
			return;
		}

		// Read exif data
		$exif = exif_read_data( $file, 0, true );

		// No header data found
		if ( false === $exif ) {
			return;
		}

		if ( empty( $exif['IFD0']['Orientation'] ) ) {
			return;
		}

		$angle = 0;
		switch ( $exif['IFD0']['Orientation'] ) {
			case 3:
				$angle = 180;
				break;
			case 6:
				$angle = -90;
				break;
			case 8:
				$angle = 90;
				break;
		}

		if ( empty( $angle ) ) {
			return;
		}

		// Create a new instance
		$thumbnail = new Image_Editor( $file );

		// Load the image
		$thumbnail->load();

		// Resize/crop the rotate
		$result = $thumbnail->rotate( $angle );

		// Save the image to disk
		$result = $thumbnail->save( $file );
	}

	/**
	 * Creates meta items for some exif data, like GPS coodirnates
	 *
	 * @param  int    $image_id  ID of an image.
	 * @param  string $file      Full path to an image.
	 */
	public static function set_image_meta_from_exif( $image_id, $file ) {
		global $db;

		// Check only for .jpg images
		if ( exif_imagetype( $file ) != IMAGETYPE_JPEG ) {
			return;
		}

		// Read exif data
		$exif = exif_read_data( $file, 0, true );

		// No header data found
		if ( false === $exif ) {
			return;
		}

		// GPS location data
		if ( ! empty( $exif[ 'GPS' ][ 'GPSLatitude' ] ) ) {
			// Source: http://developer.nokia.com/Community/Wiki/Extract_GPS_coordinates_from_digital_camera_images_using_PHP
			$lat_ref = $exif[ 'GPS'][ 'GPSLatitudeRef' ];
			$lat = $exif[ 'GPS' ][ 'GPSLatitude' ];
			list( $num, $dec ) = explode( '/', $lat[0] );
			$lat_s = $num / $dec;
			list( $num, $dec ) = explode( '/', $lat[1] );
			$lat_m = $num / $dec;
			list( $num, $dec ) = explode( '/', $lat[2] );
			$lat_v = $num / $dec;

			$lon_ref = $exif[ 'GPS' ]['GPSLongitudeRef' ];
			$lon = $exif[ 'GPS' ][ 'GPSLongitude' ];
			list( $num, $dec ) = explode( '/', $lon[0] );
			$lon_s = $num / $dec;
			list( $num, $dec ) = explode( '/', $lon[1] );
			$lon_m = $num / $dec;
			list( $num, $dec ) = explode( '/', $lon[2] );
			$lon_v = $num / $dec;

			$lat_int = ( $lat_s + $lat_m / 60.0 + $lat_v / 3600.0 );
			// Check orientation of latitude and prefix with (-) if S
			$lat_int = ( $lat_ref == 'S' ) ? '-' . $lat_int : $lat_int;

			$lon_int = ( $lon_s + $lon_m / 60.0 + $lon_v / 3600.0 );
			// Check orientation of longitude and prefix with (-) if W
			$lon_int = ( $lon_ref == 'W' ) ? '-' . $lon_int : $lon_int;

			$gps_int = array(
				'lat' => $lat_int,
				'lng' => $lon_int
			);

			// Save thumbs infos as meta
			$query = $db->prepare(
				"INSERT INTO $db->imagemeta (`image_id`, `meta_key`, `meta_value` ) VALUES ( %d, %s, %s )",
				array(
					$image_id,
					'geolocation',
					maybe_serialize( $gps_int )
				)
			);
			$db->query( $query );
		}
	}

	/**
	 * Updates an existing database entry of an image.
	 *
	 * @param  array   $image  Array with fields to edit, like title or description.
	 * @return boolean         True on success, false on failure.
	 */
	public static function edit_image( $image ) {
		global $db;

		if ( empty( $image[ 'ID' ] ) ) {
			return false;
		}

		$data = array();
		$result = false;

		if ( isset( $image[ 'title' ] ) ) {
			$data[] = $db->prepare( '`image_title` = %s', $image[ 'title' ] );
		}

		if ( isset( $image[ 'description' ] ) ) {
			$data[] = $db->prepare( '`image_description` = %s', $image['description'] );
		}

		$query = "UPDATE $db->images SET " . implode( ', ', $data ) . " WHERE `ID` = {$image[ 'ID' ]}";
		$result = $db->query( $query );

		return $result;
	}

	/**
	 * Tries to determine the real file type of the uploaded file and checks if it's
	 * a real image. It validates the files via getimagesize().
	 *
	 * @param  array         $file The uploaded file
	 * @return boolean|array       False on failure. Array on success with name and ext key.
	 */
	public static function check_file( $file ) {
		if ( ! file_exists( $file[ 'tmp_name' ] ) ) {
			return false;
		}

		// Declare image MIME types
		$ext_to_mime = array(
			'jpg|jpeg|jpe' => 'image/jpeg',
			'gif'          => 'image/gif',
			'png'          => 'image/png',
			'bmp'          => 'image/bmp',
			'tif|tiff'     => 'image/tiff',
		);
		$mime_to_ext = array(
			'image/jpeg' => 'jpg',
			'image/png'  => 'png',
			'image/gif'  => 'gif',
			'image/bmp'  => 'bmp',
			'image/tiff' => 'tif',
		);

		// Check file extension
		$type = false;
		$ext = false;
		foreach ( $ext_to_mime as $pattern => $mime_type ) {
			$pattern = '!\.(' . $pattern . ')$!i';
			if ( preg_match( $pattern, $file[ 'name' ], $matches ) ) {
				$type = $mime_type;
				$ext = $matches[1];
				break;
			}
		}

		// Extension is not valid
		if ( ! $type ) {
			return false;
		}

		// Attempt to figure out what type of image it actually is
		$image_stats = getimagesize( $file[ 'tmp_name' ] );
		if ( ! empty( $image_stats[ 'mime'] ) && $image_stats[ 'mime' ] != $type ) {
			// Extension and real type doesn't match, if it's still an image type
			// replace the extension of the file.
			if ( ! empty( $mime_to_ext[ $image_stats[ 'mime' ] ] ) ) {
				$filename_parts = explode( '.', $file[ 'name' ] );
				array_pop( $filename_parts );
				$ext = $mime_to_ext[ $image_stats[ 'mime' ] ];
				$filename_parts[] = $ext;
				$new_filename = implode( '.', $filename_parts );

				// The new filename
				return array( 'name' => $new_filename, 'ext' => $ext );
			} else {
				return false;
			}
		}

		// File is okay, return current file name
		return array( 'name' => $file[ 'name' ], 'ext' => $ext );
	}
}
