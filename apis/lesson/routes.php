<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

function addAPIRoutes($api){

	$api->get('/all', function (Request $request, Response $response, array $args) use ($api) {
		$content = file_get_contents(WP_PLATFORM_HOST.'/wp-json/bc/v1/lesson');
		$obj = json_decode($content);
	    return $response->withJson($obj);
	});
	
	return $api;
}