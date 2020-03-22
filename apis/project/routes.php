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

        $projects = [];
        $slugs = [];
        $registry = $api->db['json']->getJsonByName('_registry');
        forEach($registry as $slug => $project){
            $projects[] = $project;
            $slugs[] = $slug;
        }
        
        $client = new Client();
        $resp = $client->request('GET','https://projects.breatheco.de/json/');
        if($resp->getStatusCode() != 200) throw new Exception('The project list was not found', 404);
        $oldProjectsBody = $resp->getBody()->getContents();
        $oldProjects = json_decode($oldProjectsBody);
        forEach($oldProjects as $old){
            if(!in_array($old->slug, $slugs)) $projects[] = $old;
        }
	    return $response->withJson($projects);
	});

	$api->get('/registry/all', function (Request $request, Response $response, array $args) use ($api) {
        
        $content = $api->db['json']->getJsonByName('_registry');
	    return $response->withJson($content);
    });

    $api->put('/registry/{project_slug}', function (Request $request, Response $response, array $args) use ($api) {
        
        if(empty($args['project_slug'])) throw new Exception('Invalid param value project_slug');

        $user = DB::getMe();
        print_r($user);die();

        $content = $api->db['json']->getJsonByName('_registry');
        return $response->withJson($content);
        
    })->add($api->auth());

	$api->get('/registry/seed', function (Request $request, Response $response, array $args) use ($api) {
        
        $content = $api->db['json']->getJsonByName('_seed');
	    return $response->withJson($content);
    });

	$api->get('/registry/update', function (Request $request, Response $response, array $args) use ($api) {

        if(!file_exists("./data/_registry.json")) file_put_contents("./data/_registry.json", "{}");

        $force = (empty($_GET['force'])) ? false : $_GET['force'] === "true";
        $client = new Client();
        $seeds = $api->db['json']->getJsonByName('_seed');
        $registry = $api->db['json']->getJsonByName('_registry');
        forEach($seeds as $p){
            if(!empty($registry[$p->slug]) and !empty($registry[$p->slug]->updated_at)){
                $lastUpdate = $registry[$p->slug]->updated_at;
                $diff = (strtotime("now") - $lastUpdate);
                // more than one day
                if(!$force && $diff < 86400) continue; 
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
        $api->db['json']->toFile('_registry')->save($registry);
	    return $response->withJson($registry);
    });

	$api->get('/{project_slug}', function (Request $request, Response $response, array $args) use ($api) {

        if(empty($args['project_slug'])) throw new Exception('Invalid param value project_slug');

        $registry = $api->db['json']->getJsonByName('_registry');
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