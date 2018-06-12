<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use BreatheCode\BCWrapper as BC;
use \AC\ACAPI;
BC::init(BREATHECODE_CLIENT_ID, BREATHECODE_CLIENT_SECRET, BREATHECODE_HOST, API_DEBUG);
BC::setToken(BREATHECODE_TOKEN);

function addBreatheCodeHooks($api){
    
    $slug = 'breathecode';
	/**
	 * $parsedBody = [
	 *  "new_cohort" => "mdc-iii",
	 *  "email" => "aalejo@gmail.com"
	 * ]
	 * 
	 */
	$api->post('/'.$slug.'/second_cohort_registration', function (Request $request, Response $response, array $args) use ($api) {
        
        $log = [];
        
        $body = $request->getParsedBody();
        $email = $api->validate($body,'email')->email();
        $cohortSlug = $api->validate($body,'cohort_slug')->smallString();
        ACAPI::start(AC_API_KEY);
        try{
            $contact = ACAPI::getContactByEmail($email);
        }catch(Exception $e){ return $response->withJson(['The contact was not found in AC']); }
        if(empty($contact)) return $response->withJson(['The contact was not found in AC']);
        /*
            is it a student or alumni?
        */
        $student=null;
        try{
            $student = BC::getStudent(["student_id" => $email]);
            
        // 	$user = BC::createStudent([
        // 		'email' => urlencode($username),
        // 		'cohort_slug' => $cohortSlug
        // 	]);
        }catch(Exception $e){ 
            if(!in_array($e->getCode(), [200,404])) throw $e; 
            else $log[] = 'The email was not found in BreatheCode';
        }
        
    	if(!empty($student)){
            ACAPI::start(AC_API_KEY);
            $contact = ACAPI::createOrUpdateContact($email,[
                "p[".ACAPI::list('active_student')."]" => ACAPI::list('active_student'),
                "tags" => ACAPI::tag('recurrent-student').','.$cohortSlug
            ]);
            if($contact){
                $log[] = 'The contact was updated in Active Campaign with the recurrent-student tag and the new cohort';
                ACAPI::setupEventTracking('25182870', AC_EVENT_KEY);
                ACAPI::trackEvent($email, ACAPI::event('new_cohort_registration'));
                $log[] = 'The event "new_cohort_registration" was fired';
            } 
            
            return $response->withJson($log)->withStatus(200);
    	}
        
        return $response->withJson($log);
	});