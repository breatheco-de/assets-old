<?php
$CURRENT = dirname(__FILE__);
require($CURRENT.'/../../vendor/autoload.php');
require($CURRENT.'/ReminderManager.php');
require($CURRENT.'/../TestUtils.php');
require_once($CURRENT.'/../JsonPDO.php');

$l = new Logger();

ReminderManager::init($CURRENT.'/');
$reminders = ReminderManager::getRawReminders();
foreach ($reminders as $reminder){
    try{
        ReminderManager::validate($reminder);
    }
    catch(Exception $e){
        $l->err("Error in ".$reminder["name"].": ".$e->getMessage());
    }
}


$l->report();
exit($l->exitCode());