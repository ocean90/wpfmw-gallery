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
 * Users model
 */
class User_Model {

	/**
	 * User's ID.
	 *
	 * @var int
	 */
	public $ID;

	/**
	 * Holds user data.
	 *
	 * @var object
	 */
	public $data;

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
		if ( null !== $this->data )
			$this->ID = $this->data->ID;
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
}
