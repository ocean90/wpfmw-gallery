<?php
/**
 * Gallery - A project for 'WPF - Moderne Webanwendungen' at
 * Cologne University of Applied Sciences.
 *
 * @author    Dominik Schilling <dominik.schilling@smail.fh-koeln.de>
 * @author    Laura Hermann <laura.hermann@smail.fh-koeln.de>
 * @author    Dario Vizzaccaro <dario.vizzaccaro@smail.fh-koeln.de>
 * @link      https://github.com/ocean90/wpfmw-gallery
 * @license   MIT
 */

$schema = <<<EOT

CREATE TABLE `galleries` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `is_public` tinyint(1) NOT NULL DEFAULT 1,
  `gallery_title` text NOT NULL,
  `gallery_slug` varchar(200) NOT NULL DEFAULT '',
  `gallery_description` longtext NOT NULL,
  `gallery_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`),
  UNIQUE KEY user_id_gallery_slug(`user_id`, `gallery_slug`)
) DEFAULT CHARSET=utf8;

CREATE TABLE `imagemeta` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `image_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `meta_key` varchar(255) NOT NULL DEFAULT '',
  `meta_value` longtext NOT NULL,
  PRIMARY KEY (`ID`),
  KEY (`image_id`),
  KEY (`meta_key`)
) DEFAULT CHARSET=utf8;

CREATE TABLE `images` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `image_uploaded` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `image_filename` text NOT NULL,
  `image_title` text NOT NULL,
  `image_description` longtext NOT NULL,
  PRIMARY KEY (`ID`),
  KEY (`user_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE `image_relationships` (
  `image_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `gallery_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`image_id`, `gallery_id`),
  KEY (`gallery_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE `usermeta` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `meta_key` varchar(255) NOT NULL DEFAULT '',
  `meta_value` longtext NOT NULL,
  PRIMARY KEY (`ID`),
  KEY (`user_id`),
  KEY (`meta_key`)
) DEFAULT CHARSET=utf8;

CREATE TABLE `users` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_login` varchar(60) NOT NULL DEFAULT '',
  `user_pass` varchar(128) NOT NULL DEFAULT '',
  `user_email` varchar(200) NOT NULL DEFAULT '',
  `user_registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_status` int(10) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY (`user_login`),
  KEY (`user_email`)
) DEFAULT CHARSET=utf8;

EOT;
