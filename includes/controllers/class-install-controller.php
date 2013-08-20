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
	#	$this->check_install_status();

		// Check HTTP method, if is post install button
		// was clicked
		if ( 'POST' === $request->method ) {
			$this->run_install();
		} else {
			$view = new View( 'install/index' );
			$view->set_page_title( 'Welcome | Installation' );
			$view->render();
		}
	}

	/**
	 * Handles account creation of admin account
	 *
	 * @return void
	 */
	public function register( $request ) {
		if ( 'POST' === $request->method ) {
			$this->register_admin();
		} else {
			$view = new View( 'install/register' );
			$view->set_page_title( 'Register | Installation' );
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
		$view->set_page_title( 'Sucess | Installation' );
		$view->render();
	}

	/**
	 * Check the current install status
	 *
	 * @return void
	 */
	private function check_install_status() {
		global $db;

		// Check if a table exists
		$result = $db->query( "SHOW TABLES LIKE '$db->gallery'" );

		// If already installed, redirect to home page
		if ( ! empty( $result ) ) {
			redirect( get_site_url() );
			exit;
		}
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
			redirect( get_site_url( '_install/register/' ) );
		}
	}

	private function register_admin() {
		debug( $_POST );

		$manager = new User_Manager();
		$result = $manager->validate_new_user( (array) $_POST );
		var_dump( $result );
	}
}
