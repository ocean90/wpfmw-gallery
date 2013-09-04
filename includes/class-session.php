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

// For Security
@ini_set( 'session.use_only_cookies', 1 );

/**
 * Session handler.
 */
class Session {
	/**
	 * Holds the current session ID.
	 *
	 * @var string
	 */
	private $session = null;

	/**
	 * Constructor.
	 * Calls start method of $session is null.
	 *
	 * @return void
	 */
	function __construct() {
		if ( $this->session === null )
			$this->start();
	}

	/**
	 * Starts a session if $session is null.
	 *
	 * @return void
	 */
	public function start() {
		if ( $this->session !== null )
			return;

		session_start();

		$this->session = session_id();
	}

	/**
	 * Regenerates the current session.
	 *
	 * @return void
	 */
	public function regenerate() {
		if ( $this->session === null )
			return;

		session_regenerate_id();

		$this->session = session_id();
	}

	/**
	 * Destroys the current session.
	 *
	 * @return void
	 */
	public function destroy() {
		if ( $this->session === null )
			$this->start();

		session_destroy();

		// Destroy the remember cookie too.
		setcookie( session_name(), session_id(), time() - 3600, '/' );
	}

	/**
	 * Sets the lifetime of the session cookie.
	 *
	 * We don't use session_set_cookie_params() here, because it has to run
	 * before session_start().
	 *
	 * @param  integer $value Time in seconds
	 * @return void
	 */
	public function set_lifetime( $value = 0 ) {
		setcookie( session_name(), session_id(), time() + $value, '/' );
	}

	/**
	 * Adds a key-value pair to the session.
	 *
	 * @param string $key    The key of the item to store.
	 * @param mixes  $value  The value if the item to store.
	 */
	public function set( $key, $value ) {
		if ( $this->session === null )
			$this->start();

		$_SESSION[ $key ] = $value;
	}

	/**
	 * Returns the value of a stored item in the session.
	 *
	 * @param  string $key     The key of the stored item.
	 * @param  mixed  $default The default value which should returned when
	 *                         value is not set.
	 * @return mixed           The value of the stored item, or $default.
	 */
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
