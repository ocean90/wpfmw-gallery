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
	public function index() {
		$view = new View( 'upload/index' );
		$view->set_page_title( 'Upload' );
		$view->render();
	}

}