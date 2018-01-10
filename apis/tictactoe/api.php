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
	
    if(isset($_GET['all']))
    {
        $fileContent = file_get_contents($fileName);
        if(!$fileContent) throwError('Imposible to read the database file');
        
        $jSON = json_decode($fileContent);
        throwSuccess($jSON);
    }
    else if(isset($_POST['u1']) &&  isset($_POST['u2']) && isset($_POST['winner'])){
        
        $fileContent = file_get_contents($fileName);
        if($fileContent === false) throwError('Imposible to read the database file');
        $jSON = json_decode($fileContent);
        if(empty($jSON)) $jSON = [];
        
        array_push($jSON,[
            "u1" => $_POST['u1'], "u2" => $_POST['u2'], "winner"=> $_POST['winner']
        ]);
        file_put_contents($fileName, json_encode($jSON));
        
        throwSuccess('ok');
    }
    else{
        throwError("No recognized API call please use one of the known methods.");
    }