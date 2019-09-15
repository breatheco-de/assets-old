<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use GuzzleHttp\Client;
use \BreatheCode\BreatheCodeLogger;
use \AC\ACAPI;
use BreatheCode\BCWrapper as BC;
BC::init(BREATHECODE_CLIENT_ID, BREATHECODE_CLIENT_SECRET, BREATHECODE_HOST, API_DEBUG);
BC::setToken(BREATHECODE_TOKEN);

return function($api){

	$api->post('/auth', function (Request $request, Response $response, array $args) use ($api) {

        function getCurrentCohort($cohorts) {
            if(count($cohorts) == 0) return null;
            else if(count($cohorts) == 1) return $cohorts[0];
            else if($cohorts == 2) for($i=0;$i<count($cohorts); $i++) if (strpos($cohorts[$i]->profile_slug, 'prework') !== false) return $cohorts[$i];
            //print_r(count($cohorts)); die();

            for($i=0;$i<count($cohorts); $i++) {
                $c = $cohorts[$i];
                $start = strtotime($c->kickoff_date);
                $end = strtotime($c->ending_date);
                $current = strtotime("now");

                // Check that user date is between start & end
                $inRange = [];
                if(($current >= $start) && ($current <= $end)){
                    $inRange[] = $c;
                }
                if(count($inRange) > 0) return $inRange[0];
                else return end($cohorts);
            }
            return null;
        }

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
        		'location-admin' => ['read_basic_info', 'crud_student', 'crud_cohort'],
        		'student' => ['read_basic_info', 'student_tasks'],
        		'teacher' => ['read_basic_info', 'update_cohort_current_day', 'student_tasks'],
        		'admission' => ['read_basic_info', 'crud_student', 'crud_cohort'],
        		'career-support' => ['read_basic_info', 'user_profile', 'crud_student']
        	];
        	if(!isset($permissions[$scope])) throw new Exception('Invalid scope: '.$scope);

        	$token = BC::autenticate($username,$password,$permissions[$scope]);
		    if($token && $token->access_token)
		    {
		        BC::setToken($token->access_token);
		        $user = BC::getMe();

            	if($user->type == 'student' && ($user->status == 'blocked' || $user->status == 'student_dropped')){
            	   return $response->withJson([
            	        'msg'=> 'You access to the BreatheCode platform has been revoked'
            	   ])->withStatus(403);
            	}

		        $user->access_token = $token->access_token;
                $currentCohort = null;
		        if($user->type == 'student' || $user->type == 'teacher'){
		        	$user->cohorts = $user->full_cohorts;
                    //stage
                    $currentCohort = getCurrentCohort($user->cohorts);
		        }
		        $user->scope = $token->scope;
		        $user->assets_token = $api->jwt_encode($user->id);

		        try{
		            BreatheCodeLogger::logActivity([
		                'slug' => 'breathecode_login',
		                'user' => $user,
                        'cohort' => $currentCohort ? $currentCohort->slug : null,
                        'day' => $currentCohort ? $currentCohort->current_day : null,
                        'user_agent' => isset($body->user_agent) ? $body->user_agent : null
		            ]);
		        }catch(Exception $e){  }

	    		return $response->withJson($user);
		    }
        }
        catch(Exception $e)
        {
	    	return $response->withJson(['msg'=>$e->getMessage()])->withStatus(403);
        }
        return $response->withJson('error');
	});

	$api->post('/remind/{email}', function (Request $request, Response $response, array $args) use ($api) {

        $email = null;
        if(empty($args['email'])) throw new Exception('Invalid or missing email');
        else $email = $args['email'];

        try{
        	$result = BC::remind(['user_id' => urlencode($email)]);
	    	return $response->withJson($result);
        }
        catch(Exception $e)
        {
	    	return $response->withJson(['msg'=> $e->getMessage()])->withStatus(400);
	    	//return $response->withJson(['msg'=> "We don't seem to have that email in our records"])->withStatus(400);
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
        $profileSlug = $api->optional($body,'profile_slug');
        $github = $api->optional($body,'github')->smallString();

        if($github){
            $client = new Client();
            $resp = $client->request('GET','https://github.com/'.$github);
            if($resp->getStatusCode() == 404) throw new Exception('Github username not not found', 400);
        }

    	$user = BC::createStudent([
    		'email' => urlencode($username),
    		'first_name' => $firstName,
    		'last_name' => $lastName,
    		'github' => $github,
    		'phone' => $phone,
    		'cohort_slug' => $cohortSlug
    	]);

        //the students is added to active campaign using the zap engine

    	if(!empty($user)){
            BreatheCodeLogger::logActivity([
                'slug' => 'online_platform_registration',
                'user' => $user
            ]);
            return $response->withJson($user)->withStatus(200);
        }
        else throw new Exception('The student was not added into breathecode');
	});

	return $api;
};
