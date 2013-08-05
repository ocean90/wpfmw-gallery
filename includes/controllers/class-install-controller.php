<?php
/**
 * Gallery - A project for 'WPF - Moderne Webanwendungen' at
 * Cologne University of Applied Sciences.
 *
 * @author    Dominik Schilling <dominik.schilling@smail.fh-koeln.de>
 * @author    Laura Hermann
 * @author    Dario Vizzaccaro
 * @link      https://github.com/ocean90/wpfmw-gallery
 * @license   MIT
 */

/**
 * Controller for install action.
 */
class Install_Controller extends Controller {

	/**
	 * Constructor.
	 */
	function __construct() {
		parent::__construct();
	}

	/**
	 * Default action.
	 *
	 * @return void
	 */
	public function index( $request ) {
		// Check HTTP method, if is post install button
		// was clicked
		if ( 'POST' === $request->method ) {
			$this->run_install();
		} else {
			$view = new View( 'install/index' );
			$view->set_page_title( 'Installation' );
			$view->render();
		}
	}

	/**
	 * Handles successfully installation action.
	 *
	 * @return void
	 */
	public function success( $request ) {
		$view = new View( 'install/success' );
		$view->set_page_title( 'Installation' );
		$view->render();
	}

	/**
	 * Runs the installation.
	 * Creates the needed tables from db-schema.php.
	 *
	 * @return void
	 */
	private function run_install() {
		global $db;
		include APP_INCLUDES_PATH . 'db-schema.php';

		$result = $db->multi_query( $schema );

		if ( $result ) {
			redirect( get_site_url( '_install/success/' ) );
		}
	}
}
