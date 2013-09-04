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
			$extra_header = '
				<link rel="stylesheet" href="' . get_assets_url( 'css/libs/fancybox/jquery.fancybox.css' ) . '" type="text/css" media="screen">
				<link rel="stylesheet" href="' . get_assets_url( 'css/libs/fancybox/jquery.fancybox-thumbs.css' ) . '" type="text/css" media="screen">
			';
			$view->set_extra_header( $extra_header );
			$extra_footer = '
			<script src="' . get_assets_url( 'js/libs/jquery.fancybox.pack.js' ) . '"></script>
			<script src="' . get_assets_url( 'js/libs/jquery.fancybox-thumbs.js' ) . '"></script>
			<script src="' . get_assets_url( 'js/image-gallery.js' ) . '"></script>
			';
			$view->set_extra_footer( $extra_footer );
			$view->render();
		} else {
			show_404();
		}

	}

}
