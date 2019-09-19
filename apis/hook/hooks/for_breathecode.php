<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use BreatheCode\BCWrapper as BC;
use \AC\ACAPI;
BC::init(BREATHECODE_CLIENT_ID, BREATHECODE_CLIENT_SECRET, BREATHECODE_HOST, API_DEBUG);
BC::setToken(BREATHECODE_TOKEN);

return function($api){

    $scope = 'breathecode';
	/**
	 * $parsedBody = [
	 *  "new_cohort" => "mdc-iii",
	 *  "email" => "aalejo@gmail.com"
	 * ]
	 *
	 */
	$api->post('/'.$scope.'/cohort_registration', function (Request $request, Response $response, array $args) use ($api) {

        $log = [];

        $body = $request->getParsedBody();
        $studentId = $api->validate($body,'student_id')->int();
        $cohortSlug = $api->validate($body,'cohort_slug')->slug();

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
                "tags" => $cohortSlug
            ]);
            if($contact){
                $log[] = 'The contact was updated in Active Campaign with the tag ';
            }

            return $response->withJson($log);
    	}

        return $response->withJson($log);
	});

	return $api;

};