<?php
require "config.php";

require_once "classes/Scanner.php";
require_once "classes/FrameScanner.php";
require_once "classes/Image.php";


function human_filesize($bytes, $decimals = null) 
{
	$sz = 'BKMGTP';
	$factor = floor((strlen($bytes) - 1) / 3);
  
	if($decimals===null) {
		$decimals = $factor;
	}
	return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
}

/*
function getDetectedVideoDevices() 
{
	$scanner = new \Scanner\Scanner("/dev/v4l/by-id");
	$files = $scanner->getAll($filter);

	var_dump($files);
}

var_dump(getDetectedVideoDevices()); exit();
*/




$scannedFiles = Array();
foreach($allowedFolders as $id=>$folder) {
	$scanner = new \Scanner\FrameScanner($folder['path']);

	$files = Array();
	$files['scanner'] = $scanner;
	
	$filter = new stdClass();
	$filter->length = 5;
	$filter->frameDate = new \DateTime('now ');
	$files['files']['jpeg'] = $scanner->getFrames($filter);
	
	$files['folder'] = Array();
	$files['folder']['path'] = $folder['path'];
	
	
	$files['folder']['size'] = $scanner->getAllFramesSize($filter);
	
	$files['camera'] = Array();
	$files['camera']['name'] = $folder['name'];
	$files['camera']['description'] = $folder['description'];
	$files['camera']['id'] = $id;
	
	$scannedFiles[$id]  = $files;
}

#var_dump($scannedFiles); exit();

if ($_GET['view'] && $scannedFiles[$_GET['view']]) {
	$camera = $scannedFiles[$_GET['view']];
	require "index-view.php";
}
else {
	require "index-home.php";
}
