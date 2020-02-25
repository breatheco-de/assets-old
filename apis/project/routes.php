<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use \BreatheCode\BCWrapper;
use \JsonPDO\JsonPDO;
use GuzzleHttp\Client;

BCWrapper::init(BREATHECODE_CLIENT_ID, BREATHECODE_CLIENT_SECRET, BREATHECODE_HOST, API_DEBUG);
BCWrapper::setToken(BREATHECODE_TOKEN);

return function($api){
    
    $api->get('/all', function (Request $request, Response $response, array $args) use ($api) {

        $content = $api->db['json']->getJsonByName('registry');

        $client = new Client();
        $resp = $client->request('GET','https://projects.breatheco.de/json/');
        if($resp->getStatusCode() != 200) throw new Exception('The project list was not found', 404);

        $body = $response->getBody();
		$body->write($resp->getBody()->getContents());
	    return $response->withHeader('Content-type', 'application/json');
	});

	$api->get('/registry/all', function (Request $request, Response $response, array $args) use ($api) {
        
        $content = $api->db['json']->getJsonByName('registry');
	    return $response->withJson($content);
    });

	$api->get('/registry/seed', function (Request $request, Response $response, array $args) use ($api) {
        
        $content = $api->db['json']->getJsonByName('seed');
	    return $response->withJson($content);
    });

	$api->get('/registry/update', function (Request $request, Response $response, array $args) use ($api) {
        $client = new Client();
        $seeds = $api->db['json']->getJsonByName('seed');
        $registry = $api->db['json']->getJsonByName('registry');
        forEach($seeds as $p){
            if(!empty($registry[$p->slug]) and !empty($registry[$p->slug]->updated_at)){
                $lastUpdate = $registry[$p->slug]->updated_at;
                $diff = (strtotime("now") - $lastUpdate);
                // more than one day
                if($diff < 86400) continue; 
            }
            $url = str_replace("https://github.com/", 'https://raw.githubusercontent.com/', $p->repository).'/master/bc.json';
            $resp = $client->request('GET',$url);
            if($resp->getStatusCode() == 200){
                $body = $resp->getBody()->getContents();
                $json = json_decode($body);
                $newProject = array_merge((array) $json, (array) $p);
                $newProject["updated_at"] = strtotime("now");
                $newProject["readme"] = str_replace("https://github.com/", 'https://raw.githubusercontent.com/', $p->repository).'/master/README.md';
                if(!isset($newProject["likes"])) $newProject["likes"] = 0;
                if(!isset($newProject["downloads"])) $newProject["downloads"] = 0;
                $registry[$newProject["slug"]] = $newProject;
            }
        }
        $api->db['json']->toFile('registry')->save($registry);
	    return $response->withJson($registry);
    });

	$api->get('/{project_slug}', function (Request $request, Response $response, array $args) use ($api) {

        if(empty($args['project_slug'])) throw new Exception('Invalid param value project_slug');

        $registry = $api->db['json']->getJsonByName('registry');
        if(!empty($registry[$args['project_slug']])) return $response->withJson($registry[$args['project_slug']]);

        $client = new Client();
        $resp = $client->request('GET','https://projects.breatheco.de/json/?slug='.$args['project_slug']);
        if($resp->getStatusCode() != 200) throw new Exception('The project was not found', 404);

        $body = $response->getBody();
		$body->write($resp->getBody()->getContents());
	    return $response->withHeader('Content-type', 'application/json');
    });

    return $api;
};