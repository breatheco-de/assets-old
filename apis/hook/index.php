<?php
    require('../../vendor/autoload.php');
    require_once('../../globals.php');
    require_once('../SlimAPI.php');
    require('../../vendor_static/ActiveCampaign/ACAPI.php');
    require_once('../../vendor_static/breathecode-api/BreatheCodeAPI.php');
    require_once('../../vendor_static/breathecode-api/SlackAPI.php');
    require('HookFunctions.php');
    
	$api = new SlimAPI([
	    'debug' => true,
	    'name' => 'Hook API'
	]);
	$api->addReadme('/','./README.md');
	
	//initialize hook for functions
	HookFunctions::setAPI($api);
	
	//initialize ACAPI
	\AC\ACAPI::start(AC_API_KEY);
	\AC\ACAPI::setupEventTracking('25182870', AC_EVENT_KEY);
    
    require_once('hooks/for_the_referral_program.php');
    $api = addReferralProgramRoutes($api);
    
    //eventbrite api integration
    require_once('hooks/for_eventbrite_events.php');
    $api = addEventbriteRoutes($api);

    //eventbrite api integration
    require_once('hooks/for_data_integrity.php');
    $api = addDataIntegrityHooks($api);

    //eventbrite api integration
    require_once('hooks/for_samples.php');
    $api = addSampleRoutes($api);
    
    //slack api integration
    require_once('hooks/for_slack.php');
    $api = addSlackRoutes($api);
	
	$api->run();