<?php
namespace Scanner;

class Scanner {
	protected $path = null;
	protected $files = null;

	protected function _remove_hidden($x) 
	{
		if($x[0]=='.') return false;
		return true;
	}

	public function __construct($path) {
		$this->path = $path;
		$this->files = $this->prepareGetAll($path);
	}
	
	protected function prepareGetAll($path) {
		$files = 	array_filter(scandir($path), Array($this, "_remove_hidden"));
		sort($files);
		
		return $files;
	}
	
	public function getAll($path) {
		return $this->files;
	}
	
	public function getAllSize() {
		$size = 0;
		foreach($this->getAll() as $f) {
			$size+= filesize($this->path.$f);
		}
		
		return $size;
	}
}