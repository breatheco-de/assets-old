<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

function addAPIRoutes($api){

	$api->get('/all', function (Request $request, Response $response, array $args) use ($api) {
		$videos = array();
		foreach (glob("videos/*.mp4") as $file)
			$videos[] = [ 'name' => basename($file,".mp4"), 'path' => $file ];
	    return $response->withJson($videos);
	});
	
	$api->get('/{video_slug}', function(Request $request, Response $response, array $args) use ($api) {
        
		if(empty($args['video_slug'])) throw new Exception('Invalid param video_slug');
		if(!file_exists('videos/'.$args['video_slug'].".mp4")) throw new Exception('Video not found', 404);
		
		$video = file_get_contents('videos/'.$args['video_slug'].".mp4");
		
		$response->write($video);
    	return $response->withHeader('Content-Type', FILEINFO_MIME_TYPE);	
	});

	return $api;
}