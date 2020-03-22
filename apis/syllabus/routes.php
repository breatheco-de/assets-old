<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

return function($api){

	$getSlug = function($slug){
	    $allSlugs = [
	        "full-stack" => "full-stack",
	        "full-stack-ft" => "full-stack-ft",
	        "front-end-development" => "front-end-development",
	        "web-development" => "web-development",
	        "coding-introduction" => "coding-introduction",
	        "blockchain" => "blockchain",
	        "boats" => "boats"
	    ];
	    if($allSlugs[$slug]) return $allSlugs[$slug];
	    else throw new Exception('Syllabus slug not found');
	};

	$api->addTokenGenerationPath();

	$api->get('/all', function (Request $request, Response $response, array $args) use ($api, $getSlug) {

        $syllabus = $api->db['json']->getAllContent();
		$data = [];
		foreach($syllabus as $key => $temp){
			$totalDays = 0;
			$totalWeeks = 0;
            $tech = [];
            $days = [];
            if(isset($temp["weeks"])){
                foreach($temp["weeks"] as $w)
                    $days = array_merge($days,$w->days);
                $totalWeeks = count($temp["weeks"]);
            }
            
            $totalDays += count($days);
            foreach($days as $d){
                if(isset($d->technologies) and is_array($d->technologies)) $tech = array_merge($tech,$d->technologies);
            }

			$data[] = [
				"slug" => preg_replace('/\\.[^.\\s]{3,4}$/', '', basename($key)),
				"title" => $temp["label"],
				"weeks" => $totalWeeks,
				"days" => $totalDays,
				"technologies" => $tech
			];
		}
        return $response->withJson($data);
	});

	$api->get('/{slug}/day/{day_number}/instructions', function (Request $request, Response $response, array $args) use ($api, $getSlug) {

        $version = '1';
        if(!empty($_GET['v'])) $version = $_GET['v'];
        if(is_numeric($version)) $version = "v".$version;

		$instructionsURL = __DIR__."/data/".$args['slug']."/$version/day".($args['day_number']).".md";
		if(file_exists($instructionsURL))
			$response->withHeader('Content-Type', 'text/plain')->write(file_get_contents($instructionsURL));
        else throw new Exception('Instructions not found for: '.$args['slug'].", day ".$args['day_number']." version $version",404);

	}); 

	$api->get('/{slug}', function (Request $request, Response $response, array $args) use ($api, $getSlug) {

        $version = '1';
        if(!empty($_GET['v'])) $version = $_GET['v'];
        if(is_numeric($version)) $version = "v".$version;

        $syllabus = $api->db['json']->getJsonByName($getSlug($args['slug']).'.'.$version);

		$teacher = $request->getQueryParam('teacher',null);
        if(isset($teacher)){
            $number = 0;
            $days = [];
            if(isset($temp["weeks"])) foreach($temp["weeks"] as $w) $days = array_merge($days,$w->days);

            $days = array_map(function($day) use ($syllabus, &$number){
                if(!isset($day->weekend) || !$day->weekend){
                    $number++;
                    $day->number = $number;
                    $instructionsURL = __DIR__."/data/".$syllabus['profile']."/$version/day".($number).".md";
                    if(file_exists($instructionsURL))
                        $day->instructions_link = ASSETS_HOST."/apis/syllabus/".$syllabus['profile']."/day/".$number."/instructions?v=$version";
                }
                return $day;
            }, $days, array_keys($days));

        	return $response->withJson($syllabus);
        }
        else{
            $number = 0;
            $days = [];
            if(isset($temp["weeks"])) foreach($temp["weeks"] as $w) $days = array_merge($days,$w->days);

            $days = array_map(function($day) use ($syllabus, &$number){
                if(!isset($day->weekend) || !$day->weekend){
                    $number++;
                    $day->number = $number;
                }
                $instructionsURL = __DIR__."/data/".$syllabus['profile']."/$version/day".($number).".md";
                if(file_exists($instructionsURL))
                    $day->instructions_link = ASSETS_HOST."/apis/syllabus/".$syllabus['profile']."/day/".$number."/instructions?v=$version";
                if(isset($day->description)) return $day;
                else{
                    return null;
                }
            }, $days);
            
	        return $response->withJson($syllabus);
        }

	});

	$api->post('/{slug}', function (Request $request, Response $response, array $args) use ($api, $getSlug) {

        $syllabusSlug = $getSlug($args['slug']);
        $syllabus = $api->db['json']->getJsonByName($syllabusSlug);

		$data = $request->getParsedBody();
		if(!is_array($data)) throw new Exception('The body must be a syllabus object', 400);

		if(empty($data['label'])) throw new Exception('The syllabus must have a label', 400);
		if(empty($data['profile'])) throw new Exception('The syllabus must have a profile', 400);
		if(!is_array($data['weeks']) and !is_array($data['days'])) throw new Exception('The syllabus must an array of weeks or an array of days', 400);
		else{
			if(count($data['weeks']) == 0 and count($data['day']) == 0) throw new Exception('The syllabus must at least 1 week or at least one day', 400);
            $weekNumber = 0;
            if(count($data['weeks']) > 0){
                foreach($data['weeks'] as $w){
                    $weekNumber++;
                    if(empty($w["label"])) throw new Exception("The week $weekNumber must have a label", 400);
                    if(empty($w["topic"])) throw new Exception("The week $weekNumber must have a topic", 400);
                    if(empty($w["summary"])) throw new Exception("The week $weekNumber must have a summary", 400);
                    if(!is_array($w["days"]) || count($w["days"])==0)
                        throw new Exception("The week $weekNumber at least one day", 400);
    
                    $dayNumber = 0;
                    foreach($w["days"] as $d){
                        $dayNumber++;
                        if(empty($d["label"])) throw new Exception("The day $dayNumber of the week $weekNumber must have a label", 400);
                        if(empty($d["description"])) throw new Exception("The day $dayNumber of the week $weekNumber must have a description", 400);
                        if(empty($d["instructions"])) throw new Exception("The day $dayNumber of the week $weekNumber must have instructions", 400);
                    }
                }
            }
            else if($data["days"]){
                foreach($data["days"] as $d){
                    $dayNumber++;
                    if(empty($d["label"])) throw new Exception("The day $dayNumber must have a label", 400);
                    if(empty($d["description"])) throw new Exception("The day $dayNumber must have a description", 400);
                    if(empty($d["teacher_instructions"])) throw new Exception("The day $dayNumber must have teacher_instructions", 400);
                }
            }
		}

        $api->db['json']->toFile($syllabusSlug)->save($data);

        return $response->withJson($syllabus);
	})->add($api->auth());

	return $api;
};
