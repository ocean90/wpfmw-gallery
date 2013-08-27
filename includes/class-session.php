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

// For Security
@ini_set( 'session.use_only_cookies', 1 );

/**
 * Session handler.
 */
class Session {
	private $session;

	function __construct() {
		if ( $this->session === null )
			$this->start();
	}

	public function start() {
		if ( $this->session !== null )
			return;

		session_start();

		$this->session = session_id();
	}

	public function destroy() {
		if ( $this->session === null )
			$this->start();

		session_destroy();
	}

	public function set( $key, $value ) {
		if ( $this->session === null )
			$this->start();

		$_SESSION[ $key ] = $value;
	}

	public function get( $key, $default = null ) {
		if ( $this->session === null )
			$this->start();

		if ( isset( $_SESSION[ $key ] ) ) {
			return $_SESSION[ $key ];
		} else {
			return $default;
		}
	}
}
