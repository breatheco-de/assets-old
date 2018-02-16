<?php
    header("Content-type: application/json"); 
    header("Access-Control-Allow-Origin: *");
    require_once('../APIGenerator.php');
    
	$api = new APIGenerator('data.json','[]');

	$api->get('games', 'Get all games', function($request, $dataContent){
        return $dataContent;
	});

	$api->put('game', 'Create new game', function($request, $dataContent) use ($api){
	    
        if(!isset($request['parameters']['player1']) || !isset($request['parameters']['player2']) || !isset($request['parameters']['winner'])) throw new Exception('Mising request body with parameters player1, player2 and winner');
        
        if(is_array($dataContent)) array_push($dataContent,$request['parameters']);
        else $dataContent = array_merge([],[$request['parameters']]);
        
        $api->saveData($dataContent);
        return $dataContent;
	});
	
	$api->post('clean', 'Clean games log',function($request, $dataContent) use ($api){
	    
	    $api->saveData([]);
        return [];
	});
	
	$api->run();