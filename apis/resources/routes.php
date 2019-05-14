<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use JsonPDO\JsonPDO;

return function($api){

    $media = [];
    $types = ["article", "video", "infographic", "cheatcheet"];

	//get all cohorts and its replits
	$api->get('/types', function (Request $request, Response $response, array $args) use ($api, $types) {
	    return $response->withJson($types);
	});

	//get all cohorts and its replits
	$api->get('/technologies', function (Request $request, Response $response, array $args) use ($api, $types) {

        $technologies = [];
        $resources = $api->db['json']->getJsonByName('resources');
        foreach($resources as $r){
            if(is_array($r->technologies)) $topics = array_merge($technologies, $r->technologies);
        }
	    return $response->withJson($technologies);
	});

	//get all cohorts and its replits
	$api->get('/topics', function (Request $request, Response $response, array $args) use ($api, $types) {

        $topics = [];
        $resources = $api->db['json']->getJsonByName('resources');
        foreach($resources as $r){
            if(is_array($r->topics)) $topics = array_merge($topics, $r->topics);
        }

	    return $response->withJson($topics);
	});

	//get all cohorts and its replits
	$api->get('/all', function (Request $request, Response $response, array $args) use ($api) {

		$resources = $api->db['json']->getJsonByName('resources');
		if(!$resources) throw new Exception("Unable to find resources", 400);

        $_result = [];
        $resources = $api->db['json']->getJsonByName('resources');
        foreach($resources as $r){

            $res = (array) $r;
            if(!isset($res["up_votes"])) $res["up_votes"] = 0;
            if(!isset($res["referrer"])) $res["up_votes"] = null;
            if(!isset($res["created_at"])) $res["created_at"] = null;
            if(!isset($res["technologies"])) $res["technologies"] = [];
            if(!isset($res["topics"])) $res["topics"] = [];

            $_result[] = $res;
        }

	    return $response->withJson($_result);
	});

	return $api;
};