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
 * Controller for register action.
 */
class Register_Controller extends Controller {

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
		if ( is_user_logged_in() ) {
			redirect( get_site_url( '/' ) );
			exit;
		}

		if ( 'POST' === $request->method ) {
			$this->run_register();
		} else {
			$view = new View( 'register/index' );
			$view->set_page_title( 'Register' );

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
		if ( is_user_logged_in() ) {
			redirect( get_site_url( '/' ) );
			exit;
		}

		$view = new View( 'register/success' );
		$view->set_page_title( 'Success | Register' );
		$view->render();
	}

	/**
	 * Handles user registration.
	 *
	 * @return void
	 */
	private function run_register() {
		$result = User_Manager::validate_new_user( (array) $_POST );

		if ( ! $result['valid'] ) {
			$view = new View( 'register/index' );
			$view->set_page_title( 'Register' );

			$extra_footer = '
				<script>var _zxcvbnURL = "' . get_assets_url( 'js/zxcvbn.js' ) . '";</script>
				<script src="' . get_assets_url( 'js/libs/zxcvbn-async.js' ) . '"></script>
				<script src="' . get_assets_url( 'js/password-strength.js' ) . '"></script>
			';
			$view->set_extra_footer( $extra_footer );
			$view->assign( 'error', $result['errors'] );
			$view->render();
		} else {
			$result = User_Manager::create_user( $result['sanitized_user'] );

			if ( $result ) {
				redirect( get_site_url( 'register/success/' ) );
				exit;
			}
		}
	}

}
