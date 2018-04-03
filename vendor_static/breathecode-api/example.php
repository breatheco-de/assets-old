<?php

    require('vendor/autoload.php');
    require('api_globals.php');
    require('./BreatheCodeAPI.php');
    
    use \BreatheCode\BCWrapper;
    BCWrapper::init(CLIENT_ID, CLIENT_SECRET, HOST, DEBUG);
    BCWrapper::setToken(TOKEN);
    
    $token = BCWrapper::autenticate('a+fakestudent2@4geeksacademy.com', 'zl3hfu8y', ['read_basic_info']);
    if($token && $token->access_token)
    {
        BCWrapper::setToken($token->access_token);
        $user = BCWrapper::getMe();
        print_r($user); die();
    }
    
    
    //$student = BCWrapper::getStudent(['student_id'=>3]);
    //print_r($student); die();