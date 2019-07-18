<?php

namespace BreatheCode;
use \Google\Cloud\Datastore\DatastoreClient;
use \Google\Cloud\Datastore\Query\Query;
use \Exception;
use \DateTime;

class BreatheCodeLogger{

    private static $datastore = null;

    public static $_activites = [
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
        "classroom_attendance" => [
            "track_on_active_campaign" => false,
            "track_on_log" => true
        ],
        "classroom_unattendance" => [
            "track_on_active_campaign" => false,
            "track_on_log" => true
        ],
        //when a nps survey is answered by the student
        "nps_survey_answered" => [
            "track_on_active_campaign" => true,
            "track_on_log" => true
        ]
    ];

    public static $_codingErrorActivites = [
        "webpack_error" => [
            "track_on_active_campaign" => false,
            "track_on_log" => true
        ],
    ];

    public static function datastore(){
        if(!self::$datastore) self::$datastore = new DatastoreClient([
            'projectId' => GOOGLE_PROJECT_ID,
            'keyFilePath' => '../../breathecode-47bde0820564.json'
        ]);

        return self::$datastore;
    }

    public static function setDatastore($datastore){
        if($datastore) self::$datastore = self::$datastore;
    }

    public static function validateActivityProperties($activity, $slug){
        $props = ['track_on_active_campaign','track_on_log'];
        foreach($props as $prop)
            if(!isset($activity[$prop])){
                throw new Exception("Activity ".$slug." is missing property: ".$prop);
            }
        return true;
    }

    public static function addMessagesToActivities($messages){
        foreach($messages as $slug => $msg){
            if(isset(self::$_activites[$slug])) throw new Exception("Duplicated Activity Slug");
            self::validateActivityProperties($msg, $slug);
            self::$_activites[$slug] = [
                "track_on_active_campaign" => $msg["track_on_active_campaign"],
                "track_on_log" => $msg["track_on_log"]
            ];
        }
    }

    static function getAllTypes(){
        return [
            "student_activity" => self::$_activites,
            "coding_error" => self::$_codingErrorActivites,
        ];
    }

    static function getTrackingActivity($activitySlug){
        foreach(self::$_activites as $key => $obj)
            if($key == $activitySlug){
                $obj["type"] = "student_activity";
                $obj["slug"] = $activitySlug;
                return $obj;
            }
        foreach(self::$_codingErrorActivites as $key => $obj)
            if($key == $activitySlug){
                $obj["type"] = "coding_error";
                $obj["slug"] = $activitySlug;
                return $obj;
            }

        return null;
    }

    static function logActivity($activity, $user=null){
        $student = (!$user) ? $activity["user"] : $user;

        if(empty($student)) throw new Exception('Invalid or empty user for the activity');

        $tracking = self::getTrackingActivity($activity["slug"]);

        if($tracking){
            if($tracking["track_on_log"]){
                self::_logInternally($student, $activity, $tracking["type"]);
            }

            if($tracking["track_on_active_campaign"])
                self::_logInActiveCampaign($student, $activity, $tracking["type"]);
        }

    }

    // public static function deleteActivity($student, $type=null){
    //     $record = self::datastore()->entity($type, $activity);
    //     self::datastore()->insert($record);
    // }

    public static function _logInternally($student, $activity, $type='student_activity'){
        if(!is_callable('BreatheCode\BreatheCodeLogger::encode_'.$type)) throw new Exception("No encoder for activity type: ".$type);
        $activity = call_user_func('BreatheCode\BreatheCodeLogger::encode_'.$type, $student, $activity);
        $record = self::datastore()->entity($type, $activity);
        self::datastore()->insert($record);
    }

    private static function _logInActiveCampaign($student, $activity, $type='student_activity'){
        \AC\ACAPI::start(AC_API_KEY);
        \AC\ACAPI::setupEventTracking('25182870', AC_EVENT_KEY);
        if(!is_string($student)){
            if(!empty($student->email)) $student = $student->email;
            else if(!empty($student->username)) $student = $student->username;
            else throw new Exception('Missing user email or username');
        }
        $data = (empty($data["data"])) ? 'No additional data' : $data["data"];
        \AC\ACAPI::trackEvent($student, $activity["slug"], $data);
    }

    public static function retrieveActivity($filters, $type='student_activity'){

        $query = self::datastore()->query()->kind($type);

        if(!is_callable('BreatheCode\BreatheCodeLogger::filter_'.$type)) throw new \Exception("No method filter_".$type);
        $query = call_user_func('BreatheCode\BreatheCodeLogger::filter_'.$type, $query, $filters);
        //$query = $query->order('created_at', Query::ORDER_DESCENDING);
        if(!$query) throw new \Exception("Undefined query for ".$type);

        $items = self::datastore()->runQuery($query);
        $results = [];
        foreach($items as $ans) {
            $results[] = $query = call_user_func('BreatheCode\BreatheCodeLogger::decode_'.$type, $ans);
        }
        return $results;
    }

    public static function encode_student_activity($student, $data){
        $dataObj = json_decode($data['data'], true);
        $activity = [
            'created_at' => new DateTime(),
            'slug' => $data["slug"],
            'cohort' => (empty($dataObj)) ? null : (empty($dataObj["cohort"])) ? null : $dataObj["cohort"],
            'data' => (empty($data["data"])) ? null : $data["data"]
        ];

        if(is_string($student)) $activity['email'] = $student;
        else{
            $student = (array) $student;
            if(!empty($student['id'])) $activity['user_id'] = (string) $student['id'];

            if(!empty($student['email'])) $activity['email'] = (string) $student['email'];
            else if(!empty($student['username'])) $activity['email'] = (string) $student['username'];
        }
        return $activity;
    }

    public static function decode_student_activity($data){
        return [
            "user_id" => $data->user_id,
            "email" => $data->email,
            "data" => $data->data,
            "cohort"=>$data->cohort,
            "created_at" => $data->created_at,
            "slug" => $data->slug,
        ];
    }

    public static function encode_coding_error($student, $data){
        return [
            'created_at' => new DateTime(),
            'slug' => $data["slug"],
            'data' => (empty($data["data"])) ? 'No additional data' : $data["data"],
            'user_id' =>  $student,
            'message' => $data["message"],
            'name' => $data["name"],
            'severity' => $data["severity"],
            'details' => $data["details"],
        ];
    }

    public static function decode_coding_error($data){
        return [
            "user_id" => $ans->user_id,
            "slug" => $ans->slug,
            "data" => (empty($data["data"])) ? 'No additional data' : $data["data"],
            "message" => $ans->message,
            "name" => $ans->name,
            "severity" => $ans->severity,
            "created_at" => $data->created_at,
            "details" => $ans->error
        ];
    }

    public static function filter_student_activity($query, $filters){
        if(!empty($filters["slug"])) $query = $query->filter('slug', '=', $filters["slug"]);
        if(!empty($filters["cohort"])) $query = $query->filter('cohort', '=', $filters["cohort"]);
        if(!empty($filters["user_id"])){
            $query = $query->filter('user_id', '=', $filters["user_id"]);
        }
        if(!empty($filters["email"])) $query = $query->filter('email', '=', $filters["email"]);

        return $query;
    }

    public static function filter_coding_error($query, $filters){
        if(!empty($filters["slug"])) $query = $query->filter('slug', '=', $filters["slug"]);
        if(!empty($filters["user_id"])){
            $query = $query->filter('user_id', '=', $filters["user_id"]);
        }
        return $query;
    }

}