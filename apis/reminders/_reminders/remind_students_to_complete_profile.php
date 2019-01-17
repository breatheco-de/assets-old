<?php
/*
* @title: Remind dtudents to complete their profile
* @frequency: 2 days
* @status: draft
* @to: every student that does not have a complete profile
*/

use \BreatheCode\BCWrapper as BC;
use MomentPHP\MomentPHP as Moment;

function remind_students_to_complete_profile(){
    //get cohorts with stage different from 'finished'.
    $cohorts = BC::getAllCohorts(["stage_not" => "finished"]);
    $expiredCohorts = [];
    foreach($cohorts as $c){
        /**
        * Loop every student and make sure it has the github information setup
        
        $content = ''; //html content of the email
        
        emailReminder("student@email.com", 'You need to complete your profile information', $content);
        
        */
    }
    
}