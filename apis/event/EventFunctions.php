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
    
    public static function setAPI($api){
        self::$api = $api;
    }
    
    public static function getType($type){
        if(!isset(self::$types[$type])) throw new Exception("Invalid event type: ".$type, 400);
        
        return self::$types[$type];
    }
    
}