<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use \BreatheCode\BCWrapper;

BCWrapper::init(BREATHECODE_CLIENT_ID, BREATHECODE_CLIENT_SECRET, BREATHECODE_HOST, API_DEBUG);
BCWrapper::setToken(BREATHECODE_TOKEN);

return function($api){

    $categories = ['exercise','website','project','lesson','boilerplate'];
    $tags = ['infographic','cartoon','funny','screenshot','background','picture','drawing','explanation','css-exercises','html-exercises','react-exercises','javascript-exercises','python-exercises'];

	$api->addTokenGenerationPath();
	//get all cohorts and its replits
	$api->get('/image/all', function (Request $request, Response $response, array $args) use ($api) {

        if(!is_file("./data/images.json")){
            file_put_contents("./data/images.json", "{}");     // Save our content to the file.
        }
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

        foreach($images as $id => $img){
            if(isValidImage($img, $tags)) $results[] = $img;
        }

	    return $response->withJson($results);
	});

    $api->get('/image/categories', function (Request $request, Response $response, array $args) use ($api, $categories) {
        return $response->withJson($categories);
    });
    
    $api->get('/image/tags', function (Request $request, Response $response, array $args) use ($api, $categories, $tags) {
        return $response->withJson($tags);
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
        
        if(!is_file("./data/pending_delete.json")){
            file_put_contents("./data/pending_delete.json", "{}");     // Save our content to the file.
        }
            
        $images = (array) $api->db['json']->getJsonByName('images');
        if(empty($images[$args['uuid']])) throw new Exception("Nothing to delete found", 400);

		$pendingDelete = (array) $api->db['json']->getJsonByName('pending_delete');
		$pendingDelete[$args['uuid']] = (array) $images[$args['uuid']];
        $pendingDelete[$args['uuid']]["deleted"] = true;

        unset($images[$args['uuid']]);

		$api->db['json']->toFile('images')->save($images);
		$api->db['json']->toFile('pending_delete')->save($pendingDelete);
        
	    return $response->withJson(["ok"=>"ok"]);
    });

    $api->post('/image/upload/{uuid}', function (Request $request, Response $response, array $args) use ($api) {
        
        if(empty($args['uuid'])) throw new Exception('Missing param uuid',400);
        
        if(!is_file("./data/being_uploaded.json")){
            file_put_contents("./data/being_uploaded.json", "{}");     // Save our content to the file.
        }
            
        $pendingUpload = (array) $api->db['json']->getJsonByName('being_uploaded');
		$pendingUpload[$args['uuid']] = [
            "created_at" => date('Y-m-d'),
            "uuid" => $args['uuid']
        ];
		$api->db['json']->toFile('being_uploaded')->save($pendingUpload);
        
	    return $response->withJson(["ok"=>"ok"]);
    });
	//update replits of a cohort
	$api->post('/image', function (Request $request, Response $response, array $args) use ($api, $categories) {

        $images = [];
		try{
            if(!is_file("./data/being_uploaded.json")){
                file_put_contents("./data/being_uploaded.json", "{}");     // Save our content to the file.
            }
			$images = $api->db['json']->getJsonByName('images');
		}
		catch(Exception $e){ $api->db['json']->toFile('images')->save($images); }
		if(!is_array($images)) $images = [];

		$parsedBody = $request->getParsedBody();
		if(empty($parsedBody)) throw new Exception('Missing image information',400);

		$newImg = [];
        $newImg['tags'] = $api->validate($parsedBody,'tags')->smallString();
        $newImg['deleted'] = false;
		$newImg['description'] = $api->validate($parsedBody,'description')->smallString();

        $newImg['category'] = $api->validate($parsedBody,'category')->slug();
        if(!in_array($newImg['category'], $categories)) throw new Exception('Invalid category, it has to be one of '+implode(",",$categories),400);
        
        $newImg['url'] = $api->validate($parsedBody,'url')->url();
		preg_match('/https:\/\/ucarecdn\.com\/(.*)\/.*$/', $newImg['url'], $matches, PREG_OFFSET_CAPTURE);
		if(empty($matches) || empty($matches[1]) || empty($matches[1][0])) throw new Exception('There image URL does not follow the uploadcare standards (https://ucarecdn.com/<image_id>/): '.$newImg['url'],400);
		$newImg['uuid'] = $matches[1][0];

		$newImg['sizes'] = [];
		$newImg['type'] = 'image';

		$images[$newImg['uuid']] = $newImg;
        $api->db['json']->toFile('images')->save($images);

        // remove the image from the uplaod queue
        $pendingUpload = (array) $api->db['json']->getJsonByName('being_uploaded');
        unset($pendingUpload[$newImg['uuid']]);
        $api->db['json']->toFile('being_uploaded')->save($pendingUpload);

	    return $response->withJson($images);
	});//->add($api->auth());

	return $api;
};
