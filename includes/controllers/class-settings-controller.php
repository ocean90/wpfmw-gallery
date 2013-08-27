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
 * Controller for settings actions.
 */
class Settings_Controller extends Controller {

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

		if ( 'POST' === $request->method ) {
			$this->update_settings();
		} else {
			$view = new View( 'settings/index' );
			$view->set_page_title( 'Settings' );
			global $app;
			$view->assign( 'user', $app->current_user );
			$view->render();
		}

	}

}
