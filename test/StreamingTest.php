<?php

$CURRENT = dirname(__FILE__);
require_once($CURRENT.'/BaseTestCase.php');
require_once('./apis/streaming/StreamingFunctions.php');

use \BreatheCode\BCWrapper;

class StreamingTest extends BaseTestCase
{

    public function setUp(){
        parent::setUp();

        //adding an internal seed for random private key generation
        $this->app->setJWTKey("adSAD43gtterT%rtwre32@");

        //generating credentials for one client.
        $this->credentials['clientKey'] = $this->app->generatePrivateKey("test_client");
        $this->app->addRoutes(require(__DIR__.'/../apis/streaming/routes.php'));
    }

    public function testGetPlaylists(){
        $this->mockGET('/playlists')->expectSuccess(); //expects 200
    }

    // public function testGetCohortStreaming(){
    //     $cohort = $this->getSample('cohort');
    //     print_r($cohort); die();
    //     //$this->mockGET('/cohort/'.$cohort->slug)->expectSuccess(); //expects 200
    // }

}