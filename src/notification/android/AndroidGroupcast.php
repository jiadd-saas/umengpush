<?php
namespace Jiadd\Umeng\Notification\Android;

use Jiadd\Umeng\Notification\AndroidNotification;

class AndroidGroupcast extends AndroidNotification {
	function  __construct() {
		parent::__construct();
		$this->data["type"] = "groupcast";
		$this->data["filter"]  = NULL;
	}
}