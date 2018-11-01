<?php

require('../../vendor_static/breathecode-api/BreatheCodeAPI.php');
require('./BreatheCodeMessages.php');
require('../../vendor_static/ActiveCampaign/ACAPI.php');
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use \BreatheCode\BCWrapper as BC;
use Google\Cloud\Datastore\DatastoreClient;

BC::init(BREATHECODE_CLIENT_ID, BREATHECODE_CLIENT_SECRET, BREATHECODE_HOST, API_DEBUG);
BC::setToken(BREATHECODE_TOKEN);

function addAPIRoutes($api){
	
	$api->addTokenGenerationPath();
	//get all cohorts and its replits
	$api->get('/email/{template_slug}', function (Request $request, Response $response, array $args) use ($api) {
		$templates = BreatheCodeMessages::getTemplateName("nps_survey");
		return $response->write($templates["html"]->render([
			"intro" => "Hello %FIRSTNAME%, we need some feedback from your side, it's the only way to become a better academy and it's only one small question, we would really appreciate your answer",
			"subject" => "Net Promoter Score - BreatheCode",
			"question" => "How likely are you to recommend 4Geeks Academy to your friends or colleagues?",
			"url" => "https://assets.breatheco.de/apps/nps/survey/123"
		]));
	});

	$api->post('/test-email', function (Request $request, Response $response, array $args) use ($api) {

		//$user = BC::getUser(['user_id' => urlencode($args["user_id"])]);
		
		//$parsedBody = $request->getParsedBody();
        //$data = $api->optional($parsedBody,'data')->smallString();
        //$slug = $api->validate($parsedBody,'slug')->slug();
		
        BreatheCodeMessages::sendMail("nps_survey","aalejo@gmail.com","Feedback nps", []);
	    return $response->withJson("ok");
	});//->add($api->auth());
	
	$api->post('/notify/student/{student_id}', function (Request $request, Response $response, array $args) use ($api) {

		if(empty($args['student_id'])) throw new Exception('Invalid param student_id');
        
    	$student = BC::getStudent(['student_id' => urlencode($args['student_id'])]);
    	if(!$student) throw new Exception('Student not found');
    	if($student->status != "currently_active") throw new Exception('The student is not currently_active: '.$status->status);
    	
    	BreatheCodeMessages::addMessage("nps_survey", $student);
    	
    	return $response->withJson($student);
    	
	});//->add($api->auth());
	
	$api->post('/notify/cohort/{cohort_slug}', function (Request $request, Response $response, array $args) use ($api) {

		if(empty($args['cohort_slug'])) throw new Exception('Invalid param cohort_slug');
		
		// $parsedBody = $request->getParsedBody();
  //      $cohortSlug = $api->validate($parsedBody,'cohort_slug')->slug();
  //      if(empty($cohortSlug))  throw new Exception('Cohort '.$cohortSlug.' not found');
        
    	$students = BC::getStudentsFromCohort(['cohort_id' => $args['cohort_slug']]);
		$students = array_filter($students, function($student){
			return $status->status != "currently_active";
		});
    	
    	
    	return $response->withJson($students);
	});//->add($api->auth());
	

	return $api;
}