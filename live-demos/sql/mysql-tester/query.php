<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once('SQLDatabase.class.php');

header("Content-type:application/json");
try{

	$db = new SQLDatabase(array(
		"debug" => true,
		"prefix" => '3423423',
		"table-style" => 'hor-zebra'
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
