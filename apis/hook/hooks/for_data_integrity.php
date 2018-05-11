<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use BreatheCode\BCWrapper as BC;
use \AC\ACAPI;
BC::init(BREATHECODE_CLIENT_ID, BREATHECODE_CLIENT_SECRET, BREATHECODE_HOST, API_DEBUG);
BC::setToken(BREATHECODE_TOKEN);

function addDataIntegrityHooks($api){
	
	$api->post('/initialize', function (Request $request, Response $response, array $args) use ($api) {
        
        $parsedBody = $request->getParsedBody();
        $userEmail = null;
        if(isset($parsedBody['email'])) $userEmail = $parsedBody['email'];
        else if(isset($parsedBody['contact']['email'])) $userEmail = $parsedBody['contact']['email'];
        else throw new Exception('Please specify the user email');
        
         \AC\ACAPI::start(AC_API_KEY);
        $contact = \AC\ACAPI::getContactByEmail($userEmail);

        $fields = [];
        $fieldsToInitialize = [
            'REFERRAL_KEY','REFERRER_NAME','REFERRED_BY','GCLID','COMPANY_TYPE',
            'SENORITY_LEVEL','UTM_LOCATION','UTM_LANGUAGE','COURSE','PHONE',
            'PLATFORM_USERNAME','LEAD_COUNTRY','BREATHECODEID', 'ADMISSION_CODE_TEST_SCORE'
        ];
        foreach($contact->fields as $id => $field){
            
            if(in_array($field->perstag, $fieldsToInitialize))
            {
                if(empty($field->val))
                {
                    //initialize the field with undefined
                    $fields['field['.$id.','.$field->dataid.']'] = 'undefined';
                    
                    //override the initialization for language, making EN by default 
                    if($field->perstag == 'UTM_LANGUAGE')
                        $fields['field['.$id.','.$field->dataid.']'] = 'en';
                }
            }
            
        }    
        \AC\ACAPI::updateContact($contact->email,$fields);
        
        return $response->withJson('ok');
	});
	
	$api->post('/sync/breathecode_id', function (Request $request, Response $response, array $args) use ($api) {
        
        $parsedBody = $request->getParsedBody();
        $userEmail = null;
        if(isset($parsedBody['email'])) $userEmail = $parsedBody['email'];
        else if(isset($parsedBody['contact']['email'])) $userEmail = $parsedBody['contact']['email'];
        else throw new Exception('Please specify the user email');
        
        $user = BC::getUser(["user_id" => $userEmail]);

        \AC\ACAPI::start(AC_API_KEY);
        $contact = \AC\ACAPI::getContactByEmail($userEmail);

        $fields = [];
        foreach($contact->fields as $id => $field){
            if($field->perstag == 'BREATHECODEID')
            {
                $fields['field['.$id.','.$field->dataid.']'] = $user->id;
                $updatedContact = \AC\ACAPI::updateContact($contact->email,$fields);
                return $updatedContact;
            }
        }    
        
        return $response->withJson('ok');
	});
	
	$api->post('/sync/contact', function (Request $request, Response $response, array $args) use ($api) {
        
        $log = [];
        $parsedBody = $request->getParsedBody();
        
        $userEmail = null;
        if(!empty($parsedBody['email'])) $userEmail = $parsedBody['email'];
        else if(isset($parsedBody['contact']['email'])) $userEmail = $parsedBody['contact']['email'];
        else throw new Exception('Please specify the user email', 404);
        
        ACAPI::start(AC_API_KEY);
        try{
            $contact = ACAPI::getContactByEmail($userEmail);
        }catch(Exception $e){ return $response->withJson(['The contact was not found']); }
        if(empty($contact)) return $response->withJson(['The contact was not found']);
        /*
            is it a student or alumni?
        */
        $student=null;
        try{
            $student = BC::getStudent(["student_id" => $userEmail]);
        }catch(Exception $e){ 
            if(!in_array($e->getCode(), [200,404])) throw $e; 
            else $log[] = 'The email was not found on BreatheCode';
        }
        
        if(!empty($student)) //it is a student
        {
            $status = HookFunctions::studentCohortStatus($student);
            
            $newFields = [];
            if(!empty($status['lang'])){
                $newFields['UTM_LANGUAGE'] = $status['lang'];
                $log[] = 'The utm_language '.$status['lang'].' was added to the student';
            }
            if(!empty($status['locations'])){
                $newFields['UTM_LOCATION'] = $status['locations'];
                $log[] = 'The UTM_LOCATION '.$status['locations'].' was added to the student';
            } 
            if(!empty($status['courses'])){
                $newFields['COURSE'] = $status['courses'];
                $log[] = 'The courses '.$status['courses'].' were added to the student';
            } 
            if(!empty($student->id)){
                $newFields['BREATHECODEID'] = $student->id;
                $log[] = 'The breathecode_id '.$student->id.' was added to the student';
            } 
            
            $contactFields = ACAPI::updateContactFields($contact, $newFields);
            
            if(!empty($student->phone)){
                $contactFields["phone"] = $student->phone;
                $log[] = 'The following phone was added to the student: '.$student->phone;
            } 
            //apply tags for each cohort
            $contactFields["tags"] = $status['cohorts'];
            $log[] = 'The following tags were added to the student: '.$status['cohorts'];
            //add or remove to the active student list
            $contactFields["p[".ACAPI::list('active_student')."]"] = ACAPI::list('active_student');
            $contactFields["status[".ACAPI::list('active_student')."]"] = ($status['active']) ? 1 : 2;
            if($status['active']) $log[] = 'The student -subscribed- to the active_student list';
            else $log[] = 'The student -unsubscribed- from: active_student list';
            //add or remove to the alumni list
            $contactFields["status[".ACAPI::list('alumni')."]"] = ($status['alumni']) ? 1 : 2;
            $contactFields["p[".ACAPI::list('alumni')."]"] = ACAPI::list('alumni');
            if($status['alumni']) $log[] = 'The student -subscribed- to the alumni list';
            else $log[] = 'The student -unsubscribed- from: alumni list';
            
            \AC\ACAPI::updateContact($userEmail,$contactFields);
        }
        
        return $response->withJson($log);
	});

	return $api;
}