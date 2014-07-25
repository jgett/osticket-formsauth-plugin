<?php
require_once(INCLUDE_DIR.'class.plugin.php');
require_once(INCLUDE_DIR.'class.signal.php');
require_once(INCLUDE_DIR.'class.app.php');
require_once(INCLUDE_DIR.'class.auth.php');
 
require_once('config.php');

class FormsAuthPlugin extends Plugin {

	public $config_class = 'FormsAuthConfig';

	static function debugPrint($obj, $die=false){
		echo "<pre>";
		print_r($obj);
		echo "</pre>";
		if ($die) die();
	}
	
	function bootstrap(){
		$config = $this->getConfig();
		if ($this->dependencyCheck()){
			if ($config->get('formsauth_enable_staff'))
				StaffAuthenticationBackend::register(new StaffFormsAuthentication($config));
			if ($config->get('formsauth_enable_user'))
				UserAuthenticationBackend::register(new UserFormsAuthentication($config));
		}
	}
	
	function dependencyCheck(){
		return function_exists('curl_init');
	}
}

class FormsAuthentication{
	private $config;
	private $type;
	
	function __construct($config, $type='staff'){
		$this->config = $config;
		$this->type = $type;
	}
	
	function getConfig(){
		return $this->config;
	}
	
	function getType(){
		return $this->type;
	}
	
	function checkAuth(){
		$cookieValue = $_COOKIE[$this->getConfig()->get('formsauth_cookie')];
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->getConfig()->get('authcheck_url'));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('cookieValue' => $cookieValue)));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
		$output = curl_exec($ch);
		curl_close($ch);
		return json_decode($output);
	}
}

class StaffFormsAuthentication extends StaffAuthenticationBackend implements ExternalAuthentication{
	static $name = "Forms Authentication";
	static $id = "forms.staff";
	
	private $forms;
	private $config;
	
	function __construct($config){
		$this->config = $config;
		$this->forms = new FormsAuthentication($config, 'staff');
	}
	
	function supportsInteractiveAuthentication(){
        return false;
    } 
	
	function signOn(){
		$auth = $this->forms->checkAuth();
		
		if ($auth && $auth->success && $auth->authenticated && in_array("staff", array_map('strtolower', $auth->roles))){
			if($staff = new StaffSession($auth->username)){
				$_SESSION["formsAuth.loginUrl"] = self::replace($this->config->get('external_link_url'));
				return $staff;
			}
		}

		return false;
	}
	
	static function replace($s){
		$result = $s;
		$result = str_replace('%{hostname}', $_SERVER["HTTP_HOST"], $result);
		$result = str_replace('%{url}', ROOT_PATH."scp", $result);
		return $result;
	}
	
	function renderExternalLink(){
		$external_link_url = self::replace($this->config->get('external_link_url'));
		$external_link_text = self::replace($this->config->get('external_link_text'));
		echo '<a href="'.$external_link_url.'">'.$external_link_text.'</a>';
	}
	
	function triggerAuth(){
		//die('when is this called?');
		//nothing to do here
	}
	
	static function signOut($staff){
		$result = parent::signOut($staff);
		
		if ($_SESSION["formsAuth.loginUrl"]){
			$url = $_SESSION["formsAuth.loginUrl"];
			unset($_SESSION["formsAuth.loginUrl"]);
			header("Location: ".self::replace($url));
			exit();
		}
		else
			return $result;
	}
}

class UserFormsAuthentication extends UserAuthenticationBackend{
	static $name = "Forms Authentication";
	static $id = "forms.user";
	
	private $forms;
	
	function __construct($config){
		$this->forms = new FormsAuthentication($config, 'user');
	}
	
	function supportsInteractiveAuthentication(){
        return false;
    } 
	
	function signOn(){
		$auth = $this->forms->checkAuth();
	}
}
?>