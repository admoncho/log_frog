<?php 
session_start();
ob_start();
session_start();

// Create a global configuration
$GLOBALS['config'] = array(
	'mysql' => array(
		'host' 		=> '127.0.0.1',
		'username' 	=> 'logistic_qmonkey',
		'password' 	=> '.]QgV?h17~85',
		'db' 		=> 'logistic_quantum'
	),
	'remember' => array(
		'cookie_name'	=> 'hash',
		'cookie_expiry' =>  1209600
	),
	'session' => array(
		'session_name'	=> 'user',
		'token_name'	=> 'token'
	)
);

// Autoload classes
function autoload($class) {
	require_once $_SESSION['ProjectPath'] . '/resource/library/quantum/class/' . $class . '.php';
	// require_once $_SESSION['ProjectPath'] . '/classes/' . $class . '.php';
}
spl_autoload_register('autoload');

// Include functions
require_once $_SESSION['ProjectPath'] .'/functions/sanitize.php';

// Check for users that have requested to be remembered
if(Cookie::exists(Config::get('remember/cookie_name'))) {
	$hash = Cookie::get(Config::get('remember/cookie_name'));
	$hashCheck = DB::getInstance()->get('user_session', array('hash', '=', $hash));

	if($hashCheck->count()) {
		$user = new User($hashCheck->first()->user_id);
		$user->login();
	}

}

// gloVars
$SCRIPT_FILENAME = $_SERVER['SCRIPT_FILENAME'];
$domain = 'logisticsfrog.com';					// Declare domain
$rootFolder = 'logistic';						// Declare public_html parent folder
$cdn = 'http://y.devseven.com/ds/';				// Declare content deliver network
// date_default_timezone_set('America/New_York');	// Declare timezone

// Arrays

# user-i Group
$_QU_i_group = DB::getInstance()->query("SELECT * FROM _QU_i_group");
foreach ($_QU_i_group->results() as $_QU_i_group_data) {
	$_IA_internal_group_name[$_QU_i_group_data->group_id] = $_QU_i_group_data->name;
}

# user-e Group
$_QU_e_group = DB::getInstance()->query("SELECT * FROM _QU_e_group");
$_QU_e_group_count = $_QU_e_group->count();
foreach ($_QU_e_group->results() as $_QU_e_group_data) {
	$_IA_internal_group_name[$_QU_e_group_data->group_id] = $_QU_e_group_data->name;
}

# Quantum Skin
$skin = DB::getInstance()->query("SELECT * FROM _QC_skin");
foreach ($skin->results() as $skin_data) {
	$_IA_skin_class[$skin_data->skin_id] = $skin_data->class;
}
