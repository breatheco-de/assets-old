<?php

class HookFunctions{
    
    static $api;
    
    public static function setAPI($api){
        self::$api = $api;
    }
    
    static function getReferralCode($id, $email, $created_at){
        return sha1($id.$email.$created_at);
    }
    
    static function checking($user){
        
        $emails = $user["emails"];
        foreach($emails as $email){
            $contact = \AC\ACAPI::getContactByEmail($email['email']);
            if($contact){
                self::$api->log(SlimAPI::$INFO, 'Tracking event for: '.$email['email']);
                \AC\ACAPI::trackEvent($email['email'], 'public_event_attendance', $eventData=null);
                return true;
            } 
        }

        $contact = \AC\ACAPI::createContact($emails[0]['email'], [
    		"first_name"        => $user['first_name'],
    		"last_name"         => $user['last_name'],
    		"p[{7}]"            => 7
        ]);
        
        return $contact;
    }
}