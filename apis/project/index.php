<?php
	require_once('../../vendor/autoload.php');
	require_once('../../globals.php');
    use GuzzleHttp\Client;

	use Psr\Http\Message\ServerRequestInterface as Request;
	use Psr\Http\Message\ResponseInterface as Response;
	$api = new \SlimAPI\SlimAPI([
		'debug' => API_DEBUG,
		'name' => 'Projects API'
	]);
	$api->addReadme('/','./README.md');

	$api->get('/all', function (Request $request, Response $response, array $args) use ($api) {
        $client = new Client();
        $resp = $client->request('GET','https://projects.breatheco.de/json/');
        if($resp->getStatusCode() != 200) throw new Exception('The project was not found', 404);

        $body = $response->getBody();
		$body->write($resp->getBody()->getContents());
	    return $response->withHeader('Content-type', 'application/json');
	});

	$api->get('/{project_slug}', function (Request $request, Response $response, array $args) use ($api) {

        if(empty($args['project_slug'])) throw new Exception('Invalid param value project_slug');

        $client = new Client();
        $resp = $client->request('GET','https://projects.breatheco.de/json/?slug='.$args['project_slug']);
        if($resp->getStatusCode() != 200) throw new Exception('The project was not found', 404);

        $body = $response->getBody();
		$body->write($resp->getBody()->getContents());
	    return $response->withHeader('Content-type', 'application/json');
	});


	$api->run();