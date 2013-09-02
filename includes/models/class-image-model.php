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
 * Image model
 */
class Image_Model {

	/**
	 * Image's ID.
	 *
	 * @var int
	 */
	public $ID = 0;

	/**
	 * Holds image data.
	 *
	 * @var object
	 */
	public $data = null;

	/**
	 * Cache for meta values.
	 *
	 * @var array;
	 */
	private $meta = array();

	/**
	 * Constructor.
	 */
	function __construct( $id ) {
		$this->init( $id );
	}

	/**
	 * Sets the image data.
	 *
	 * @param  int  $id The ID of the image.
	 * @return void
	 */
	private function init( $id ) {
		$this->data = self::get_data_by( 'id', $id );

		// Image doesn't exists
		if ( null === $this->data )
			return;

		$this->ID = $this->data->ID;
		$this->meta = $this->set_meta();
	}

	public function __get( $key ) {
		if ( ! $this->ID )
			return false;

		if ( isset( $this->data->$key ) ) {
			return $this->data->$key;
		} else {
			return $this->get_meta( $key );
		}
	}

	public function __isset( $key ) {
		if ( isset( $this->data->$key ) ) {
			return true;
		} else {
			return (bool) $this->get_meta( $key );
		}
	}

	public function get_meta( $key ) {
		global $db;

		if ( ! $this->ID )
			return false;

		if ( ! isset( $this->meta[ $key ] ) )
			return null;

		return $this->meta[ $key ];
	}

	private function set_meta() {
		global $db;

		if ( ! $this->ID )
			return false;

		$query = $db->prepare( "SELECT * FROM $db->imagemeta WHERE image_id = %d", $this->ID );

		$results = $db->get_results( $query );

		if ( empty ( $results ) ) {
			return array();
		}

		$meta = array();
		foreach ( $results as $index => $value ) {
			$meta[ $value->meta_key ] = is_serialized( $value->meta_value ) ? unserialize( $value->meta_value ) : $value->meta_value;
		}

		return $meta;
	}

	/**
	 * Returns data of a image by field.
	 *
	 * @param  string  $field  id or filename.
	 * @param  mixed   $value  The value of the field.
	 * @return mixed           false on error, object on success.
	 */
	public static function get_data_by( $field, $value ) {
		global $db;

		if ( 'id' === $field ) {
			if ( ! is_numeric( $value ) )
				return false;
			$value = intval( $value );
			if ( $value < 1 )
				return false;
		} else {
			$value = trim( $value );
		}

		if ( empty( $value ) )
			return false;

		switch ( $field ) {
			case 'id':
				$db_field = 'ID';
				break;
			case 'filename':
				$db_field = 'image_filename';
				break;
			default:
				return false;
		}

		$query = $db->prepare( "SELECT * FROM $db->images WHERE $db_field = %s", $value );

		return $db->get_row( $query );
	}

	/**
	 * Returns a image by field.
	 *
	 * @param  string  $field  filename
	 * @param  mixed   $value  The value of the field.
	 * @return mixed           false on error, object on success.
	 */
	public static function get_image_by( $field, $value ) {
		global $db;

		$value = trim( $value );

		if ( empty( $value ) )
			return false;

		switch ( $field ) {
			case 'filename':
				$db_field = 'image_filename';
				break;
			default:
				return false;
		}

		$query = $db->prepare( "SELECT ID FROM $db->images WHERE $db_field = %s", $value );

		$result = $db->get_row( $query );

		if ( ! empty ( $result ) ) {
			return new Image_Model( $result->ID );
		} else {
			return null;
		}
	}
}
