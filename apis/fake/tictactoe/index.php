<?php
	require_once('../../vendor/autoload.php');
	require_once('../../globals.php');
	require_once('../JsonPDO.php');
	require_once('../SlimAPI.php');
	
	use Psr\Http\Message\ServerRequestInterface as Request;
	use Psr\Http\Message\ResponseInterface as Response;
	$api = new SlimAPI([
		'name' => 'Replit API - BreatheCode Platform',
		'debug' => API_DEBUG
	]);
	$api->addDB('json', new JsonPDO('data.json','[]',false));
	$api->addReadme('/','./README.md');

	$api->get('/game/all', function (Request $request, Response $response, array $args) use ($api) {
	    $content = $api->db['json']->getAllContent();
        return $response->withJson($content);
	});

	$api->put('/game/', function (Request $request, Response $response, array $args) use ($api) {
	    
	    $body = $request->getParsedBody();
        if(!isset($body['player1']) || !isset($body['player2']) || !isset($body['winner'])) 
            throw new Exception('Mising request body with parameters player1, player2 and winner');
        
        $content = $api->db['json']->getAllContent();
        if(is_array($content)) array_push($content,$body);
        else $content = array_merge([],[$body]);
        
        $api->db['json']->saveData($content);
        return $response->withJson($content);
	});

	$api->delete('/game/all', function (Request $request, Response $response, array $args) use ($api) {
	    
	    $api->db['json']->saveData([]);
        return [];
	});
	
	$api->run();