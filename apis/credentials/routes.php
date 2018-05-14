<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require_once('../../vendor_static/breathecode-api/BreatheCodeAPI.php');
require('../../vendor_static/ActiveCampaign/ACAPI.php');
require_once('../../globals.php');
use \AC\ACAPI;
use BreatheCode\BCWrapper as BC;
BC::init(BREATHECODE_CLIENT_ID, BREATHECODE_CLIENT_SECRET, BREATHECODE_HOST, API_DEBUG);
BC::setToken(BREATHECODE_TOKEN);

use wrapi\slack\slack;

function addAPIRoutes($api){
	
	$api->get('/slack', function (Request $request, Response $response, array $args) use ($api) {
	    $slack = new slack(SLACK_API_TOKEN);
	    $channels = $slack->channels->list(array("exclude_archived" => 1));
	    return $response->withJson($channels);
	});
	
	
	$api->post('/auth', function (Request $request, Response $response, array $args) use ($api) {
        
        $body = json_decode($request->getBody()->getContents());
        if(empty($body->username)) throw new Exception('Invalid username');
        if(empty($body->password)) throw new Exception('Invalid password');
        
        $username = $body->username;
        $password = $body->password;
        try{
        	$user = BC::getUser(['user_id' => urlencode($username)]);
        	$scope = (empty($user->type)) ? 'student':$user->type;

        	$permissions = [
        		'admin' => ['super_admin'],
        		'student' => ['read_basic_info', 'student_tasks'],
        		'teacher' => ['read_basic_info'],
        		'admissions' => ['read_basic_info', 'crud_student', 'crud_cohort'],
        		'student_support' => ['read_basic_info', 'user_profile']
        	];
        	if(!isset($permissions[$scope])) throw new Exception('Invalid scope'); 
        	
        	$token = BC::autenticate($username,$password,$permissions[$scope]);
		    if($token && $token->access_token)
		    {
		        BC::setToken($token->access_token);
		        $user = BC::getMe();
		        $user->access_token = $token->access_token;
		        if($user->type == 'student'){
		        	$user->cohorts = $user->full_cohorts;
		        }
		        $user->scope = $token->scope;
	    		return $response->withJson($user);
		    }
        }
        catch(Exception $e)
        {
	    	return $response->withJson(['msg'=>$e->getMessage()])->withStatus(403);
        }
        return $response->withJson('error');
	});
	
	$api->put('/signup', function (Request $request, Response $response, array $args) use ($api) {
        
        $body = $request->getParsedBody();
        $username = $api->validate($body,'email')->email();
        $firstName = $api->validate($body,'first_name')->smallString();
        $lastName = $api->validate($body,'last_name')->smallString();
        $phone = $api->validate($body,'phone')->smallString();
        $cohortSlug = $api->validate($body,'cohort_slug')->smallString();
        
    	$user = BC::createStudent([
    		'email' => urlencode($username),
    		'full_name' => $firstName.' '.$lastName,
    		'phone' => $phone,
    		'cohort_slug' => $cohortSlug
    	]);
    	
    	if(!empty($user)){
            ACAPI::start(AC_API_KEY);
            $result = ACAPI::createOrUpdateContact($username,[
                "first_name" => $firstName,
                "last_name" => $lastName,
                "phone" => $phone,
                "p[".ACAPI::list('active_student')."]" => ACAPI::list('active_student'),
                "tags" => ACAPI::tag('platform_signup').','.$cohortSlug
            ]);
            if($result) ACAPI::addToAutomations($username, ['online_platform_registration']);
            
            return $response->withJson($user)->withStatus(200);
    	}
        else{
            throw new Exception('The student was not added into breathecode');
        }
	});
	
	$api->post('/auth/github', function (Request $request, Response $response, array $args) use ($api) {
        
	    return $response->withJson($quizObj);
	});
	
	return $api;
}