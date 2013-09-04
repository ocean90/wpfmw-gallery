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
 * Controller for search handling.
 */
class Search_Controller extends Controller {

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

		if ( 'GET' === $request->method ) {
			$this->show_results();
		} else {
			show_404();
		}
	}

	private function show_results() {
		$galleries = Gallery_Manager::get_galleries( array(
			'limit'        => 10,
			'images_limit' => 2,
			'order'        => 'DESC',
			'search'       => $_GET[ 'q' ],
		) );

		$view = new View( 'search/index' );
		$view->assign( 'galleries', $galleries );
		$view->set_page_title( 'Search' );
		$extra_footer = '
		<script>
		( function( $ ) {
			$( function() {
				$( ".carousel" ).carousel( { interval: false } );
			} );
		} )( jQuery );
		</script>
		';
		$view->set_extra_footer( $extra_footer );
		$view->render();
	}
}
