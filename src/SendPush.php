<?php
namespace Jiadd\Umeng;

use Jiadd\Umeng\Notification\Android\AndroidBroadcast;
use Jiadd\Umeng\Notification\Android\AndroidCustomizedcast;
use Jiadd\Umeng\Notification\Android\AndroidFilecast;
use Jiadd\Umeng\Notification\Android\AndroidGroupcast;
use Jiadd\Umeng\Notification\Android\AndroidUnicast;
use Jiadd\Umeng\Notification\Ios\IOSBroadcast;
use Jiadd\Umeng\Notification\Ios\IOSCustomizedcast;
use Jiadd\Umeng\Notification\Ios\IOSFilecast;
use Jiadd\Umeng\Notification\Ios\IOSGroupcast;
use Jiadd\Umeng\Notification\Ios\IOSUnicast;

class SendPush {
	protected $androidAppkey           = NULL;
	protected $androidAppMasterSecret     = NULL;
    protected $iosAppkey           = NULL;
    protected $iosAppMasterSecret     = NULL;
	protected $timestamp        = NULL;
	protected $deviceToken = NULL;

	protected $params = NULL;
	protected $extra = NULL;

    /**
     * SendPush constructor.
     * @param array $config = [
     *  'android_app_key',
     *  'android_app_master_secret',
     *  'ios_app_key',
     *  'ios_app_master_secret'
     * ]
     * @param $deviceToken
     * @param array $params=[
     *  'ticker'
     *  'title'
     *  'text'
     * ]
     * @param array $extra
     */
	function __construct($config, $deviceToken, $params, $extra=[]) {
		$this->androidAppkey = $config['android_app_key'];
        $this->androidAppMasterSecret = $config['android_app_master_secret'];
        $this->iosAppkey = $config['ios_app_key'];
        $this->iosAppMasterSecret = $config['ios_app_master_secret'];
		$this->timestamp = strval(time());

		$this->deviceToken = $deviceToken;
		$this->params = $params;
		$this->extra = $extra;
	}

