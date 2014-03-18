<?php
require "config.php";

require "classes/Scanner.php";
require "classes/FrameScanner.php";
require "classes/Image.php";

error_reporting(E_ALL); ini_set('display_errors', 1);

$scannedFiles = Array();
foreach($allowedFolders as $id=>$folder) {
	$scanner = new \Scanner\FrameScanner($folder['path']);

	$filter = new stdClass();
	$filter->maxFrameDate = new \DateTime($folder['maxFrameAge']);
	$frames = $scanner->getFrames($filter);
	
	foreach($frames as $frame) {
		unlink("{$folder['path']}/{$frame}");
	}
}

