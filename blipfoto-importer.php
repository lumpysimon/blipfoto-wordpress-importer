<?php
/*
Plugin Name: Blipfoto Importer
Plugin URI:  https://wordpress.org/blipfoto-importer
Description: Import entries from a Blipfoto journal into your WordPress website
Version:     1.3
Author:      Simon Blackbourn
Author URI:  http://simonblackbourn.net
License:     GPL2



	------------
	What it does
	------------

	Blipfoto (https://www.blipfoto.com) is an online daily photo journal. Each day you can upload one photo and add some words. It is also a very friendly community where you can comment on and rate other people's photos.

	Blipfoto Importer lets you easily import all your photos and journal entries from Blipfoto into your WordPress website.



	-----
	About
	-----

	This plugin is not an official Blipfoto product.
	I originally wrote it at a time when there were concerns about the future of the Blipfoto website, as a way for people to retrieve all their text and photos and safely store them on their own WordPress website. Blipfoto is now fully owned and run by its users (woo hoo!), so hopefully those concerns are no longer valid, but this plugin is still a very useful tool for anyone who wants to mirror their Blipfoto journal to their WordPress website.

	http://simonblackbourn.net
	https://www.twitter.com/lumpysimon
	https://www.blipfoto.com/lumpysimon



	-------
	License
	-------

	Copyright (c) Simon Blackbourn. All rights reserved.

	Released under the GPL license:
	http://www.opensource.org/licenses/gpl-license.php

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.



*/



defined( 'ABSPATH' ) or die();



if ( ! defined( 'BLIPFOTO_IMPORTER_PLUGIN_PATH' ) )
	define( 'BLIPFOTO_IMPORTER_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

if ( ! defined( 'BLIPFOTO_IMPORTER_PLUGIN_DIR' ) )
	define( 'BLIPFOTO_IMPORTER_PLUGIN_DIR', plugin_dir_url( __FILE__ ) );



$blipfoto_importer_plugin = new blipfoto_importer_plugin;
use Blipfoto\Api\Client;



class blipfoto_importer_plugin {



	function __construct() {

		foreach ( glob( dirname( __FILE__ ) . '/lib/*.php' ) as $component ) {
			require $component;
		}

		$files = array(
				'Exceptions' => array(
				'BaseException',
				'ApiResponseException',
				'InvalidResponseException',
				'NetworkException',
				'OAuthException'
				),
			'Api' => array(
				'Client',
				'OAuth',
				'Request',
				'Response'
				),
			);

		$path = BLIPFOTO_IMPORTER_PLUGIN_PATH . 'lib/Blipfoto/';

		foreach ( $files as $folder => $files ) {
			foreach ( $files as $file ) {
				require( $path . $folder . '/' . $file . '.php' );
			}
		}

	}



}
