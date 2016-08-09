<?php
// Best framebuster, supported by all the recent browsers
// Disallow to embed page into iframe
header("X-Frame-Options: SAMEORIGIN");
// Block unverified type scripts (type="text/javascript", type="text/css")
header("X-Content-Type-Options: nosniff");


// change the following paths if necessary
$yii=dirname(__FILE__).'/../yii/framework/yii.php';
$config=dirname(__FILE__).'/../protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);

// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

// NOTE (Andrey M.): Overriding request method to enable REST support for Flash
$_SERVER['REQUEST_METHOD'] = (isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])) ? $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'] : $_SERVER['REQUEST_METHOD'];
// ELB offloads HTTPS decryption, so yii gets HTTP traffic while URLs are HTTPS
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO']=='https')
	$_SERVER['HTTPS'] = 'on';

// Guzzle client file
// to verify the integrity of its own archive
// By deleting this file, Guzzle rewrites /tmp/guzzle-cacert.pem
// and everything is returned to normal
$guzzleTmpFile = sys_get_temp_dir() . '/guzzle-cacert.pem';
//if (file_exists($guzzleTmpFile))
//    @unlink($guzzleTmpFile);

### Enable requests logging
/*if(!empty($_COOKIE)){
	$logging_dir = "assets/request/";
	
	foreach($_COOKIE AS $cookie_name => $cookie_value){
		if(preg_match('/^[a-f0-9]{32}$/', $cookie_name)){
		
			// Get user ID
			$user_data = substr($cookie_value, 40);
			$user_data_unserialized = @unserialize($user_data);
			$user_id = $user_data_unserialized[0];
			
			// Create logging dir if not created
			if(!is_dir($logging_dir)){
				mkdir($logging_dir);
				chmod($logging_dir, 0777);
			}
			
			// Put the request there
			$record = "--> ".time()." "."http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']." ".strtolower($_SERVER['REQUEST_METHOD'])." ".serialize($_POST)."\n";
			file_put_contents($logging_dir.$user_id.".log", $record, FILE_APPEND);
		}
	}
}*/
if(!ini_get('date.timezone')) {
	$timezone = "UTC";
	if(function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);
}


// used to catch fatal errors, which cannot be handled by regular error handler
register_shutdown_function(function(){
    $error = error_get_last();
    if ($error!==null)
    {
    	$msg = "SHUTDOWN PHP ERROR[{$error['type']}]:\n{$error['file']} : line[{$error['line']}]\n{$error['message']}\n";
    	// shutdown func does not provide backtrace!
    	/*
        ob_start();
        debug_print_backtrace();
        $trace = ob_get_contents();
        ob_end_clean(); 
        */
        error_log("$msg\n");
        if (YII_DEBUG) echo "$msg\n";
    }
});

//register update project status
register_shutdown_function(function(){
    Project::updateProjectStatus();
    ProjectJobReportCache::updateReport();
});

require(dirname(__FILE__) . '/../yii/framework/YiiBase.php');
class Yii extends YiiBase {
    /**
     * @static
     * @return CWebApplication
     */
    public static function app()
    {
        return parent::app();
    }
}


Yii::createWebApplication($config)->run();