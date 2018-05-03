<?php
    if(!isset($_POST['username']) || !isset($_POST['password'])){
        header("Location: login.php?error=missing");
        die();
    }
    
	require('../../vendor/autoload.php');
    require('../../vendor_static/breathecode-api/BreatheCodeAPI.php');
	require('../../globals.php');
	
	use BreatheCode\BCWrapper as BC;
    BC::init(BREATHECODE_CLIENT_ID, BREATHECODE_CLIENT_SECRET, BREATHECODE_HOST, API_DEBUG);
    BC::setToken(BREATHECODE_TOKEN);
    try{
        $username = $_POST['username'];
        $password = $_POST['password'];
    	$user = BC::getUser(['user_id' => urlencode($username)]);
    	if(!$user) throw new Exception('Email not found');
    	
    	$permissions = [
    		'student' => ['read_basic_info', 'student_tasks'],
    		'admin' => ['read_basic_info', 'super_admin']
    	];
    	if(!isset($permissions[$user->type])) throw new Exception('Invalid user type'); 
    	
    	$token = BC::autenticate($username,$password,$permissions[$user->type]);
	    if($token && $token->access_token)
	    {
	        BC::setToken($token->access_token);
	        $user = BC::getMe();
	        $user->access_token = $token->access_token;
	        $user->cohorts = $user->full_cohorts;
	        $user->scope = $token->scope;
	        
	        /**
	         * TODO: it seems that this login approach is not goign to work because 
	         * there is no way I can set the local storage or cookirs of abother subdomain form PHP
	         * I have to take care of the login on every client nativaly.
	         * 
	         * The files process_login.php and login.php need to be deleted
	         **/
    		
            switch($user->type){
                case "student":
                    header('Location: '.STUDENT_URL);
                    exit();
                break;
                case "admin":
                    header('Location: '.ADMIN_URL);
                    exit();
                break;
                case "student":
                    header('Location: '.TEACHER_URL);
                    exit();
                break;
                default:
                    throw new Exception("Invalid user type");
                break;
            }
	    }
    }
    catch(Exception $e){
        $withCallback = (isset($_POST['callback'])) ? '&callback='.$_POST['callback'] : '';
        header("Location: index.php?error=missing$withCallback");
        die();
    }
?>