<?php
require_once(INCLUDE_DIR.'/class.plugin.php');
require_once(INCLUDE_DIR.'/class.forms.php');
 
class FormsAuthConfig extends PluginConfig {
	function getOptions() {
		return array(
			'formsauth_enable_staff' => new BooleanField(array(
				'id' => 'formsauth_enable_staff',
				'label' => 'Staff',
				'configuration' => array('desc' => 'Enable forms authentication for staff logins')
			)),
			'formsauth_enable_user' => new BooleanField(array(
				'id' => 'formsauth_enable_user',
				'label' => 'Users',
				'configuration' => array('desc' => 'Enable forms authentication for user logins')
			)),
			'formsauth_cookie' => new TextboxField(array(
				'id' => 'formsauth_cookie',
				'label' => 'Forms Auth Cookie',
				'configuration' => array('desc' => 'The name of the cookie used by ASP.NET FormsAuthentication', 'length' => 50)
			)),
			'authcheck_url' => new TextboxField(array(
				'id' => 'authcheck_url',
				'label' => 'Authentication URL',
				'length' => 100,
				'configuration' => array('desc' => 'The URL that will check the cookie and respond with proper json', 'length' => 500)
			)),
			'external_link_url' => new TextboxField(array(
				'id' => 'external_link_url',
				'label' => 'External Link URL',
				'length' => 100,
				'configuration' => array('desc' => 'URL for the external login page', 'length' => 500)
			)),
			'external_link_text' => new TextboxField(array(
				'id' => 'external_link_text',
				'label' => 'External Link Text',
				'length' => 100,
				'configuration' => array('desc' => 'Link text for the external login page', 'length' => 500)
			))
		);
	}
	
	/* override to set the title */
	function getForm() {
		$result = parent::getForm();
		$result->title = "Forms Auth";
		/*echo '<hr/>';
		echo '<strong>Current Cookies:</strong>';
		echo '<ul>';
		foreach ($_COOKIE as $key => $value)
			echo "<li>$key</li>";
		echo '</ul>';
		echo '<hr/>';*/
		return $result;
	}
 
	function pre_save(&$config, &$errors) {
		global $msg;
 
		if (!$errors)
			$msg = 'Configuration updated successfully';
 
		return true;
	}
}
?>