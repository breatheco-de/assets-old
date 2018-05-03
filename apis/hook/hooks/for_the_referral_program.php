<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use BreatheCode\BCWrapper as BC;
BC::init(BREATHECODE_CLIENT_ID, BREATHECODE_CLIENT_SECRET, BREATHECODE_HOST, API_DEBUG);
BC::setToken(BREATHECODE_TOKEN);

function addReferralProgramRoutes($api){
	
	$api->get('/referral_code/{email}', function (Request $request, Response $response, array $args) use ($api) {
        
        if(empty($args['email'])) throw new Exception('Please specify the user email');
        
         \AC\ACAPI::start(AC_API_KEY);
        $contact = \AC\ACAPI::getContactByEmail($args['email']);
        
        return $response->withJson([
            'referral_code' => HookFunctions::getReferralCode($contact->id, $contact->email, $contact->sdate)
        ]);
        
	});

	$api->post('/referral_code', function (Request $request, Response $response, array $args) use ($api) {
        
        $parsedBody = $request->getParsedBody();
        if(!empty($parsedBody['email'])) $userEmail = $parsedBody['email'];
        else if(isset($parsedBody['contact']['email'])) $userEmail = $parsedBody['contact']['email'];
        else throw new Exception('Please specify the user email');
        
         \AC\ACAPI::start(AC_API_KEY);
        $contact = \AC\ACAPI::getContactByEmail($userEmail);

        $referralHash = HookFunctions::getReferralCode($contact->id, $contact->email, $contact->sdate);

        $fields = [];
        foreach($contact->fields as $id => $field){
            if($field->perstag == 'REFERRAL_KEY')
            {
                $fields['field['.$id.','.$field->dataid.']'] = $referralHash;
                $updatedContact = \AC\ACAPI::updateContact($contact->email,$fields);
                return $updatedContact;
            }
        }    
        return $response->withJson('ok');
	});
	
	return $api;
}