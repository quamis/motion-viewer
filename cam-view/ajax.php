<?php
require "config.php";

require "classes/Scanner.php";
require "classes/FrameScanner.php";
require "classes/Image.php";


switch($_GET['action']) {
	case 'getImages':
		$files = Array();
		$scannedFiles = Array();
		$scanner = new \Scanner\FrameScanner($allowedFolders[$_GET['camera']]['path']);
		
		$filter = new stdClass();
		$filter->frameDate = \DateTime::createFromFormat("Y-m-d", $_GET['date']);
		$frames = $scanner->getFrames($filter);
		
		$files['files']['jpeg'] = Array();
		foreach($frames as $f) {
			$img = new \Scanner\Image($f);
			$files['files']['jpeg'][] = Array(
				'frameDate' => $img->frameDate->format("Y-m-d H:i:s"),
				'snapshot' => $img->snapshot,
				'id' => $img->id,
				'event' => $img->event,
				'event_frame' => $img->event_frame,
				'path' => $img->path,
			);
			
		}
		
		header("Content-type: application/json");
		echo json_encode($files);
		exit();
	break;
}