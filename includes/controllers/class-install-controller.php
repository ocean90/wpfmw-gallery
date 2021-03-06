<?php
/**
 * Gallery - A project for 'WPF - Moderne Webanwendungen' at
 * Cologne University of Applied Sciences.
 *
 * @author    Dominik Schilling <dominik.schilling@smail.fh-koeln.de>
 * @author    Laura Hermann <laura.hermann@smail.fh-koeln.de>
 * @author    Dario Vizzaccaro <dario.vizzaccaro@smail.fh-koeln.de>
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
		$this->check_install_status();

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
			$this->run_register();
		} else {
			$view = new View( 'install/register' );
			$view->set_page_title( 'Register | Installation' );

			$extra_footer = '
	<script>var _zxcvbnURL = "' . get_assets_url( 'js/libs/zxcvbn.js' ) . '";</script>
	<script src="' . get_assets_url( 'js/zxcvbn-async.js' ) . '"></script>
	<script src="' . get_assets_url( 'js/password-strength.js' ) . '"></script>
			';
			$view->set_extra_footer( $extra_footer );
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
	 * Checks the current install status.
	 *
	 * @return void
	 */
	private function check_install_status() {
		global $db;

		// Check if a table exists
		$result = $db->query( "SHOW TABLES LIKE '$db->galleries'" );

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
			exit;
		}
	}

	/**
	 * Handles admin register action.
	 *
	 * @return void
	 */
	private function run_register() {
		$result = User_Manager::validate_new_user( (array) $_POST );

		if ( ! $result['valid'] ) {
			$view = new View( 'install/register' );
			$view->set_page_title( 'Register | Installation' );
			$view->assign( 'error', $result['errors'] );

			$extra_footer = '
	<script>var _zxcvbnURL = "' . get_assets_url( 'js/libs/zxcvbn.js' ) . '";</script>
	<script src="' . get_assets_url( 'js/zxcvbn-async.js' ) . '"></script>
	<script src="' . get_assets_url( 'js/password-strength.js' ) . '"></script>
			';
			$view->set_extra_footer( $extra_footer );
			$view->render();
		} else {
			$result = User_Manager::create_user( $result['sanitized_user'] );

			if ( $result ) {
				redirect( get_site_url( '_install/success/' ) );
				exit;
			}
		}
	}
}
