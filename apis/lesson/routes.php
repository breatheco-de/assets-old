<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use GuzzleHttp\Client;

return function($api){

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

		if(isset($data["status"])) $status = explode(",",$data["status"]);
            $client = new Client();
            // $r = $client->request('GET', 'https://content.breatheco.de/static/api/lessons.json');
            // $lessons = json_decode($r->getBody()->getContents());
            $content = file_get_contents('https://content.breatheco.de/static/api/lessons.json');
            $lessons = json_decode($content);

			if($status){
				$newLessons = [];
				foreach($lessons as $l) if(in_array($l->status,$status)) $newLessons[] = $l;
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
	    return $response->withRedirect($base.$args["slug"]."?plain=true".$token);
	});


	return $api;
};
