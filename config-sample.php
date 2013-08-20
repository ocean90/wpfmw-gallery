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

/*****
 * CONFIG FILE
 */

// URL for the application
$app->config->url = 'http://localhost';

// If you want to install the application in a subdir
// you have to change this value.
// For each subdir + 1.
// Example: http://localhost/gallery/ => offset = 1
$app->config->segment_offset = 0;

/*****
 * Database settings
 */

// The name of the database
$app->config->db->name   = '';

// Database username
$app->config->db->user   = '';

// Database password
$app->config->db->pw     = '';

// Database hostname
$app->config->db->host   = 'localhost';
