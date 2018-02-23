<?php

namespace BreatheCode;

require(dirname(__FILE__).'/httpful.phar');

use \Httpful\Request as Curl;

class BCWrapper{
    
    private static $clientId = '';
    private static $clientSecret = '';
    
    private static $accessToken = [];

    private static $host = '';
    
    private static $debug = '';
    
    public static function init($clientId, $clientSecret, $host, $debug=false){
        self::$clientId = $clientId;
        self::$clientSecret = $clientSecret;
        self::$host = $host;
        self::$debug = $debug;
    }
    
    private static $scopes = [
    	"student" => ['read_basic_info', 'read_talent_tree', 'student_assignments', 'user_profile' ],
    	"teacher" => ['read_basic_info', 'read_talent_tree', 'student_assignments', 'teacher_assignments', 'user_profile'],
    	"admin" => ['read_basic_info', 'super_admin']
    	];
    	
    private static function getTokenType($request){

    	$specialMethods = [
    		'credentials/user/',
    		'atemplate/sync/',
    		'location/sync/',
    		'cohort/sync/',
    		'assignment/sync/',
    		'token',
    		'user/sync'
    	];
    	
    	if(self::array_contains($request, $specialMethods)) return 'admin';
    	else return 'user';
    }
    
    private static function array_contains($string,$arr){ 
    	foreach($arr as $item){ 
    		if (strpos($item, $string) !== false) return true; 
    	}
    	return false;
    }
    
    public static function setToken($token, $type='standard'){
		self::$accessToken[$type] = $token;
		return $token;
    }
    
    public static function getToken($type='standard'){
    	
    	if(!empty(self::$accessToken[$type])) return self::$accessToken[$type];
    	else return null;
    }
    
    public static function request($method,$resource,$args=[],$decode=true){
        $method = strtoupper($method);

		$args['client_id'] = self::$clientId;
		$args['client_secret'] = self::$clientSecret;
        $args['access_token'] = self::getToken();
        
        $template = Curl::init()->addHeader('Content-type', 'application/json')
            ->addHeader('user-agent', 'Httpful/0.2.19')
            ->addHeader('Origin', 'https://assets-alesanchezr.c9users.io');
        Curl::ini($template);
		
        if($method=='GET') $response = Curl::get(self::$host.$resource.'?'.http_build_query($args))->send();
        else if($method=='POST') $response = Curl::post(self::$host.$resource)->body($args)->send();
		else throw new \Exception('Invalid HTTP request type '.$method);

		if(!$response) throw new \Exception('CURL Error');
		if($response->code==500) 
		{
		    if(self::$debug) 
		    {
		    	//print_r($response); die();
		    	$responseBody = $response->body;
		    	$errorObj = json_decode($responseBody);
		    	throw new \Exception($errorObj->msg);
		    }
		    throw new \Exception('There was a problem, check your username and password.');
		}
		else if($response->code==401) 
		{
			if(self::$debug) throw new \Exception('Unauthorized BreatheCode API request for method: '.$resource);
			else throw new \Exception('Unauthorized credentials');
		}
		else if($response->code!=200){
		    throw new \Exception('Code: '.$response->code.', error');
		}

		if(!$response->body)
		{
			$message = 'Error decoding API result';
			if(self::$debug) 
			{
				$message .= ': ';
				$message .= json_last_error_msg();
			}
			throw new \Exception($message);
		}
		
		if(isset($response->body->code))
		{
    		if($response->body->code!='200') {
    		    if(self::$debug) throw new \Exception($response->body->msg);
    		    else throw new \Exception('There was a problem in the request');
    		}
    		return $response->body->data;
		}
		else return $response->body;
    }
    
    private static function validate($params,$key){
        if(empty($params[$key])) throw new \Exception('Undefined required parameter '.$key);
    }
    
    public static function autenticate($username, $password){
		$wpUser = get_user_by('email',$username);
		if(!$wpUser) throw new \Exception('The wp_user doest not exist');
		$type = get_user_meta($wpUser->ID, 'type', true);

		$args = [
    		'grant_type' => "password",
    		'username' => $username,
    		'password' => $password,
    		'scope' => implode (" ", self::$scopes[$type])
		];
		
		
		// send the response back to the front end
		$token = self::request('post','token',$args);
		if(!empty($token->access_token))
		{
		    self::setToken($token->access_token);
		    $user = self::request('get','me',[]);

    		if(empty($user) or empty($user->wp_id)) {
    		    throw new \Exception('There is no wordpress ID for this user in the Breathecode API');
    		}
    		else return $user;
    		
		}else throw new \Exception('There is no access_token for worpress client in the API');
		
		return false;
    }
    
    public static function createCredentials($params){
        
        self::validate($params,'email');
        self::validate($params,'wp_id');
        self::validate($params,'type');

        return self::request('post','/credentials/user/',$params);
    }
    
    public static function updateCredentials($params){
        
        self::validate($params,'user_id');
        self::validate($params,'password');
        
        $params['password'] = wp_hash_password($params['password']);

        return self::request('post','/credentials/user/'.$params['user_id'],$params);
    }
    
    public static function updateUserSettings($params){
        
        self::validate($params,'user_id');
        self::validate($params,'settings');
        
        return self::request('post','/settings/user/'.$params['user_id'],$params['settings']);
    }
    
	public static function getStudent($params=[]){
	
        self::validate($params,'student_id');
	    
	    return self::request('GET','student/'.$params['student_id'],$params);
	}
    
