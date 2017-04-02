<?php
$debug = false;
if(isset($_GET['debug']) && $_GET['debug']==true) 
{
	$debug = true;
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
}

require_once('SQLDatabase.class.php');

header("Content-type:application/json");
$result = array();
try{
	$result["tablestyles"] = $_GET['tablestyles'];
	$result["debug"] = $debug;
	$result["prefix"] = rand(0,999999);
	$result["db"] = $_GET['db'];

	$db = new SQLDatabase(array(
		"debug" => $result["debug"],
		"prefix" => $result["prefix"],
		"db" => $result["db"],
		"table-style" => $result["tablestyles"]
		));
	$db->executeSQL(urldecode($_GET['sql']));

	$result["logs"] = $db->getLogs();
	$result["code"] = 200;
	$result["output"] = $db->getHTML();
	echo json_encode($result);
}
catch(Exception $e)
{
	$result["code"] = 400;
	$result["output"] = $e->getMessage();
	echo json_encode($result);	
}
