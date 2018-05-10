<?php

namespace AC;

use Exception;
require_once(dirname(__FILE__)."/lib/includes/ActiveCampaign.class.php");
require_once(dirname(__FILE__)."/Connector.php");

class ACAPI{
    
    private static $lists = [
        'hot_leads' => 1,
        'soft_leads' => 8,
        'active_student' => 4,
        'alumni' => 9,
        'workshops' => 7,
        'newsletter' => 1,
        'hiring_partners' => 10
    ];
    
    private static $tags = [
        'platform_signup'
    ];
    
    private static $connector = [];
    
    public static function tag($slug){
        if(is_array($slug)){
            foreach($slug as $s) if(!in_array($s, self::$tags)) throw new Exception('Invalid Active Campaign Tag');
            return implode(',',$slug);
        } 
        else{
            if(!in_array($slug, self::$tags)) throw new Exception('Invalid Active Campaign Tag');
            return $slug;
        } 
    }
    
    public static function list($slug){
        if(!isset(self::$lists[$slug])) throw new Exception('Invalid Active Campaign List: '.$slug);
        return self::$lists[$slug];
    }
    
    public static function start($apiKey){
        
        $apiOldURL = 'https://4geeks.api-us1.com';
        $apiURL = 'https://4geeks.api-us1.com/api/3/';
        
        if(empty(self::$connector['new'])) self::$connector['new'] = new \AC\Connector($apiURL, $apiKey);
        if(empty(self::$connector['old'])) self::$connector['old'] = new \ActiveCampaign($apiOldURL, $apiKey);
        
    }
    
    public static function setupEventTracking($actid, $key, $email = null){
        self::$connector['old']->track_email = $email;
        self::$connector['old']->track_actid = $actid;
        self::$connector['old']->track_key = $key;
    }
    
    /**
     *  $contact = array(
    		"email"              => "test@example.com",
    		"first_name"         => "Test",
    		"last_name"          => "Test",
    		"p[{$list_id}]"      => $list_id,
    		"status[{$list_id}]" => 1, // "Active" status
    	);
    */
    
    public static function subscribeToList($contact, $listId, $tags=null){
        
        $contact["p[{$listId}]"] = $listId;
        $contact["status[{$listId}]"] = 1;
        if($tags) $contact["tags"] = $tags;
        
    	$result = self::$connector['old']->api("contact/sync", $contact);
    	if (!(int)$result->success) throw new Exception('Syncing contact failed. Error returned: '. $result->error);
        
        // successful request
        return (int)$result->subscriber_id;
    }
    
    public static function tagContact($email, $tags){
        
        $request["email"] = $email;
        $request["tags"] = $tags;

    	$result = self::$connector['old']->api("contact/tag_add", $request);
    	if (!(int)$result->success) throw new Exception('Syncing contact failed. Error returned: '. $result->error);
        
        // successful request
        return (int)$result->subscriber_id;
    }
    
    public static function updateContact($email, $fieldsToUpdate){
        
    	$contact = array_merge($fieldsToUpdate, ["email" => $email]);
    	
    	/*
    	$contact = array(
    		"first_name"         => "Test",
    		"last_name"          => "Test",
    		"p[{$list_id}]"      => $list_id,
    		"status[{$list_id}]" => 1, // "Active" status
    		"tags" => "platform_signup"
    	);*/
    	$result = self::$connector['old']->api("contact/sync", $contact);
    	if (!(int)$result->success) throw new Exception('Syncing contact failed. Error returned: '. $result->error);
        
        // successful request
        return $result;
    }
    
    public static function createContact($email, $fieldsToUpdate){
        
    	$contact = array_merge($fieldsToUpdate, ["email" => $email]);
    	
    	/*
    	$contact = array(
    		"first_name"            => "Test",
    		"last_name"             => "Test",
    		"p[{$list_id}]"         => $list_id,
    		"tag"                   => 'tag1,tag2,tag3',
    		"phone"                 => '+3245234543',
    		"status[{$list_id}]"    => 1, // "Active" status
    	);*/
    	$result = self::$connector['old']->api("contact/add", $contact);
    	if ($result->success == 0) throw new Exception('Add contact failed. Error returned: '. $result->error);
        
        // successful request
        return $result;
    }
    
    public static function createOrUpdateContact($email, $fieldsToUpdate){
        $contact = self::getContactByEmail($email);
        if($contact) return self::updateContact($email, $fieldsToUpdate);
        else return self::createContact($email, $fieldsToUpdate);
    }
    
    public static function updateContactFields($contact, $new_fields=[]){
        
    	$slugsToUpdate = array_map(function($vallue, $key){
            return $key;
    	},$fields);
        foreach($contact->fields as $original_key => $original_value){
            
            if(in_array($original_value->perstag, $slugsToUpdate))
            {
                //initialize the field with undefined
                $fields['field['.$id.','.$original_value->dataid.']'] = $new_fields[$original_value->perstag];
            }
            
        }
    }
    
    public static function getContactByEmail($email){
        
        if(!is_string($email)) throw new Exception('The email must by a string');
    	$result = self::$connector['old']->api("contact/view?email=".$email);

    	if ($result->http_code != 200) throw new Exception('Error returned: '. $result->error);
        if(0 === (int)$result->success) return null;
        // successful request
        return $result;
    }
    
    public static function getAllCustomFields(){
        
        $customFields = self::$connector['old']->api("list/field/view?ids=all");
        //print_r($customFields); die();
        // successful request
        return $customFields;
    }
    
    public static function getCustomField($slug, $listIds='1'){
        
        $slug = strtoupper($slug);
        $args = [];
        $args['ids'] = $listIds;
        $args['global_fields'] = 1;
        $args['full'] = 1;
        
        $list = (array) self::$connector['old']->api("list/list", $args);
        $list = array_shift($list);
        $courseField = self::findField($list->fields,$slug);
        
        if(!$courseField) throw new Exception('Unable to find custom field: '.$slug);
        return $courseField;
    }
    
    private static function findField($fields, $slug){
        foreach($fields as $f) if($f->perstag == $slug) return $f;
        return null;
    }
    
    public static function trackEvent($email, $event, $eventData=null){
        
        self::$connector['old']->track_email = $email;
        $request["event"] = $event;
        $request["event-data"] = $eventData;
		
    	$result = self::$connector['old']->api("tracking/log", $request);
    	if (!(int)$result->success) throw new Exception('Syncing contact failed. Error returned: '. $result->message);
        
        // successful request
        if(!is_object($result)) return (int)$result;
        else return $result;
    }
}