    public static function getUserSettings($params){
        
        self::validate($params,'user_id');
        
        return (array) self::request('get','/settings/user/'.$params['user_id']);
    }
    
	public static function getStudentBadges($args=[],$decode=true){
	
	    $allBadges = [];
	    $allSpecialties = self::getAllSpecialtiesByProfile(['profile_id' => 1]);
        if($allSpecialties and count($allSpecialties)>0)
        {
            foreach($allSpecialties as $specialty) 
            {
                $badges = $specialty->badges;
                foreach($badges as $b) 
                {
                	$badgeArray = [];
                    $badgeArray['points_acumulated'] = 0;
                    $badgeArray['is_achived'] = false;
                    $badgeArray['name'] = $b;
                    $badgeArray['percent'] = 0;
                    $badgeArray['slug'] = $b;
                    $allBadges[$b] = $badgeArray;
                }
            }
        }
        
	    $studentBadges = self::request('GET','badges/student/'.$args['student_id']);
	    foreach($studentBadges as $badge) 
	    {
	        if(!isset($allBadges[$badge->slug])) $allBadges[$badge->slug] = [];
            $allBadges[$badge->slug]['name'] = $badge->name;
            $allBadges[$badge->slug]['points_acumulated'] = $badge->points_acumulated;
            $allBadges[$badge->slug]['is_achived'] = $badge->is_achieved;
            if($badge->points_acumulated>0){
            	//print_r($badge); die();
            	if($badge->points_to_achieve && $badge->points_to_achieve>0) 
            		$allBadges[$badge->slug]['percent'] = round(($badge->points_acumulated / $badge->points_to_achieve)*100,2);
            	else $allBadges[$badge->slug]['percent'] = 0;
            } 
            else $allBadges[$badge->slug]['percent'] = 0;
            
            $allBadges[$badge->slug]['background-class'] = floor($allBadges[$badge->slug]['percent']/10);
	    }
	    //print_r($allBadges); die();
	    return $allBadges;
	}
	
	public static function getStudentBriefing($args=[],$decode=true){
	
	    return self::request('GET','briefing/student/'.$args['student_id'],$args,$decode);
	}
	
	public static function getAllSpecialtiesByProfile($args=[],$decode=true){
	
	    $specialties = self::request('GET','specialties/profile/'.$args['profile_id']);
	    return $specialties;
	}
	
	public static function getAllBadges($args=[],$decode=true){
	
	    return self::request('GET','badges/',$args,$decode);
	}
	
	public static function getBadge($args=[],$decode=true){
	
	    return self::request('GET','badge/'.$args['badge_id'],$args,$decode);
	}
	
	public static function getStudentActivity($args=[],$decode=true){
	
		self::validate($args,'student_id');
		
	    $activities = self::request('GET','activity/student/'.$args['student_id']);
	    return $activities;
	}
	
	public static function addStudentActivity($args=[],$decode=true){
	
		self::validate($args,'student_id');
		self::validate($args,'type');
		self::validate($args,'name');
		self::validate($args,'description');
		self::validate($args,'badge_slug');
		self::validate($args,'points_earned');
		
	    $result = self::request('POST','activity/student/'.$args['student_id'], $args);
	    return $result;
	}
	
	public static function getStudentAssignments($args=[],$decode=true){
	
		self::validate($args,'student_id');
		
	    $assignments = self::request('GET','assignments/student/'.$args['student_id']);
	    return $assignments;
	}
	
	public static function getTeacherAssignments($args=[],$decode=true){
	
	    $assignments = self::request('GET','assignments/teacher/'.$args['teacher_id'],$args);
	    return $assignments;
	}
	
	public static function getTeacherCohorts($args=[],$decode=true){
	
	    $cohorts = self::request('GET','cohorts/teacher/'.$args['teacher_id'],$args);
	    return $cohorts;
	}

	public static function getAssignmentTemplates($args=[],$decode=true){
	
	    $assignments = self::request('GET','atemplates/',$args);
	    return $assignments;
	}
	
	public static function getSingleStudentAssignment($args=[],$decode=true){

	    $assignment = self::request('GET','student/assignment/'.$args['assignment_id']);
	    return $assignment;
	}
	
	public static function updateStudentAssignment($args=[],$decode=true){

	    $assignment = self::request('POST','student/assignment/'.$args['assignment_id'],$args);
	    return $assignment;
	}
	
	public static function createCohortAssignment($args=[],$decode=true){

	    $assignment = self::request('POST','assignment/cohort/'.$args['cohort_id'],$args);
	    return $assignment;
	}
	
	public static function syncProjectTemplate($args=[],$decode=true){

	    $template = self::request('POST','atemplate/sync/'.$args['wp_id'],$args);
	    return $template;
	}
	
	public static function syncLocation($args=[],$decode=true){

	    $template = self::request('POST','location/sync/',$args);
	    return $template;
	}
	
	public static function syncCohort($args=[],$decode=true){

	    $cohort = self::request('POST','cohort/sync/',$args);
	    return $cohort;
	}
	
	public static function syncUser($params){
        
        self::validate($params,'email');
        self::validate($params,'wp_id');
        self::validate($params,'type');

        return self::request('post','/user/sync',$params);
    }
	
	public static function syncAssignment($params){
        
        self::validate($params,'template_slug');
        self::validate($params,'student_id');
        self::validate($params,'teacher_id');
        self::validate($params,'duedate');
        self::validate($params,'status');

        return self::request('post','/assignment/sync/',$params);
    }
}