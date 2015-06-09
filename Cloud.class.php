<?php
/**
 *    ACS Cloud Service PHP Helper Class
 *    by WisdomSky.
 * 
 * */

class Cloud {

	const API 			= "https://api.cloud.appcelerator.com/v1";
	

	protected $key 		= null;
	
	
	protected $user 	= null;
	
	
	protected $pass 	= null;
	
	
	protected $curl 	= null;
	
	
	protected $curlopts = array();
	
	
	public $log 		= "";
	
	 
	/**
	 * ACS Class constructor
	 * 	
	 * @param Array $args The constructor arguments
	 * 			<br><strong>key</strong> - App key 
	 * 			<br><strong>user</strong> - Admin username 
	 * 			<br><strong>pass</strong> - Admin password 
	 * */
	public function __construct($args) {
		
		if($args instanceof Cloud){
			$this->key = $args->key;
			if ( $args->user!=null ) {
							$this->user = $args->user;
			}
			if ( $args->pass!=null ) {
				$this->pass = $args->pass;
			}
		} else {
			if ( isset($args["key"]) ) {
				$this->key 	= $args["key"];
			}
			if ( isset($args["user"]) ) {
				$this->user = $args["user"];
			}
			if ( isset($args["pass"]) ){
				$this->pass = $args["pass"];
			}
		}
		
		$this->curl = curl_init();
		if ( $this->key!=null && $this->user!=null && $this->pass!=null) {
			$this->login($this->user, $this->pass);
		}
	}


	public function login($user,$pass){
		$this->user = $user;
		$this->pass = $pass;
		$this->curlopts = array(CURLOPT_URL => 	Cloud::API.'/users/login.json?key='.$this->key,
					CURLOPT_COOKIEJAR 			=> 	'cookie.txt',
					CURLOPT_COOKIEFILE 			=> 	'cookie.txt',
					CURLOPT_RETURNTRANSFER 		=> 	true,
					CURLOPT_POST 				=> 	1,
					CURLOPT_POSTFIELDS  		=>  "login=".$this->user."&password=".$this->pass,
					CURLOPT_FOLLOWLOCATION  	=>  1,
					CURLOPT_TIMEOUT 			=> 	60);
			
			$this->exec();
	}
	
	public function me(){
		return new User($this);
	}
	
	
	public function logout(){
		$this->api("/users/logout", "GET", array(), false);
	}

	protected function api($endpoint, $http_method, Array $params, $clone) {
		
		
		if ( strlen($endpoint) < 1 ) {
			throw new Exception("Invalid endpoint");
		}
		
		
		$obj = $clone ? clone $this : $this;
		$obj->curlopts[CURLOPT_URL]				= Cloud::API.$endpoint.".json?key=".$obj->key;
		$obj->curlopts[CURLOPT_POSTFIELDS] 		= $params;
		$obj->curlopts[CURLOPT_CUSTOMREQUEST]	= $http_method;	
		
		$obj->exec();
		if ( $clone ) {
			return $obj;
		} else {
			return $this->log;
		}
		
	}


	public function __call($var, $args) {
		
		switch ( $var ) {
			case "CustomObjects":
				$obj = new CustomObjects($this);
				$obj->classname = $args[0];
				return $obj;
				break;
			case "PushNotification":
				$obj = new PushNotification($this);
				return $obj;
				break;
			case "api":
				if ( !isset($args[0]) || $args[0] == null ) {
					$args[0] = "";
				}
				if ( !isset($args[1]) || $args[1] == null ) {
					$args[1] = "POST";
				}
				if ( !isset($args[2]) || $args[2] == null ) {
					$args[2] = array();
				}
				if ( !isset($args[3]) || $args[3] == null ) {
					$args[3] = false;
				}
				return $this->api($args[0], $args[1], $args[2], $args[3]);
				break;
		}
		
	}
	
	
	public function __get($var) {
		switch ( $var ) {
			case "CustomObjects":
				$obj = new CustomObjects($this);
				return $obj;
				break;
			case "PushNotification":
				$obj = new PushNotification($this);
				return $obj;
		}
	}
	
	
	/**
	 * Returns the response from the request wrapped in a Response object
	 * 
	 * @return Response
	 */
	public function response() {
		return new Response($this->log);
	}
	
	
	/**
	 * Returns the HTTP response code from the request
	 * 
	 * @return Integer
	 */
	public function getResponseCode() {
		$meta = json_decode($this->log)->meta;
		return $meta->code;
	}
	
	
	/**
	 * Executes the request and returns the response text
	 * 
	 * @return String
	 */
	protected function exec() {
		curl_setopt_array($this->curl, $this->curlopts);
		$log = curl_exec($this->curl);
		$this->log = $log;
		return $log; 
	}
	
}



