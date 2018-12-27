<?php
use BreatheCode\BCWrapper as BC;
use BreatheCode\SlackWrapper;
use GuzzleHttp\Client;

class ZapManager{
    
    static $api;
    static $host = ASSETS_HOST.'/apis/zap/execute';
    
    static $_zaps = [
        "change_cohort_status@not-started" => [],
        "change_cohort_status@on-prework" => [],
        "change_cohort_status@on-course" => [],
        "change_cohort_status@on-final-project" => ["nps_survey_cohort"],
        "change_cohort_status@finished" => []
    ];
    
    static $actions = [
        "nps_survey_cohort" => [
            "title" => "NPS Survey will be sent each cohort student"
        ]
    ];
    
    public static function setAPI($api){
        self::$api = $api;
    }
    
    public static function getZaps($withDetails=false){
        if(!$withDetails) return self::$_zaps;
        
        $detailedZaps = [];
        foreach(self::$_zaps as $zapSlug => $actions){
            $detailedZaps[$zapSlug] = [];
            foreach($actions as $actionSlug){
                $detailedZaps[$zapSlug][] = self::getActionsDetails($actionSlug);
            }
        }
        return $detailedZaps;
    }
    
    public static function getActionsFromZap($zapSlug){
        if(!isset(self::$_zaps[$zapSlug]))  throw new Exception('Uknown zap: '.$zapSlug, 400);
        return self::$_zaps[$zapSlug];
    }
    
    public static function getActionsDetails($slug){
        if(!isset(self::$actions[$slug]))  throw new Exception('No action known for status: '.$slug, 400);
        self::$actions[$slug]["slug"] = $slug;
        return self::$actions[$slug];
    }
    
    public static function executeAction($slug, $payload){
        $action = self::getActionsDetails($slug);
        // Send an asynchronous request.
        $client = new Client();
        $action['slug'] = $slug;
        $action['data'] = $payload;
        $response = $client->request(
            empty($action['method']) ? 'POST' : $action['method'], 
            self::$host.'/'.$slug, 
            ['body' => json_encode($payload)]
        );
        if($response->getStatusCode() != 200){
            throw new Exception('There was an error executing the Zap', 500);
        }
        else{
            return $response;
        }
    }
    
}