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
 * Class for creating thumbnails via the GD extension.
 * This is a trimmed version of the image editor from WordPress.
 */
class Thumbnailer {

	private $image = null;
	private $size = null;
	private $mime_type = null;
	private $default_mime_type = 'image/jpeg';
	private $quality = 90;

	function __construct( $file ) {
		$this->file = $file;
	}

	function __destruct() {
		if ( $this->image ) {
			// we don't need the original in memory anymore
			imagedestroy( $this->image );
		}
	}

	/**
	 * Loads image from $this->file into new GD Resource.
	 *
	 * @return boolean
	 */
	public function load() {
		if ( ! is_file( $this->file ) ) {
			return false;
		}

		$this->image = @imagecreatefromstring( file_get_contents( $this->file ) );

		if ( ! is_resource( $this->image ) ) {
			return false;
		}

		$size = @getimagesize( $this->file );
		if ( ! $size ) {
			return false;
		}

		if ( function_exists( 'imagealphablending' ) && function_exists( 'imagesavealpha' ) ) {
			imagealphablending( $this->image, false );
			imagesavealpha( $this->image, true );
		}

		$this->update_size( $size[0], $size[1] );
		$this->mime_type = $size[ 'mime' ];

		return true;
	}

	/**
	 * Checks to see if editor supports the mime-type specified.
	 *
	 * @param string $mime_type
	 * @return boolean
	 */
	public static function supports_mime_type( $mime_type ) {
		$image_types = imagetypes();
		switch( $mime_type ) {
			case 'image/jpeg':
				return ($image_types & IMG_JPG) != 0;
			case 'image/png':
				return ($image_types & IMG_PNG) != 0;
			case 'image/gif':
				return ($image_types & IMG_GIF) != 0;
		}

		return false;
	}

	/**
	 * Sets or updates current image size.
	 *
	 * @param int $width
	 * @param int $height
	 */
	private function update_size( $width = 0, $height = 0 ) {
		$this->size = array(
			'width'  => $width,
			'height' => $height
		);
	}

	/**
	 * Gets dimensions of image.
	 *
	 * @return array {'width'=>int, 'height'=>int}
	 */
	public function get_size() {
		return $this->size;
	}

	/**
	 * Resizes current image.
	 * Wraps _resize, since _resize returns a GD Resource.
	 *
	 * @param int $max_w
	 * @param int $max_h
	 * @param boolean $crop
	 * @return boolean
	 */
	public function resize( $max_w, $max_h, $crop = false ) {
		if ( ( $this->size['width'] == $max_w ) && ( $this->size['height'] == $max_h ) )
			return true;

		$resized = $this->_resize( $max_w, $max_h, $crop );

		if ( is_resource( $resized ) ) {
			imagedestroy( $this->image );
			$this->image = $resized;
			return true;
		}

		return false;
	}

	private function _resize( $max_w, $max_h, $crop = false ) {
		$dims = $this->image_resize_dimensions( $this->size['width'], $this->size['height'], $max_w, $max_h, $crop );
		if ( ! $dims ) {
			return false;
		}
		list( $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h ) = $dims;

		$resized = imagecreatetruecolor( $dst_w, $dst_h );
		if ( is_resource( $resized ) && function_exists( 'imagealphablending' ) && function_exists( 'imagesavealpha' ) ) {
			imagealphablending( $resized, false );
			imagesavealpha( $resized, true );
		}
		imagecopyresampled( $resized, $this->image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h );

		if ( is_resource( $resized ) ) {
			$this->update_size( $dst_w, $dst_h );
			return $resized;
		}

		return false;
	}

	/**
	 * Saves current in-memory image to file.
	 *
	 *
	 * @param string $destfilename
	 * @param string $mime_type
	 * @return array|boolean
	 */
	public function save( $filename = null, $mime_type = null ) {
		 $saved = $this->_save( $this->image, $filename, $mime_type );

		 if ( ! empty( $saved ) ) {
			$this->file = $saved['path'];
			$this->mime_type = $saved['mime-type'];
		}

		return $saved;
	}

	private function _save( $image, $filename = null, $mime_type = null ) {
		list( $filename, $extension, $mime_type ) = $this->get_output_format( $filename, $mime_type );

		if ( ! $filename )
			$filename = $this->generate_filename( null, null, $extension );

		if ( 'image/gif' == $mime_type ) {
			if ( ! $this->make_image( $filename, 'imagegif', array( $image, $filename ) ) )
				return false;
		}
		elseif ( 'image/png' == $mime_type ) {
			// convert from full colors to index colors, like original PNG.
			if ( function_exists('imageistruecolor') && ! imageistruecolor( $image ) )
				imagetruecolortopalette( $image, false, imagecolorstotal( $image ) );

			if ( ! $this->make_image( $filename, 'imagepng', array( $image, $filename ) ) )
				return false;
		}
		elseif ( 'image/jpeg' == $mime_type ) {
			if ( ! $this->make_image( $filename, 'imagejpeg', array( $image, $filename, $this->quality ) ) )
				return false;
		}
		else {
			return false;
		}

		// Set correct file permissions
		$stat = stat( dirname( $filename ) );
		$perms = $stat['mode'] & 0000666; //same permissions as parent folder, strip off the executable bits
		@ chmod( $filename, $perms );

		return array(
			'path'      => $filename,
			'file'      => $this->basename( $filename ),
			'width'     => $this->size['width'],
			'height'    => $this->size['height'],
			'mime-type' => $mime_type,
		);
	}

