<?php
    header("Content-type: application/json"); 
    header("Access-Control-Allow-Origin: *");
    
    require('../api_globals.php');
    require('../../vendor/autoload.php');
    require('../../vendor_static/breathecode-api/BreatheCodeAPI.php');
    require('../../vendor_static/ActiveCampaign/ACAPI.php');
    require('HookFunctions.php');
    
    use \BreatheCode\BCWrapper;
    BCWrapper::init(BREATHECODE_CLIENT_ID, BREATHECODE_CLIENT_SECRET, BREATHECODE_HOST, true);
    BCWrapper::setToken(BREATHECODE_TOKEN);
    
    require_once('../APIGenerator.php');
	$api = new APIGenerator('data.json','[]');
	if(API_DEBUG) $api->logRequests('requests.log');

	$api->get('referral_code', 'Get student referral_code', function($request, $dataContent){
        
        if(!isset($request['parameters']['email'])) throw new Exception('Please specify the user email');
        
         \AC\ACAPI::start();
        $contact = \AC\ACAPI::getContactByEmail($request['parameters']['email']);
        return [
            'referral_code' => HookFunctions::getReferralCode($contact->id, $contact->email, $contact->sdate)
        ];
        
	});
	
	$api->post('referral_code', 'Update student referral_code on active campaign', function($request, $dataContent){
        
        $userEmail = null;
        if(isset($request['parameters']['email'])) $userEmail = $request['parameters']['email'];
        else if(isset($request['parameters']['contact']['email'])) $userEmail = $request['parameters']['contact']['email'];
        else throw new Exception('Please specify the user email');
        
         \AC\ACAPI::start();
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
        return null;
	});
	
	$api->post('update_bc_info_on_ac', 'Update student breathecode info on active campaign', function($request){
        
        $userEmail = null;
        if(isset($request['parameters']['email'])) $userEmail = $request['parameters']['email'];
        else if(isset($request['parameters']['contact']['email'])) $userEmail = $request['parameters']['contact']['email'];
        else throw new Exception('Please specify the user email');
        
        $user = BCWrapper::getUser(["user_id" => $userEmail]);

        \AC\ACAPI::start();
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
        
        return null;
	});
	
	$api->post('initialize', 'Initialize contact info on active campaign', function($request, $dataContent){
        
        $userEmail = null;
        if(isset($request['parameters']['email'])) $userEmail = $request['parameters']['email'];
        else if(isset($request['parameters']['contact']['email'])) $userEmail = $request['parameters']['contact']['email'];
        else throw new Exception('Please specify the user email');
        
         \AC\ACAPI::start();
        $contact = \AC\ACAPI::getContactByEmail($userEmail);

        $fields = [];
        $fieldsToInitialize = [
            'REFERRAL_KEY','REFERRER_NAME','REFERRED_BY','GCLID','COMPANY_TYPE',
            'SENORITY_LEVEL','UTM_LOCATION','UTM_LANGUAGE','COURSE','PHONE',
            'PLATFORM_USERNAME','LEAD_COUNTRY','BREATHECODEID'
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
        return 'ok';
	});

	$api->run();