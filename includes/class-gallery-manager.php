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
 * Handles gallery creation, etc
 */
class Gallery_Manager {

	/**
	 * Constructor.
	 */
	function __construct( ) {
	}

	/**
	 * Creates a database entry for an image.
	 * Image title and description are left blank and should to be filled
	 * later.
	 *
	 * @param  string   $filename The name of the uploaded file.
	 * @return int|bool            False on failure, row ID on success.
	 */
	public static function create_gallery( $gallery ) {
		global $db;

		$slug = self::unique_slug( sanitize_slug( $gallery[ 'title' ] ) );

		$query = $db->prepare(
			"INSERT INTO $db->galleries (`user_id`, `is_public`, `gallery_title`, `gallery_slug`, `gallery_description`, `gallery_created`) VALUES ( %d, %d, %s, %s, %s, %s )",
			array(
				$gallery[ 'user_id' ],
				(int) $gallery[ 'is_public' ],
				$gallery[ 'title' ],
				$slug,
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

	/**
	 * Creates relationships between gallery and images.
	 *
	 * @param  int    $gallery_id The ID of a gallery.
	 * @param  array  $image_ids  Array of image IDs.
	 * @return void
	 */
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
	}

	/**
	 * Checks for an unique slug.
	 * A slug must be unique per user. If a slug exists already it will get a suffix
	 *
	 * @param  string $slug  Slug of a gallery.
	 * @return string        Unique slug of a gallery.
	 */
	public static function unique_slug( $slug ) {
		global $db;

		$current_user_id = User_Manager::get_current_user()->ID;
		$check_sql = "SELECT `gallery_slug` FROM $db->galleries WHERE `gallery_slug` = %s AND `user_id` = %d LIMIT 1";

		$slug_check = $db->get_field( $db->prepare( $check_sql, array( $slug, $current_user_id ) ) );

		if ( $slug_check ) {
			/*
			 * Slugs exists, append a suffix and check again
			 * until we find an unique slug.
			 */

			$suffix = 2; // Start with suffix = 2 because the is already one gallery with the same title
			do {
				$alt_slug = $slug . '-' . $suffix;
				$slug_check = $db->get_field( $db->prepare( $check_sql, array( $alt_slug, $current_user_id ) ) );
				$suffix = $suffix + 1;
			} while ( $slug_check );

			// Unique slug found
			$slug = $alt_slug;
		}

		return $slug;
	}

}
