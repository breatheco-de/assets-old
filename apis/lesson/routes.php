<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

function addAPIRoutes($api){

	$api->get('/all', function (Request $request, Response $response, array $args) use ($api) {
        $data = $request->getParams();
        $limit = '';
        if(isset($data["status"])) $limit = '?status='.$data["status"];
		$content = file_get_contents(WP_PLATFORM_HOST.'/wp-json/bc/v1/lesson'.$limit);
		$obj = json_decode($content);
	    return $response->withJson($obj);
	});
	
	return $api;
}