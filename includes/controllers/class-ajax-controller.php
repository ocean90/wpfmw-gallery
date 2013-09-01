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
 * Controller for upload action.
 */
class Ajax_Controller extends Controller {

	/**
	 * Constructor.
	 */
	function __construct() {}

	/**
	 * Default action.
	 *
	 * @return void
	 */
	public function index( $request ) {
		if ( User_Manager::is_user_logged_in() ) {
			exit;
		}
	}

	/**
	 * Default action.
	 *
	 * @return void
	 */
	public function upload( $request ) {
		// Uploads are only for logged in users
		if ( ! User_Manager::is_user_logged_in() ) {
			die( '1' );
		}

		// Get the image
		$image = $_FILES[ 'image' ];

		// Check if image was uploaded via HTTP POST
		if ( ! is_uploaded_file( $image[ 'tmp_name' ] ) ) {
			die( '2' );
		}

		// Check error status
		if ( $image[ 'error' ] !== UPLOAD_ERR_OK ) { // http://php.net/manual/en/features.file-upload.errors.php
			die( '3' );
		}

		// Do some file name checks for security reasons
		if ( ! $image_file = check_image_file( $image ) ) {
			die( '4' );
		}

		// Filename = md5 hash of current time and image file name
		$filename = md5( microtime() . $image_file[ 'name' ] ) . '.' . $image_file[ 'ext' ];

		$current_user_id = User_Manager::get_current_user()->ID;
		$path = APP_CONTENT_PATH . '/' . $current_user_id . '/';

		if ( ! is_dir( APP_CONTENT_PATH ) )
			mkdir( APP_CONTENT_PATH );

		if ( ! is_dir( $path ) )
			mkdir( $path );

		// Image is okay, move it to the content dir
		if ( false === @ move_uploaded_file( $image[ 'tmp_name' ], $path . $filename ) ) {
			die( '5' );
		}

		$data = array(
			'hash'     => $_POST[ 'hash' ],
			'id'       => 1, // TODO
			'filename' => $filename
		);

		die( json_encode( $data ) );
	}

}
