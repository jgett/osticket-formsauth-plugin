<?php
set_include_path(get_include_path().PATH_SEPARATOR.dirname(__file__).'/include');
return array(
    'id' => 'auth:formsauth', # notrans
    'version' => '0.1',
    'name' => 'Forms Auth',
    'author' => 'Jim Getty',
    'description' => 'Use ASP.NET FormsAuthentication to handle staff and user logins',
    'url' => 'https://github.com/jgett/osticket-formsauth-plugin',
    'plugin' => 'formsauth.php:FormsAuthPlugin'
);
?>