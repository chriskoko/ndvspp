<?php
  session_start();
  $localhost_allowed=1;
  $memcache_stat = 0;

// ### CORS headers Allow from any origin
   if (isset($_SERVER['HTTP_ORIGIN'])) {
     $http_origin = $_SERVER['HTTP_ORIGIN'];
     //$ip = ($_SERVER['CF-Connecting-IP']) ? $_SERVER['CF-Connecting-IP'] : $_SERVER['REMOTE_ADDR'];
     $ip = ($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER['REMOTE_ADDR'];
   if (
     preg_match( "/localhost/i", $http_origin) ||
     preg_match( "/climatecrisis.zenergi.co.uk /i", $http_origin) ||
     preg_match( "/kokodev.co.uk/i", $http_origin) ||
     preg_match( "/kokodigital.co.uk/i", $http_origin) ||
     preg_match( "/kokogames.com/i", $http_origin) ||
     $ip == '109.153.31.192' || //chris IP
     preg_match( "/preview.construct.net/i", $http_origin)
    ){
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
		}
  }
 // ## Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
		exit(0);
}

date_default_timezone_set("Europe/London");
require '../config/newrelic.php'; // ## new relic set app name
require '../config/connstr.php'; //## database conn string
require '../config/memcache.php'; //## memcache stuff
require 'logging.php'; // ### slim custom logging class
require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim(
array(
    'mode' => 'development'
));



// ### API Wide Variables ############################################################
$app->APPCache = $memcache_stat ? 'memcache' : '0'; //if memcache server available use it
$app->APPMemcacheHash = 'climatecrisis'; //memcache hash make sure its unique to the project
$app->APPScoreboardFile = 'scores_weekly.json'; // json cache file for weekyly scores
$app->APPScoreboardDefaultLength = 100; // amount of records for normal scoreboard

// score/comp submisson restrictions below
$app->APPBannedIps = ''; // comma delimited with pipes(|1.1.1.1|,|8.8.8.8|) list of ips to not allow post of scores /comp entrees;
$app->APPBannedEmails = ''; // comma delimited with pipes(|joebloggs@jeans.com|,|mickymouse@disney.com|) list of Emails to not allow post of scores /comp entrees;
$app->APPMinScore = 0; // limit min score allowed ### MIN Score
$app->APPMaxScore = 100000; // limit max score allowed ##### MAX Score
$app->APPMinTimeSubmit  = 1; // add minium time in seconds allowed between score submissions - session based

// ##################################################################################

// ### Slim Settings ################################################################

// Only invoked if mode is "production"
$app->configureMode('production', function () use ($app) {
    $app->config(array(
        'log.enable' => false,
        'debug' => false,
    ));
	$app->contentType('application/json;charset=utf-8');
});

// Only invoked if mode is "development"
$app->configureMode('development', function () use ($app) {
    $app->config(array(
        'log.enable' => true,
        'debug' => true,
		'log.writer' => new FileLogWriter($app),
    ));
	$app->contentType('application/json;charset=utf-8');
});

$app->getLog()->info('### MemCache On? app->APPCache = '. $app->APPCache);


// ##################################################################################

// ### Global Functions / Auth Function ###################################
require 'globalfunctions.php';
require 'authfunction.php';
// ##################################################################################

// ### Test for quick test of parts of code #################################################################

$app->get('/', function () use ($app) {
	echo '{"response": '.koko_json_encode('hello world').'}';
});

$app->get('/testtoken', function () use ($app) {
	$data = $app->request(); // get data send with request
	$token = $data->get('token');
	$score = $data->get('score');
	$checktoken = md5(substr($token,8,15).$score.substr($token,3,7).$score.substr($token,5,3));
	echo $checktoken;
});

$app->get('/deletecache', function () use ($app ){
	$myFile = $app->APPScoreboardFile;
	unlink('json/'.$myFile);
	echo str_replace('.json',$app->APPMemcacheHash,$myFile);
	$memcache->delete(str_replace('.json',$app->APPMemcacheHash,$myFile));
	echo 'deleted cache';

});

// ##################################################################################

// ### Token/ Login Security ########################################################
include 'tokenlogin.php';
// ##################################################################################

// ### Scoreboards ##################################################################
include 'scoreboards.php';
// ##################################################################################

// ### Post Scores ##################################################################
include 'postscore.php';
// ##################################################################################

// ### Leagues ##################################################################
 include 'leagues.php';
// ##################################################################################

$app->run();
// Run Slim App
?>
