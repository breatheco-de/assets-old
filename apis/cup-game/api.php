<?php

	header("Content-type: application/json"); 
	header("Access-Control-Allow-Origin: *");
	$fileName = 'data.json';
	
	function throwError($msg){
	    $result = [];
	    $result['message'] = $msg;
        $result['code'] = 500;
        echo json_encode($result);
        die();
	}
	function throwSuccess($data){
	    $result = [];
	    $result['data'] = $data;
        $result['code'] = 200;
        echo json_encode($result);
        die();
	}
	
	if(!isset($_GET['method'])) throwError("No recognized API call ".$_GET['method']." please use one of the known methods.");

    if($_GET['method'] == 'pending_attempts')
    {
        $fileContent = file_get_contents($fileName);
        if(!$fileContent) throwError('Imposible to read the database file');
        $jSON = json_decode($fileContent);
        throwSuccess($jSON);
    }
    else if($_GET['method'] == 'clean_attempts'){
        
        $jSON = [];
        $jSON["pending_attempts"] = [];
        file_put_contents($fileName, json_encode($jSON));
        
        throwSuccess('ok');
    }
    else if($_GET['method'] == 'add_attempt'){
        
        $incomingJSON = file_get_contents('php://input');
        $incomingAttempt = json_decode($incomingJSON);
        
        if(!isset($incomingAttempt->full_name)) throwError('Missing full_name');
        if(!isset($incomingAttempt->avatar_slug)) throwError('Missing avatar_slug');
        if(!isset($incomingAttempt->moves) || !is_array($incomingAttempt->moves)) throwError('Missing moves or is not an array');
        
        $fileContent = file_get_contents($fileName);
        if(!$fileContent) throwError('Imposible to read the database file');
        
        $jSON = json_decode($fileContent);
		array_push($jSON->pending_attempts, [
		    "full_name" => $incomingAttempt->full_name,
		    "avatar_slug" => $incomingAttempt->avatar_slug,
		    "moves" => $incomingAttempt->moves
		]);

        file_put_contents($fileName, json_encode($jSON));
        
        throwSuccess('ok');
    }
    else{
        throwError("No recognized API call '".$_GET['method']."' please use one of the known methods.");
    }