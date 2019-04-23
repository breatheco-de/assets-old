<?php
    require('../../vendor/autoload.php');
    require('../../globals.php');
    require('../../vendor_static/ActiveCampaign/ACAPI.php');
    require('../../vendor_static/breathecode-api/BreatheCodeAPI.php');
    require('../../vendor_static/breathecode-api/SlackAPI.php');
    require('HookFunctions.php');
    
	$api = new \SlimAPI\SlimAPI([
	    'debug' => API_DEBUG,
	    'name' => 'Hook API'
	]);
	$api->addReadme('/','./README.md');
	
	//initialize hook for functions
	HookFunctions::setAPI($api);
	
	//initialize ACAPI
	\AC\ACAPI::start(AC_API_KEY);
	\AC\ACAPI::setupEventTracking('25182870', AC_EVENT_KEY);
    
    $api->addRoutes(require('hooks/for_the_referral_program.php'));
    $api->addRoutes(require('hooks/for_eventbrite_events.php'));
    $api->addRoutes(require('hooks/for_active_campaign.php'));
    $api->addRoutes(require('hooks/for_samples.php'));
    $api->addRoutes(require('hooks/for_breathecode.php'));
    $api->addRoutes(require('hooks/for_slack.php'));
	
	$api->run();