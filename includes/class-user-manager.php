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
	 * Creates a new user.
	 *
	 * @param  array   $user  The new user to create. Array must include username,
	 *                        email and password.
	 * @return boolean        True on success, false if not.
	 */
	public function create_user( $user ) {
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
	 * Validates a new user.
	 *
	 * @param  array         $user  The new user to valid. Array must include username,
	 *                              email, password1 and password2.
	 * @return boolean|array        True if valid, array if not.
	 */
	public function validate_new_user( $user ) {
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
