<?php
namespace Scanner;

class FrameScanner extends Scanner{
	protected $frames = null;

	protected function _allow_jpegs($x) 
	{
		if(preg_match("/\.(jpg|jpeg)$/i", $x)) return true;
		return false;
	}
	
	protected function _remove_hidden($x) 
	{
		if($x[0]=='.') return false;
		if(preg_match("/lastsnap.jpg/", $x)) return false;
		return true;
	}

	public function __construct($path) {
		$this->path = $path;
		$this->frames = $this->prepareGetAllFrames($this->path);
	}
	
	protected function prepareGetAllFrames($path) {
		$files = 	array_filter($this->prepareGetAll($path), Array($this, "_allow_jpegs"));
		rsort($files);
		
		return $files;
	}
	
	public function getAllFrames() {
		return $this->frames;
	}
	
	public function getAllFramesSize() {
		$size = 0;
		foreach($this->getAllFrames() as $f) {
			$size+= filesize($this->path.$f);
		}
		
		return $size;
	}
	
	public function getFrames($filterObj) {
		$ret = $this->frames;
		if(isset($filterObj->frameDate)) {
			$ret = array_filter($ret, create_function('$img', 'return (bool)preg_match("/^'.$filterObj->frameDate->format("Ymd").'\./", $img);'));
		}
		
		if(isset($filterObj->maxFrameDate)) {
			$ret = array_filter($ret, create_function('$img', 'return ("'.$filterObj->maxFrameDate->format("Ymd").'" > $img);'));
		}
		
		if(isset($filterObj->length)) {
			$ret = array_slice($ret, 0, $filterObj->length);
		}
		return array_values($ret);
	}
	
	public function getRecordedDays() {
		$dates = Array();
		foreach($this->getAllFrames() as $f) {
			$img = new \Scanner\Image($f);
			$dt = $img->frameDate->format("Y-m-d");
			$dates[$dt]++;
		}
		
		$ret = array_keys($dates);
		rsort($ret);
		return $ret;
	}
	
	public function getStatsByDay() {
		$dates = Array();
		foreach($this->getAllFrames() as $f) {
			$img = new \Scanner\Image($f);
			$dt = $img->frameDate->format("Y-m-d");
			
			if(!isset($dates[$dt])) {
				$dates[$dt] = Array();
				$dates[$dt]['date'] = \DateTime::createFromFormat("Y-m-d", $dt);
			}
			
			$dates[$dt]['frames']++;
		}
		
		return $dates;
	}
	
}