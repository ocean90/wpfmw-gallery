<?php
/**
 * Gallery - A project for 'WPF - Moderne Webanwendungen' at
 * Cologne University of Applied Sciences.
 *
 * @author    Dominik Schilling <dominik.schilling@smail.fh-koeln.de>
 * @author    Laura Hermann
 * @author    Dario Vizzaccaro
 * @link      https://github.com/ocean90/wpfmw-gallery
 * @license   MIT
 */

/**
 * Users model
 */
class User_Model {

	public $username;
	public $email;
	public $first_name;
	public $last_name;

	/**
	 * Constructor.
	 *
	 */
	function __construct( $id ) {
		$this->set_user( $id );
	}


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

		$query = $db->prepare( "SELECT * FROM $db->users WHERE $db_field = \"%s\"", $value );
		debug($query);

		$user = $db->query( $query );
		return $user;
	}
}
