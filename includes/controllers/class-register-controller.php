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
	public function index() {
		if ( is_user_logged_in() ) {
			redirect( get_site_url( '/' ) );
			exit;
		}

		$view = new View( 'register/index' );
		$view->set_page_title( 'Register' );

		$extra_footer = '
<script>var _zxcvbnURL = "' . get_assets_url( 'js/zxcvbn.js' ) . '";</script>
<script src="' . get_assets_url( 'js/zxcvbn-async.js' ) . '"></script>
<script src="' . get_assets_url( 'js/password-strength.js' ) . '"></script>
		';
		$view->set_extra_footer( $extra_footer );
		$view->render();
	}

}
