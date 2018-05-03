<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use BreatheCode\BCWrapper as BC;
BC::init(BREATHECODE_CLIENT_ID, BREATHECODE_CLIENT_SECRET, BREATHECODE_HOST, API_DEBUG);
BC::setToken(BREATHECODE_TOKEN);

function addDataIntegrityHooks($api){
	
	$api->post('/update_bc_info_on_ac', function (Request $request, Response $response, array $args) use ($api) {
        
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
	
	$api->post('/sync/active_students', function (Request $request, Response $response, array $args) use ($api) {
        
        //TODO: this services removes or adds active students to the active, student list 
        //\AC\ACAPI::updateContact($contact->email,$fields);
        
        return $response->withJson('ok');
	});

	return $api;
}