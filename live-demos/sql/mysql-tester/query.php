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
try{

	die($_GET['table']);
	$db = new SQLDatabase(array(
		"debug" => $debug,
		"prefix" => rand(0,999999),
		"table-style" => $_GET['table']
		));

	$db->executeSQL(urldecode($_GET['sql']));

	$result = array(
		"code" => 200,
		"output" => $db->getHTML()
		);
	echo json_encode($result);
}
catch(Exception $e)
{
	$result = array(
		"code" => 400,
		"output" => $e->getMessage()
		);
	echo json_encode($result);	
}
