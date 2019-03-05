<?php
	require_once('../../../vendor/autoload.php');
	require_once('../../../globals.php');
	require_once('../../JsonPDO.php');
	require_once('../../SlimAPI.php');
	
	use Psr\Http\Message\ServerRequestInterface as Request;
	use Psr\Http\Message\ResponseInterface as Response;
	$api = new SlimAPI([
		'name' => 'Fake Todo\'s - BreatheCode Platform',
		'debug' => true
	]);
	$api->addDB('json', new JsonPDO('./data','[]',false));
	$api->addReadme('/','./README.md');
	
	function validateTasks($tasks){
        foreach($tasks as $task) 
        	if(empty($task['label']) or !isset($task['done'])){
        		throw new Exception('The request body must be an array of todo\'s with at least one task, also make sure every task has a "label" and done "property", read the API documentation for more details');
        	}
        	else if(!is_bool($task['done']))
        		throw new Exception('Each task "done" property must be a boolean with value either true or false, make sure it\'s not a string either');
	}

	$api->get('/user/{username}', function (Request $request, Response $response, array $args) use ($api) {
		
		if(!isset($args['username'])) throw new Exception('The username is missing on the URL');
			$todos = $api->db['json']->getJsonByName($args['username']);

		if($todos) return $response->withJson($todos);
		else throw new Exception('This use does not exists, first call the POST method first to create the list for this username',404);
	});

	$api->post('/user/{username}', function (Request $request, Response $response, array $args) use ($api) {
	    
        if(!isset($args['username'])) throw new Exception('The username is missing on the URL');
        if($args['username']) throw new Exception('The example user is read-only, please pick another username', 400);
        
	    $body = $request->getParsedBody();
        if(!is_array($body)) throw new Exception('The request body must be an empty array');
        
		try{
			$oldTodos = $api->db['json']->getJsonByName($args['username']);
		}
		catch(Exception $e){}

		if(!empty($oldTodos)) throw new Exception('This user already has a list of todos, use PUT instead to update it',400);
		else $api->db['json']->toNewFile($args['username'])->save($body);

        return $response->withJson(["result" => "ok"]);
	});
	
	$api->put('/user/{username}', function (Request $request, Response $response, array $args) use ($api) {
	    
        if(!isset($args['username'])) throw new Exception('The username is missing on the URL', 400);
        if($args['username']) throw new Exception('The example user is read-only, please pick another username', 400);
        
	    $body = $request->getParsedBody();
        if(!is_array($body)) throw new Exception('The request body must be an array of todo\'s', 400);
        
        validateTasks($body);
        
		try{
			$oldTodos = $api->db['json']->getJsonByName($args['username']);
		}
		catch(Exception $e){}

		if($oldTodos) $api->db['json']->toFile($args['username'])->save($body);
		else throw new Exception('This use does not exists, first call the POST method first to create the todo list for this username',404);

        return $response->withJson(["result" => "ok"]);
	});
	
	$api->delete('/user/{username}', function (Request $request, Response $response, array $args) use ($api) {
	    
	    if($args['username']) throw new Exception('The example user is read-only, please pick another username', 400);
	    $result = $api->db['json']->deleteFile($args['username']);
        return $response->withJson(["result" => "ok"]);
	});
	
	$api->run();