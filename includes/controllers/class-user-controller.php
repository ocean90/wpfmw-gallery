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
 * Controller for user actions.
 */
class User_Controller extends Controller {

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

		if ( empty( $request->segments[1] ) ) {
			redirect( get_site_url( '/' ) );
			exit;
		} else {
			$user = User_Model::get_data_by( 'login', $request->segments[1] );
		}

		$view = new View( 'user/index' );
		$view->set_page_title( 'User' . $user->user_login );
		$view->assign( 'user', $user );
		$view->render();

	}

}