/**
 * http://docs.appcelerator.com/cloud/latest/#!/api/PushNotifications
 * @link http://docs.appcelerator.com/cloud/latest/#!/api/PushNotifications
 * 
 * 
 */
class PushNotification extends Cloud {
	
	private $parent = null;
	
	
	public function __construct(&$args) { 
		parent::__construct($args);
		$this->parent = &$args;
	}
	
	
	/**
	 * PushNotification#notify()
	 * <br>
	 * http://docs.appcelerator.com/cloud/latest/#!/api/PushNotifications-method-notify
	 * @link http://docs.appcelerator.com/cloud/latest/#!/api/PushNotifications-method-notify
	 * @param String $args
	 * 
	 */
	public function notify(Array $args) {  
		
			if(isset($args["channel"])){
				$_channel = $args["channel"];
				unset($args["channel"]);
			} else {
				throw new Exception("Channel is required");
			}
			
			
			$to_ids = "everyone";
			if(isset($args["to_ids"])){
				$to_ids = $args["to_ids"];
			}
			
			$this->parent->log = $this->api("/push_notification/notify", "POST", array(
								"channel" => $_channel,
								"payload" => stripcslashes(json_encode((object)$args)),
								"to_ids"  => $to_ids), false);
								

	}
	
	
	/**
	 * PushNotification#reset_badge()
	 * <br>
	 * http://docs.appcelerator.com/cloud/latest/#!/api/PushNotifications-method-reset_badge_get
	 * @link http://docs.appcelerator.com/cloud/latest/#!/api/PushNotifications-method-reset_badge_get
	 * 
	 * @param $device_token the arguments
	 * 			<br><strong>device_token</strong> - the target device's device token (optional)
	 * 
	 */
	private function reset_badge($arr) {
		$this->parent->log = $this->api("/push_notification/reset_badge", (count($arr)>0 ? "PUT" : "GET"), $arr, false);
		
	}
	 
	 
	/**
	 * PushNotification#count()
	 * <br>
	 * http://docs.appcelerator.com/cloud/latest/#!/api/PushNotifications-method-count
	 * @link http://docs.appcelerator.com/cloud/latest/#!/api/PushNotifications-method-count
	 * 
	 * @param void
	 * 
	 */
	public function count() {
		$this->parent->log = $this->api("/push_notification/count", "GET", array(), false);
	}
	
	
	/**
	 * PushNotification#set_badge()
	 * <br>
	 * http://docs.appcelerator.com/cloud/latest/#!/api/PushNotifications-method-set_badge
	 * @link http://docs.appcelerator.com/cloud/latest/#!/api/PushNotifications-method-set_badge
	 * 
	 * @param $args the arguments
	 * 		<br><strong>badge_number</strong> - number of badge to set
	 * 		<br><strong>device_token</strong> - the target device's device token
	 * 
	 */	
	public function set_badge($args) {
		$this->parent->log = $this->api("/push_notification/set_badge", "PUT", $args, false);
	}
	
	
	public function __call($var,$args) {
		switch ( $var ) {
			case "reset_badge":
				$this->reset_badge(count($args)>0?$args[0]:array());
				break;
		}
	}
	
}

/**
 * http://docs.appcelerator.com/cloud/latest/#!/api/CustomObjects
 * @link http://docs.appcelerator.com/cloud/latest/#!/api/CustomObjects
 * 
 */
class CustomObjects extends Cloud {

