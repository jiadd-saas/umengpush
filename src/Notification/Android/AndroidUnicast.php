<?php
namespace Jiadd\Umeng\Notification\Android;

use Jiadd\Umeng\Notification\AndroidNotification;

class AndroidUnicast extends AndroidNotification {
	function __construct() {
		parent::__construct();
		$this->data["type"] = "unicast";
		$this->data["device_tokens"] = NULL;
	}

}