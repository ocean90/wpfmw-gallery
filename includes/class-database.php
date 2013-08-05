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
 * MYSQLi wrapper.
 */
class Database extends mysqli {

	/**
	 * Gallery table.
	 *
	 * @var string
	 */
	public $gallery = 'gallery';

	/**
	 * Image meta table.
	 *
	 * @var string
	 */
	public $imagemeta = 'imagemeta';

	/**
	 * Images table.
	 *
	 * @var string
	 */
	public $images = 'images';

	/**
	 * Image relationships table.
	 *
	 * @var string
	 */
	public $image_relationships = 'image_relationships';

	/**
	 * User  table.
	 *
	 * @var string
	 */
	public $users = 'users';

	/**
	 * User meta table.
	 *
	 * @var string
	 */
	public $usermeta = 'usermeta';

	/**
	 * Holds the latest error string.
	 *
	 * @var string
	 */
	public $_error = '';

	/**
	 * Constructor.
	 * Sets database connection and calls constructor of parent class.
	 *
	 * @param object $settings Database settings from config.php
	 */
	function __construct( $settings ) {
		// Sanity checks
		if ( empty( $settings->name ) || empty( $settings->user ) || empty( $settings->host ) )
			die( 'Please check your database settings!' );

		// Call parent constructor
 		@parent::__construct( $settings->host, $settings->user, $settings->pw, $settings->name );

 		// Check for errors
 		if ( $this->connect_errno )
			die( sprintf( 'MySQL Connect Error: %d - %s', $this->connect_errno, $this->connect_error ) );
	}

	/**
	 * Returns the latest error.
	 *
	 * @return string The error string. Errno + error.
	 */
	function get_last_error() {
		return $this->_error;
	}

	/**
	 * Wrapper for mysqli::query.
	 * Calls the parent query methode but does return 'false' on an empty
	 * result.
	 *
	 * @param  string $query The query
	 * @return mixed  The result of the query.
	 */
	public function query( $query ) {
		// Call parent methode
		$result = parent::query( $query );

		if ( ! $result ) {
			$this->_error = sprintf( 'MySQL Error: %d - %s', $this->errno, $this->error );
			return false;
		}

		// Check for results
		if ( 0 === $result->num_rows )
			return false;

		return $result;
	}
}
