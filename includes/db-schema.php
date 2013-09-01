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

$schema = <<<EOT

CREATE TABLE `gallery` (
  `ID` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `is_public` tinyint(1) NOT NULL DEFAULT '1',
  `gallery_title` text NOT NULL,
  `gallery_description` longtext NOT NULL,
  PRIMARY KEY (`ID`)
) DEFAULT CHARSET=utf8;

CREATE TABLE `imagemeta` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `image_id` bigint(20) unsigned NOT NULL,
  `meta_key` varchar(255) NOT NULL,
  `meta_value` longtext NOT NULL,
  PRIMARY KEY (`ID`),
  KEY (`image_id`),
  KEY (`meta_key`)
) DEFAULT CHARSET=utf8;

CREATE TABLE `images` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `uploaded_date` datetime NOT NULL,
  `image_filename` text NOT NULL,
  `image_title` text NOT NULL,
  `image_caption` longtext NOT NULL,
  PRIMARY KEY (`ID`),
  KEY (`user_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE `image_relationships` (
  `image_id` bigint(20) unsigned NOT NULL,
  `gallery_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`image_id`, `gallery_id`),
  KEY (`gallery_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE `usermeta` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `meta_key` varchar(255) NOT NULL,
  `meta_value` longtext NOT NULL,
  PRIMARY KEY (`ID`),
  KEY (`user_id`),
  KEY (`meta_key`)
) DEFAULT CHARSET=utf8;

CREATE TABLE `users` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_login` varchar(60) NOT NULL,
  `user_pass` varchar(64) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`),
  KEY (`user_login`),
  KEY (`user_email`)
) DEFAULT CHARSET=utf8;

EOT;
