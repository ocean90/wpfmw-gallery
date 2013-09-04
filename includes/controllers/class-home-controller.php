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
			$this->index_for_logged_in();
		} else {
			$this->index_for_public();
		}
	}

	private function index_for_logged_in() {
		$galleries = Gallery_Manager::get_galleries( array(
			'limit'        => 10,
			'images_limit' => 2,
			'order'        => 'DESC'
		) );

		$view = new View( 'home/index' );
		$view->assign( 'galleries', $galleries );
		$view->set_page_title( 'Timeline | Gallery' );
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

	private function index_for_public() {
		$view = new View( 'home/index-public' );
		$view->set_page_title( 'Welcome | Gallery' );
		$extra_footer = '
		<script>
		( function( $ ) {
			$( function() {
				$( ".carousel" ).carousel();
			} );
		} )( jQuery );
		</script>
		';
		$view->set_extra_footer( $extra_footer );
		$view->render();
	}

}
