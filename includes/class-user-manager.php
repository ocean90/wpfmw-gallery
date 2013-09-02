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
 * Handles User creation, etc
 */
class User_Manager {

	/**
	 * Constructor.
	 */
	function __construct( ) {
	}

	/**
	 * Checks if the current visitor is a logged in user.
	 *
	 * @return boolean True if user is logged in, false if not logged in.
	 */
	public static function is_user_logged_in() {
		if ( self::get_current_user() !== null )
			return true;

		return false;
	}

	/**
	 * Returns the current user.
	 *
	 * @return User_Model The current user. Null if no user logged in.
	 */
	public static function get_current_user() {
		global $app;

		return $app->current_user;
	}

	public static function set_current_user() {
		global $app;

		$user_id = $app->session->get( 'user' );
		if ( empty( $user_id ) )
			$app->current_user = null;
		else {
			$user = new User_Model( $user_id );

			if ( $user->ID !== 0 )
				$app->current_user = $user;
			else
				$app->current_user = null;
		}
	}

	/**
	 * Creates a new user.
	 *
	 * @param  array   $user  The new user to create. Array must include username,
	 *                        email and password.
	 * @return boolean        True on success, false if not.
	 */
	public static function create_user( $user ) {
		global $db;

		$query = $db->prepare(
			"INSERT INTO $db->users (`user_login`, `user_pass`, `user_email`, `user_registered`) VALUES ( %s, %s, %s, %s )",
			array(
				$user['name'],
				hash_password( $user['password'] ),
				$user['email'],
				gmdate( 'Y-m-d H:i:s' )
			)
		);

		return $db->query( $query );
	}

	/**
	 * Updates an existing user
	 *
	 * @param  array   $user Array with fields to edit.
	 * @return boolean
	 */
	public static function edit_user( $user ) {
		global $db;

		if ( empty( $user[ 'ID' ] ) ) {
			return false;
		}

		$data = array();
		$result = false;

		// Update email
		if ( isset( $user[ 'email' ] ) ) {
			$data[] = $db->prepare( '`user_email` = %s', $user[ 'email' ] );
		}

		// Update password
		if ( isset( $user[ 'password' ] ) ) {
			$data[] = $db->prepare( '`user_pass` = %s', hash_password( $user['password'] ) );
		}

		// Update meta
		if ( isset( $user[ 'meta' ] ) ) {
			$current_user = self::get_current_user();

			foreach ( $user[ 'meta' ] as $key => $value ) {
				// Check if the key is new, means we have to create the entry
				// or an existing one, means we have to update the entry
				if ( $current_user->$key === null ) {
					$query = $db->prepare(
						"INSERT INTO $db->usermeta (`user_id`, `meta_key`, `meta_value` ) VALUES ( %d, %s, %s )",
						array(
							$user[ 'ID' ],
							$key,
							maybe_serialize( $value )
						)
					);
				} else {
					$query = $db->prepare(
						"UPDATE $db->usermeta SET `meta_value` = %s WHERE `user_id` = %d AND `meta_key` = %s",
						array(
							maybe_serialize( $value ),
							$user[ 'ID' ],
							$key
						)
					);
				}
				$result = $db->query( $query );
			}
		}

		if ( empty( $data ) ) {
			return $result;
		}

		$query = "UPDATE $db->users SET " . implode( ', ', $data ) . " WHERE `ID` = {$user[ 'ID' ]}";

		$result = $db->query( $query );

		return $result;
	}

	/**
	 * Validates a new user.
	 *
	 * @param  array   $user  The new user to valid. Array must include username,
	 *                        email, password1 and password2.
	 * @return array          Array with valid key which has a boolean value. True on
	 *                        success, false on failure. See value of error key then.
	 */
	public static function validate_new_user( $user ) {
		$errors = array();

		// Check username
		if ( empty( $user[ 'username' ] ) ) {
			$errors[] = 'emptyusername';
		} else {
			$username = $raw_username = trim( $user[ 'username' ] );
			$username = sanitize_key( $username );

			if ( $raw_username !== $username ) {
				$errors[] = 'invalidusername';
			}
		}

		// Check email
		if ( empty( $user[ 'email' ] ) ) {
			$errors[] = 'emptyemail';
		} else {
			$email = trim( $user[ 'email' ] );

			if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
				$errors[] = 'invalidemail';
			}
		}

		// Check passwords
		if ( empty( $user[ 'password1' ] ) || empty( $user[ 'password2' ] ) ) {
			$errors[] = 'emptypassword';
		} else {
			$password1 = trim( $user[ 'password1' ] );
			$password2 = trim( $user[ 'password2' ] );

			if ( $password1 !== $password2 ) {
				$errors[] = 'passwordmismatch';
			}
		}

		// Check if username/email exists
		if ( empty( $errors ) ) {
			if ( User_Model::get_data_by( 'email', $email ) ) {
				$errors[] = 'mailexists';
			} elseif ( User_Model::get_data_by( 'login', $username ) ) {
				$errors[] = 'usernameexists';
			}
		}

		// Return if error
		if ( ! empty( $errors ) )
			return array( 'valid' => false, 'errors' => $errors );

		// Return on success
		return array(
			'valid' => true,
			'sanitized_user' => array(
				'name'     => $username,
				'email'    => $email,
				'password' => $password1
			)
		);
	}

}
