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
 * Controller for login action.
 */
class Login_Controller extends Controller {

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
		// Check HTTP method, if is post login button
		// was clicked
		if ( 'POST' === $request->method ) {
			$this->run_login();
		} else {
			$view = new View( 'login/index' );
			$view->set_page_title( 'Login' );
			$view->render();
		}
	}

	/**
	 * Runs the login.
	 *
	 * @return void
	 */
	private function run_login() {
		global $app;

		$login = trim( $_POST['login'] );
		$password = trim( $_POST['password'] );

		$errors = array();

		if ( empty( $login ) )
			$errors[] = 'emptylogin';

		if ( empty( $password ) )
			$errors[] = 'emptypassword';

		// Check if username/email exists
		if ( empty( $errors ) ) {
			$user = User_Model::get_data_by( 'email', $login );

			if ( ! empty( $user ) ) {

				if ( check_password( $password, $user->user_pass ) ) {
					$app->session->regenerate();
					$app->session->set( 'user', $user->ID );

					if ( ! empty( $_POST['remember'] ) ) {
						$lifetime = 60 * 60 * 24 * 30; // 30 days
						$app->session->set_lifetime( $lifetime );
					}

					redirect( get_site_url( '/' ) );
					exit;
				} else {
					$errors[] = 'wrongpassword';
				}
			} else {
				$errors[] = 'nouser';
			}
		}

		$view = new View( 'login/index' );
		$view->set_page_title( 'Login' );
		$view->assign( 'error', $errors );
		$view->render();
	}

}