	private $parent = null;
	
	
	public function __construct(&$args) {
		parent::__construct($args);
		$this->parent = &$args;
	}
	
	
	/**
	 * Creates a new CustomObject of the specified class name.
	 * @param String $name the class name
	 * @return CustomObject
	 */
	public function create($name) {
		$obj = new CustomObject($this->parent);
		return $obj->create($name);
	}
	
	
	/**
	 * Creates a new QueryCustomObject of the specified class name.
	 * @param String $name the class name
	 * @return QueryCustomObjects
	 */	
	public function query($name) {
		$obj = new QueryCustomObjects($this->parent);
		return $obj->create($name);
	}
	
} 

class QueryCustomObjects extends Cloud {
		
	
	private $parent = null;	
	
			
	public $classname = null;	
	
		
	private $params = array();
		
		
	public function __construct(&$args) {
		parent::__construct($args);
		$this->parent = &$args;
	}
	
	
	/**
	 * Sets the classname
	 * @param string $name the classname
	 * @return QueryCustomObjects
	 */
	public function create($name) {
		$this->classname = $name;
		return $this;
	}	
	
	
	public function __set($var, $val) {
		$this->params[$var] = $val;
	}
	
	
	/**
	 * Retrieves results from the query
	 * 
	 * @return CustomObject[]
	 */
	public function results() {

		if ( isset($this->params["where"]) ) {
			$this->params["where"] 					= json_encode((object)$this->params["where"]);
		}
		if( isset($this->params["sel"]) ) {
			$this->params["sel"] 					= json_encode((object)$this->params["sel"]);
		}
		if( isset($this->params["unsel"]) ) {
			$this->params["unsel"] 					= json_encode((object)$this->params["unsel"]);
		}
		
		$this->parent->log = $this->api("/objects/".$this->classname."/query", "GET", $this->params, false);
		
		$results = array();
		foreach ( $this->response()->toObject()->response->{$this->classname} as $item ) {
			$obj = new CustomObject($this->parent);
			$obj->create($this->classname);
			$obj->get($item->id);
			foreach ( get_object_vars($item) as $key => $value ) {
				$obj->{$key} = $value;
			}
			
			$results[] = $obj;
		}
		
		return $results;
	}
	
	
}

class CustomObject extends Cloud implements ORM{
	
	private $parent = null;
		
		
	private $params = array();
	
	
	public $classname = null;
	
	
	public $id = null;
	 
	
	public function __construct(&$args) {
		parent::__construct($args);
		$this->parent 		= &$args;
	}
	
	
	public function __set($var,$val) {
		$this->params[$var] = $val;
	}
	
	
	public function __get($var) {
		return $this->params[$var];
	}
	
	
	/**
	 * Sets the classname
	 * @param string $name the classname
	 * @return CustomObject
	 */
	public function create($name) {
		$this->classname = $name;
		return $this;
	}
	
	
	/**
	 * Sets the target CustomObject ID.
	 * @param String $id the ACS Object id
	 */
	public function get($id) {
		$this->id = $id;
		
		$this->parent->log = $this->api("/objects/".$this->classname."/show", "GET", array("id" => $this->id), false);
		
		$res = $this->response()->toObject()->response->{$this->classname}[0];
		foreach ( get_object_vars($res) as $key => $value ) {
			if(is_object($value)){
				$this->params[$key] = json_encode($value);
			} else {
				$this->params[$key] = $value;
			}
			
		}
		
		
	}
	
	
	/**
	 * Deletes the referenced CustomObject from the ACS
	 * @return void
	 */
	public function destroy() {
		if($this->id!=null){
			$this->parent->log = $this->api("/objects/".$this->classname."/delete", "DELETE", array("id" => $this->id), false);
			if ( $this->response()->success() ) {
				$this->id = null;
			}
		}		
	}
	
	
	/**
	 * Creates the CustomObject into the ACS.
	 * 
	 * If the the CustomObject already existed in the ACS, the modified fields will be updated instead.
	 * 
	 * @return void
	 */
	public function save() {
		$what = "create";
		$params = array("fields" => json_encode((object)$this->params));
		$method = "POST";
		if ( $this->id!=null ) {
			$what = "update";	
			$params["id"] = $this->id;		
			$method = "PUT";
		}
		
		$this->parent->log = $this->api("/objects/".$this->classname."/".$what, $method, $params, false);
		
		
		if ( $this->response()->success() ) {
			$obj = $this->response()->response->{$this->classname}[0];
			$this->id = $obj->id;
		}
		
	}
	
	
	/**
	 * Returns the response from the request wrapped in a Response object
	 * 
	 * @return COResponse
	 */
	public function response() {
		return new COResponse($this->log);
	}
	
}

