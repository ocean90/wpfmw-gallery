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
class Upload_Controller extends Controller {

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
		if ( ! is_user_logged_in() ) {
			redirect( get_site_url( '/login/' ) );
			exit;
		}

		// was clicked
		if ( 'POST' === $request->method ) {
			$this->run_upload();
		} else {
			$view = new View( 'upload/index' );
			$view->set_page_title( 'Upload' );
			$extra_footer = '
				<script src="' . get_assets_url( 'js/image-uploader.js' ) . '"></script>
			';
			$view->set_extra_footer( $extra_footer );
			$view->render();
		}
	}

	private function run_upload() {
		$raw_files = $_FILES[ 'images' ];
		$files_count = 0;

		if ( is_array( $raw_files[ 'name' ] ) ) {
			$files_count = count( $raw_files[ 'name' ] );
		}

		// No files selected
		if ( 0 === $files_count ) {
			redirect( get_site_url( '/upload/' ) );
			exit;
		}

		$files = array();
		for ( $i = 0; $i < $files_count; $i++ ) {
			$files[] = array(
				'name'     => $raw_files[ 'name' ][ $i ],
				'type'     => $raw_files[ 'type' ][ $i ],
				'tmp_name' => $raw_files[ 'tmp_name' ][ $i ],
				'error'    => $raw_files[ 'error' ][ $i ],
				'size'     => $raw_files[ 'size' ][ $i ],
			);
		}

		var_dump($_POST);
		var_dump($files);
	}

}
