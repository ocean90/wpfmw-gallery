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
	 * Validates a new user.
	 *
	 * @param  array         $user  The new user to valid. Array must include username,
	 *                              email, password1 and password2.
	 * @return boolean|array        True if valid, array if not.
	 */
	function validate_new_user( $user ) {
		$error = array();

		if ( empty( $user[ 'username' ] ) ) {
			$error[] = 'emptyusername';
		} else {
			$username = $raw_username = trim( $user[ 'username' ] );
			$username = sanitize_key( $username );

			if ( $raw_username !== $username ) {
				$error[] = 'invalidusername';
			}
		}

		if ( empty( $user[ 'email' ] ) ) {
			$error[] = 'emptyemail';
		} else {
			$email = trim( $user[ 'email' ] );

			if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
				$error[] = 'invalidemail';
			}
		}

		if ( empty( $user[ 'password1' ] ) || empty( $user[ 'password2' ] ) ) {
			$error[] = 'emptypassword';
		} else {
			$password1 = trim( $user[ 'password1' ] );
			$password2 = trim( $user[ 'password2' ] );

			if ( $password1 !== $password2 ) {
				$error[] = 'passwordmismatch';
			}
		}

		if ( empty( $error ) ) {
			if ( User_Model::get_data_by( 'email', $email ) ) {
				$error[] = 'mailexists';
			} elseif ( User_Model::get_data_by( 'login', $username ) ) {
				$error[] = 'usernameexists';
			}
		}

		if ( ! empty( $error ) )
			return $error;

		return true;
	}

}
