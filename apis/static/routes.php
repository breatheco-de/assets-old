<?php

require('../../vendor_static/breathecode-api/BreatheCodeAPI.php');
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use \BreatheCode\BCWrapper;

BCWrapper::init(BREATHECODE_CLIENT_ID, BREATHECODE_CLIENT_SECRET, BREATHECODE_HOST, API_DEBUG);
BCWrapper::setToken(BREATHECODE_TOKEN);

function addAPIRoutes($api){
	
	$api->addTokenGenerationPath();
	//get all cohorts and its replits
	$api->get('/image/all', function (Request $request, Response $response, array $args) use ($api) {
		
		$images = $api->db['json']->getJsonByName('images');
		
		$tags = [];
		if(!empty($_GET['tags'])) $tags = explode(",", $_GET['tags']);
		
		$results = [];
		function isValidImage($img, $tags){
			if(!empty($tags)){
				foreach($tags as $tag) 
					if(!in_array($tag, explode(",", $img->tags))) 
						return false;
			}

			if(!empty($_GET['category'])){
				if($img->category != $_GET['category']) 
					return false;
			}
			
			return true;
		}

		if(!empty($_GET) && count($_GET) > 0){
			foreach($images as $img)
				if(isValidImage($img, $tags)) $results[] = $img;
		}
	    else $results = $images;
	    
	    return $response->withJson($results);
	});
	
	$api->get('/image/{uuid}', function (Request $request, Response $response, array $args) use ($api) {
		
		if(empty($args['uuid'])) throw new Exception('Missing param uuid',400);
		
		$images = $api->db['json']->getJsonByName('images');
		foreach($images as $img){
			if($img->uid == $args['uuid']) return $response->withJson($img);
		}
	    
	    throw new Exception('Image not found: '.$args['uuid'],404);
	});
	
	$api->delete('/image/{uuid}', function (Request $request, Response $response, array $args) use ($api) {
		
		if(empty($args['uuid'])) throw new Exception('Missing param uuid',400);
		
		$images = $api->db['json']->getJsonByName('images');
		$images = array_filter($images, function($img) use ($args){
			return ($img->uuid != $args['uuid']);
		});
		
		$api->db['json']->toFile('images')->save($images);
	    
	    return $response->withJson(["ok"=>"ok"]);
	});

	//update replits of a cohort
	$api->post('/image', function (Request $request, Response $response, array $args) use ($api) {

		$images = [];
		try{
			$images = $api->db['json']->getJsonByName('images');
		}
		catch(Exception $e){ $api->db['json']->toFile('images')->save($images); }
		if(!is_array($images)) $images = [];
			
		$parsedBody = $request->getParsedBody();
		if(empty($parsedBody)) throw new Exception('Missing image information',400);
		
		$newImg = [];
		$newImg['tags'] = $api->validate($parsedBody,'tags')->smallString();
		$newImg['description'] = $api->validate($parsedBody,'description')->smallString();
		$newImg['category'] = $api->validate($parsedBody,'category')->slug();
		$newImg['url'] = $api->optional($parsedBody,'url')->url();
		//$newImg['uid'] = https://ucarecdn.com/;
		preg_match('/https:\/\/ucarecdn\.com\/(.*)\/.*$/', $newImg['url'], $matches, PREG_OFFSET_CAPTURE);
		if(empty($matches) || empty($matches[1]) || empty($matches[1][0])) throw new Exception('There image URL does not follow the uploadcare standards (https://ucarecdn.com/<image_id>/): '.$newImg['url'],400);
		$newImg['uuid'] = $matches[1][0];
		
		$newImg['sizes'] = [];
		$newImg['type'] = 'image';
		
		foreach($images as $oldImg){
			$oldImg = (array) $oldImg;
			if($oldImg['uuid'] == $newImg['uuid']) throw new Exception('There is already an image with this uuid: '.$newImg['uuid'],400);
		}
		
		$images[] = $newImg;
		$api->db['json']->toFile('images')->save($images);
		
	    return $response->withJson($images);
	});//->add($api->auth());
	
	return $api;
}