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
		if ( ! User_Manager::is_user_logged_in() ) {
			redirect( get_site_url( '/login/' ) );
			exit;
		}

		$view = new View( 'upload/index' );
		$view->set_page_title( 'Upload' );
		$extra_footer = '
			<script>var ajaxURL = "' . get_site_url( 'ajax/' ) . '";</script>
			<script src="' . get_assets_url( 'js/libs/md5.min.js' ) . '"></script>
			<script src="' . get_assets_url( 'js/image-uploader.js' ) . '"></script>
		';
		$view->set_extra_footer( $extra_footer );
		$view->render();
	}

}
