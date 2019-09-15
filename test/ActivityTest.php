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

        //$this->credentials['clientKey'] = $this->app->generatePrivateKey("my_super_id");
        $this->app->setJWTKey("!SC96fa4!!#%88730397!sd#$$%3423454523");
        $this->app->addRoutes(require(__DIR__.'/../apis/activity/routes.php'));
    }

    // function testAllActivitiesMustBeValid(){
    //     foreach(BreatheCodeLogger::$_activites as $slug => $props)
    //         $this->assertTrue(BreatheCodeLogger::validateActivityProperties($props, $slug));
    // }

    public function testGetStudentActivity(){
        $this->mockGET('/user/a.alejo@gmail.com')->expectSuccess(); //expects 200
    }

}