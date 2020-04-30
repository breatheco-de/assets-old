<?php 
	header("Content-type: application/json"); 
	header("Access-Control-Allow-Origin: *");

	$response = [];	
	try{
		$response['code'] = 200;	
        
        if ($_SERVER['REQUEST_METHOD'] != 'POST') throw new Exception('Invalid request method, make sure you are doing a POST request, you seem to be doing a '.$_SERVER['REQUEST_METHOD']);
		if(strpos($_SERVER["CONTENT_TYPE"],'json')==false and $_SERVER["CONTENT_TYPE"]!='') throw new Exception('Please set the content-type to application/json, right now its "'.(empty($_SERVER["CONTENT_TYPE"]) ? "empty" : $_SERVER["CONTENT_TYPE"])."'");
		$entityBody = file_get_contents('php://input');
		if(!$entityBody) throw new Exception('The body\'s content is empty, please set the body with a JSON string');
		
		$content = json_decode($entityBody);
		if(!$content){
            $msg = json_last_error_msg();
            throw new Exception('The body\'s content is not a json or it has some misspells, make sure the format of the body is correct, details: '.$msg);
        } 
		if(!isset($content->title)) throw new Exception('The project body content is missing a title');
		
		$response['message'] = 'The project with the id '.$content->id.' was successfully saved.';
	}
	catch(Exception $e) {
		$response['code'] = 400;	
		$response['message'] = $e->getMessage();
	}
	
	echo json_encode($response);
?>