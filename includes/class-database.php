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
	public function __construct( $settings ) {
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
	public function get_last_error() {
		return $this->_error;
	}

	/**
	 * Escapes content by reference for insertion into the database, for security.
	 *
	 * @param  string $string to escape
	 * @return string escaped
	 */
	public function escape( &$string ) {
		$string = $this->real_escape_string( $string );
	}

	/**
	 * Prepares a SQL query for safe execution. Uses sprintf()-like syntax.
	 *
	 * @param  string       $query Query statement with sprintf()-like placeholders.
	 * @param  array|string $args  Either string or array, see vsprintf().
	 * @return mixed               Sanitized query or false on failure.
	 */
	public function prepare( $query, $args ) {
		if ( empty( $query ) || empty( $args ) )
			return;

		// If single placeholder passed move it into an array
		if ( ! is_array( $args ) )
			$args = array( $args );

		// Prepare placeholders
		$query = str_replace( array( "'%s'", '"%s"' ), '%s', $query ); // Remove single/double quotes around %s
		$query = preg_replace( '|(?<!%)%s|', "'%s'", $query );         // and quote the strings

		// Escape values
		array_walk( $args, array( $this, 'escape' ) );

		// Replace placeholders with escaped values
		return vsprintf( $query, $args );
	}

	/**
	 * Wrapper for mysqli::query.
	 * Calls the parent query methode but does return 'false' on an empty
	 * result.
	 *
	 * @param  string $query The query.
	 * @return mixed         The result of the query.
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
