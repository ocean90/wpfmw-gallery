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
 * Simple autoloader for PHP classes.
 */
class Autoloader {
	/**
	 * Holds the existing classes.
	 *
	 * @var array
	 */
	private static $classes = array();

	/**
	 * Constructor.
	 * Sets existing classes and registers spl_autoload_register.
	 */
	function __construct() {
		self::$classes = array(
			'configurator'    => APP_INCLUDES_PATH . 'class-configurator.php',
			'application'     => APP_INCLUDES_PATH . 'class-application.php',
			'request'         => APP_INCLUDES_PATH . 'class-request.php',
			'database'        => APP_INCLUDES_PATH . 'class-database.php',
			'controller'      => APP_INCLUDES_PATH . 'class-controller.php',
			'view'            => APP_INCLUDES_PATH . 'class-view.php',
			'user_manager'    => APP_INCLUDES_PATH . 'class-user-manager.php',
			'image_manager'   => APP_INCLUDES_PATH . 'class-image-manager.php',
			'gallery_manager' => APP_INCLUDES_PATH . 'class-gallery-manager.php',
			'session'         => APP_INCLUDES_PATH . 'class-session.php',
			'user_model'      => APP_INCLUDES_PATH . 'models/class-user-model.php',
			'gallery_model'   => APP_INCLUDES_PATH . 'models/class-gallery-model.php',
			'passwordhash'    => APP_INCLUDES_PATH . 'libs/PasswordHash.php',
		);

		spl_autoload_register( 'Autoloader::load_class' );
	}

	/**
	 * Callback method for spl_autoload_register
	 *
	 * @param  string $name Class name
	 * @return void
	 */
	public static function load_class( $name ) {
		// Change class to lower characters
		$name = strtolower( $name );

		// Check if class exists. On success require class
		if ( array_key_exists( $name, self::$classes ) )
			require self::$classes[ $name ];
	}
}

new Autoloader;
