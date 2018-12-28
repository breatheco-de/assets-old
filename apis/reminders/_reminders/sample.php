<?php
/*
* @title: Sample Reminder
* @frequency: 1 seconds
* @to: reminders_careersupport@4geeksacademy.com
*/

/**
 ----- If you want to use the BreatheCode API ------
  
 $cohorts = BC::getAllCohorts(["stage_not" => "finished"]);

 ----- If you want to send an email -------

 emailReminder($to, $subject, $message);
*/
use \BreatheCode\BCWrapper as BC;
use MomentPHP\MomentPHP as Moment;

function sample(){
    //echo "hello";
}