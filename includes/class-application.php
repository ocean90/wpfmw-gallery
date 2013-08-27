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
 * Base class of the application.
 */
class Application {

	/**
	 * Stores the config.
	 *
	 * @var Configurator
	 */
	public $config;

	/**
	 * Stores the database connection.
	 *
	 * @var Database
	 */
	public $db;

	/**
	 * Constructor.
	 * Inits config field.
	 */
	function __construct() {
		$this->config = new Configurator();
		$this->config->db = new StdClass();

		$this->session = new Session();
	}

	/**
	 * Inits the database connection.
	 *
	 * @return void
	 */
	public function init_database() {
		global $db;

		$db = $this->db = new Database( $this->config->db );
	}
}
