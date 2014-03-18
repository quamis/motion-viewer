<?php
namespace Scanner;

class Image {
	public $frameDate = null;
	public $snapshot = null;
	public $event = null;
	public $event_frame = null;
	public $path = null;
	public $id = null;
	
	public function __construct($path) {
		$this->path = $path;
	
		$info = $this->extractInfo($this->path);
		$this->frameDate = $info['frameDate'];
		$this->snapshot = $info['snapshot'];
		$this->event = $info['event'];
		$this->event_frame = $info['event_frame'];
		$this->id = preg_replace("/[^a-z0-9_]/i", "", preg_replace("/(\.(jpg|jpeg))$/", "", $this->path));
	}
	
	protected function extractInfo($path) {
		$ret = Array();
		preg_match("/^(?P<date>[0-9]{4}[0-9]{2}[0-9]{2}\.[0-9]{2}[0-9]{2}[0-9]{2})-(?P<tail>.+)\.jpg$/i", $path, $m);
		
		$ret['frameDate'] = \DateTime::createFromFormat("Ymd\.His", $m['date']);
		
		if($m['tail']=='snapshot') {
			$ret['snapshot'] = true;
		}
		else {
			$ret['snapshot'] = false;
			$tail = $m['tail'];
			preg_match("/^(?P<evt>[0-9]+)\.(?P<frame>[0-9]+).+$/i", $tail, $m);
			$ret['event'] = $m['evt'];
			$ret['event_frame'] = $m['frame'];
		}
		
		return $ret;
	}
}