<?php 
	header("Content-type: application/json"); 
	header("Access-Control-Allow-Origin: *");

	$response = [];	
	try{
		$response['code'] = 200;	
		
		if(count($_POST)==0) throw new Exception('Sad, the project was not saved because the resquest URL is right but you did not specify any form parameters.');
		if(strpos($_SERVER["CONTENT_TYPE"],'json')==false) throw new Exception('Please set the content-type to application/json, right now is: '.$_SERVER["CONTENT_TYPE"]);
		$entityBody = file_get_contents('php://input');
		if(!$entityBody) throw new Exception('The body\'s content is empty, please set the body with a JSON string');
		
		$content = json_decode($entityBody);
		if(!$content) throw new Exception('The body\'s content is not a json or it has some mispels, make sure the format of the body is correct');
		
		$response['message'] = 'The project with the id '.$content->id.' was successfully saved.';
	}
	catch(Exception $e) {
		$response['code'] = 400;	
		$response['message'] = $e->getMessage();
	}
	
	echo json_encode($response);
?>