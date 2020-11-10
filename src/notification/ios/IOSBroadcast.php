<?php
namespace Jiadd\Umeng\Notification\Ios;

use Jiadd\Umeng\Notification\IOSNotification;

class IOSBroadcast extends IOSNotification {
	function  __construct() {
		parent::__construct();
		$this->data["type"] = "broadcast";
	}
}