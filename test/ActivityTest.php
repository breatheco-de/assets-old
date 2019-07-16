<?php
/*
* @title: Every cohort must have a replit
*/

$CURRENT = dirname(__FILE__);
require_once($CURRENT.'/BaseTestCase.php');

use \BreatheCode\BreatheCodeLogger;

class ActivityTest extends BaseTestCase {

    public function setUp()
    {
        parent::setUp();

        $this->app->setJWTKey("adSAD43gtterT%rtwre32@");
        $this->credentials['clientKey'] = $this->app->generatePrivateKey("my_super_id");
        $this->app->addRoutes(require(__DIR__.'/../apis/activity/routes.php'));
    }

    function testAllActivitiesMustBeValid(){
        foreach(BreatheCodeLogger::$_activites as $slug => $props)
        $this->assertTrue(BreatheCodeLogger::validateActivityProperties($props, $slug));
    }

    public function testGetStudentActivity(){
        $this->mockGET('/activity/student/a.alejo@gmail.com')->expectSuccess(); //expects 200
    }

}