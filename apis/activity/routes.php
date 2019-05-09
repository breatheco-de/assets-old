<?php

require('../BreatheCodeLogger.php');
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use \BreatheCode\BCWrapper as BC;
use Google\Cloud\Datastore\DatastoreClient;

BC::init(BREATHECODE_CLIENT_ID, BREATHECODE_CLIENT_SECRET, BREATHECODE_HOST, API_DEBUG);
BC::setToken(BREATHECODE_TOKEN);

return function($api){

	$api->addTokenGenerationPath();
	//get all cohorts and its replits
	$api->get('/types', function (Request $request, Response $response, array $args) use ($api) {
	    return $response->withJson(BreatheCodeLogger::getAllTypes());
	});
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

	//create bulk user activity
	$api->post('/user/bulk', function (Request $request, Response $response, array $args) use ($api) {

		$activities = $request->getParsedBody();
		foreach($activities as $activity){
	        $id = $api->validate($activity,'id')->int();
	        $email = $api->validate($activity,'email')->email();
	        $slug = $api->validate($activity,'slug')->slug();
	        $data = $api->optional($activity,'data')->smallString();

	        BreatheCodeLogger::logActivity([
	            'slug' => $slug,
	            'user' => [
	            	'id' => $id,
	            	'email' => $email
	            ],
	            'data' => $data
	        ]);
		}

	    return $response->withJson("ok");
	})->add($api->auth());

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

	//create user activity
	$api->post('/coding_error', function (Request $request, Response $response, array $args) use ($api) {

        $parsedBody = $request->getParsedBody();
        function logError($api, $data){
            $details = $api->optional($data,'details')->string();
            $slug = $api->validate($data,'slug')->slug();
            $message = $api->validate($data,'message')->text();
            $user = $api->validate($data,'username')->string();
            $severity = $api->validate($data,'severity')->int();
            $name = $api->validate($data,'name')->string(0,20);

            BreatheCodeLogger::logActivity([
                'slug' => $slug,
                'user' => $user,
                'details' => $details,
                'name' => $name,
                'message' => $message,
                'severity' => $severity,
            ]);
        }

        if(is_array($parsedBody)) foreach($parsedBody as $error) logError($api, $error);
        else logError($api, $parsedBody);

	    return $response->withJson("ok");

	});//->add($api->auth());

	$api->get('/coding_error/{user_id}', function (Request $request, Response $response, array $args) use ($api) {

        $user = BC::getUser(['user_id' => urlencode($args["user_id"])]);

        $filters=[];

        if(filter_var($args["user_id"], FILTER_VALIDATE_EMAIL)) $filters["email"] = $args["user_id"];
        else $filters["user_id"] = $args["user_id"];

        if(!empty($_GET['slug'])) $filters["slug"] = $_GET['slug'];
        $result = BreatheCodeLogger::retrieveActivity($filters, 'coding_error');

	    return $response->withJson([
	        "user" => $user,
	        "log" => $result
	    ]);
	});

	return $api;
};