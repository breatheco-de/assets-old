<?php
/*
* @title: Every cohort must have a replit
*/

$CURRENT = dirname(__FILE__);
require_once($CURRENT.'/BaseTestCase.php');
require_once($CURRENT.'/../apis/BreatheCodeLogger.php');

class ActivityTest extends BaseTestCase {

    public function setUp()
    {
        parent::setUp();
    }

    function testAllActivitiesMustBeValid(){
        foreach(BreatheCodeLogger::$_activites as $slug => $props)
        $this->assertTrue(BreatheCodeLogger::validateActivityProperties($props, $slug));
    }

}