<?php 
session_start();
ob_start();

$user = 'beaeda1956a42a';
$password = '70d9d374';
$db = 'logistic_quantum';
$host = 'us-cdbr-azure-northcentral-b.cloudapp.net';
$port = 3306;

$link = mysqli_init();
$success = mysqli_real_connect(
   $link, 
   $host, 
   $user, 
   $password, 
   $db,
   $port
);

# Create a global configuration
$GLOBALS['config'] = array(
	'mysql' => array(
		'host' 		=> $host, 
		'username' 	=> $user, 
		'password' 	=> $password,
		'db' 		=> $db,
                'port'          => $port
	),
	'remember' => array(
		'cookie_name'	=> 'hash',
		'cookie_expiry' =>  1209600
	),
	'session' => array(
		'session_name'	=> 'user',
		'token_name'	=> 'token'
	),
	"paths" => array(
    "img" => array(
      "content" => $_SESSION['ProjectPath'] . "/img/content",
      "layout" => $_SESSION['ProjectPath'] . "/img/layout"
    ),
    "dashboard" => array(
      "dashboard" => $_SESSION['ProjectPath'] . "/dashboard"
    )
  )
);

# Autoload classes
function autoload($class) {
	
	require_once $_SESSION['ProjectPath'] . '/resource/library/quantum/class/' . $class . '.php';
}

spl_autoload_register('autoload');

# Check for users that have requested to be remembered
if(Cookie::exists(Config::get('remember/cookie_name'))) {
	$hash = Cookie::get(Config::get('remember/cookie_name'));
	$hashCheck = DB::getInstance()->query("SELECT * FROM user_session WHERE hash = '" . $hash . "'");

	if($hashCheck->count()) {
		$user = new User($hashCheck->first()->user_id);
		$user->login();
	}

}

# Create constants for heavily used paths (makes things a lot easier).
defined("LIBRARY_PATH") or define("LIBRARY_PATH", $_SESSION['ProjectPath'] . '/resource/library');
defined("TEMPLATE_PATH") or define("TEMPLATE_PATH", $_SESSION['ProjectPath'] . '/resource/template');
defined("COMPONENT_PATH") or define("COMPONENT_PATH", $_SESSION['ProjectPath'] . '/resource/template/back-end/component/');

# Set no session pages related to quantum templating scripts
# 1 use quantum design, 2 use template design
$quantum_no_session_templating = 1;

# Require environment config
require 'environment_config.php';

### THIS HAS TO DIE ###
// gloVars
$SCRIPT_FILENAME = $_SERVER['SCRIPT_FILENAME'];

$domain = 'logisticsfrog.com';        			    // Declare domain
$rootFolder = 'logistic';												// Declare public_html parent folder
$cdn = 'http://y.devseven.com/ds/';							// Declare content delivery network
### THIS HAS TO DIE ###
