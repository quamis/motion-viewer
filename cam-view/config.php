<?php

ini_set('memory_limit', '256M');
set_time_limit(60);
date_default_timezone_set("Europe/Bucharest");
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', 1);
ini_set('html_errors', 1);

$setup = Array(
	'installation-name' => 'home surveillance',
	'installation-address' => 'test address, nr 99, etj 5, apt 1',
);


$allowedFolders = Array(
	'cam1' => Array(
		'path' => "/var/www/cam/cam1/",
		'name' => "Living",
		'description' => 'Cam 1',
		'maxFrameAge' => '-2weeks'
	)
);

