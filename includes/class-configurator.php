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
 * Object to store settings.
 */
class Configurator {

	/**
	 * Contains the saved settings.
	 *
	 * @var array
	 */
	private $settings = array();

	/**
	 * Contructor.
	 */
	function __construct() {}

	/**
	 * Getter.
	 *
	 * @param  string $key The key of the data to retrieve.
	 * @return mixed       The data.
	 */
	public function __get( $key ) {
		if ( array_key_exists( $key, $this->settings ) )
			return $this->settings[ $key ];

		return null;
	}

	/**
	 * Setter.
	 *
	 * @param  string $key   The key of the data to save.
	 * @param  mixed  $value The data.
	 * @return void
	 */
	public function __set( $key, $value ) {
		$this->settings[ $key ] = $value;
	}

	/**
	 * Issetter.
	 *
	 * @param  string  $key The key of the data.
	 * @return boolean      True if exsits, false if not.
	 */
	public function __isset( $key ) {
		return isset( $this->settings[ $key ] );
	}

	/**
	 * Unsetter.
	 *
	 * @param  string  $key The key of the data.
	 * @return void
	 */
	public function __unset( $key ) {
		unset( $this->settings[ $key ] );
	}

}
