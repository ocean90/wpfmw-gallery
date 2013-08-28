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
 * Controller for logout action.
 */
class Logout_Controller extends Controller {

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

		$this->run_logout();
	}

	/**
	 * Runs the logout.
	 *
	 * @return void
	 */
	private function run_logout() {
		global $app;

		$app->session->destroy();

		redirect( get_site_url( '/login/?loggedout' ) );
		exit;
	}

}
