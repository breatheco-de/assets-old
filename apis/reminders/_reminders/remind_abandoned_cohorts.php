<?php
/*
* @title: Remind cohorts with more than 90 days
* @frequency: 1 days
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

function remind_abandoned_cohorts(){
    //get cohorts with stage different from 'finished'.
    $cohorts = BC::getAllCohorts(["stage_not" => "finished"]);
    $expiredCohorts = [];
    foreach($cohorts as $c){
        //loop them and make sure they have a kickoff date
        if(!empty($c->kickoff_date) && $c->kickoff_date != '0000-00-00'){
            $now = new Moment(new DateTime());
            $kickoff = new Moment($c->kickoff_date, 'Y-m-d');
            //veryfy if more than 90 days have passed since the kickoffdate
            if($kickoff->add(90,'days')->isBefore($now)) $expiredCohorts[] = $c;
        }
    }
    
    // create the plan text content that will be sent by email
    $content = "The following cohorts have to be updated on breathecode: \n\n";
    foreach($expiredCohorts as $c) $content .= "    - ".$c->name." (".$c->slug.") started on ".$c->kickoff_date." and the stage is still ".$c->stage." \n";
    
    // send reminder
    if(count($expiredCohorts) > 0) emailReminder("reminders_careersupport@4geeksacademy.com", 'Expired Cohorts', $content);
}