	/**
	 * Either calls editor's save function or handles file as a stream.
	 *
	 * @param string|stream $filename
	 * @param callable $function
	 * @param array $arguments
	 * @return boolean
	 */
	private function make_image( $filename, $function, $arguments ) {
		$result = call_user_func_array( $function, $arguments );

		return $result;
	}

	/**
	 * Builds an output filename based on current file, and adding proper suffix
	 *
	 * @param string $suffix
	 * @param string $dest_path
	 * @param string $extension
	 * @return string filename
	 */
	public function generate_filename( $suffix = null, $dest_path = null, $extension = null ) {
		// $suffix will be appended to the destination filename, just before the extension
		if ( ! $suffix )
			$suffix = $this->get_suffix();

		$info = pathinfo( $this->file );
		$dir  = $info['dirname'];
		$ext  = $info['extension'];

		$name = $this->basename( $this->file, ".$ext" );
		$new_ext = strtolower( $extension ? $extension : $ext );

		if ( ! is_null( $dest_path ) && $_dest_path = realpath( $dest_path ) )
			$dir = $_dest_path;

		return $dir . '/' . "{$name}-{$suffix}.{$new_ext}";
	}

	/**
	 * Builds and returns proper suffix for file based on height and width.
	 *
	 * @since 3.5.0
	 * @access public
	 *
	 * @return string suffix
	 */
	public function get_suffix() {
		if ( ! $this->get_size() )
			return false;

		return "{$this->size['width']}x{$this->size['height']}";
	}

