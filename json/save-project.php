<?php 
	header("Content-type: application/json"); 
	header("Access-Control-Allow-Origin: *");

	$response = [];	
	try{
		$response['code'] = 200;	
		
		if(count($_POST)==0) throw new Exception('Sad, the project was not saved because the resquest URL is right but you did not specify any form parameters.');
		if(strpos($_SERVER["CONTENT_TYPE"],'form-data')==false) throw new Exception('Please set the content-type to multipart/form-data, right now is: '.$_SERVER["CONTENT_TYPE"]);
		if(!isset($_POST['id'])) throw new Exception('You need to specify the project id in the parameters of the request');
		
		$response['message'] = 'The project with the id '.$_POST['id'].' was successfully saved.';
	}
	catch(Exception $e) {
		$response['code'] = 400;	
		$response['message'] = $e->getMessage();
	}
	
	echo json_encode($response);
?>