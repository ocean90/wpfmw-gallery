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
 * Controller for user actions.
 */
class User_Controller extends Controller {

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
		if ( ! User_Manager::is_user_logged_in() ) {
			redirect( get_site_url( '/login/' ) );
			exit;
		}

		if ( empty( $request->segments[1] ) ) {
			redirect( get_site_url( '/' ) );
			exit;
		} else {
			$user = User_Model::get_user_by( 'username', $request->segments[1] );
		}

		if ( ! empty( $user ) ) {

			$galleries = Gallery_Manager::get_galleries( array(
				'user_id'   => $user->ID,
				'limit'     => 20,
				'order'     => 'DESC',
				'is_public' => ( $user->ID === User_Manager::get_current_user()->ID ) ? -1 : 1, // Show private galleries if creator = current user
			) );

			$view = new View( 'user/index' );
			$view->set_page_title( $user->user_login . ' | User' );
			$view->assign( 'user', $user );
			$view->assign( 'galleries', $galleries );
			$view->render();
		} else {
			show_404();
		}

	}

}
