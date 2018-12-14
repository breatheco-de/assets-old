<?php

require('../../vendor_static/breathecode-api/BreatheCodeAPI.php');
require('../BreatheCodeLogger.php');
require('../../vendor_static/ActiveCampaign/ACAPI.php');
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use \BreatheCode\BCWrapper as BC;
use PHPHtmlParser\Dom;

BC::init(BREATHECODE_CLIENT_ID, BREATHECODE_CLIENT_SECRET, BREATHECODE_HOST, API_DEBUG);
BC::setToken(BREATHECODE_TOKEN);

function addAPIRoutes($api){
	
	$api->addTokenGenerationPath();
	//get all cohorts and its replits
	$api->get('/student/{student_id}/contributions', function (Request $request, Response $response, array $args) use ($api) {
        
        $student = BC::getStudent(['student_id' => urlencode($args["student_id"])]);
        if(!$student) throw new Exception('The student was not found in BreatheCode', 404);
        if(empty($student->github)) throw new Exception('Student Github username is uknown' , 400);

		preg_match("/^(?:http(?:s)?:\/\/(?:www\.)?github.com\/)?([a-zA-Z0-9]*)\/?\??(?:\s*)*$/", $student->github, $matches);
		if(empty($matches[1])) throw new Exception('Imposible to determin the github username from '.$student->github , 400);
        
		$resp = @file_get_contents('https://github.com/users/alesanchezr/contributions');

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
	
	//create user activity

	return $api;
}