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
 * Handles galleries creation, etc
 */
class Gallery_Manager {

	/**
	 * Constructor.
	 */
	function __construct( ) {
	}

	/**
	 * Creates an database entry for an image
	 * Image title and description are left blank and needs to be filled
	 * later
	 *
	 * @param  string   $filename The name of the uploaded file.
	 * @return int|bool            False on failure, row ID on success.
	 */
	public static function create_gallery( $gallery ) {
		global $db;

		$query = $db->prepare(
			"INSERT INTO $db->galleries (`user_id`, `is_public`, `gallery_title`, `gallery_description`, `gallery_created`) VALUES ( %d, %d, %s, %s, %s )",
			array(
				User_Manager::get_current_user()->ID,
				(int) $gallery[ 'is_public' ],
				$gallery[ 'title' ],
				$gallery[ 'description' ],
				gmdate( 'Y-m-d H:i:s' ),
			)
		);

		$result = $db->query( $query );
		if ( $result )
			return $db->insert_id;
		else
			return false;
	}

	public static function create_relationships( $gallery_id, $image_ids ) {
		global $db;

		$queries = array();
		foreach ( $image_ids as $image_id ) {
			$queries[] = $db->prepare(
				"INSERT INTO $db->image_relationships (`image_id`, `gallery_id` ) VALUES ( %d, %d )",
				array(
					$image_id,
					$gallery_id
				)
			);
		}

		$result = $db->multi_query( implode( ';', $queries ) );
		var_dump($result);
	}

}
