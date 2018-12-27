<?php


use Google\Cloud\Datastore\DatastoreClient;
use Google\Cloud\Datastore\Query\Query;

class BreatheCodeLogger{
    
    private static $datastore = null;
    
    private static $_activites = [
        "breathecode_login" => [ 
            "track_on_active_campaign" => true,
            "track_on_log" => true
        ],
        "online_platform_registration" => [ 
            "track_on_active_campaign" => true,
            "track_on_log" => true
        ],
        "public_event_attendance" => [ 
            "track_on_active_campaign" => true,
            "track_on_log" => true
        ],
        //when a nps survey is answered by the student
        "nps_survey_answered" => [ 
            "track_on_active_campaign" => true,
            "track_on_log" => true
        ]
    ];
    
    public static function datastore(){
        if(!self::$datastore) self::$datastore = new DatastoreClient([ 
            'projectId' => GOOGLE_PROJECT_ID,
            'keyFilePath' => '../../breathecode-efde1976e6d3.json'
        ]);
        return self::$datastore;
    }
    
    public static function setDatastore($datastore){
        if($datastore) self::$datastore = self::$datastore;
    }
    
    private static function validateActivityProperties($activity, $slug){
        $props = ['track_on_active_campaign','track_on_log'];
        foreach($props as $prop)
            if(!isset($activity[$prop])){
                throw new Exception("Activity ".$slug." is missing property: ".$prop);
            } 
    }
    
    public static function addMessatesToActivities($messages){
        foreach($messages as $slug => $msg){
            if(isset(self::$_activites[$slug])) throw new Exception("Duplicated Activity Slug");
            self::validateActivityProperties($msg, $slug);
            self::$_activites[$slug] = [
                "track_on_active_campaign" => $msg["track_on_active_campaign"],
                "track_on_log" => $msg["track_on_log"]
            ];
        }
    }
    
    static function getActivity($activity){
        foreach(self::$_activites as $key => $obj)
            if($key == $activity){
                $obj["slug"] = $key;
                return $obj;
            } 
        
        return null;
    }
    
    static function logActivity($activity, $user=null){
        $student = (!$user) ? $activity["user"] : $user;
        $data = (empty($activity["data"])) ? null : $activity["data"];
        
        $activity = self::getActivity($activity["slug"]);
        if($activity){
            if($activity["track_on_log"]){
                self::_logInternally($student, $activity["slug"], $data);
            }
                
            if($activity["track_on_active_campaign"]) 
                self::_logInActiveCampaign($student, $activity["slug"], $data);
        }

    }
    
    public static function _logInternally($student, $activity_slug, $data='No additional data'){
        
        $activity = [
            'created_at' => new DateTime(),
            'slug' => $activity_slug,
            'data' => $data
        ];

        if(is_string($student)) $activity['email'] = $student;
        else{
            //print_r($student); die();
            if(!empty($student->id)) $activity['user_id'] = (string) $student->id;
            
            if(!empty($student->email)) $activity['email'] = (string) $student->email;
            else if(!empty($student->username)) $activity['email'] = (string) $student->username;
        }
        
        $record = self::datastore()->entity('student_activity', $activity);
        self::datastore()->insert($record);
    }
    
    private static function _logInActiveCampaign($student, $activity_slug, $data=null){
        \AC\ACAPI::start(AC_API_KEY);
        \AC\ACAPI::setupEventTracking('25182870', AC_EVENT_KEY);
        if(!is_string($student)){
            if(!empty($student->email)) $student = $student->email;
            else if(!empty($student->username)) $student = $student->username;
            else throw new Exception('Missing user email or username');
        }
        \AC\ACAPI::trackEvent($student, $activity_slug, $data);
    }
    
    public static function retrieveActivity($filters){
        
        $query = self::datastore()->query()->kind('student_activity');
        if(!empty($filters["slug"])) $query = $query->filter('slug', '=', $filters["slug"]);
        if(!empty($filters["user_id"])){
            $query = $query->filter('user_id', '=', $filters["user_id"]);
        } 
        if(!empty($filters["email"])) $query = $query->filter('email', '=', $filters["email"]);
        //$query = $query->order('created_at', Query::ORDER_DESCENDING);
        $items = self::datastore()->runQuery($query);
        $results = [];
        foreach($items as $ans) {
            $results[] = [
                "user_id" => $ans->user_id,
                "email" => $ans->email,
                "data" => $ans->data,
                "created_at" => $ans->created_at,
                "slug" => $ans->slug,
            ];
        }
        return $results;
    }
    
}