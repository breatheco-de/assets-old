<?php
    header("Content-type: application/json"); 
    header("Access-Control-Allow-Origin: *");
    
    require('../../vendor/autoload.php');
    require('../../vendor_static/breathecode-api/BreatheCodeAPI.php');
    require('../../vendor_static/ActiveCampaign/ACAPI.php');
    require('HookFunctions.php');
    
    $clientId = 'alesanchezr';
    $clientSecret = 'd04f78ef196471d5a954fe71aab4fe63bd95a8a4';
    $host = 'https://talenttree-alesanchezr.c9users.io/';
    
    use \BreatheCode\BCWrapper;
    BCWrapper::init($clientId, $clientSecret, $host, true);
    BCWrapper::setToken('d69eae97e7f874c6cdf46de524178e8ca5f1583e');
    
    require_once('../APIGenerator.php');
	$api = new APIGenerator('data.json','[]');
	$api->logRequests('requests.log');

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
            'PLATFORM_USERNAME'
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