<?php

require('../../vendor_static/breathecode-api/BreatheCodeAPI.php');
require('../BreatheCodeLogger.php');
require('../../vendor_static/ActiveCampaign/ACAPI.php');
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use \BreatheCode\BCWrapper as BC;
use Google\Cloud\Datastore\DatastoreClient;

BC::init(BREATHECODE_CLIENT_ID, BREATHECODE_CLIENT_SECRET, BREATHECODE_HOST, API_DEBUG);
BC::setToken(BREATHECODE_TOKEN);

function addAPIRoutes($api){
	
	$api->addTokenGenerationPath();
	//get all cohorts and its replits
	$api->get('/user/{user_id}', function (Request $request, Response $response, array $args) use ($api) {
        
        $user = BC::getUser(['user_id' => urlencode($args["user_id"])]);
        
        $filters=[];
        
        if(filter_var($args["user_id"], FILTER_VALIDATE_EMAIL)) $filters["email"] = $args["user_id"];
        else $filters["user_id"] = $args["user_id"];
        
        if(!empty($_GET['slug'])) $filters["slug"] = $_GET['slug'];
        $result = BreatheCodeLogger::retrieveActivity($filters);
        
	    return $response->withJson([
	        "user" => $user,
	        "log" => $result
	    ]);
	});
	
	//create user activity
	$api->post('/user/{user_id}', function (Request $request, Response $response, array $args) use ($api) {

		$user = BC::getUser(['user_id' => urlencode($args["user_id"])]);
		
		$parsedBody = $request->getParsedBody();
        $data = $api->optional($parsedBody,'data')->smallString();
        $slug = $api->validate($parsedBody,'slug')->slug();
		
        BreatheCodeLogger::logActivity([
            'slug' => $slug,
            'user' => $user,
            'data' => $data
        ]);
	    return $response->withJson("ok");
	})->add($api->auth());

	return $api;
}