<?php
namespace Jiadd\Umeng\Notification\Ios;

use Jiadd\Umeng\Notification\IOSNotification;

class IOSListcast extends IOSNotification {
	function __construct() {
		parent::__construct();
		$this->data["type"] = "listcast";
		$this->data["device_tokens"] = NULL;
	}

}