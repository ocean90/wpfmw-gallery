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
 * Controller for home handling.
 */
class Home_Controller extends Controller {

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
		if ( User_Manager::is_user_logged_in() ) {
			$view = new View( 'home/index' );
			$view->set_page_title( 'Timeline | Gallery' );
			$view->render();
		} else {
			$view = new View( 'home/index-public' );
			$view->set_page_title( 'Welcome | Gallery' );
			$extra_header = '
	<link rel="stylesheet" href="' . get_assets_url( 'css/libs/craftyslide.css' ) . '">
			';
			$view->set_extra_header( $extra_header );
			$extra_footer = '
	<script src="' . get_assets_url( 'js/libs/craftyslide.min.js' ) . '"></script>
	<script>
 	$("#slideshow").craftyslide();
	</script>
			';
			$view->set_extra_footer( $extra_footer );
			$view->render();
		}
	}

}
