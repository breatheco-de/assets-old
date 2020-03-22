<?php
	require_once('../../vendor/autoload.php');
	require_once('../../globals.php');
	
	use Psr\Http\Message\ServerRequestInterface as Request;
	use Psr\Http\Message\ResponseInterface as Response;
    use GuzzleHttp\Client;

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
    
	$api->get('/project/{project_slug}', function (Request $request, Response $response, array $args) use ($api) {
        
        if(empty($args['project_slug'])) throw new Exception('Invalid param value project_slug');

        $client = new Client();

        $resp = $client->request('GET','https://raw.githubusercontent.com/breatheco-de/exercise-'.$args['project_slug'].'/master/bc.json');
        if($resp->getStatusCode() != 200){
            $resp = $client->request('GET','https://projects.breatheco.de/json/?slug='.$args['project_slug']);
            if($resp->getStatusCode() != 200) throw new Exception('The project was not found', 404);
        } 

        $respText = $resp->getBody()->getContents();
        $project = json_decode($respText, true);
        if(empty($project["video-id"])) throw new Exception('The project has no video tutorial', 404);

        
        return $response->withJson([
            "video-id" => $project["video-id"],
            "timeline" => $project["timeline"]
        ]);
	});
    
	$api->get('/{tutorial_slug}', function (Request $request, Response $response, array $args) use ($api) {
        
        if(empty($args['tutorial_slug'])) throw new Exception('Invalid param tutorial_slug');
        $syllabus = $api->db['json']->getJsonByName($args['tutorial_slug']);
        
        return $response->withJson($syllabus);
	});
    
    
	$api->run();