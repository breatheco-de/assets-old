<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

function addAPIRoutes($api){

	$api->get('/all', function (Request $request, Response $response, array $args) use ($api) {
		$content = $api->db['json']->getAllContent();
	    return $response->withJson($content);
	});
	
	$api->get('/{quiz_slug}/user/{user_id}/response', function(Request $request, Response $response, array $args) use ($api) {
        
		if(empty($args['quiz_slug'])) throw new Exception('Invalid param quiz_slug');
		if(empty($args['user_id'])) throw new Exception('Invalid param user_id');
		
		$row = $api->db['sqlite']->response()
			->where('quiz_slug',$args['quiz_slug'])
			->where('user_id',$args['user_id'])
			->fetch();
		if(!$row) $row = (object) [];
		return $response->withJson($row);	
	});

	$api->get('/response', function(Request $request, Response $response, array $args) use ($api) {
		$responses = $api->db['sqlite']->response()->fetchAll();
		return $response->withJson($responses);	
	});
	
	$api->put('/response', function(Request $request, Response $response, array $args) use ($api) {
        
        $parsedBody = $request->getParsedBody();
        if(empty($parsedBody['quiz_id'])) throw new Exception('Invalid param quiz_id');
        if(empty($parsedBody['user_id'])) throw new Exception('Invalid param user_id');
        if(!isset($parsedBody['correct_answers'])) throw new Exception('Invalid param correct_answers');
        
        $quizSlug = $parsedBody['quiz_id'];
        $userId = $parsedBody['user_id'];
        $correctAnswers = $parsedBody['correct_answers'];
		
		$row = $api->db['sqlite']->createRow( 'response', $properties = array(
			'quiz_slug' => $quizSlug,
			'user_id' => $userId,
			'timestamp' => date("Y-m-d H:i:s"),
			'correct_answers' => $correctAnswers
		) );
		
		$row->save();
		
        return $response->withJson($row);
	});
	
	$api->get('/{slug}', function (Request $request, Response $response, array $args) use ($api) {
        
        $slug = $args['slug'];
        $quizObj = $api->db['json']->getJsonByName($slug);

        if(!empty($quizObj)){
	        $quizObj['info'] = (array) $quizObj['info'];
	        $quizObj['info']['slug'] = $slug;
		    return $response->withJson($quizObj);
        }
	    return $response->withJson($quizObj);
	});
	
	return $api;
}