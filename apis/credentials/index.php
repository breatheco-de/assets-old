<?php
	require('../../globals.php');
	require_once('../../vendor/autoload.php');
	require_once('../SlimAPI.php');
	require_once('../../vendor_static/breathecode-api/BreatheCodeAPI.php');
	
	use BreatheCode\BCWrapper as BC;
    BC::init(BREATHECODE_CLIENT_ID, BREATHECODE_CLIENT_SECRET, BREATHECODE_HOST, API_DEBUG);
    BC::setToken(BREATHECODE_TOKEN);
    
	use Psr\Http\Message\ServerRequestInterface as Request;
	use Psr\Http\Message\ResponseInterface as Response;
	$api = new SlimAPI();
	
	$api->post('/authenticate', function (Request $request, Response $response, array $args) use ($api) {
        
        $body = json_decode($request->getBody()->getContents());
        if(empty($body->username)) throw new Exception('Invalid username');
        if(empty($body->password)) throw new Exception('Invalid password');
        
        $username = $body->username;
        $password = $body->password;
        try{
        	$token = BC::autenticate($username,$password,['read_basic_info', 'student_tasks']);
		    if($token && $token->access_token)
		    {
		        BC::setToken($token->access_token);
		        $user = BC::getMe();
		        $user->access_token = $token->access_token;
		        $user->scope = $token->scope;
	    		return $response->withJson($user);
		    }
        }
        catch(Exception $e)
        {
	    	return $response->withJson(['msg'=>$e->getMessage()])->withStatus(403);
        }
	});
	$api->post('/autheticate/github', function (Request $request, Response $response, array $args) use ($api) {
        
	    return $response->withJson($quizObj);
	});
	
	$api->run(); 