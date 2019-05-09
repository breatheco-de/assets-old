<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use JsonPDO\JsonPDO;

return function($api){

    $media = [];
    $types = ["article", "video", "infographic", "cheatcheet"];
    $types = ["article", "video", "infographic", "cheatcheet"];

	//get all cohorts and its replits
	$api->get('/type', function (Request $request, Response $response, array $args) use ($api, $types) {
	    return $response->withJson($types);
	});

	//get all cohorts and its replits
	$api->get('/all', function (Request $request, Response $response, array $args) use ($api) {

		$resources = $api->db['json']->getJsonByName('resources');
		if(!$resources) throw new Exception("Unable to find resources", 400);

	    return $response->withJson($resources);
	});

	return $api;
};