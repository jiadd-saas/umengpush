<?php
namespace Jiadd\Umeng\Notification\Android;

use Jiadd\Umeng\Notification\AndroidNotification;

class AndroidListcast extends AndroidNotification {
	function __construct() {
		parent::__construct();
		$this->data["type"] = "listcast";
		$this->data["device_tokens"] = NULL;
	}

}