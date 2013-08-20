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
 * Parses the requested URL.
 */
class Request {

	/**
	 * The URL as a string.
	 *
	 * @var string
	 */
	public $url;

	/**
	 * URL segments as an array.
	 *
	 * @var array
	 */
	public $segments = array();

	/**
	 * The request method (GET, POST, etc).
	 *
	 * @var string
	 */
	public $method;

	/**
	 * Parameters array, depending on HTTP method used.
	 *
	 * @var array
	 */
	public $parameters = array();

	/**
	 * Constructor.
	 * Creates a request object from an URL.
	 *
	 * @param string $url Optional; will default to current URL if none is passed
	 */
	public function __construct( $url = null ) {
		global $app;

		// Get URL
		if ( empty( $url ) )
			$url = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '';

		// Bail if URL is empty
		if ( empty( $url ) )
			return false;

		// Get the segements of the path
		$path = trim( parse_url( $url, PHP_URL_PATH ), '/' );
		$segments = explode( '/', $path );
		$offset = ! empty( $app->config->segment_offset ) ? $app->config->segment_offset : 0;
		$segments = array_slice( $segments, $offset );

		// Set variables
		$this->url = $url;
		$this->segments = $segments;
		$this->method = strtoupper( $_SERVER['REQUEST_METHOD'] );

		// Store additional parameters based on method
		switch ( $this->method ) {
			case 'GET':
				$this->parameters = $_GET;
			break;
		}
	}
}