class User extends Cloud implements ORM {
	
	private $parent = null;
	
	
	public $id = null;
	
	
	private $params = array();
	
	
	public function __construct(&$args) {
		parent::__construct($args);
		$this->parent = &$args;
		
		$this->api("/users/show/me", "GET", array(), false);
		$user = $this->response()->toObject()->response->users[0];
		foreach ( get_object_vars($user) as $key => $value ) {
			$this->params[$key] = $value;
		}
		
		if(@strlen($user->id)>0){
			$this->id = $user->id;
		}
		
	}
	
	
	public function get($id) {
		$this->id = $id;
	}
	
	
	public function __set($var,$val) {
		$this->params[$var] = $val;
	}
	
	
	public function __get($var) {
		return $this->params[$var];
	}
	
	
	public function save() {
		$what = "create";
		$method = "POST";
		
		if ( $this->id!=null ) {
			$what = "update";		
			$method = "PUT";
		}
		
		
		if ( isset($this->params["custom_fields"]) ) {
			$this->params["custom_fields"] = json_encode((object)$this->params["custom_fields"]);
		}
		
		
		foreach ($this->params as $key => $value) {
			if(is_object($this->params[$key])){
				$this->params[$key] = json_encode($value);
			}
		}

		$this->parent->log = $this->api("/users/".$what, $method, $this->params, false);

		if ( $this->response()->success() ) {
			$obj = $this->response()->response->users[0];
			$this->id = $obj->id;
		}
	}
	public function destroy() {
		if($this->id!=null){
			$this->parent->log = $this->api("/users/delete", "DELETE", array(), false);
			if ( $this->response()->success() ) {
				$this->id = null;
			}
		}	
	}
}



class Response {
	
	/**
	 * The HTTP response code
	 * @return Integer
	 */
	public $code = null;
	
	
	/**
	 * The status response. the value could either be ok or fail.
	 * @return String
	 */
	public $status = null;
	
	
	/**
	 * The ACS method where the request is sent
	 * @return String
	 */
	public $method_name = null;
	
	
	/**
	 * The request response
	 * @return String
	 */
	public $log = "";
	
	
	/**
	 * Response Class constructor
	 * @param $log The response text that will be formatted
	 * @return void
	 */
	public function __construct($log) {
		$this->log 			= $log;
		$meta 				= json_decode($log)->meta;
		$this->code 		= $meta->code;
		$this->status 		= $meta->status;
		$this->method_name 	= $meta->method_name;
	}
	
	
	public function __get($var) {
		return $this->{$var};
	}
	
	
	/**
	 * Returns true if the request was successful and returns false if not
	 *  @return Boolean
	 */
	public function success() {
		return ($this->code==200) ? true : false;
	}
	
	
	/**
	 * Converts the response text into a standard object.
	 * A wrapper function that uses json_decode to turn the data into object
	 * 
	 * @return stdClass
	 */
	public function toObject() {
		return json_decode($this->log);
	}
	
	
	public function __toString() {
		return $this->log;
	}
}

class COResponse extends Response {
	
	/**
	 * The response data
	 * @return stdClass
	 */
	public $response = "";
	
	
	/**
	 * COResponse Class constructor
	 * @param $log The response text that will be formatted
	 * @return void
	 */
	public function __construct($log) {
		parent::__construct($log);
		$this->response = json_decode($log)->response;
	}
	
	
}

interface ORM {
	public function destroy();
	public function get($id);
	public function save();
}
