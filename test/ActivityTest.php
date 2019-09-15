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

        $this->app->setJWTKey("sgfsdfgtr5545545rg23232323DFDFDF");
        $this->credentials['clientKey'] = $this->app->generatePrivateKey("test_client");
        //$this->app->generatePrivateKey("test_client");
        $this->app->addRoutes(require(__DIR__.'/../apis/activity/routes.php'));
    }

    // function testAllActivitiesMustBeValid(){
    //     foreach(BreatheCodeLogger::$_activites as $slug => $props)
    //         $this->assertTrue(BreatheCodeLogger::validateActivityProperties($props, $slug));
    // }

    public function testGetStudentActivity(){
        $this->mockGET('/user/a.alejo@gmail.com?access_token='.$this->credentials["clientKey"])->expectSuccess(); //expects 200
    }

}