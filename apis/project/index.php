<?php
	require_once('../../vendor/autoload.php');
	require_once('../../globals.php');
	require_once('../SlimAPI.php');
	
	use Psr\Http\Message\ServerRequestInterface as Request;
	use Psr\Http\Message\ResponseInterface as Response;
	$api = new SlimAPI([
		'debug' => API_DEBUG,
		'name' => 'Projects API'
	]);
	$api->addReadme('/','./README.md');	
	
	$api->get('/all', function (Request $request, Response $response, array $args) use ($api) {
		$body = $response->getBody();
		$body->write(file_get_contents('https://projects.breatheco.de/app/projects.php'));
	    return $response->withHeader('Content-type', 'application/json');
	});
	
	$api->run(); 