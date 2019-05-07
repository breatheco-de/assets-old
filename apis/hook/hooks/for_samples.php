<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use BreatheCode\BCWrapper as BC;
BC::init(BREATHECODE_CLIENT_ID, BREATHECODE_CLIENT_SECRET, BREATHECODE_HOST, API_DEBUG);
BC::setToken(BREATHECODE_TOKEN);

return function($api){
	$scope = 'sample';

	$api->get('/'.$scope.'/student', function (Request $request, Response $response, array $args) use ($api) {
        $students = BC::getAll('student',['limit' => 1]);
        return $response->withJson($students[0]);
	});

	$api->get('/'.$scope.'/user', function (Request $request, Response $response, array $args) use ($api) {
        $users = BC::getAll('user',['limit' => 1]);
        return $response->withJson($users[0]);
	});

	$api->get('/'.$scope.'/cohort', function (Request $request, Response $response, array $args) use ($api) {
        $cohorts = BC::getAll('cohort',['limit' => 1]);
        return $response->withJson($cohorts[0]);
	});

	return $api;
};