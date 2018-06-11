<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require_once('../../globals.php');
function addAPIRoutes($api){
	
	$api->get('/all', function (Request $request, Response $response, array $args) use ($api) {
        
        $templatesPDO = new JsonPDO('data/','[]',false);
		$songs = $templatesPDO->getAllContent();
        return $response->withJson($songs);
	});
	
	$api->get('/songs', function (Request $request, Response $response, array $args) use ($api) {
        $templatesPDO = new JsonPDO('data/','[]',false);
		$songs = $templatesPDO->getJsonByName('songs');
        return $response->withJson($songs);
	});
	
	$api->get('/fx', function (Request $request, Response $response, array $args) use ($api) {
        
        $templatesPDO = new JsonPDO('data/','[]',false);
		$songs = $templatesPDO->getJsonByName('fx');
        return $response->withJson($songs);
	});
	
	return $api;
}