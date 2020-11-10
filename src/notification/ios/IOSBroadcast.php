<?php
namespace Umeng\Notification\Ios;

use Umeng\Notification\IOSNotification;

class IOSBroadcast extends IOSNotification {
	function  __construct() {
		parent::__construct();
		$this->data["type"] = "broadcast";
	}
}