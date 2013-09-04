<?php
/**
 * Gallery - A project for 'WPF - Moderne Webanwendungen' at
 * Cologne University of Applied Sciences.
 *
 * @author    Dominik Schilling <dominik.schilling@smail.fh-koeln.de>
 * @author    Laura Hermann <laura.hermann@smail.fh-koeln.de>
 * @author    Dario Vizzaccaro <dario.vizzaccaro@smail.fh-koeln.de>
 * @link      https://github.com/ocean90/wpfmw-gallery
 * @license   MIT
 */

/**
 * Controller for settings actions.
 */
class Settings_Controller extends Controller {

	/**
	 * Constructor.
	 */
	function __construct() {}

	/**
	 * Default action.
	 *
	 * @return void
	 */
	public function index( $request ) {
		if ( ! User_Manager::is_user_logged_in() ) {
			redirect( get_site_url( '/login/' ) );
			exit;
		}

		if ( 'POST' === $request->method ) {
			$this->update_settings();
		} else {
			$view = new View( 'settings/index' );
			$view->set_page_title( 'Settings' );
			$view->assign( 'user', User_Manager::get_current_user() );
			$view->render();
		}

	}

	private function update_settings() {
		$errors = array();
		$update = array();
		$current_user = User_Manager::get_current_user();

		// Check email
		if ( empty( $_POST[ 'email' ] ) ) {
			$errors[] = 'emptyemail';
		} else {
			$email = trim( $_POST[ 'email' ] );

			if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
				$errors[] = 'invalidemail';
			} else {
				if ( $current_user->user_email !== $email ) {
					if ( ! User_Model::get_data_by( 'email', $email ) ) {
						$update['email'] = $email;
					} else {
						$errors[] = 'mailexists';
					}
				}
			}
		}

		// Check passwords if changed
		if ( ! empty( $_POST[ 'password1' ] ) && ! empty( $_POST[ 'password2' ] ) ) {
			$password1 = trim( $_POST[ 'password1' ] );
			$password2 = trim( $_POST[ 'password2' ] );

			if ( $password1 !== $password2 ) {
				$errors[] = 'passwordmismatch';
			} else {
				$update[ 'password' ] = $password1;
			}
		}

		if ( ! empty( $_POST[ 'meta' ] ) ) {
			$meta = (array) $_POST[ 'meta' ];

			foreach ( $meta as $key => $value ) {
				$value = trim( $value );
				if ( $current_user->$key !== $value ) {
					$update[ 'meta' ][ $key ] = $value;
				}
			}
		}

		if ( ! empty( $errors ) ) {
			$view = new View( 'settings/index' );
			$view->set_page_title( 'Settings' );
			$view->assign( 'user', User_Manager::get_current_user() );
			$view->assign( 'error', $errors );
			$view->render();
			return;
		}

		if ( ! empty( $update  ) ) {
			$update[ 'ID' ] = User_Manager::get_current_user()->ID;
			$result = User_Manager::edit_user( $update );
			if ( $result ) {
				redirect( get_site_url( '/settings/?success' ) );
				exit;
			} else {
				redirect( get_site_url( '/settings/?error' ) );
				exit;
			}
		} else {
			// Nothing changed
			redirect( get_site_url( '/settings/' ) );
			exit;
		}
	}

}
