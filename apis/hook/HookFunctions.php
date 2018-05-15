<?php
use BreatheCode\BCWrapper as BC;
use BreatheCode\SlackWrapper;

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
    
    static function studentCohortStatus($user){
        $status = [];
        
        $courses = [];
        $cohorts = [];
        $locations = [];
        foreach($user->cohorts as $cohortId){
            $cohort = BC::getCohort(['cohort_id' => $cohortId]);
            $active = ['not-started', 'on-prework', 'on-course','on-final-project'];
            $alumni = ['finished'];
            if(in_array($cohort->stage, $active)) $status['active'] = true;
            if(in_array($cohort->stage, $alumni)) $status['alumni'] = true;
            
            //get the language
            $status['lang'] = $cohort->language;
            
            //add all the cohorts
            $courses[] = $cohort->profile_slug;
            
            //add all the cohorts
            $cohorts[] = $cohort->slug;
            
            //add all the cohorts
            $locations[] = $cohort->location_slug;
        }
        if(!isset($status['active'])) $status['active'] = false;
        if(!isset($status['alumni'])) $status['alumni'] = false;
        $status['courses'] = implode(',',$courses);
        $status['cohorts'] = implode(',',$cohorts);
        $status['locations'] = implode(',',$locations);
        
        return $status;
    }
    
    static function getOrCreateSlackChannel($channelSlug){
	    //get all groups
        $slack = SlackWrapper::getWrappers(SLACK_API_TOKEN, SLACK_API_TOKEN_LEGACY);
	    $result = $slack['new']->groups->list();
        if(!$result['ok']) throw new Exception('Could not retieve list of channels from slack: '.$result['error']);
	    $targetGroup = null;
	    foreach($result['groups'] as $group)
	        if($group['name'] === $channelSlug) $targetGroup = $group;

        if(!$targetGroup){
            $result = $slack['new']->groups->create(['name' => $channelSlug]);
            if(!$result['ok']) throw new Exception('Private channel '.$channelSlug.' could not be created: '.$result['error']);
            $targetGroup = $results['group'];
        }
        return $targetGroup;
    }
    
    static function inviteUserToSlackChannel($email, $channels){
        $slack = SlackWrapper::getWrappers(SLACK_API_TOKEN, SLACK_API_TOKEN_LEGACY);
        $chanls = implode(",",$channels);

        $result = $slack['old']->users->admin->invite(http_build_query([
                "email" => $email,
                "channels" => $chanls
            ]));
        if(!$result['ok']) throw new Exception('Could not invite the user to '.$chanls.': '.$result['error']);
        else return $result;
    }
}