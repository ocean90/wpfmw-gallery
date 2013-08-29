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

// Set application paths (has trailing slash)
define( 'APP_PATH', dirname( dirname( __FILE__ ) ) . '/' );
define( 'APP_INCLUDES_PATH', APP_PATH . 'includes/' );
define( 'APP_VIEWS_PATH', APP_INCLUDES_PATH . 'views/' );
define( 'APP_CONTENT_PATH', APP_PATH . 'images/' );

// Load some files
require APP_INCLUDES_PATH . 'autoloader.php';
require APP_INCLUDES_PATH . 'functions.php';

check_compatibility();

// Init the application
global $app;
$app = new Application();

// Load config file
if ( is_file( APP_PATH . 'config.php' ) )
	require APP_PATH . 'config.php';
else
	die( 'Config Error: Please copy <code>config-sample.php</code>, change the settings and name it <code>config.php</code>!' );

// Check for .htaccess
if ( ! is_file( APP_PATH . '.htaccess' ) )
	die( 'Config Error: Please copy <code>.htaccess-sample</code>, change the RewriteBase if neccessary and name it <code>.htaccess</code>!' );

// Init the database
$app->init_database();

User_Manager::set_current_user();

// Parse the request
global $request;
$request = new Request();

// Check if application is installed
maybe_install();


