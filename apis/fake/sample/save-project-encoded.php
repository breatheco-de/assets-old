<?php 
	header("Content-type: application/json"); 
	header("Access-Control-Allow-Origin: *");

	$response = [];	
	try{
		$response['code'] = 200;	
        
        if ($_SERVER['REQUEST_METHOD'] != 'POST') throw new Exception('Invalid request method, make sure you are doing a POST request, you seem to be doing a '.$_SERVER['REQUEST_METHOD']);
		if(count($_POST)==0) throw new Exception('Sad, the project was not saved because the resquest URL is right but you did not specify any form parameters.');
		if($_SERVER["CONTENT_TYPE"]!='application/x-www-form-urlencoded') throw new Exception('Please set the content-type to form-urlencoded');
		if(!isset($_POST['id'])) throw new Exception('You need to specify the project id in the parameters of the request');
		
		$response['message'] = 'The project with the id '.$_POST['id'].' was successfully saved.';
	}
	catch(Exception $e) {
		$response['code'] = 400;	
		$response['message'] = $e->getMessage();
	}
	
	echo json_encode($response);
?>