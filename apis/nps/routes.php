<?php

require('../../vendor_static/breathecode-api/BreatheCodeAPI.php');

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Carbon\Carbon;
use \BreatheCode\BCWrapper;

require('../../vendor_static/ActiveCampaign/ACAPI.php');

BCWrapper::init(BREATHECODE_CLIENT_ID, BREATHECODE_CLIENT_SECRET, BREATHECODE_HOST, API_DEBUG);
BCWrapper::setToken(BREATHECODE_TOKEN);

\AC\ACAPI::start(AC_API_KEY);
\AC\ACAPI::setupEventTracking('25182870', AC_EVENT_KEY);

function addAPIRoutes($api){

	$api->get('/responses', function (Request $request, Response $response, array $args) use ($api) {
		$content = $api->db['sqlite']->response();
	    return $response->withJson($content);
	});
	
	$api->get('/student/{user_id}', function (Request $request, Response $response, array $args) use ($api) {
		
		$api->validate($args['user_id'])->int();
		
		$student = BCWrapper::getStudent(['student_id'=>$args['user_id']]);
	    return $response->withJson($student);
	});
	
	$api->get('/responses/user/{user_id}', function(Request $request, Response $response, array $args) use ($api) {
        
		$api->validate($args['user_id'])->int();
		
		$row = $api->db['sqlite']->response()
			->where('user_id',$args['user_id']);

		if(!$row) $row = (object) [];
		return $response->withJson($row);	
	});

	$api->get('/response', function(Request $request, Response $response, array $args) use ($api) {
		$responses = $api->db['sqlite']->response()->fetchAll();
		return $response->withJson($responses);	
	});
	
	$api->put('/response', function(Request $request, Response $response, array $args) use ($api) {

        $parsedBody = $request->getParsedBody();
        $email = $api->validate($parsedBody['score'])->int();
        $score = $api->validate($parsedBody['email'])->email();
        
        $a1 = [];
		if(isset($parsedBody['user_id']))
			$a1 = $api->db['sqlite']->response()->where('user_id',$parsedBody['user_id'])->fetchAll();
		
		$a2 = $api->db['sqlite']->response()->where('email',$parsedBody['email'])->fetchAll();
		$answers = array_merge($a1,$a2);
		foreach($answers as $ans){
			$now = Carbon::now();
			$day = Carbon::createFromFormat('Y-m-d H:i:s', $ans->created_at);
			if($now->diffInHours($day) < 24) throw new Exception('You have already answered',400);
		} 
        
		$row = $api->db['sqlite']->createRow( 'response', [
			'score' => $score,
			'email' => $email,
			'user_id' => $api->optional($parsedBody,'user_id'),
			'cohort_slug' => $api->optional($parsedBody,'cohort_slug'),
			'profile_slug' => $api->optional($parsedBody,'profile_slug'),
			'comment' => $api->optional($parsedBody,'comment'),
			'tags' => $api->optional($parsedBody,'tags'),
			'created_at' => date("Y-m-d H:i:s")
		]);
		
		$row->save();
		
		try{
        	$contact = \AC\ACAPI::trackEvent($email, 'nps_survey_answered', $score);
		}
		catch(Exception $e)
		{
			$api->sendMail(
				ADMIN_EMAIL, 
				'API Error: Event tracking for nps_survey_answered',
				'There has been an error trying to register the nps_survey_answered event in active campaign for the user '.$email
			);
		}
        return $response->withJson($row);
	});
	
	return $api;
}