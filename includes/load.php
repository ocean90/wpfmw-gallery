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
 * Load the controller based on the current request.
 * http://example.com/{controller}/{action}/[{subactions}]
 */

// Get the controller
$controller = str_replace( '_', '', $request->segments[0] );
// If the first segment is empty we are on the home screen
if ( empty( $controller ) )
	$controller = 'home';

// Get the action, if none set to index
$action = empty( $request->segments[1] ) ? 'index' : $request->segments[1];

// Get the controller class
$class = '';
switch ( $controller ) {
	case 'install' :
	case 'home' :
	case 'login' :
	case 'logout' :
	case 'register' :
	case 'settings' :
	case 'user' :
		$action = 'index';
	case 'upload' :
 	$class = ucfirst( $controller ) . '_Controller';
		require APP_INCLUDES_PATH . "/controllers/class-$controller-controller.php";
		break;
	default:
}
// Check if the current action hadnler exists in the controller class
if ( ! method_exists( $class, $action ) ) {
	$controller = 'error';
	$class = 'Error_Controller';
	require APP_INCLUDES_PATH .  "/controllers/class-$controller-controller.php";
	$action = 'index';
}

// Create an instance of the controller class
$object = new $class;

// Call the action methode on the controller and pass the current request
call_user_func_array( array( $object, $action ), array( 'request' => $request ) );
