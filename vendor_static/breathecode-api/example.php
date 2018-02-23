<?php

    require('./BreatheCodeAPI.php');
    
    $clientId = 'alesanchezr';
    $clientSecret = 'd04f78ef196471d5a954fe71aab4fe63bd95a8a4';
    $host = 'https://talenttree-alesanchezr.c9users.io/';
    
    use \BreatheCode\BCWrapper;
    BCWrapper::init($clientId, $clientSecret, $host, true);
    BCWrapper::setToken('d69eae97e7f874c6cdf46de524178e8ca5f1583e');
    
    $student = BCWrapper::getStudent(['student_id'=>3]);
    print_r($student); die();