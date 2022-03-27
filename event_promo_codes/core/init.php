<?php ob_start();
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
error_reporting( E_ALL ^ E_DEPRECATED );

//------------------//
// CONFIGURE HTTPS //

if($_SERVER['HTTP_HOST'] != 'localhost' && $_SERVER['HTTP_HOST'] != '127.0.0.1'){
    $http = 'https'; 
    if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off"){
        // $redirect = $http.'://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        // header('HTTP/1.1 301 Moved Permanently');
        // header('Location: ' . $redirect);
        // exit();
    }
}else{
    $http = 'http';
}


function def(){
	define("CT","Controller");
	define("_","/");
	define("view_session_off_","views/app_session_off/");
	define("view_session_off","views/app_session_off");
	define("P",".php");
	define("PL",".php");
	define("SUCCESS",200);
	define("BAD_REQUEST",400);
	define("UNAUTHORIZED",401);
	define("NOT_FOUND",404);
	define("FORBIDDEN",403);
	define("BAD_REQUEST_METHOD",405);
	define("NOT_REGISTERED",406);
	define("FAILLURE",500);
	define("DLYTXNCOUNTERSIZE",5);
	define("PROMOMINUMAMOUNT",600);
	define("CURRENCY","UGX");
	define("LOANLOG",Config::get('logs/promo'));
}
// Initialize Global Date Class >> Timezone
require 'classes/Dates.php';

// Initialize Global Functions
require_once 'functions/global.php';


$GLOBALS['config'] = array(
	'mysql' => array(
        
		// DB Local
		'host' => 'localhost',
		'username' => 'root',
		'password' => '',
		'db' => 'safeboda_test_db',
		'port' => '3306'

		
        
	),
	
	'session' => array(
		'event' => 'event_session',
		
	),
	
	
	
	
	'time' => array(
		'date_time' => Dates::get('D, Y-m-d h:i:s a'),
		'timestamp' => $time,
		'seconds' => $time,
		'browser_token_expiry' => 60*60*12,
	),
	
	'logs'=>array(
		'promo'=>$_SERVER['DOCUMENT_ROOT'].'/safeboda_test/logs/promo.log',
	)
);


// Load Classes
spl_autoload_register (function ($class) {
	
	$pathArray = explode("\\", $class);
	if(count($pathArray)>1){
		require_once $class . '.php';
	}else{
		require_once 'classes/'.$class . '.php';
	}

});

//Initialize Define
def();

$db = DB::getInstance();

$init = (object)[
		'db_status'=>$db->connected(),
		'app_token'=>microtime(true)
	];
	
$appData = new AppData();
$HASH	 = new Hash();
$appData->setDBStatus($db->connected());





?>