<?php

require('../BreatheCodeLogger.php');
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use \BreatheCode\BCWrapper as BC;
use GuzzleHttp\Client;
use PHPHtmlParser\Dom;
use \JsonPDO\JsonPDO;

BC::init(BREATHECODE_CLIENT_ID, BREATHECODE_CLIENT_SECRET, BREATHECODE_HOST, API_DEBUG);
BC::setToken(BREATHECODE_TOKEN);

return function($api){

	$api->addTokenGenerationPath();
	//get all cohorts and its replits
	$api->get('/student/{student_id}/contributions', function (Request $request, Response $response, array $args) use ($api) {

        $student = BC::getStudent(['student_id' => urlencode($args["student_id"])]);
        if(!$student) throw new Exception('The student was not found in BreatheCode', 404);
        if(empty($student->github)) throw new Exception('Student Github username is uknown' , 400);

		preg_match("/^(?:http(?:s)?:\/\/(?:www\.)?github.com\/)?([a-zA-Z0-9]*)\/?\??(?:\s*)*$/", $student->github, $matches);
		if(empty($matches[1])) throw new Exception('Imposible to determin the github username from '.$student->github , 400);

		$resp = @file_get_contents('https://github.com/users/'.$matches[1].'/contributions');

		if($resp){
			$styles = 'style="fill:#aaa;text-align:center;font:10px Helvetica, arial, freesans, clean, sans-serif;white-space:nowrap;"';
			$dom = new Dom;
			$dom->load($resp);
			$svgNode = $dom->find('.js-calendar-graph-svg');
			$svg = '<?xml version="1.1" standalone="no"?>';
			$svg .= '<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">';
			$svg .= '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" '.$styles.' width="563" height="88">';
			$svg .= $svgNode[0]->innerHTML;
			$svg .= '</svg>';

	        return $response->withHeader('Content-Type','image/svg+xml')->write($svg);
		}
		else throw new Exception('Image could not be generated', 400);
	});

	$api->get('/issues/sync', function (Request $request, Response $response, array $args) use ($api) {

        $projects = [
        	'projects', 'assets', 'content', 'react-components', 'breathecode-cli'
        ];
		$orm = new JsonPDO('./issues/');
        $client = new Client();
        function removePullRequests($issues){
            $result = [];
            foreach($issues as $issue)
                if(!isset($issue["pull_request"])) $result[] = $issue;
            return $result;
        }
        foreach($projects as $p){
			$resp = $client->request('GET', "https://api.github.com/repos/breatheco-de/$p/issues", [
			    'Headers' => ["Authorization" => "63a8bc87eac02fa19ac128f47b710aba73b7e10d"]
			]);
			$respText = $resp->getBody()->getContents();
			$issues = removePullRequests(json_decode($respText, true));

			try{
				$original = $orm->getJsonByName($p.'.json');
				$orm->toFile($p)->save($issues);
			}
			catch(Exception $e){
				$orm->toNewFile($p)->save($issues);
			}
        }

        return $response->withJson('ok');
	});

	$api->get('/issues/all', function (Request $request, Response $response, array $args) use ($api) {

		$orm = new JsonPDO('./issues/');
		$projects = $orm->getAllContent();

		$result = [];
		foreach($projects as $name => $issues){
			preg_match_all('/.\/issues\/(.*).json/m', $name, $matches, PREG_SET_ORDER, 0);
			if(isset($matches[0][1])) $result[$matches[0][1]] = $issues;
		}

        return $response->withJson($result);
	});

	$api->get('/project/{project_name}/issues', function (Request $request, Response $response, array $args) use ($api) {

		$orm = new JsonPDO('./issues/');
		$project = $orm->getJsonByName($args['project_name']);
        return $response->withJson($project);
	});

	//create user activity

	return $api;
};