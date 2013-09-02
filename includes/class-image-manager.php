<?php
/**
 * Gallery - A project for 'WPF - Moderne Webanwendungen' at
 * Cologne University of Applied Sciences.
 *
 * @author    Dominik Schilling <dominik.schilling@smail.fh-koeln.de>
 * @author    Laura Hermann <laura.hermann@smail.fh-koeln.de>
 * @author    Dario Vizzaccaro
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

	public static function get_url_of_image( $image ) {
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

		return get_content_url( $user_id . '/' . $filename );
	}

	/**
	 * Creates an database entry for an image
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
