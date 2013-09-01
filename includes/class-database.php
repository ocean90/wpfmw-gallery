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
	 * Holds the latest results
	 *
	 * @var string
	 */
	private $_last_result = array();

	/**
	 * Holds the insert ID.
	 *
	 * @var integer
	 */
	public $_insert_id = 0;

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
	 * This does more or less the same as mysqli_stmt::bind_param(), but in a much easier
	 * way.
	 *
	 * @param  string       $query Query statement with sprintf()-like placeholders.
	 * @param  array|string $args  Either string or array, see vsprintf().
	 * @return mixed               Sanitized query or false on failure.
	 */
	public function prepare( $query ) {
		if ( empty( $query ) )
			return;

		$args = func_get_args();
		array_shift( $args );
		if ( isset( $args[0] ) )
			$args = $args[0];

		// If single placeholder passed, move it into an array
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
		$this->flush();

		// Call parent methode
		$result = parent::query( $query );

		if ( ! $result ) {
			$this->_error = sprintf( 'MySQL Error: %d - %s', $this->errno, $this->error );
			return false;
		}

		// Check for results
		if ( ! is_bool( $result ) && 0 === $result->num_rows )
			return false;

		if ( is_object( $result ) ) {
			$rows = 0;
			while ( $row = $result->fetch_object() ) {
				$this->_last_result[ $rows++ ] = $row;
			}
		}

		$this->_insert_id = $this->insert_id;

		if ( is_resource( $result ) )
			$result->close();

		return true;
	}

	/**
	 * Returns all results for a query.
	 *
	 * @param  string $query The query.
	 * @return mixed         The result of the query. Null if empty.
	 */
	public function get_results( $query ) {
		$result = $this->query( $query );

		if ( empty( $this->_last_result ) )
			return null;

		return $this->_last_result;
	}

	/**
	 * Returns just one row of a query.
	 *
	 * @param  string $query The query.
	 * @return mixed         The result of the query. Null if empty.
	 */
	public function get_row( $query, $i = 0 ) {
		$result = $this->query( $query );

		if ( empty( $this->_last_result ) )
			return null;

		if ( ! isset( $this->_last_result[ $i ] ) )
			return null;

		return $this->_last_result[ $i ];
	}

	/**
	 * Resets some internal vars, becaouse the instance is used
	 * for multiple queries.
	 *
	 * @return void
	 */
	private function flush() {
		$this->_error = '';
		$this->_last_result = array();
		$this->_insert_id = 0;
	}
}
