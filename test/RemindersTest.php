<?php
/*
* @title: Every cohort must have a replit
*/

$CURRENT = dirname(__FILE__);
require_once($CURRENT.'/BaseTestCase.php');
require($CURRENT.'/../apis/reminders/ReminderManager.php');

class RemindersTest extends BaseTestCase {

    var $currentPath = '';
    
    public function setUp()
    {
        parent::setUp();
        $this->currentPath = dirname(__FILE__);
    }

    function testAllCohortsMustHaveReplits(){
        ReminderManager::init($this->currentPath.'/../apis/reminders/');
        $reminders = ReminderManager::getRawReminders();
        foreach ($reminders as $reminder){
            $this->assertSame($this->_validateReminder($reminder), "Reminder ".$reminder["title"]." is valid");
        }
    }
    
    private function _validateReminder($reminder){
        if(ReminderManager::validate($reminder)) return "Reminder ".$reminder["title"]." is valid";
        else return false;
    }

}