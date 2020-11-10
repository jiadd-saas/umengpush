<?php
namespace Jiadd\Umeng\Notification\Android;

use Jiadd\Umeng\Notification\AndroidNotification;

class AndroidBroadcast extends AndroidNotification {
	function  __construct() {
		parent::__construct();
		$this->data["type"] = "broadcast";
	}
}