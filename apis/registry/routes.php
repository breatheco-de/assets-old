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
        
        $content = $api->db['json']->getJsonByName('_registry');
	    return $response->withJson($content);
    });

	$api->get('/seed', function (Request $request, Response $response, array $args) use ($api) {
        
        $content = $api->db['json']->getJsonByName('_seed');
	    return $response->withJson($content);
    });

	$api->get('/update', function (Request $request, Response $response, array $args) use ($api) {
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

                if(!file_exists("./data/".$p->slug.".json")) file_put_contents("./data/".$p->slug.".json", $body);
                else $api->db['json']->toFile($p->slug)->save($body);
            }
        }
        $api->db['json']->toFile('registry')->save($registry);
	    return $response->withJson($registry);
    });

	$api->get('/{slug}', function (Request $request, Response $response, array $args) use ($api) {

        if(empty($args['slug'])) throw new Exception('Invalid param value slug');

        $registry = $api->db['json']->getJsonByName('_registry');
        if(!empty($registry[$args['slug']])) return $response->withJson($registry[$args['slug']]);

        $content = $api->db['json']->getJsonByName($args['_slug']);

        return $response->withJson($content);
    });

    return $api;
};