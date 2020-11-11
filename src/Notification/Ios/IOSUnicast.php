<?php
namespace Jiadd\Umeng\Notification\Ios;

use Jiadd\Umeng\Notification\IOSNotification;

class IOSUnicast extends IOSNotification {
	function __construct() {
		parent::__construct();
		$this->data["type"] = "unicast";
		$this->data["device_tokens"] = NULL;
	}

}