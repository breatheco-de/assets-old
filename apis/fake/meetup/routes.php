<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

return function($api){
    
    $scope = '';

	$api->get($scope.'/events', function(Request $request, Response $response, array $args) use ($api) {
		return $response->withJson($api->db['json']->getJsonByName('events'));	
	});
	$api->get($scope.'/meetups', function(Request $request, Response $response, array $args) use ($api) {
		return $response->withJson($api->db['json']->getJsonByName('meetups'));	
	});
	$api->get($scope.'/session', function(Request $request, Response $response, array $args) use ($api) {
		return $response->withJson($api->db['json']->getJsonByName('session'));	
	});
	
	return $api;
};