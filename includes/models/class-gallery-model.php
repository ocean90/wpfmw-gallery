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
 * Gallery model.
 */
class Gallery_Model {

	/**
	 * Galery's ID.
	 *
	 * @var int
	 */
	public $ID = 0;

	/**
	 * Holds gallery data.
	 *
	 * @var object
	 */
	public $data = null;

	/**
	 * Holds images of the gallery.
	 *
	 * @var array;
	 */
	public $images = array();

	/**
	 * Constructor.
	 */
	function __construct( $id, $args = array() ) {
		$this->init( $id, $args );
	}

	/**
	 * Sets the gallery data.
	 *
	 * @param  int  $id The ID of a gallery.
	 * @return void
	 */
	private function init( $id, $args = array() ) {
		$this->data = self::get_data( $id );

		// Gallery doesn't exists
		if ( null === $this->data )
			return;

		$this->ID = $this->data->ID;

		$defaults = array(
			'limit'     => 10,
			'with_meta' => true
		);
		$args = array_merge( $defaults, $args );

		if ( $args[ 'with_meta' ] )
			$this->images = $this->set_images( $args[ 'limit' ] );
	}

	public function __get( $key ) {
		if ( ! $this->ID )
			return false;

		if ( isset( $this->data->$key ) ) {
			return $this->data->$key;
		} else {
			return null;
		}
	}

	private function set_images( $limit = 10 ) {
		global $db;

		if ( ! $this->ID )
			return false;

		$query = $db->prepare( "SELECT `image_id` FROM $db->image_relationships WHERE `gallery_id` = %d LIMIT %d", array( $this->ID, $limit ) );

		$results = $db->get_results( $query );

		if ( empty( $results ) ) {
			return array();
		}

		$images = array();
		foreach ( $results as $result ) {
			$image = new Image_Model( $result->image_id );
			if ( ! empty( $image->ID ) ) {
				$images[] = $image;
			}
		}

		return $images;
	}

	/**
	 * Returns data of a gallery by field.
	 *
	 * @param  int   $id  Gallery ID.
	 * @return mixed
	 */
	public static function get_data( $id ) {
		global $db;

		$query = $db->prepare( "SELECT * FROM $db->galleries WHERE `ID` = %d", $id );

		return $db->get_row( $query );
	}

	/**
	 * Returns a gallery by field.
	 *
	 * @param  string  $slug     Slug.
	 * @param  mixed   $user_id  User ID.
	 * @return mixed             False on error, object on success.
	 */
	public static function get_gallery_by_slug( $slug, $user_id ) {
		global $db;

		$slug = trim( $slug );
		if ( empty( $slug ) || empty( $user_id ) )
			return false;

		$query = $db->prepare( "SELECT `ID` FROM $db->galleries WHERE `user_id` = %d AND `gallery_slug` = %s", array( $user_id, $slug ) );
		$result = $db->get_row( $query );

		if ( ! empty ( $result ) ) {
			return new Gallery_Model( $result->ID );
		} else {
			return null;
		}
	}
}
