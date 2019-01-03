<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

function addAPIRoutes($api){

	//old lessons from wordpress
	$api->get('/all', function (Request $request, Response $response, array $args) use ($api) {
        $data = $request->getParams();
        $limit = '';
        if(isset($data["status"])) $limit = '?status='.$data["status"];
		$content = file_get_contents(WP_PLATFORM_HOST.'/wp-json/bc/v1/lesson'.$limit);
		$obj = json_decode($content);
	    return $response->withJson($obj);
	});
	
	//new lessons from gatsby
	$api->get('/all/v2', function (Request $request, Response $response, array $args) use ($api) {
        $data = $request->getParams();
        $status = null;
        if(isset($data["status"])) $status = $data["status"];
		$content = file_get_contents('https://content.breatheco.de/static/api/lessons.json');
		$lessons = json_decode($content);
		
		if($status){
			$newLessons = [];
			foreach($lessons as $l) if($l->status == $status) $newLessons[] = $l;
			$lessons = $newLessons;
		}

    	return $response->withJson($lessons);
		
	});

	$api->get('/link/{slug}', function (Request $request, Response $response, array $args) use ($api) {

        if(!isset($args["slug"])) throw new Exception("Missing Slug", 400);
        
        $token = '';
        if(isset($args["access_token"])) $token = "&access_token=".$args["access_token"];
        
		//?plain=true&access_token=
		$base = 'https://content.breatheco.de/lesson/';
		$fileHeaders = @get_headers($base);
		
		$badResponses = ['HTTP/1.1 404 Not Found', 'HTTP/1.1 403 Forbidden'];
		if(!$fileHeaders || in_array($fileHeaders[0], $badResponses))
			$base = 'https://breatheco.de/en/lesson/';
			
	    return $response->withJson([
	    	"slug" => $args["slug"],
	    	"url" => $base.$args["slug"]."?plain=true".$token
	    ]);
	});

	$api->get('/redirect/{slug}', function (Request $request, Response $response, array $args) use ($api) {

        if(!isset($args["slug"])) throw new Exception("Missing Slug", 400);

        $token = '';
        $data = $request->getParams();
        if(isset($data["access_token"])) $token = "&access_token=".$data["access_token"];
        
		$base = 'https://content.breatheco.de/lesson/';
		$fileHeaders = @get_headers($base.rtrim($args["slug"], '/') . '/');

		$badResponses = ['HTTP/1.1 404 Not Found', 'HTTP/1.1 403 Forbidden'];
		if(!$fileHeaders || in_array($fileHeaders[0], $badResponses))
			$base = 'https://breatheco.de/en/lesson/';
		
	    return $response->withRedirect($base.$args["slug"]."?plain=true".$token);
	});
	

	$api->post('/migrate-lessons', function (Request $request, Response $response, array $args) use ($api) {

        // @TODO : Script to change a lesson slug (migrate tasks from old slug to new slug)
		
	    return $response->withRedirect($base.$args["slug"]."?plain=true".$token);
	});
	
    //update the list of lessons
	$api->post('/lessons', function (Request $request, Response $response, array $args) use ($api) {

		// @TODO: save the lessons on a local json file

		// $lessons = $request->getParsedBody();
		// if(!is_array($lessons)) throw new Exception("You must specify an array of lessons", 400);

		// $templatesPDO = new JsonPDO('_templates/','{}',false);
		// if($originalCohort) $api->db['json']->toFile($args['cohort_slug'])->save($lessons);
		
	 //   return $response->withRedirect($base.$args["slug"]."?plain=true".$token);
	});
	
	return $api;
}