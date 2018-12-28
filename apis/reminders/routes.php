<?php

require_once('../JsonPDO.php');

require('../../vendor_static/ActiveCampaign/ACAPI.php');
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require('../../vendor_static/breathecode-api/BreatheCodeAPI.php');
use \BreatheCode\BCWrapper as BC;
BC::init(BREATHECODE_CLIENT_ID, BREATHECODE_CLIENT_SECRET, BREATHECODE_HOST, API_DEBUG);
BC::setToken(BREATHECODE_TOKEN);

require('./ReminderManager.php');
ReminderManager::init();

function addAPIRoutes($api){
	
	$api->addTokenGenerationPath();
	//get all cohorts and its replits
	$api->get('/all', function (Request $request, Response $response, array $args) use ($api) {
        $reminders = ReminderManager::getReminders();
	    return $response->withJson($reminders);
	});

	$api->get('/expired', function (Request $request, Response $response, array $args) use ($api) {
        
        $pending = ReminderManager::getPending();
	    return $response->withJson($pending);
	});

	$api->get('/execute/all', function (Request $request, Response $response, array $args) use ($api) {
        
        $pending = ReminderManager::getPending();
        $success = [];
        foreach($pending as $file){
    		try{
        		ReminderManager::execute($file["name"]);
        		$success[] = $file["name"];
    		}
    		catch(Exception $e){
    			$errors[] = $file["name"].': '.$e->getMessage();
    		}
        }

	    return $response->withJson([ 
    		"success" => (count($errors) === 0), 
    		"msg" => count($pending)." functions were executed, ".count($success)." succeded and ".count($errors)." failed",
	    	"errors" => $errors
	    ]);
	});
	
	$api->get('/execute/next', function (Request $request, Response $response, array $args) use ($api) {
        
        $pending = ReminderManager::getPending();
        if(!empty($pending[0])){
    		try{
        		$result = ReminderManager::execute($pending[0]["name"]);
	    		return $response->withJson([ "success" => true, "msg" => "1 function was executed: ".$pending[0]["name"], "errors" => [] ]);
    		}
    		catch(Exception $e){
	    		return $response->withJson([ "success" => false, "msg" => "1 function was executed and failed: ".$pending[0]["name"], "errors" => $e->getMessage() ]);
    		}
        }
        else return $response->withJson([ "success" => true, "msg" => "Nothing to execute", "errors" => [] ]);

	});
	
	$api->get('/execute/single/{name}', function (Request $request, Response $response, array $args) use ($api) {
        
        if(!empty($args["name"])){
    		try{
        		$result = ReminderManager::execute(strpos( $args["name"],".php") ? $args["name"] : $args["name"].".php");
	    		return $response->withJson([ "success" => true, "msg" => "1 function was executed", "errors" => [] ]);
    		}
    		catch(Exception $e){
	    		return $response->withJson([ "success" => false, "msg" => "1 function was executed and failed", "errors" => $e->getMessage() ]);
    		}
        }
        else throw new Exception('Invalid reminder name', 400);

	});
	
	return $api;
}