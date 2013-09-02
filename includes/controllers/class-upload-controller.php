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
 * Controller for upload action.
 */
class Upload_Controller extends Controller {

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

		if ( 'POST' === $request->method ) {
			$this->create_gallery();
		} else {
			$view = new View( 'upload/index' );
			$view->set_page_title( 'Upload' );
			$extra_footer = '
				<script>var ajaxURL = "' . get_site_url( 'ajax/' ) . '";</script>
				<script src="' . get_assets_url( 'js/libs/md5.min.js' ) . '"></script>
				<script src="' . get_assets_url( 'js/image-uploader.js' ) . '"></script>
			';
			$view->set_extra_footer( $extra_footer );
			$view->render();
		}
	}

	private function create_gallery() {
		$current_user = User_Manager::get_current_user();
		$gallery = array();
		$gallery[ 'user_id' ] = $current_user->ID;
		$gallery[ 'title' ] = trim( $_POST[ 'gallery-title' ] );
		$gallery[ 'description' ] = trim( $_POST[ 'gallery-description' ] );
		$gallery[ 'is_public' ] = ! empty( $_POST[ 'gallery-is-public' ] );

		$new_gallery = Gallery_Manager::create_gallery( $gallery );

		$images = (array) $_POST[ 'images' ];
		$image_ids = array_keys( $images );

		// Update image titles and descriptions
		foreach ( $images as $image_id => $data ) {
			$image = array_merge( array( 'ID' => $image_id ), $data );
			Image_Manager::edit_image( $image );
		}

		// Create relationships
		Gallery_Manager::create_relationships( $new_gallery[ 'id' ], $image_ids );

		if ( $gallery ) {
			var_dump($gallery);
			$path = sprintf( 'user/%s/gallery/%s/', $current_user->user_login, $new_gallery[ 'slug' ] );
			redirect( get_site_url( $path ) );
			exit;
		} else {
			var_dump( $gallery );
		}
	}

}