    public function sendAndroidUnicast() {
        try {
            $unicast = new AndroidUnicast();
            var_dump($this->androidAppkey, $this->androidAppkey);
            $unicast->setAppMasterSecret($this->androidAppMasterSecret);
            $unicast->setPredefinedKeyValue("appkey",           $this->androidAppkey);
            $unicast->setPredefinedKeyValue("timestamp",        $this->timestamp);
            // Set your device tokens here
            $unicast->setPredefinedKeyValue("device_tokens",    $this->deviceToken);
            $unicast->setPredefinedKeyValue("ticker",           $this->params['ticker'] ?? '');   // 必填，通知栏提示文字
            $unicast->setPredefinedKeyValue("title",            $this->params['title'] ?? '');    // 必填，通知标题
            $unicast->setPredefinedKeyValue("text",             $this->params['text'] ?? '');     // 必填，通知文字描述
            $unicast->setPredefinedKeyValue("after_open",       "go_app");
            // Set 'production_mode' to 'false' if it's a test device.
            // For how to register a test device, please see the developer doc.
            // Set extra fields
            if ($this->extra) {
                foreach ($this->extra as $key=>$item) {
                    $unicast->setExtraField($key, $item);
                }
            }

            $result = $unicast->send();
            return $result;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function sendIOSUnicast() {
        try {
            $unicast = new IOSUnicast();
            $unicast->setAppMasterSecret($this->iosAppMasterSecret);
            $unicast->setPredefinedKeyValue("appkey",           $this->iosAppkey);
            $unicast->setPredefinedKeyValue("timestamp",        $this->timestamp);
            // Set your device tokens here
            $unicast->setPredefinedKeyValue("device_tokens",    $this->deviceToken);
            $unicast->setPredefinedKeyValue("alert", [
                'title' => $this->params['title'] ?? '',
                'subtitle' => $this->params['subtitle'] ?? '',
                'body' => $this->params['body'] ?? ''
            ]);
            // Set customized fields
            if ($this->extra) {
                foreach ($this->extra as $key=>$item) {
                    $unicast->setCustomizedField($key, $item);
                }
            }
            //TODO 上线删掉
            $unicast->setPredefinedKeyValue("production_mode", "false");

            $result = $unicast->send();
            return $result;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }


    //TODO 未调试
	function sendAndroidBroadcast() {
		try {
			$brocast = new AndroidBroadcast();
			$brocast->setAppMasterSecret($this->androidAppMasterSecret);
			$brocast->setPredefinedKeyValue("appkey",           $this->androidAppkey);
			$brocast->setPredefinedKeyValue("timestamp",        $this->timestamp);
			$brocast->setPredefinedKeyValue("ticker",           "Android broadcast ticker");
			$brocast->setPredefinedKeyValue("title",            "中文的title");
			$brocast->setPredefinedKeyValue("text",             "Android broadcast text");
			$brocast->setPredefinedKeyValue("after_open",       "go_app");
			// Set 'production_mode' to 'false' if it's a test device. 
			// For how to register a test device, please see the developer doc.
			$brocast->setPredefinedKeyValue("production_mode", "true");
			// [optional]Set extra fields
			$brocast->setExtraField("test", "helloworld");
			print("Sending broadcast notification, please wait...\r\n");
			$brocast->send();
			print("Sent SUCCESS\r\n");
		} catch (Exception $e) {
			print("Caught exception: " . $e->getMessage());
		}
	}

    //TODO 未调试
	function sendAndroidFilecast() {
		try {
			$filecast = new AndroidFilecast();
			$filecast->setAppMasterSecret($this->appMasterSecret);
			$filecast->setPredefinedKeyValue("appkey",           $this->appkey);
			$filecast->setPredefinedKeyValue("timestamp",        $this->timestamp);
			$filecast->setPredefinedKeyValue("ticker",           "Android filecast ticker");
			$filecast->setPredefinedKeyValue("title",            "Android filecast title");
			$filecast->setPredefinedKeyValue("text",             "Android filecast text");
			$filecast->setPredefinedKeyValue("after_open",       "go_app");  //go to app
			print("Uploading file contents, please wait...\r\n");
			// Upload your device tokens, and use '\n' to split them if there are multiple tokens
			$filecast->uploadContents("aa"."\n"."bb");
			print("Sending filecast notification, please wait...\r\n");
			$filecast->send();
			print("Sent SUCCESS\r\n");
		} catch (Exception $e) {
			print("Caught exception: " . $e->getMessage());
		}
	}

    //TODO 未调试
	function sendAndroidGroupcast() {
		try {
			/* 
		 	 *  Construct the filter condition:
		 	 *  "where": 
		 	 *	{
    	 	 *		"and": 
    	 	 *		[
      	 	 *			{"tag":"test"},
      	 	 *			{"tag":"Test"}
    	 	 *		]
		 	 *	}
		 	 */
			$filter = 	array(
							"where" => 	array(
								    		"and" 	=>  array(
								    						array(
							     								"tag" => "test"
															),
								     						array(
							     								"tag" => "Test"
								     						)
								     		 			)
								   		)
					  	);
					  
			$groupcast = new AndroidGroupcast();
			$groupcast->setAppMasterSecret($this->appMasterSecret);
			$groupcast->setPredefinedKeyValue("appkey",           $this->appkey);
			$groupcast->setPredefinedKeyValue("timestamp",        $this->timestamp);
			// Set the filter condition
			$groupcast->setPredefinedKeyValue("filter",           $filter);
			$groupcast->setPredefinedKeyValue("ticker",           "Android groupcast ticker");
			$groupcast->setPredefinedKeyValue("title",            "Android groupcast title");
			$groupcast->setPredefinedKeyValue("text",             "Android groupcast text");
			$groupcast->setPredefinedKeyValue("after_open",       "go_app");
			// Set 'production_mode' to 'false' if it's a test device. 
			// For how to register a test device, please see the developer doc.
			$groupcast->setPredefinedKeyValue("production_mode", "true");
			print("Sending groupcast notification, please wait...\r\n");
			$groupcast->send();
			print("Sent SUCCESS\r\n");
		} catch (Exception $e) {
			print("Caught exception: " . $e->getMessage());
		}
	}

    //TODO 未调试
	function sendAndroidCustomizedcast() {
		try {
			$customizedcast = new AndroidCustomizedcast();
			$customizedcast->setAppMasterSecret($this->appMasterSecret);
			$customizedcast->setPredefinedKeyValue("appkey",           $this->appkey);
			$customizedcast->setPredefinedKeyValue("timestamp",        $this->timestamp);
			// Set your alias here, and use comma to split them if there are multiple alias.
			// And if you have many alias, you can also upload a file containing these alias, then 
			// use file_id to send customized notification.
			$customizedcast->setPredefinedKeyValue("alias",            "xx");
			// Set your alias_type here
			$customizedcast->setPredefinedKeyValue("alias_type",       "xx");
			$customizedcast->setPredefinedKeyValue("ticker",           "Android customizedcast ticker");
			$customizedcast->setPredefinedKeyValue("title",            "Android customizedcast title");
			$customizedcast->setPredefinedKeyValue("text",             "Android customizedcast text");
			$customizedcast->setPredefinedKeyValue("after_open",       "go_app");
			print("Sending customizedcast notification, please wait...\r\n");
			$customizedcast->send();
			print("Sent SUCCESS\r\n");
		} catch (Exception $e) {
			print("Caught exception: " . $e->getMessage());
		}
	}

    //TODO 未调试
	function sendAndroidCustomizedcastFileId() {
		try {
			$customizedcast = new AndroidCustomizedcast();
			$customizedcast->setAppMasterSecret($this->appMasterSecret);
			$customizedcast->setPredefinedKeyValue("appkey",           $this->appkey);
			$customizedcast->setPredefinedKeyValue("timestamp",        $this->timestamp);
			// if you have many alias, you can also upload a file containing these alias, then
			// use file_id to send customized notification.
			$customizedcast->uploadContents("aa"."\n"."bb");
			// Set your alias_type here
			$customizedcast->setPredefinedKeyValue("alias_type",       "xx");
			$customizedcast->setPredefinedKeyValue("ticker",           "Android customizedcast ticker");
			$customizedcast->setPredefinedKeyValue("title",            "Android customizedcast title");
			$customizedcast->setPredefinedKeyValue("text",             "Android customizedcast text");
			$customizedcast->setPredefinedKeyValue("after_open",       "go_app");
			print("Sending customizedcast notification, please wait...\r\n");
			$customizedcast->send();
			print("Sent SUCCESS\r\n");
		} catch (Exception $e) {
			print("Caught exception: " . $e->getMessage());
		}
	}

    //TODO 未调试
	function sendIOSBroadcast() {
		try {
			$brocast = new IOSBroadcast();
			$brocast->setAppMasterSecret($this->appMasterSecret);
			$brocast->setPredefinedKeyValue("appkey",           $this->appkey);
			$brocast->setPredefinedKeyValue("timestamp",        $this->timestamp);

			$brocast->setPredefinedKeyValue("alert", "IOS 广播测试");
			$brocast->setPredefinedKeyValue("badge", 0);
			$brocast->setPredefinedKeyValue("sound", "chime");
			// Set 'production_mode' to 'true' if your app is under production mode
			$brocast->setPredefinedKeyValue("production_mode", "false");
			// Set customized fields
			$brocast->setCustomizedField("test", "helloworld");
			print("Sending broadcast notification, please wait...\r\n");
			$brocast->send();
			print("Sent SUCCESS\r\n");
		} catch (Exception $e) {
			print("Caught exception: " . $e->getMessage());
		}
	}

    //TODO 未调试
	function sendIOSFilecast() {
		try {
			$filecast = new IOSFilecast();
			$filecast->setAppMasterSecret($this->appMasterSecret);
			$filecast->setPredefinedKeyValue("appkey",           $this->appkey);
			$filecast->setPredefinedKeyValue("timestamp",        $this->timestamp);

			$filecast->setPredefinedKeyValue("alert", "IOS 文件播测试");
			$filecast->setPredefinedKeyValue("badge", 0);
			$filecast->setPredefinedKeyValue("sound", "chime");
			// Set 'production_mode' to 'true' if your app is under production mode
			$filecast->setPredefinedKeyValue("production_mode", "false");
			print("Uploading file contents, please wait...\r\n");
			// Upload your device tokens, and use '\n' to split them if there are multiple tokens
			$filecast->uploadContents("aa"."\n"."bb");
			print("Sending filecast notification, please wait...\r\n");
			$filecast->send();
			print("Sent SUCCESS\r\n");
		} catch (Exception $e) {
			print("Caught exception: " . $e->getMessage());
		}
	}

    //TODO 未调试
	function sendIOSGroupcast() {
		try {
			/* 
		 	 *  Construct the filter condition:
		 	 *  "where": 
		 	 *	{
    	 	 *		"and": 
    	 	 *		[
      	 	 *			{"tag":"iostest"}
    	 	 *		]
		 	 *	}
		 	 */
			$filter = 	array(
							"where" => 	array(
								    		"and" 	=>  array(
								    						array(
							     								"tag" => "iostest"
															)
								     		 			)
								   		)
					  	);
					  
			$groupcast = new IOSGroupcast();
			$groupcast->setAppMasterSecret($this->appMasterSecret);
			$groupcast->setPredefinedKeyValue("appkey",           $this->appkey);
			$groupcast->setPredefinedKeyValue("timestamp",        $this->timestamp);
			// Set the filter condition
			$groupcast->setPredefinedKeyValue("filter",           $filter);
			$groupcast->setPredefinedKeyValue("alert", "IOS 组播测试");
			$groupcast->setPredefinedKeyValue("badge", 0);
			$groupcast->setPredefinedKeyValue("sound", "chime");
			// Set 'production_mode' to 'true' if your app is under production mode
			$groupcast->setPredefinedKeyValue("production_mode", "false");
			print("Sending groupcast notification, please wait...\r\n");
			$groupcast->send();
			print("Sent SUCCESS\r\n");
		} catch (Exception $e) {
			print("Caught exception: " . $e->getMessage());
		}
	}

    //TODO 未调试
	function sendIOSCustomizedcast() {
		try {
			$customizedcast = new IOSCustomizedcast();
			$customizedcast->setAppMasterSecret($this->appMasterSecret);
			$customizedcast->setPredefinedKeyValue("appkey",           $this->appkey);
			$customizedcast->setPredefinedKeyValue("timestamp",        $this->timestamp);

			// Set your alias here, and use comma to split them if there are multiple alias.
			// And if you have many alias, you can also upload a file containing these alias, then 
			// use file_id to send customized notification.
			$customizedcast->setPredefinedKeyValue("alias", "xx");
			// Set your alias_type here
			$customizedcast->setPredefinedKeyValue("alias_type", "xx");
			$customizedcast->setPredefinedKeyValue("alert", "IOS 个性化测试");
			$customizedcast->setPredefinedKeyValue("badge", 0);
			$customizedcast->setPredefinedKeyValue("sound", "chime");
			// Set 'production_mode' to 'true' if your app is under production mode
			$customizedcast->setPredefinedKeyValue("production_mode", "false");
			print("Sending customizedcast notification, please wait...\r\n");
			$customizedcast->send();
			print("Sent SUCCESS\r\n");
		} catch (Exception $e) {
			print("Caught exception: " . $e->getMessage());
		}
	}
}
