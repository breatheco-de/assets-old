<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Carbon\Carbon;
use \BreatheCode\BCWrapper as BC;

require('../BreatheCodeMessages.php');

BC::init(BREATHECODE_CLIENT_ID, BREATHECODE_CLIENT_SECRET, BREATHECODE_HOST, API_DEBUG);
BC::setToken(BREATHECODE_TOKEN);

\AC\ACAPI::start(AC_API_KEY);
\AC\ACAPI::setupEventTracking('25182870', AC_EVENT_KEY);

BreatheCodeMessages::connect([
    'projectId' => 'breathecode-197918',
    'keyFilePath' => '../../breathecode-47bde0820564.json'
]);

return function($api){

	$api->get('/responses', function (Request $request, Response $response, array $args) use ($api) {
		$content = $api->db['sqlite']->response()->orderBy( 'created_at', 'DESC' );
	    return $response->withJson($content);
	});

	$api->get('/student/{user_id}', function (Request $request, Response $response, array $args) use ($api) {

		$api->validate($args['user_id'])->int();

		$student = BC::getStudent(['student_id'=>$args['user_id']]);
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
        $score = $api->validate($parsedBody['score'])->int();
        $email = $api->validate($parsedBody['email'])->email();

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

        $cohort = $api->optional($parsedBody,'cohort_slug')->smallString();
		$row = $api->db['sqlite']->createRow( 'response', [
			'score' => $score,
			'email' => $email,
			'user_id' => $api->optional($parsedBody,'user_id')->int(),
			'cohort_slug' => $cohort,
			'profile_slug' => $api->optional($parsedBody,'profile_slug')->smallString(),
			'comment' => $api->optional($parsedBody,'comment')->bigString(),
			'tags' => $api->optional($parsedBody,'tags')->smallString(),
			'created_at' => date("Y-m-d H:i:s")
		]);

		$row->save();

		try{
			$user = BC::getUser(['user_id' => urlencode($email)]);
	        BreatheCodeLogger::logActivity([
	            'slug' => 'nps_survey_answered',
	            'user' => ($user) ? $user : $email,
	            'data' => json_encode([ "score" => $score, "cohort" => $cohort ])
	        ]);
	        BreatheCodeMessages::markManyAs('answered',[
	        	'slug' => 'nps_survey',
	        	'user_id' => ($user) ? $user->id : $email
	        ], [ "score" => $score ]);
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
};