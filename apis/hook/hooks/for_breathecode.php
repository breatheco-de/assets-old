<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use BreatheCode\BCWrapper as BC;
use \AC\ACAPI;
BC::init(BREATHECODE_CLIENT_ID, BREATHECODE_CLIENT_SECRET, BREATHECODE_HOST, API_DEBUG);
BC::setToken(BREATHECODE_TOKEN);

function addBreatheCodeHooks($api){
    
    $scope = 'breathecode';
	/**
	 * $parsedBody = [
	 *  "new_cohort" => "mdc-iii",
	 *  "email" => "aalejo@gmail.com"
	 * ]
	 * 
	 */
	$api->post('/'.$scope.'/second_cohort_registration', function (Request $request, Response $response, array $args) use ($api) {
        
        $log = [];
        
        $body = $request->getParsedBody();
        $studentId = $api->validate($body,'student_id')->int();
        $cohortSlug = $api->validate($body,'cohort_slug')->smallString();
        
        $student = BC::getStudent(["student_id" => $studentId]);
        
        if(!$student) throw new Exception("Invalid student id: "+$studentId,400);
        
        
        ACAPI::start(AC_API_KEY);
        $contact = ACAPI::getContactByEmail($student->email);

    	$resp = BC::addStudentCohort([
    		'student_id' => $student->id,
    		'cohort_id' => $cohortSlug
    	]);
    	
    	if(!empty($student)){
            ACAPI::start(AC_API_KEY);
            $contact = ACAPI::createOrUpdateContact($student->email,[
                "p[".ACAPI::list('active_student')."]" => ACAPI::list('active_student'),
                "tags" => ACAPI::tag('recurrent-student').','.$cohortSlug
            ]);
            if($contact){
                $log[] = 'The contact was updated in Active Campaign with the recurrent-student tag and the new cohort';
                ACAPI::setupEventTracking('25182870', AC_EVENT_KEY);
                ACAPI::trackEvent($student->email, ACAPI::event('new_cohort_registration'));
                $log[] = 'The event "new_cohort_registration" was fired';
            } 
            
            return $response->withJson($log);
    	}
        
        return $response->withJson($log);
	});
	
	return $api;
	
}