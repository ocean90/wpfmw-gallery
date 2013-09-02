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
 * User model
 */
class User_Model {

	/**
	 * User's ID.
	 *
	 * @var int
	 */
	public $ID = 0;

	/**
	 * Holds user data.
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
	 *
	 */
	function __construct( $id ) {
		$this->init( $id );
	}

	/**
	 * Sets the user data.
	 *
	 * @param  int  $id The ID of the user.
	 * @return void
	 */
	private function init( $id ) {
		$this->data = self::get_data_by( 'id', $id );

		// User doesn't exists
		if ( null === $this->data )
			return;

		$this->ID = $this->data->ID;
		$this->meta = $this->set_meta();
		$this->data->user_nicename = $this->set_user_nicename();
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

	private function set_user_nicename() {
		$name = '';
		$firstname = $this->get_meta( 'firstname' );
		if ( ! empty( $firstname ) ) {
			$name = $firstname;
		}

		$lastname = $this->get_meta( 'lastname' );
		if ( ! empty( $lastname ) ) {
			if ( empty( $name ) ) {
				$name = $lastname;
			} else {
				$name .= ' ' . $lastname;
			}
		}

		return $name;
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

		$query = $db->prepare( "SELECT * FROM $db->usermeta WHERE user_id = %d", $this->ID );

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
	 * Returns data of a user by field.
	 *
	 * @param  string  $field  id, email or login.
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
			case 'email':
				$db_field = 'user_email';
				break;
			case 'login':
			case 'username':
				$db_field = 'user_login';
				break;
			default:
				return false;
		}

		$query = $db->prepare( "SELECT * FROM $db->users WHERE $db_field = %s", $value );

		return $db->get_row( $query );
	}

	/**
	 * Returns a user by field.
	 *
	 * @param  string  $field  email or login.
	 * @param  mixed   $value  The value of the field.
	 * @return mixed           false on error, object on success.
	 */
	public static function get_user_by( $field, $value ) {
		global $db;

		$value = trim( $value );

		if ( empty( $value ) )
			return false;

		switch ( $field ) {
			case 'email':
				$db_field = 'user_email';
				break;
			case 'login':
			case 'username':
				$db_field = 'user_login';
				break;
			default:
				return false;
		}

		$query = $db->prepare( "SELECT ID FROM $db->users WHERE $db_field = %s", $value );

		$result = $db->get_row( $query );

		if ( ! empty ( $result ) ) {
			return new User_Model( $result->ID );
		} else {
			return null;
		}
	}
}
