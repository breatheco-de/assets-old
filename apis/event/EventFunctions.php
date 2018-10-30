<?php
use BreatheCode\BCWrapper as BC;
use BreatheCode\SlackWrapper;

class EventFunctions{
    
    static $api;
    static $types = [
        'workshop' => 'workshop',
        'coding_weekend' => 'coding_weekend',
        'hackathon' => 'hackathon',
        'intro_to_coding' => 'intro_to_coding',
        '4geeks_night' => '4geeks_night',
        'other' => 'other'
    ];
    static $status = [
        'published' => 'published',
        'unlisted' => 'unlisted',
        'pending_review' => 'pending_review',
        'draft' => 'draft'
    ];
    static $recurrentTypes = [
        'published' => 'every_week',
        'unlisted' => 'one_time'
    ];
    
    public static function setAPI($api){
        self::$api = $api;
    }
    
    public static function getType($type){
        if(!isset(self::$types[$type])) throw new Exception("Invalid event type: ".$type, 400);
        
        return self::$types[$type];
    }
    
    public static function getStatus($type){
        if(!isset(self::$status[$type])) throw new Exception("Invalid event status: ".$type, 400);
        
        return self::$status[$type];
    }
    
}