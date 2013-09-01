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
 * Controller for gallery actions.
 */
class Gallery_Controller extends Controller {

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
			$user = User_Model::get_user_by( 'username', $request->segments[1] );
		}

		if ( empty( $user ) ) {
			show_404();
			return;
		}

		if ( empty( $request->segments[3] ) ) {
			redirect( get_site_url( '/user/' . $user->user_login . '/' ) );
			exit;
		} else {
			$gallery = Gallery_Model::get_gallery_by_slug( $request->segments[3], $user->ID );
		}

		if ( ! empty( $gallery ) ) {
			$view = new View( 'gallery/index' );
			$view->set_page_title( 'Gallery' );
			$view->assign( 'gallery', $gallery );
			$view->render();
		} else {
			show_404();
		}

	}

}
