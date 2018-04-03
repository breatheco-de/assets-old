<?php
	require_once('../../vendor/autoload.php');
	require_once('../JsonPDO.php');
	require_once('../SlimAPI.php');
	
	use Psr\Http\Message\ServerRequestInterface as Request;
	use Psr\Http\Message\ResponseInterface as Response;
	$api = new SlimAPI();
	
	$api->addDB('json', new JsonPDO('data/','[]',false));
    
	$api->get('/full-stack', function (Request $request, Response $response, array $args) use ($api) {
        $quizObj = $api->db['json']->getJsonByName('full-stack-development');
        return $response->withJson($quizObj);
	});
    
	$api->get('/web-dev', function (Request $request, Response $response, array $args) use ($api) {
        $quizObj = $api->db['json']->getJsonByName('web-development');
        return $response->withJson($quizObj);
        return $response->withJson($quizObj);
	});
    
	$api->get('/web-intro', function (Request $request, Response $response, array $args) use ($api) {
        $quizObj = $api->db['json']->getJsonByName('web-introduction');
        return $response->withJson($quizObj);
	});
    
	$api->get('/blockchain', function (Request $request, Response $response, array $args) use ($api) {
        $quizObj = $api->db['json']->getJsonByName('blockchain');
        return $response->withJson($quizObj);
	});
	
	$api->run();