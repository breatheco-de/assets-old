<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use \BreatheCode\BCWrapper;
use \JsonPDO\JsonPDO;
use GuzzleHttp\Client;

BCWrapper::init(BREATHECODE_CLIENT_ID, BREATHECODE_CLIENT_SECRET, BREATHECODE_HOST, API_DEBUG);
BCWrapper::setToken(BREATHECODE_TOKEN);

return function($api){

    $getRecordErrors = function($r){
        $difficulty = ['beginner', 'easy', 'medium', 'hard'];
        $language = ['html','css', 'react', 'javascript', 'node', "java", "python3"];

        if(!isset($r["difficulty"])) $log[] = "Missing difficulty";
        else if(!in_array($r["difficulty"], $difficulty)) $log[] = "Invalid difficulty";

        if(!isset($r["duration"])) $log[] = "Missing duration";
        else if(!is_numeric($r["duration"])) $log[] = "Invalid duration: ".$r["duration"];

        if(!isset($r["graded"])) $log[] = "Missing graded (expected bool)";
        else if(!is_bool($r["graded"])) $log[] = "Invalid graded (expected bool): ".$r["graded"];

        if(!isset($r["video-solutions"])) $log[] = "Missing video-solutions (expected bool)";
        else if(!is_bool($r["video-solutions"])) $log[] = "Invalid video-solutions (expected bool): ".$r["video-solutions"];
        
        if(!isset($r["language"])) $log[] = "Missing language";
        else if(!in_array($r["language"], $language)) $log[] = "Invalid language: ".$r["language"];

        if(count($log) == 0) return false;
        else return $log;
    };

	$api->get('/all', function (Request $request, Response $response, array $args) use ($api) {
        
        $content = $api->db['json']->getJsonByName('_registry');
	    return $response->withJson($content);
    });

	$api->get('/seed', function (Request $request, Response $response, array $args) use ($api) {
        
        $content = $api->db['json']->getJsonByName('_seed');
	    return $response->withJson($content);
    });

	$api->get('/update', function (Request $request, Response $response, array $args) use ($api, $getRecordErrors) {
        
        if(!file_exists("./data/_registry.json")) file_put_contents("./data/_registry.json", "{}");

        $force = (empty($_GET['force'])) ? false : $_GET['force'] === "true";
        $client = new Client();
        $seeds = $api->db['json']->getJsonByName('_seed');
        $registry = $api->db['json']->getJsonByName('_registry');
        $log = [];
        forEach($seeds as $p){
            if(!isset($p->repository) or empty($p->repository)){
                $log[] = "Missing repository url for $p->slug";
                continue;
            }

            if(!empty($registry[$p->slug]) and !empty($registry[$p->slug]->updated_at)){
                $lastUpdate = $registry[$p->slug]->updated_at;
                $diff = (strtotime("now") - $lastUpdate);
                // more than one day
                if(!$force && $diff < 86400){
                    $log[] = "Record ignored, already on the registry ".$p->slug;
                    continue; 
                } 
            }
            $url = str_replace("https://github.com/", 'https://raw.githubusercontent.com/', $p->repository).'/master/bc.json';
            $log[] = "Trying to fetch $p->slug with $p->repository";

            try{

                $resp = $client->request('GET',$url);
                if($resp->getStatusCode() == 200){
                    $body = $resp->getBody()->getContents();
                    $json = json_decode($body);
                    $newProject = array_merge((array) $json, (array) $p);
                    $newProject["updated_at"] = strtotime("now");
                    $newProject["readme"] = str_replace("https://github.com/", 'https://raw.githubusercontent.com/', $p->repository).'/master/README.md';
                    if(!isset($newProject["likes"])) $newProject["likes"] = 0;
                    if(!isset($newProject["downloads"])) $newProject["downloads"] = 0;
                    
                    if(!isset($newProject["technology"])) $newProject["technology"] = 0;
                    $registry[$newProject["slug"]] = $newProject;
                    
                    //validate for errors
                    $errors = $getRecordErrors($newProject);
                    if($errors){
                        $log[] = "Record ".$p->slug." ignored because of following errors: ".implode($errors, ", ");
                        continue;
                    } 
                    
                    if(!file_exists("./data/".$p->slug.".json")){
                        $log[] = "The record added successfully: ".$p->slug;
                        file_put_contents("./data/".$p->slug.".json", $body);
                    } 
                    else{
                        $log[] = "The record replaced successfully: ".$p->slug;
                        $api->db['json']->toFile($p->slug)->save($json);
                    } 
                    
                }
            }
            catch(Exception $e){
                $log[] = "Error fetching $p->slug for url: $url";
            }
        }
        $api->db['json']->toFile('registry')->save($registry);
	    return $response->withJson($log);
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