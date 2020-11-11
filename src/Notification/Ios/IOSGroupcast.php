<?php
namespace Jiadd\Umeng\Notification\Ios;

use Jiadd\Umeng\Notification\IOSNotification;

class IOSGroupcast extends IOSNotification {
	function  __construct() {
		parent::__construct();
		$this->data["type"] = "groupcast";
		$this->data["filter"]  = NULL;
	}
}