<?php
require "config.php";

$filename = $allowedFolders[$_GET['camera']]['path'].$_GET['src'];

$cacheFile = "cache/".preg_replace("/[^a-z0-9_]/", "", $_GET['camera'].$_GET['src'].$_GET['sz']).".jpg";

if(file_exists($cacheFile)) {
	header('Content-Type: image/jpeg');
	echo file_get_contents($cacheFile);
	exit();
}

// Set a maximum height and width
$width = 128;
$height = 128;

if( $_GET['sz']=="128x128" ) {
	$width = 128;
	$height = 128;
}
else if( $_GET['sz']=="640x480" ) {
	$width = 640;
	$height = 480;
}
else if( $_GET['sz']=="real" ) {
	header('Content-Type: image/jpeg');
	echo file_get_contents($filename);
	exit();
}

// Get new dimensions
list($width_orig, $height_orig) = getimagesize($filename);
$ratio_orig = $width_orig/$height_orig;
if ($width/$height > $ratio_orig) {
   $width = $height*$ratio_orig;
} 
else {
   $height = $width/$ratio_orig;
}

// Resample
$image_p = imagecreatetruecolor($width, $height);
$image = imagecreatefromjpeg($filename);

imagetruecolortopalette($image, false, 128);

imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

// Save cache
imagejpeg($image_p, $cacheFile, 15);

// Output
header('Content-Type: image/jpeg');
imagejpeg($image_p, null, 15);
