<?php 
	header("Content-type: application/json"); 
	header("Access-Control-Allow-Origin: *");

	$response = [];	
	try{
        $response['code'] = 200;	
        
        if ($_SERVER['REQUEST_METHOD'] != 'POST') throw new Exception('Invalid request method, make sure you are doing a POST request, you seem to be doing a '.$_SERVER['REQUEST_METHOD']);
		if(strpos($_SERVER["CONTENT_TYPE"],'application/json')==false) throw new Exception('Please set the content-type to application/json, right now is: '.$_SERVER["CONTENT_TYPE"]);
		
		$response['message'] = 'Excelent';
	}
	catch(Exception $e) {
		$response['code'] = 400;	
		$response['message'] = $e->getMessage();
	}
	
	echo json_encode($response);
?>