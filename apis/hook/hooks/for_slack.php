<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use BreatheCode\BCWrapper as BC;
BC::init(BREATHECODE_CLIENT_ID, BREATHECODE_CLIENT_SECRET, BREATHECODE_HOST, API_DEBUG);
BC::setToken(BREATHECODE_TOKEN);
use BreatheCode\SlackWrapper;

function addSlackRoutes($api){
	$scope = 'slack';
	
	$api->post('/'.$scope.'/invite', function (Request $request, Response $response, array $args) use ($api) {
        
        $parsedBody = $request->getParsedBody();
        $userEmail = '';
        if(!empty($parsedBody['email'])) $userEmail = $parsedBody['email'];
        else if(isset($parsedBody['contact']['email'])) $userEmail = $parsedBody['contact']['email'];
        else throw new Exception('Please specify the user email');
        
        $user = BC::getUser(["user_id" => $userEmail]);
        if($user){
            
            $channels = [
    	        'academy_news_events' => "C6R5DV6LW",
    	        'coding_weekends' => "CAEK465QR",
    	        'general' => "C0BG1MAV7",
    	        'job-hunting' => "C6PNCBMMX"
    	    ];
    	    $channelIds = [];
    	    foreach($channels as $key=>$id) $channelIds[] = $id;
    	    
            if($user->type == 'student'){
                $student = BC::getStudent(["student_id" => $userEmail]);
                foreach($student->cohorts as $cohort)
                {
                    $group = HookFunctions::getOrCreateSlackChannel($cohort);	    
                    $channelIds[] = $group['id'];
                }
            } 
                	    
    	    $result = HookFunctions::inviteUserToSlackChannel($userEmail,$channelIds);
            
            return $response->withJson($result);
        }
        else throw new ArgumentException('User not found');
        
	});
	
	return $api;
}