	/**
	 * Returns preferred mime-type and extension based on provided
	 * file's extension and mime, or current file's extension and mime.
	 *
	 * @param string $filename
	 * @param string $mime_type
	 * @return array { filename|null, extension, mime-type }
	 */
	protected function get_output_format( $filename = null, $mime_type = null ) {
		$new_ext = $file_ext = null;
		$file_mime = null;

		// By default, assume specified type takes priority
		if ( $mime_type ) {
			$new_ext = $this->get_extension( $mime_type );
		}

		if ( $filename ) {
			$file_ext = strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) );
			$file_mime = $this->get_mime_type( $file_ext );
		}
		else {
			// If no file specified, grab editor's current extension and mime-type.
			$file_ext = strtolower( pathinfo( $this->file, PATHINFO_EXTENSION ) );
			$file_mime = $this->mime_type;
		}

		// Check to see if specified mime-type is the same as type implied by
		// file extension.  If so, prefer extension from file.
		if ( ! $mime_type || ( $file_mime == $mime_type ) ) {
			$mime_type = $file_mime;
			$new_ext = $file_ext;
		}

		if ( ! $this->supports_mime_type( $mime_type ) ) {
			$mime_type = $this->default_mime_type;
			$new_ext = $this->get_extension( $mime_type );
		}

		if ( $filename ) {
			$ext = '';
			$info = pathinfo( $filename );
			$dir  = $info['dirname'];

			if( isset( $info['extension'] ) )
				$ext = $info['extension'];

			$filename = $dir . '/' . $this->basename( $filename, ".$ext" ) . ".{$new_ext}";
		}

		return array( $filename, $new_ext, $mime_type );
	}

	/**
	 * Returns first matched mime-type from extension.
	 *
	 * @param string $extension
	 * @return string|boolean
	 */
	private static function get_mime_type( $extension = null ) {
		if ( ! $extension )
			return false;

		$mime_types = self::get_mime_types();
		$extensions = array_keys( $mime_types );

		foreach( $extensions as $_extension ) {
			if ( preg_match( "/{$extension}/i", $_extension ) ) {
				return $mime_types[$_extension];
			}
		}

		return false;
	}

	/**
	 * Returns first matched extension from Mime-type.
	 *
	 * @param string $mime_type
	 * @return string|boolean
	 */
	private static function get_extension( $mime_type = null ) {
		$extensions = explode( '|', array_search( $mime_type, self::get_mime_types() ) );

		if ( empty( $extensions[0] ) )
			return false;

		return $extensions[0];
	}

	/**
	 * Retrieve list of mime types and file extensions.
	 *
	 * @return array Array of mime types keyed by the file extension regex corresponding to those types.
	 */
	private static function get_mime_types() {
		return array(
			'jpg|jpeg|jpe' => 'image/jpeg',
			'gif' => 'image/gif',
			'png' => 'image/png',
			'bmp' => 'image/bmp',
			'tif|tiff' => 'image/tiff',
		);
	}

	/**
	 * Language friendly version of basename().
	 *
	 * @param string $path A path.
	 * @param string $suffix If the filename ends in suffix this will also be cut off.
	 * @return string
	 */
	private function basename( $path, $suffix = '' ) {
		return urldecode( basename( str_replace( array( '%2F', '%5C' ), '/', urlencode( $path ) ), $suffix ) );
	}

	/**
	 * Retrieve calculated resized dimensions.
	 *
	 * @param int $orig_w Original width.
	 * @param int $orig_h Original height.
	 * @param int $dest_w New width.
	 * @param int $dest_h New height.
	 * @param bool $crop Optional, default is false. Whether to crop image or resize.
	 * @return bool|array False on failure. Returned array matches parameters for imagecopyresampled() PHP function.
	 */
	private function image_resize_dimensions($orig_w, $orig_h, $dest_w, $dest_h, $crop = false) {

		if ($orig_w <= 0 || $orig_h <= 0)
			return false;
		// at least one of dest_w or dest_h must be specific
		if ($dest_w <= 0 && $dest_h <= 0)
			return false;

		if ( $crop ) {
			// crop the largest possible portion of the original image that we can size to $dest_w x $dest_h
			$aspect_ratio = $orig_w / $orig_h;
			$new_w = min($dest_w, $orig_w);
			$new_h = min($dest_h, $orig_h);

			if ( !$new_w ) {
				$new_w = intval($new_h * $aspect_ratio);
			}

			if ( !$new_h ) {
				$new_h = intval($new_w / $aspect_ratio);
			}

			$size_ratio = max($new_w / $orig_w, $new_h / $orig_h);

			$crop_w = round($new_w / $size_ratio);
			$crop_h = round($new_h / $size_ratio);

			$s_x = floor( ($orig_w - $crop_w) / 2 );
			$s_y = floor( ($orig_h - $crop_h) / 2 );
		} else {
			// don't crop, just resize using $dest_w x $dest_h as a maximum bounding box
			$crop_w = $orig_w;
			$crop_h = $orig_h;

			$s_x = 0;
			$s_y = 0;

			list( $new_w, $new_h ) = $this->constrain_dimensions( $orig_w, $orig_h, $dest_w, $dest_h );
		}

		// if the resulting image would be the same size or larger we don't want to resize it
		if ( $new_w >= $orig_w && $new_h >= $orig_h )
			return false;

		// the return array matches the parameters to imagecopyresampled()
		// int dst_x, int dst_y, int src_x, int src_y, int dst_w, int dst_h, int src_w, int src_h
		return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );
	}

	/**
	 * Calculates the new dimensions for a downsampled image.
	 *
	 * @param int $current_width Current width of the image.
	 * @param int $current_height Current height of the image.
	 * @param int $max_width Optional. Maximum wanted width.
	 * @param int $max_height Optional. Maximum wanted height.
	 * @return array First item is the width, the second item is the height.
	 */
	private function constrain_dimensions( $current_width, $current_height, $max_width=0, $max_height=0 ) {
		if ( ! $max_width && ! $max_height )
			return array( $current_width, $current_height );

		$width_ratio = $height_ratio = 1.0;
		$did_width = $did_height = false;

		if ( $max_width > 0 && $current_width > 0 && $current_width > $max_width ) {
			$width_ratio = $max_width / $current_width;
			$did_width = true;
		}

		if ( $max_height > 0 && $current_height > 0 && $current_height > $max_height ) {
			$height_ratio = $max_height / $current_height;
			$did_height = true;
		}

		// Calculate the larger/smaller ratios
		$smaller_ratio = min( $width_ratio, $height_ratio );
		$larger_ratio  = max( $width_ratio, $height_ratio );

		if ( intval( $current_width * $larger_ratio ) > $max_width || intval( $current_height * $larger_ratio ) > $max_height )
	 		// The larger ratio is too big. It would result in an overflow.
			$ratio = $smaller_ratio;
		else
			// The larger ratio fits, and is likely to be a more "snug" fit.
			$ratio = $larger_ratio;

		$w = intval( $current_width  * $ratio );
		$h = intval( $current_height * $ratio );

		// Sometimes, due to rounding, we'll end up with a result like this: 465x700 in a 177x177 box is 117x176... a pixel short
		// We also have issues with recursive calls resulting in an ever-changing result. Constraining to the result of a constraint should yield the original result.
		// Thus we look for dimensions that are one pixel shy of the max value and bump them up
		if ( $did_width && $w == $max_width - 1 )
			$w = $max_width; // Round it up
		if ( $did_height && $h == $max_height - 1 )
			$h = $max_height; // Round it up

		return array( $w, $h );
	}
}
