<?php
/*
* @title: Every cohort must have a replit
*/

$CURRENT = dirname(__FILE__);
require_once($CURRENT.'/BaseTestCase.php');

class ReplitsTest extends BaseTestCase {

    public function setUp()
    {
        parent::setUp();
    }

    function testAllCohortsMustHaveReplits(){
        $resp = $this->get(BREATHECODE_HOST.'cohorts/');
        foreach($resp->data as $cohort){
            if($cohort->stage != 'not-started' && $cohort->stage != 'finished'){
                
                $this->get(self::ASSETS_API.'replit/templates');
                $this->checkURLAndContent(self::ASSETS_API.'replit/cohort/'.$cohort->slug, 'https:\/\/repl.it\/classroom\/invite\/');
            }
        }
    }

}