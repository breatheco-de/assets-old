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

function addAPIRoutes($api){
	
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
        		'student' => ['read_basic_info', 'student_tasks'],
        		'admin' => ['read_basic_info', 'super_admin']
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
        
        $body = json_decode($request->getBody()->getContents());
        if(empty($body->email)) throw new Exception('Invalid email');
        if(empty($body->first_name)) throw new Exception('Invalid first_name');
        if(empty($body->last_name)) throw new Exception('Invalid last_name');
        if(empty($body->phone)) throw new Exception('Invalid phone');
        if(empty($body->cohort_slug)) throw new Exception('Invalid cohort_slug');
        
        $username = $body->email;
        $firstName = $body->first_name;
        $lastName = $body->last_name;
        $phone = $body->phone;
        $cohortSlug = $body->cohort_slug;
        try{
        	$user = BC::createStudent([
        		'email' => urlencode($username),
        		'full_name' => $firstName.' '.$lastName,
        		'phone' => $phone,
        		'cohort_slug' => $cohortSlug
        	]);
        	
        	if($user){
                ACAPI::start(AC_API_KEY);
                $result = ACAPI::createOrUpdateContact($body->email,[
                    "first_name" => $firstName,
                    "last_name" => $lastName,
                    "phone" => $phone,
                    "p[".ACAPI::list('active_student')."]" => ACAPI::list('active_student'),
                    "tags" => ACAPI::tag('platform_signup').','.$cohortSlug
                ]);
                
                return $response->withJson($user)->withStatus(200);
        	}
        }
        catch(Exception $e)
        {
            if(API_DEBUG) throw $e;
	    	return $response->withJson(['msg'=>$e->getMessage()])->withStatus(500);
        }
        return $response->withJson('error');
	});
	
	$api->post('/auth/github', function (Request $request, Response $response, array $args) use ($api) {
        
	    return $response->withJson($quizObj);
	});
	
	return $api;
}