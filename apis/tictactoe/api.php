<?php
    header("Content-type: application/json"); 
    header("Access-Control-Allow-Origin: *");
    require_once('../APIGenerator.php');
    
	$api = new APIGenerator('data.json','[]');

	$api->method('games', function($dataContent){
        return $dataContent;
	});

	$api->method('game', function($dataContent) use ($api){
        if(!isset($_POST['player1']) || !isset($_POST['player2']) || !isset($_POST['winner'])) throw new Exception('Mising POST parameters');
        
        if(empty($dataContent)) $dataContent = [];
        
        array_push($dataContent,[
            "player1" => $_POST['player1'], 
            "player2" => $_POST['player2'], 
            "winner"=> $_POST['winner']
        ]);
        
        $api->saveData($dataContent);
        return $dataContent;
	});
	
	$api->method('clean', function($dataContent) use ($api){
	    
	    $api->saveData([]);
        return [];
	});
	
	$api->run();