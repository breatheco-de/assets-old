<?php
	require_once('../../vendor/autoload.php');
	require_once('../../globals.php');
	
	use Psr\Http\Message\ServerRequestInterface as Request;
	use Psr\Http\Message\ResponseInterface as Response;
	$api = new \SlimAPI\SlimAPI([
		'name' => 'Video Tutorials API - BreatheCode Platform',
		'debug' => API_DEBUG
	]);
	
	$api->addDB('json', new \JsonPDO\JsonPDO('data/','[]',false));
	$api->addReadme('/','./README.md');
	
	$api->get('/all', function (Request $request, Response $response, array $args) use ($api) {
        
        $content = $api->db['json']->getAllContent();
        
        return $response->withJson($content);
	});
    
	$api->get('/{tutorial_slug}', function (Request $request, Response $response, array $args) use ($api) {
        
        if(empty($args['tutorial_slug'])) throw new Exception('Invalid param tutorial_slug');
        $syllabus = $api->db['json']->getJsonByName($args['tutorial_slug']);
        
        return $response->withJson($syllabus);
	});
    
    
	$api->run();